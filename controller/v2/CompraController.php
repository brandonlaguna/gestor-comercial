<?php
use Carbon\Carbon;
class CompraController extends ControladorBase{
    public $conectar;
	public $adapter;
    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Compras/M_ReporteCompras',
            'Compras/M_GuardarCompra',
            'Personas/M_Personas',
            'DocumentoSucursal/M_DocumentoSucursal',
            'Cart/M_Cart',
            'FormaPago/M_FormaPago',
            'Cartera/M_GuardarCartera',
            'Cart/M_CartImpuesto',
            'Cart/M_CartRetencion',
            'Impuestos/M_DetalleImpuestoGeneral',
            'Retenciones/M_DetalleRetencionGeneral',
            'Compras/M_GuardarArticulosCompras',
            'Sucursales/M_Sucursales',
            'Impuestos/M_Impuestos',
            'Retenciones/M_Retenciones'
        ],$this->adapter);
    }

    public function index()
    {
        $this->frameview('v2/Compras/compras',[]);
        $this->load('v2/Compras/comprasTable',[]);
        $this->load('v2/Compras/comprasModals',[]);
        $this->load('v2/Compras/comprasScript',[]);
    }

    public function crear()
    {
        $filtroFormasPago = [
            'fp_proceso'        => "Ingreso",
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

        $this->frameview('v2/Compras/crearCompra/crearCompra',[
            "contabilidad"      => 'Ingreso',
            "sucursal"          => $this->M_Sucursales->getSucursales($_SESSION['idsucursal']),
            "idusuario"         => $_SESSION["usr_uid"],
            "pos"               => "Ingreso",
            "comprobantes"      => $this->M_DocumentoSucursal->getDocumentoSucursal(null,['proceso'=>'Ingreso']),
            "formaspago"        => $this->M_FormaPago->obtenerFormaPago($filtroFormasPago),
            "items"             => $items,
            "autocomplete"      => "nombre_articulo",
            "impuestos"         => $this->M_Impuestos->getImpuestos(),
            "retenciones"       => $this->M_Retenciones->getRetenciones()
        ]);
        $this->load('v2/Compras/crearCompra/crearCompraModals',[]);
        $this->load('v2/Compras/crearCompra/crearCompraScript',[]);
    }

    public function getComprasAjax()
    {
        $arrayCompras = [];
        $compras = $this->M_ReporteCompras->getReporteCompras();
        $rowCompras = 0;
        foreach ($compras as $compra) {
            $infoCompra ='<div class="btn-group p-1">
									<button type="button" class="btn btn-circle btn-info btn-sm" onclick="btnAbonosCompra('.$compra['idingreso'].', '.$rowCompras.');">
										<i class="fas fa-money-check-alt">
											<sup>
												<span class="badge badge-light">'.$compra['cantidadAbonos'].'</span>
											</sup>
										</i>
									</button>
								</div>';
            $arrayCompras[] =[
                0   => $compra['idingreso'],
                1   => $compra['fecha'],
                2   => '<div class="row">
                            <div class="col-10">
                                <b>Proveedor:</b> '.$compra['nombreProveedor'].'
                                <br><b>Tipo comprobante:</b> '.$compra['tipo_comprobante'].'
                                <br><b>Tipo pago:</b> '.$compra['tipo_pago'].'
                            </div>
                            <div class="col-2 text-right">
                                <div class="btn-group-vertical btn-group-sm">
                                    '.$infoCompra.'
                                </div>
                            </div>
                        </div>',
                3   => $compra['serie_comprobante'].'-'.$compra['num_comprobante'],
                4   => moneda($compra['totalImpuesto'],0,'$'),
                5   => moneda($compra['totalCompra'],0,'$'),
                6   => status($compra['estado']),
                7   => '<a href="#print/compra/'.$compra['idingreso'].'"><i class="far fa-file-pdf text-success"></i></a>',
            ];
            $rowCompras++;
        }
        echo json_encode($arrayCompras);
    }

    public function crearCompra()
    {
            //ver que los datos se enviaron
            $estado         = false;
            $mensajeCompra  = 'No se pudo almacenar ésta compra';
            $estadoCompra   = 'error';
            $success        = '';
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
                $subtotal   = $this->M_Cart->getSubTotal($getCart['ci_id']);
                $total      = $this->calculoCompra2($_POST["comprobante"]);
                $saveIngreso = $this->M_GuardarCompra->guardarCompra([
                    'idusuario'             => $_SESSION['usr_uid'],
                    'idsucursal'            => $_SESSION['idsucursal'],
                    'idproveedor'           => $dataproveedor['idpersona'],
                    'tipo_pago'             => $formapago['fp_nombre'],
                    'tipo_comprobante'      => $comprobante['nombre_documento'],
                    'serie_comprobante'     => $comprobante['ultima_serie'],
                    'num_comprobante'       => zero_fill($comprobante['ultimo_numero']+1,8),
                    'factura_proveedor'     => $_POST["factura_proveedor"],
                    'fecha'                 => date_format_calendar($_POST["start_date"],"/"),
                    'fecha_final'           => date_format_calendar($_POST["end_date"],"/"),
                    'impuesto'              => 0,
                    'sub_total'             => $subtotal['subtotal'],
                    'subtotal_importe'      => 0,
                    'total'                 => $total,
                    'importe_pagado'        => $total,
                    'estado'                => 'A'
                ]);
                if(!$saveIngreso['idingreso']){
                    throw new Exception("Error al ingresar la compra, refresca la pagina e intentalo de nuevo.");
                }
                //si la forma de pago es cartera o credito
                if($formapago['fp_nombre'] == "Credito"){
                    //generar cartera
                    $cartera = $this->M_GuardarCartera->guardarCarteraProveedor([
                        'idingreso'         => $saveIngreso['idingreso'],
                        'idsucursal'        => $_SESSION['idsucursal'],
                        'fecha_pago'        => date_format_calendar($_POST["end_date"],"/"),
                        'fecha_ultimo_pago' => '0001-01-01',
                        'total_pago'        => 0,
                        'deuda_total'       => $total,
                        'contabilidad'      => 0,
                        'estado'            => 'A'
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
                        'dig_registro_comprobante'  => $saveIngreso['idingreso'],
                        'dig_detalle_registro'      => 'I',
                        'dig_contabilidad'          => 0,
                        'dig_im_id'                 => $impuestos['cdim_im_id'],
                    ];
                }
                $this->M_DetalleImpuestoGeneral->guardarActualizar($arrayImpuestos);
                //registrar retenciones
                $arrayRetenciones = [];
                foreach($retenciones as $retenciones){
                    $arrayRetenciones[] =[
                        'drg_registro_comprobante'  => $saveIngreso['idingreso'],
                        'drg_detalle_registro'      => "I",
                        'drg_contabilidad'          => 0,
                        'drg_re_id'                 => $retenciones['cdr_re_id'],
                    ];
                }
                $this->M_DetalleRetencionGeneral->guardarActualizar($arrayRetenciones);
                    $impuesto_compra = 0;
                    $arrayArticulos=[];
                    foreach ($getArticulos as $articulos) {
                        $impuesto_compra += $articulos['cdi_precio_total_lote'] -($articulos['cdi_precio_total_lote'] /(($articulos['cdi_importe']/100)+1));
                        $arrayArticulos[] = [
                            'idingreso'                 => $saveIngreso['idingreso'],
                            'idarticulo'                => $articulos['cdi_idarticulo'],
                            'codigo'                    => 0,
                            'serie'                     => 0,
                            'descripcion'               => '',
                            'stock_ingreso'             => $articulos['cdi_stock_ingreso'],
                            'stock_actual'              => 0,
                            'precio_compra'             => $articulos['cdi_precio_unitario'],
                            'iva_compra'                => $articulos['cdi_precio_total_lote'] -($articulos['cdi_precio_total_lote'] /(($articulos['cdi_importe']/100)+1)),
                            'importe_categoria'         => $articulos['cdi_importe'],
                            'precio_total_lote'         => $articulos['cdi_precio_total_lote'],
                            'precio_ventadistribuidor'  => 0,
                            'precio_ventapublico'       => 0,
                        ];
                    }
                    $addArticulos = $this->M_GuardarArticulosCompras->gurardarArticulosCompra($arrayArticulos);
                    if($addArticulos){
                        //eliminar carro
                        $this->M_Cart->deleteCart();
                        $this->M_GuardarCompra->actualizarCompra([
                            'subtotal_importe'  => $impuesto_compra,
                            'idingreso'         => $saveIngreso['idingreso']
                        ]);
                        $this->M_DocumentoSucursal->usarDocumentoSucursal([
                            'iddetalle_documento_sucursal'  => $comprobante['iddetalle_documento_sucursal'],
                            'ultimo_numero'                 => zero_fill($comprobante['ultimo_numero']+1,8)

                        ]);
                        $success        = "file/ingreso/".$saveIngreso['idingreso'];
                        $estado         = true;
                        $mensajeCompra  = 'Compra realizada';
                    }else{
                        throw new Exception("Error al almacenar los articulos");
                    }
        } catch (\Throwable $e) {
            $mensajeCompra = $e->getMessage();
        }
        echo json_encode([
            'success'       => $success,
            'mensajeCompra' => $mensajeCompra,
            'estado'        => $estado,
            'estadoCompra'  => $estadoCompra
        ]);
    }

    public function calculoCompra2($idcomprobante)
    {
            $dataretenciones = new Retenciones($this->adapter);
            $dataimpuestos= new Impuestos($this->adapter);
            $totalcart = new ColaIngreso($this->adapter);
            $colaretencion = new Colaretencion($this->adapter);
            $colaimpuestos= new ColaImpuesto($this->adapter);
            $colaingreso = new ColaIngreso($this->adapter);
            $getCart = $colaingreso->getCart();
            foreach($getCart as $getCart){}

            $retenciones = $colaretencion->getRetencionBy($getCart->ci_id);
            $impuestos = $colaimpuestos->getImpuestosBy($getCart->ci_id);

            $subtotal = $colaingreso->getSubTotal($getCart->ci_id);
            $totalimpuestos = $colaingreso->getImpuestos($getCart->ci_id);
            
            //obter subtotal
            foreach ($subtotal as $subtotal) {}
            //valores a imprimir
            $subtotalimpuesto = 0;
            $listImpuesto = [];
            $listRetencion =[];
            $total_bruto = $subtotal->subtotal;
            $total_neto = $subtotal->subtotal;
            //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
            foreach ($totalimpuestos as $imp) {
                $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                foreach($impuestos as $data){}
                if($impuestos){
                   if($data->im_porcentaje == $imp->cdi_importe){
                    //$total_neto = $subtotalimpuesto;
                    //$total_bruto -= $subtotalimpuesto;
                   }
                   else{

                   }
                }else{
                
                }
                
                foreach ($impuestos as $impuesto) {
                    if($imp->cdi_importe == $impuesto->im_porcentaje){
                        //calculado
                        $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                        //concatenacion del nombre
                        $im_nombre = $impuesto->im_nombre." ".$impuesto->im_porcentaje."%";
                        //arreglo
                        $listImpuesto[] = array($im_nombre,$calc,$impuesto->cdim_id);
                        /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                        $total_neto += $calc;
                    }else{
                        //si el impuesto puede afectar al subtotal calcula sobre el subtotal, esto para algunosimpuestos obligatorios
                        //sirve para no afectar a algunos articulos como tal, sino solo sobre el subtotal antes de iva
                        if($impuesto->im_subtotal){
                            $sub = ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                            $calc = $sub *($impuesto->im_porcentaje/100);
                            $im_nombre = $impuesto->im_nombre." ".$impuesto->im_porcentaje."%";
                            $listImpuesto[] = array($im_nombre,$calc,$impuesto->cdim_id);
                            $total_neto += $calc;
                        }
                    }
                }
            }
                foreach ($retenciones as $retencion) {
                    if($retencion->re_im_id <= 0){
                        //concatenacion del nombre
                        $re_nombre = $retencion->re_nombre." ".$retencion->re_porcentaje."%";
                        //calculado $subtotal->cdi_debito*($retencion->re_porcentaje/100)
                        $calc = $total_bruto* ($retencion->re_porcentaje/100);
                        //arreglo
                        $listRetencion[] = array($re_nombre,$calc);
                        /************************RESTANDO RETENCION DEL CALCULO*****************************/
                        $total_neto -= $calc;
                    }else{
                    foreach ($totalimpuestos as $imp) {
                    $impid = $dataimpuestos->getImpuestosById($retencion->re_im_id);
                    foreach ($impid as $impid) {
                        if($imp->cdi_importe == $impid->im_porcentaje){
                            $re_nombre = $retencion->re_nombre." (".$retencion->re_porcentaje."%)";
                            $iva =$imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));

                            $calc =$iva*($retencion->re_porcentaje/100);

                            $listRetencion[] = array($re_nombre,$calc);
                            /************************RESTANDO RETENCION DEL CALCULO*****************************/
                            $total_neto -= $calc;
                        }else{
                        }
                    }
                }
            }
            }
            return $total_neto;
    }
}