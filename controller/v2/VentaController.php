<?php
use Carbon\Carbon;
class VentaController extends ControladorBase{
    public $conectar;
	public $adapter;
    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Ventas/M_ReporteVentas',
            'Ventas/M_GuardarVenta',
            'Personas/M_Personas',
            'DocumentoSucursal/M_DocumentoSucursal',
            'Cart/M_Cart',
            'FormaPago/M_FormaPago',
            'Cartera/M_GuardarCartera',
            'Cart/M_CartImpuesto',
            'Cart/M_CartRetencion',
            'Impuestos/M_DetalleImpuestoGeneral',
            'Retenciones/M_DetalleRetencionGeneral',
            'Ventas/M_GuardarArticulosVentas',
            'Sucursales/M_Sucursales',
            'Impuestos/M_Impuestos',
            'Retenciones/M_Retenciones',
            'MetodosPago/M_MetodosPago',
            'Empleados/M_Empleados',
            'Impuestos/M_DetalleImpuestoComprobantes',
            'Cart/M_CartMetodoPago'
        ],$this->adapter);
    }

    public function index()
    {
        $this->frameview('v2/Ventas/ventas',[]);
        $this->load('v2/Ventas/ventasTable',[]);
        $this->load('v2/Ventas/ventasModals',[]);
        $this->load('v2/Ventas/ventasScript',[]);
    }

    public function getVentasAjax()
    {
        $arrayVentas = [];
        $ventas = $this->M_ReporteVentas->getReporteVentas();
        $rowVentas = 0;
        foreach ($ventas as $venta) {
            $infoVenta ='<div class="btn-group btn-circle btn-sm">
									<button type="button" class="btn btn-circle btn-info btn-sm" onclick="btnAbonosVenta('.$venta['idventa'].', '.$rowVentas.');">
										<i class="fas fa-money-check-alt">
											<sup>
												<span class="badge badge-light">'.$venta['cantidadAbonos'].'</span>
											</sup>
										</i>
									</button>
								</div>';
            $arrayVentas[] =[
                0   => $venta['idventa'],
                1   => $venta['fecha'],
                2   => '<div class="row">
                            <div class="col-10">
                                <b>Cliente:</b> '.$venta['nombreCliente'].'
                                <br><b>Tipo comprobante:</b> '.$venta['tipo_comprobante'].'
                                <br><b>Tipo pago:</b> '.$venta['tipo_pago'].'
                            </div>
                            <div class="col-2 text-right">
                                <div class="btn-group-vertical btn-group-sm">
                                    '.$infoVenta.'
                                </div>
                            </div>
                        </div>',
                3   => $venta['serie_comprobante'].'-'.$venta['num_comprobante'],
                4   => moneda($venta['totalImpuesto'],0,'$'),
                5   => moneda($venta['totalVenta'],0,'$'),
                6   => status($venta['estado']),
                7   => '<a href="#file/venta/'.$venta['idventa'].'"><i class="far fa-file-pdf text-success"></i></a>',
            ];
            $rowVentas++;
        }
        echo json_encode($arrayVentas);
    }

    public function crear()
    {
        $filtroFormasPago = [
            'fp_proceso'        => "Venta",
            'fp_idsucursal'     => $_SESSION['idsucursal'],
        ];

        $cart = $this->M_Cart->crearCarrito([
            'ci_usuario'        => $_SESSION["usr_uid"],
            'ci_idsucursal'     => $_SESSION["idsucursal"],
            'ci_idproveedor'    => 0,
            'ci_tipo_pago'      => 0,
            'ci_comprobante'    => 0,
            'ci_fecha'          => date("Y-m-d"),
            'ci_fecha_final'    => date("Y-m-d")
        ]);
        $items = $this->M_Cart->obtenerArticulos([
            'idsucursal'    => $_SESSION['idsucursal'],
            'idusuario'     => $_SESSION['usr_uid'],
            'ci_id'         => $cart
        ]);
        $comprobantes = $this->M_DocumentoSucursal->getDocumentoSucursal(null,['proceso'=>'Venta']);
        foreach($comprobantes as $default){
            //si hay un comprobante pordefecto guarda los impuestos y los almacena para las ventas tipo pos
                if($default['dds_propertie'] == 'selected'){
                    //obtener impuestos del comprobante
                    $impuesto_default = $this->M_DetalleImpuestoComprobantes->obtenerImpuestosComprobante([ 'dic_det_documento_sucursal' => $default['iddetalle_documento_sucursal'] ]);
                    $arrayColaImpuestos = [];
                    foreach($impuesto_default as $impuesto_default){
                        $arrayColaImpuestos[] = [
                            'cdim_ci_id'            => $cart,
                            'cdim_idcomprobante'    => 0,
                            'cdim_im_id'            => $impuesto_default['im_id'],
                            'cdim_contabilidad'     => 0,
                        ];
                    }
                    $this->M_CartImpuesto->guardarCartImpuestos($arrayColaImpuestos);
                }
            }

        $this->frameview('v2/Ventas/crearVenta/crearVenta',[
            "contabilidad"      => 'Venta',
            "sucursal"          => $this->M_Sucursales->getSucursales($_SESSION['idsucursal']),
            "idusuario"         => $_SESSION["usr_uid"],
            "pos"               => "Venta",
            "comprobantes"      => $comprobantes,
            "formaspago"        => $this->M_FormaPago->obtenerFormaPago($filtroFormasPago),
            "items"             => $items,
            "autocomplete"      => "nombre_articulo",
            "impuestos"         => $this->M_Impuestos->getImpuestos(),
            "retenciones"       => $this->M_Retenciones->getRetenciones(),
            "control"           => '',
            "metodospago"       => $this->M_MetodosPago->obtenerMetodosPago([ 'mp_idsucursal' => $_SESSION['idsucursal'] ]),
            "empleado"          => $this->M_Empleados->obtenerEmpleado([ "idusuario" => $_SESSION["usr_uid"] ]),
            "hidden"            => "default_hidden"
        ]);
        $this->load('v2/Ventas/crearVenta/crearVentaModals',[]);
        $this->load('v2/Ventas/crearVenta/crearVentaScript',[]);
    }

    public function crearVenta()
    {
            //ver que los datos se enviaron
            $estado         = false;
            $mensajeVenta  = 'No se pudo almacenar ésta compra';
            $estadoVenta   = 'error';
            $success        = '';
            $arrayAuditoria = [];
            try {
                if(!$_POST["proveedor"])
                    throw new Exception("Agrega un proveedor válido");
                if(!$_POST["comprobante"])
                    throw new Exception("Agrega un comprobante válido");
                /******************************************************************************************/
                $dataproveedor = $this->M_Personas->buscarPersona($_POST["proveedor"],'num_documento');
                if(!$dataproveedor){
                    throw new Exception("Proveedor no existe");
                }
                /***********************************configuracion del comprobante***************************************/
                $comprobante = $this->M_DocumentoSucursal->getDocumentoSucursal($_POST["comprobante"]);
                if(!$comprobante)
                    throw new Exception("Comprobante no válido");
                //obtener carro de articulos
                $getCart    = $this->M_Cart->obtenerUltimoCarrito([
                    'idsucursal'    => $_SESSION['idsucursal'],
                    'idusuario'     => $_SESSION['usr_uid']
                ]);
                if(!$getCart){
                    throw new Exception("Ésta solicitud de venta ya venció");
                }
                //obtener articulos
                $getArticulos = $this->M_Cart->obtenerArticulos([
                    'idsucursal'    => $_SESSION['idsucursal'],
                    'idusuario'     => $_SESSION['usr_uid'],
                    'ci_id'         => $getCart['ci_id']
                ]);
                //si hay articulos en el carro de lo contrario cancela el proceso
                if(!$getArticulos){
                    throw new Exception("No hay articulos agregados");
                }
                //obtener tipo de pago
                $formapago  = $this->M_FormaPago->obtenerFormaPago( ['fp_id' => $_POST["formaPago"]] );
                //articulos recuperados
                $totalcart   = $this->M_Cart->getSubTotal($getCart['ci_id']);
                $saveVenta = $this->M_GuardarVenta->guardarActualizarVenta([
                    'idsucursal'            => $_SESSION['idsucursal'],
                    'idpedido'              => 0,
                    'idCliente'             => $dataproveedor['idpersona'],
                    'idusuario'             => $_SESSION['usr_uid'],
                    'tipo_venta'            => 0,
                    'tipo_pago'             => $formapago['fp_nombre'],
                    'tipo_comprobante'      => $comprobante['nombre_documento'],
                    'serie_comprobante'     => $comprobante['ultima_serie'],
                    'num_comprobante'       => zero_fill($comprobante['ultimo_numero']+1,8),
                    'fecha'                 => date_format_calendar($_POST["start_date"],"/"),
                    'fecha_final'           => date_format_calendar($_POST["end_date"],"/"),
                    'impuesto'              => 0,
                    'sub_total'             => $totalcart['subtotal'],
                    'subtotal_importe'      => 0,
                    'total'                 => $totalcart['totalCart'],
                    'importe_pagado'        => $totalcart['totalCart'],
                    'affected'              => 0,
                    'observaciones'         => 0,
                    'estado'                => 'A'
                ]);
                if(!$saveVenta['idventa']){
                    throw new Exception("Error al ingresar la compra, refresca la pagina e intentalo de nuevo.");
                }
                //si la forma de pago es cartera o credito
                if($formapago['fp_nombre'] == "Credito"){
                    //generar cartera
                    $cartera = $this->M_GuardarCartera->guardarCarteraCliente([
                        'idventa'               => $saveVenta['idventa'],
                        'idsucursal'            => $_SESSION['idsucursal'],
                        'fecha_pago'            => date_format_calendar($_POST["end_date"],"/"),
                        'fecha_ultimo_pago'     => '0001-01-01',
                        'total_pago'            => 0,
                        'deuda_total'           => $totalcart['totalCart'],
                        'contabilidad'          => 0,
                        'estado'                => 'A'
                    ]);
                }
                /***********congifuracion de impuestos y retenciones ************/
                $impuestos = $this->M_CartImpuesto->getImpuestosBy(['cdim_ci_id' => $getCart['ci_id']]);
                $retenciones = $this->M_CartRetencion->getRetencionBy(['cdr_ci_id' => $getCart['ci_id']]);
                /***********fin congifuracion de impuestos y retenciones ************/
                //registrar impuestos
                $arrayImpuestos = [];
                foreach($impuestos as $impuestos){
                    $arrayImpuestos[]=[
                        'dig_registro_comprobante'  => $saveVenta['idventa'],
                        'dig_detalle_registro'      => 'I',
                        'dig_contabilidad'          => 0,
                        'dig_im_id'                 => $impuestos['cdim_im_id'],
                    ];
                }
                $guardarImpuestos = $this->M_DetalleImpuestoGeneral->guardarActualizar($arrayImpuestos);
                //registrar retenciones
                if(count($arrayImpuestos) >0 && !$guardarImpuestos)
                    $arrayAuditoria[] =['tipo' => 'Impuestos Venta', 'estado' => 'No se pudo almacenar los impuestos en la venta #'.$saveVenta['idventa'], 'llaveForanea' => $saveVenta['idventa'], 'tabla' => 'venta'];

                $arrayRetenciones = [];
                foreach($retenciones as $retenciones){
                    $arrayRetenciones[] =[
                        'drg_registro_comprobante'  => $saveVenta['idventa'],
                        'drg_detalle_registro'      => "I",
                        'drg_contabilidad'          => 0,
                        'drg_re_id'                 => $retenciones['cdr_re_id'],
                    ];
                }
                $this->M_DetalleRetencionGeneral->guardarActualizar($arrayRetenciones);
                if(count($arrayRetenciones) >0 && !$guardarImpuestos)
                    $arrayAuditoria[] =['tipo' => 'Retenciones Venta', 'estado' => 'No se pudo almacenar las retenciones en la venta #'.$saveVenta['idventa'], 'llaveForanea' => $saveVenta['idventa'], 'tabla' => 'venta'];

                $impuesto_venta = 0;
                $arrayArticulos=[];
                foreach ($getArticulos as $articulos) {
                    $impuesto_venta += $articulos['cdi_precio_total_lote'] -($articulos['cdi_precio_total_lote'] /(($articulos['cdi_importe']/100)+1));
                    $arrayArticulos[] = [
                        'idventa'               => $saveVenta['idventa'],
                        'idarticulo'            => $articulos['cdi_idarticulo'],
                        'cantidad'              => $articulos['cdi_stock_ingreso'],
                        'precio_venta'          => $articulos['cdi_precio_unitario'],
                        'iva_compra'            => $articulos['cdi_precio_total_lote'] -($articulos['cdi_precio_total_lote'] /(($articulos['cdi_importe']/100)+1)),
                        'importe_categoria'     => $articulos['cdi_importe'],
                        'precio_total_lote'     => $articulos['cdi_precio_total_lote'],
                        'estado'                => 'A'
                    ];
                }
                $addArticulos = $this->M_GuardarArticulosVentas->gurardarArticulosVenta($arrayArticulos);
                if($comprobante['dds_afecta_inventario'])
                    $this->actualizarInventario($arrayArticulos);
                    $totalMetodosPago = 0;
                    $status_pago = false;
                    $listaMetodo = $this->M_CartMetodoPago->obtenerMetodosPagos([ 'cdmp_ci_id'   => $getCart['ci_id'] ]);
                    if($listaMetodo){
                        foreach($listaMetodo as $metodo){
                            $totalMetodosPago += $metodo['cdmp_monto'];
                            $status_pago =true;
                        }
                    }
                    $arrayMetodosPago = [];
                    if($status_pago){
                        foreach($listaMetodo as $metodopago){
                            $dmpg_monto_devolucion = 0;
                            if($metodopago['mp_devolucion'] && $metodopago['totalMetodoPagos'] > $totalcart['totalCart']){
                                $dmpg_monto_devolucion = $metodopago['totalMetodoPagos'] - $totalcart['totalCart'];
                            }
                            $arrayMetodosPago[]=[
                                'dmpg_registro_comprobante' => $saveVenta['idventa'],
                                'dmpg_detalle_registro'     => 'V',
                                'dmpg_contabilidad'         => 0,
                                'dmpg_mp_id'                => $metodopago['mp_id'],
                                'dmpg_monto'                => $metodopago['cdmp_monto'],
                                'dmpg_devolucion'           => $dmpg_monto_devolucion,
                            ];
                        }
                    }else{
                        $mpdefault = $this->M_MetodosPago->obtenerMetodoPagoDefecto([ 'mp_idsucursal' => $_SESSION['idsucursal'] ]);
                        $arrayMetodosPago[] = [
                            'dmpg_registro_comprobante' => $saveVenta['idventa'],
                            'dmpg_detalle_registro'     => 'V',
                            'dmpg_contabilidad'         => 0,
                            'dmpg_mp_id'                => $mpdefault['mp_id'],
                            'dmpg_monto'                => $totalcart['totalCart'],
                            'dmpg_devolucion'           => 0
                        ];
                    }
                    $guardatMetodosPagoTransaccion = $this->M_MetodosPago->guardarMetodoPagoTransaccion($arrayMetodosPago);
                    if(!$guardatMetodosPagoTransaccion)
                        $arrayAuditoria[] =['tipo' => 'Metodos Pago', 'estado' => 'No se pudo almacenar los metodos de pago en la venta #'.$saveVenta['idventa'], 'llaveForanea' => $saveVenta['idventa'], 'tb_cola_detalle_metodo_pago' => 'venta'];

                    if($addArticulos){
                        //eliminar carro
                        $this->M_Cart->deleteCart();
                        $this->M_GuardarVenta->guardarActualizarVenta([
                            'subtotal_importe'  => $impuesto_venta,
                            'idventa'         => $saveVenta['idventa']
                        ]);
                        $this->M_DocumentoSucursal->usarDocumentoSucursal([
                            'iddetalle_documento_sucursal'  => $comprobante['iddetalle_documento_sucursal'],
                            'ultimo_numero'                 => zero_fill($comprobante['ultimo_numero']+1,8)
                        ]);
                        $success        = "PrintVenta/index/".$saveVenta['idventa'];
                        $estadoVenta    = 'success';
                        $estado         = true;
                        $mensajeVenta   = 'Venta realizada';
                    }else{
                        throw new Exception("Error al almacenar los articulos");
                    }
        } catch (\Throwable $e) {
            $mensajeVenta = $e->getMessage();
        }
        echo json_encode([
            'success'       => $success,
            'mensajeVenta'  => $mensajeVenta,
            'estado'        => $estado,
            'estadoVenta'   => $estadoVenta,
            'auditoria'     => $arrayAuditoria,
        ]);
    }

    private function actualizarInventario($arrayArticulos)
    {
        $this->loadModel(['Articulos/M_ArticulosInventario', 'Articulos/M_Articulos']);
        $arrayStock = [];
        $estadoActualizarInventario = false;
        $actualizarInventario = false;
        foreach ($arrayArticulos as $articulo) {
            $infoArticulo = $this->M_Articulos->getArticuloBy([ 'idarticulo' => $articulo['idarticulo'] ]);
            $actualizarInventario = $this->M_ArticulosInventario->actualizarInventario([
                'st_idsucursal'     => $_SESSION['idsucursal'],
                'idarticulo'        => $articulo['idarticulo'],
                'stock'             => $infoArticulo['stock'] - $articulo['cantidad'],
            ]);
        }
        if($actualizarInventario)
            $estadoActualizarInventario = true;

        return $estadoActualizarInventario;
    }
}