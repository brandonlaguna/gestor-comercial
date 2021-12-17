<?php
use Carbon\Carbon;
class VentaContableController extends ControladorBase{
    public $conectar;
	public $adapter;
    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Impuestos/M_Impuestos',
            'Permisos/M_Permisos',
            'ComprobanteContable/M_ComprobanteContable',
            'ComprobanteContable/M_DetalleComprobanteContable',
            'Impuestos/M_DetalleImpuestoGeneral',
            'Retenciones/M_DetalleRetencionGeneral',
            'DocumentoSucursal/M_DocumentoSucursal',
            'Cart/M_Cart',
            'FormaPago/M_FormaPago'
        ],$this->adapter);
    }

    public function index()
    {
        $this->frameview('v2/VentaContable/ventaContable',[]);
        $this->load('v2/VentaContable/ventaContableScript',[]);
        $this->load('v2/VentaContable/ventaContableModals',[]);
        $this->load('v2/VentaContable/ventaContableTable',[]);
    }

    public function obtenerVentasContablesAjax()
    {
        $comprobantesContables = $this->M_ComprobanteContable->obtenerComprobanteContable([
            'cc_tipo_comprobante'   => 'V'
        ]);
        $infoventaContable = '';
        $arrayVentaContable = [];
        foreach ($comprobantesContables as $ventaContable) {
            $arrayVentaContable[] =[
                0   => $ventaContable['cc_id_transa'],
                1   => $ventaContable['cc_fecha_cpte'],
                2   => '<div class="row">
                            <div class="col-10">
                                <b>Cliente:</b> '.$ventaContable['nombre_tercero'].'
                                <br><b>Tipo comprobante:</b> '.$ventaContable['tipo_comprobante'].'
                                <br><b>Tipo pago:</b> '.$ventaContable['fp_nombre'].'
                            </div>
                            <div class="col-2 text-right">
                                <div class="btn-group-vertical btn-group-sm">
                                    '.$infoventaContable.'
                                </div>
                            </div>
                        </div>',
                3   => moneda($ventaContable['cdi_debito'],0,'$'),
                4   => moneda($ventaContable['cdi_credito'],0,'$'),
                5   => moneda($ventaContable['subtotal'],0,'$'),
                6   => status($ventaContable['cc_estado']),
                7   => '<a href="#print/contabilidad/'.$ventaContable['cc_id_transa'].'"><i class="far fa-file-pdf text-success"></i></a>',
            ];
        }
        echo json_encode($arrayVentaContable);
    }

    public function nueva()
    {
        $this->Verificar->verificarPermisoAccion($this->M_Permisos->estado(2,$_SESSION["usr_uid"]));
        $sucursal       = new Sucursal($this->adapter);
        $comprobante    = new Comprobante($this->adapter);
        $formapago      = new FormaPago($this->adapter);
        $cart           = new ColaIngreso($this->adapter);
        $impuesto       = new Impuestos($this->adapter);
        $retencion      = new Retenciones($this->adapter);
        $metodopago     = new MetodoPago($this->adapter);
        $empleados      = new Empleado($this->adapter);

        //functions
        $metodospago    = $metodopago->getAllMetodoPago();
        $empleado       = $empleados->getEmpleadoByUserId($_SESSION["usr_uid"]);
        //ubicacion
        $idusuario          = $_SESSION["usr_uid"];
        $pos_proceso        ="Venta";
        $contabilidad       = "Contable";
        $autocomplete       = "codigo_contable";
        $control_proceso    ="";
        //obtener sucursal
        $idsucursal     = $_SESSION["idsucursal"];
        $getsucursal    = $sucursal->getSucursalById($idsucursal);
        $formaspago     = $formapago->getFormaPago($pos_proceso);
        $comprobantes   = $comprobante->getComprobanteContable($pos_proceso);
        //cargar impuestos y retenciones
        $impuestos      = $impuesto->getImpuestosAll();
        $retenciones    = $retencion->getRetencionesAll();
        //
        $cart->setCi_usuario($_SESSION["usr_uid"]);
        $cart->setCi_idsucursal($_SESSION["idsucursal"]);
        $cart->setCi_idproveedor(0);
        $cart->setCi_tipo_pago(0);
        $cart->setCi_comprobante(0);
        $cart->setCi_fecha(date("Y-m-d"));
        $cart->setCi_fecha_final(date("Y-m-d"));
        $addCart    = $cart->createCart();

        $items      = $cart->loadArtByCart($addCart);

        $hidden= "default_hidden";
        $this->load('v2/VentaContable/nuevaVenta/ventaContableScript',[]);
        $this->load('v2/VentaContable/nuevaVenta/ventaContableModal',[]);
        $this->frameview("v2/VentaContable/nuevaVenta/ventaContable",array(
            "contabilidad"=>$contabilidad,
            "sucursal"=>$getsucursal,
            "idusuario"=>$idusuario,
            "pos"=>$pos_proceso,
            "autocomplete"=>$autocomplete,
            "control"=>$control_proceso,
            "comprobantes" => $comprobantes,
            "formaspago" => $formaspago,
            "items"=>$items,
            "impuestos"=>$impuestos,
            "retenciones"=>$retenciones,
            "metodospago"=>$metodospago,
            "empleado"=>$empleado,
            "hidden"=>$hidden,
        ));
    }

    public function crearVentaContable()
    {
        $success    = false;
        $type       = "message";
        $response   = "Error al crear la venta";
        $alertType  = "error";
        try {
            $estadoPermiso = $this->Verificar->verificarPermisoAccion($this->M_Permisos->estado(2,$_SESSION["usr_uid"]));
            if(!$estadoPermiso){
                throw new Exception("No tienes permisos para crear esta venta, quizá haya caducado la sesion");
            }
            if(!isset($_SESSION["idsucursal"])){
                throw new Exception("No se puede acceder a esta sucursal, cierra sesion e intentalo nuevamente");
            }
            if(!isset($_POST["proveedor"])){
                throw new Exception("Agrega un proveedor valido");
            }
            //lista de modelos
            $dataarticulos = new Articulo($this->adapter);
            $puc = new PUC($this->adapter);
            $impuestos = new Impuestos($this->adapter);
            $retenciones = new Retenciones($this->adapter);
            $categorias = new Categoria($this->adapter);
            $cartera = new Cartera($this->adapter);
            $mymediafiles= new MyMediaFiles($this->adapter);
            $clientes = new Persona($this->adapter);
            $comprobantes = new Comprobante($this->adapter);
            $colaimpuesto = new Colaimpuesto($this->adapter);
            $colaretencion = new Colaretencion($this->adapter);
            $detalleimpuesto = new DetalleImpuesto($this->adapter);
            $detalleretencion = new DetalleRetencion($this->adapter);
            $carro = new ColaIngreso($this->adapter);
            $cierreturno = new CierreTurno($this->adapter);
            //funciones
            $forma_pago = $_POST["formaPago"];
            //dividir estring
            //capurar datos del cliente
            if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"])){
                $array = explode(" - ", $_POST["proveedor"]);
                $i =0;
            }else{
                throw new Exception("Debe ingresar un cliente");
            }
            //se recupera datos del cliente
            foreach ($array as $search) {$getClientes = $clientes->getClienteByDocument($array[$i]);
                //si se encontro algo en proveedores lo retorna
                foreach ($getClientes as $datacliente) {}
                    $i++;
            }
            if(!$datacliente){
                throw new Exception("Cliente inexistente");
            }
            $idproveedor    = $datacliente->idpersona;
            $idcomprobante  = $_POST["comprobante"];
            $comprobante = $this->M_DocumentoSucursal->getDocumentoSucursal($idcomprobante);
            /*************info comprobante***********/
            $tipoComprobante        = $comprobante['iddetalle_documento_sucursal'];
            $serieComprobante       = $comprobante['ultima_serie'];
            $ultimoNComprobante     = $comprobante['ultimo_numero']+1;
            $comprobanteContable    = $comprobante['contabilidad'];
            /***********fin info comprobante*********/
             //obtener carro de articulos
            $getCart    = $this->M_Cart->obtenerUltimoCarrito([
                'idsucursal'    => $_SESSION['idsucursal'],
                'idusuario'     => $_SESSION['usr_uid']
            ]);
            //obtener articulos
            $getArticulos = $this->M_Cart->obtenerArticulos([
                'idsucursal'    => $_SESSION['idsucursal'],
                'idusuario'     => $_SESSION['usr_uid'],
                'ci_id'         => $getCart['ci_id']
            ]);
            $cuentaContablePago = false;
            foreach ($getArticulos as $buscarCuentaPago) {
                if($buscarCuentaPago['cdi_type'] == "CO"){
                    $cuentaContablePago = true;
                }
            }
            if(!$cuentaContablePago){
                throw new Exception("Debe ingresar una cuenta contable");
            }
            //obtener tipo de pago
            $formapago      = $this->M_FormaPago->obtenerFormaPago(['fp_id' => $_POST["formaPago"]]);
            $formadepago    = $formapago['fp_id'];
            //articulos recuperados
            $end_date = date_format_calendar($_POST["end_date"],"/");
            //si hay articulos en el carro de lo contrario cancela el proceso
            $state =0;
            if(!$getArticulos){
                throw new Exception("Ningun articulo agregado.");
            }
                //verificar si es una afectacion de otra factura
                $arrayDetalleComprobanteContable =[];
                $addVenta = $this->M_ComprobanteContable->guardarActualizarComprobanteContable([
                    'cc_id_tipo_cpte'       => 0,
                    'cc_num_cpte'           => 0,
                    'cc_cons_cpte'          => 0,
                    'cc_idusuario'          => $_SESSION["usr_uid"],
                    'cc_idproveedor'        => $datacliente->idpersona,
                    'cc_tipo_comprobante'   => "V",
                    'cc_id_forma_pago'      => $forma_pago,
                    'cc_det_fact_prov'      => $serieComprobante.zero_fill($ultimoNComprobante,8),
                    'cc_fecha_cpte'         => date_format_calendar($_POST["start_date"],"/"),
                    'cc_fecha_final_cpte'   => $end_date,
                    'cc_nit_cpte'           => $datacliente->num_documento,
                    'cc_dig_verifi'         => 0,
                    'cc_ccos_cpte'          => $_SESSION["idsucursal"],
                    'cc_fp_cpte'            => $formadepago,
                    'cc_estado'             => "A",
                    'cc_log_reg'            => $_SESSION['usr_uid']."_".date("Y-m-d h:i:s"),
                    'cc_det_subtotal'       => 0
                ]);
                if($addVenta){
                    $agregarConsecutivo = $this->M_ComprobanteContable->guardarActualizarComprobanteContable([
                        'cc_id_tipo_cpte'       => $tipoComprobante,
                        'cc_num_cpte'           => $serieComprobante,
                        'cc_cons_cpte'          => zero_fill($ultimoNComprobante,8),
                        'cc_id_transa'          => $addVenta,
                    ]);
                    if($agregarConsecutivo){
                        $comprobantes->usarComprobante($idcomprobante);
                    }else{
                        throw new Exception("No se pudo agregar un consecutivo a este comprobante");
                    }
                }else{
                    throw new Exception("Comprobante contable no pudo ser almacenado");
                }
                $listImpuestos = $colaimpuesto->getImpuestosBy($getCart['ci_id']);
                $listRetenciones = $colaretencion->getRetencionBy($getCart['ci_id']);
                /***********fin congifuracion de impuestos y retenciones ************/
                //registrar impuestos
                $arrayImpuestos =[];
                foreach($listImpuestos as $dataImp){
                    $arrayImpuestos[] =[
                        'dig_registro_comprobante'  => $addVenta,
                        'dig_detalle_registro'      => "V",
                        'dig_contabilidad'          => 1,
                        'dig_im_id'                 => $dataImp->cdim_im_id,
                    ];
                }
                $this->M_DetalleImpuestoGeneral->guardarActualizar($arrayImpuestos);
                //registrar retenciones
                $arrayRetenciones=[];
                foreach($listRetenciones as $dataRe){
                    $arrayRetenciones[] =[
                        'drg_registro_comprobante'  => $addVenta,
                        'drg_detalle_registro'      => "V",
                        'drg_contabilidad'          => 1,
                        'drg_re_id'                 => $dataRe->cdr_re_id,
                    ];
                }
                $this->M_DetalleRetencionGeneral->guardarActualizar($arrayRetenciones);
                $total_retenido =0;
                foreach ($getArticulos as $item) {
                    if($item['cdi_type'] == "AR"){
                        $articulo = $dataarticulos->getArticuloById($item['cdi_idarticulo']);
                        foreach ($articulo as $articulo) {}
                        //almacenando articulos
                        //$addStock = $dataarticulos->removeCantStock($item['cdi_idarticulo'],$item['cdi_stock_ingreso']);
                        //obtener la categoria de este articulo
                        $categoria = $categorias->getCategoriaById($articulo->idcategoria);
                        foreach ($categoria as $categoria) {}
                        //obtener codigo contable de la categoria -> codigo de inventario
                        $cuenta_inventario = $puc->getPucById($categoria->cod_venta);
                        foreach ($cuenta_inventario as $cuenta_inventario) {}
                        $inventario = ($cuenta_inventario !=null)? $cuenta_inventario->idcodigo:$categoria->cod_venta;
                        $arrayDetalleComprobanteContable[]=[
                            'dcc_id_trans'          => $addVenta,
                            'dcc_seq_detalle'       => 0,
                            'dcc_cta_item_det'      => $inventario,
                            'dcc_det_item_det'      => $articulo->nombre_articulo,
                            'dcc_cod_art'           => $articulo->idarticulo,
                            'dcc_cant_item_det'     => $item['cdi_stock_ingreso'],
                            'dcc_ter_item_det'      => $datacliente->num_documento,
                            'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                            'dcc_d_c_item_det'      => 'C',
                            'dcc_valor_item'        => $item['cdi_precio_unitario']*$item['cdi_stock_ingreso'],
                            'dcc_base_imp_item'     => $item['cdi_importe'],
                            'dcc_base_ret_item'     => 0,
                            'dcc_fecha_vcto_item'   => '0001-01-01',
                            'dcc_dato_fact_prove'   => 0,
                        ];
                        $dataarticulos->removeCantStock($articulo->idarticulo,$item['cdi_stock_ingreso']);
                    }else{}
                }
                //recorrer cada articulo y buscar sus impuestos
                foreach ($getArticulos as $dataimpuestos) {
                    if($dataimpuestos['cdi_type'] == "AR"){
                    //$listImpuestos = $impuestos->getImpuestosByComprobanteId($tipoComprobante);
                    foreach ($listImpuestos as $listImpuesto) {
                        if($listImpuesto->im_porcentaje == $dataimpuestos['cdi_importe']){
                            $cuenta = $puc->getPucById($listImpuesto->im_cta_contable);
                            foreach($cuenta as $cuenta){}
                            if($cuenta != null){
                                $precio_total_lote_sin_iva = $dataimpuestos['cdi_precio_total_lote'] / (($listImpuesto->im_porcentaje /100)+1);
                                $arrayDetalleComprobanteContable[]=[
                                    'dcc_id_trans'          => $addVenta,
                                    'dcc_seq_detalle'       => 0,
                                    'dcc_cta_item_det'      => $cuenta->idcodigo,
                                    'dcc_det_item_det'      => $cuenta->tipo_codigo,
                                    'dcc_cod_art'           => 0,
                                    'dcc_cant_item_det'     => $dataimpuestos['cdi_stock_ingreso'],
                                    'dcc_ter_item_det'      => $datacliente->num_documento,
                                    'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                                    'dcc_d_c_item_det'      => 'C',
                                    'dcc_valor_item'        => $precio_total_lote_sin_iva * ($listImpuesto->im_porcentaje/100),
                                    'dcc_base_imp_item'     => $item['cdi_importe'],
                                    'dcc_base_ret_item'     => 0,
                                    'dcc_fecha_vcto_item'   => '0001-01-01',
                                    'dcc_dato_fact_prove'   => 0,
                                ];
                            }
                        }else{
                            $cuenta = $puc->getPucById($listImpuesto->im_cta_contable);
                            if($cuenta !=null){
                            if($listImpuesto->im_subtotal){
                                foreach($cuenta as $cuenta){}
                                $precio_total_lote_sin_iva = $dataimpuestos['cdi_precio_total_lote'] / (($dataimpuestos['cdi_importe'] /100)+1);
                                $arrayDetalleComprobanteContable[]=[
                                    'dcc_id_trans'          => $addVenta,
                                    'dcc_seq_detalle'       => 0,
                                    'dcc_cta_item_det'      => $cuenta->idcodigo,
                                    'dcc_det_item_det'      => $cuenta->tipo_codigo,
                                    'dcc_cod_art'           => 0,
                                    'dcc_cant_item_det'     => $dataimpuestos['cdi_stock_ingreso'],
                                    'dcc_ter_item_det'      => $datacliente->num_documento,
                                    'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                                    'dcc_d_c_item_det'      => 'C',
                                    'dcc_valor_item'        => $precio_total_lote_sin_iva * ($listImpuesto->im_porcentaje/100),
                                    'dcc_base_imp_item'     => $item['cdi_importe'],
                                    'dcc_base_ret_item'     => 0,
                                    'dcc_fecha_vcto_item'   => '0001-01-01',
                                    'dcc_dato_fact_prove'   => 0,
                                ];
                            }else{}
                        }
                        }
                    }
                }
            }
            $impuesto_actual =[];
            //recorrer cada articulo y buscar sus retenciones
            $retencion= false;
            foreach ($getArticulos as $dataretenciones) {
                //filtro por articulo, marcadoen tipo 'AR'
                if($dataretenciones['cdi_type'] == "AR"){
                    //recupero la lista de retenciones
                    $retencion = $colaretencion->getRetencionBy($getCart['ci_id']);
                    //preparo lista de impuestos
                    $dataimpuestos = $colaimpuesto->getImpuestosBy($getCart['ci_id']);
                    //recorro los impuestos en busqueda del correspondiente
                    if($dataimpuestos && $dataretenciones['cdi_importe'] >0){
                        foreach($dataimpuestos as $cola_imp){
                        if($cola_imp->im_porcentaje == $dataretenciones['cdi_importe']){
                            //guardo este impuesto en una variable
                            $impuesto_actual = $impuestos->getImpuestoBy('im_id',$cola_imp->im_id,"");
                            //$impuesto_actual = $cola_imp;
                        }
                    }
                }
                    //recorro las retenciones
                     //[0]=> retencion al subtotal, [1]=> retencion a impuestos
                    $retencion_actual = [];
                    $tipo_proceso = 0;
                    if($retencion){
                    foreach($retencion as $cola_ret){
                        foreach($impuesto_actual as $imp_act){}
                        if($cola_ret->re_im_id == $imp_act->im_id && $impuesto_actual && $dataretenciones['cdi_importe']>0){
                            $cuenta_retencion = $puc->getPucById($cola_ret->re_cta_contable);
                            foreach ($cuenta_retencion as $cuenta_retencion){}
                            // if($cola_ret->re_im_id == $imp_act->im_id){
                                $impuesto_articulo = ($dataretenciones['cdi_importe']>0)?$dataretenciones['cdi_importe']:1;
                                $total = $dataretenciones['cdi_precio_total_lote'] / (($impuesto_articulo/100)+1);
                                $total_impuesto = $total * ($impuesto_articulo/100);
                                $calculo_retencion = $total_impuesto * ($cola_ret->re_porcentaje/100);
                                $total_retenido += $calculo_retencion;
                                $arrayDetalleComprobanteContable[]=[
                                    'dcc_id_trans'          => $addVenta,
                                    'dcc_seq_detalle'       => 0,
                                    'dcc_cta_item_det'      => $cuenta_retencion->idcodigo,
                                    'dcc_det_item_det'      => $cuenta_retencion->tipo_codigo,
                                    'dcc_cod_art'           => 0,
                                    'dcc_cant_item_det'     => $dataretenciones['cdi_stock_ingreso'],
                                    'dcc_ter_item_det'      => $datacliente->num_documento,
                                    'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                                    'dcc_d_c_item_det'      => 'D',
                                    'dcc_valor_item'        => $calculo_retencion,
                                    'dcc_base_imp_item'     => 0,
                                    'dcc_base_ret_item'     => $total_impuesto,
                                    'dcc_fecha_vcto_item'   => '0001-01-01',
                                    'dcc_dato_fact_prove'   => 0,
                                ];
                        }elseif($cola_ret->re_im_id ==0){
                            $cuenta_retencion = $puc->getPucById($cola_ret->re_cta_contable);
                            foreach ($cuenta_retencion as $cuenta_retencion){}
                            $impuesto_articulo = ($dataretenciones['cdi_importe']>0)?$dataretenciones['cdi_importe']:0;
                            if($impuesto_articulo > 0){
                                $total = $dataretenciones['cdi_precio_total_lote'] / (($impuesto_articulo/100)+1);
                            }else{
                                $total = $dataretenciones['cdi_precio_total_lote'] ;
                            }
                            $retencion_total = $total * ($cola_ret->re_porcentaje/100);
                            $total_retenido +=$retencion_total;
                            $retencion_this = $retencion_total;
                            $arrayDetalleComprobanteContable[]=[
                                'dcc_id_trans'          => $addVenta,
                                'dcc_seq_detalle'       => 0,
                                'dcc_cta_item_det'      => $cuenta_retencion->idcodigo,
                                'dcc_det_item_det'      => $cuenta_retencion->tipo_codigo,
                                'dcc_cod_art'           => 0,
                                'dcc_cant_item_det'     => $dataretenciones['cdi_stock_ingreso'],
                                'dcc_ter_item_det'      => $datacliente->num_documento,
                                'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                                'dcc_d_c_item_det'      => 'D',
                                'dcc_valor_item'        => $retencion_this,
                                'dcc_base_imp_item'     => 0,
                                'dcc_base_ret_item'     => $total,
                                'dcc_fecha_vcto_item'   => '0001-01-01',
                                'dcc_dato_fact_prove'   => 0,
                            ];
                        }
                    }
                    }
                }
            }
            //recuperar cuenta de pago
            foreach ($getArticulos as $datapago) {
                if($datapago['cdi_type'] == "CO"){
                    //cargaremos informacio de donde va a salir el dinero
                    if($formadepago == "Credito"){
                        $cuenta = $puc->getPucById($formapago->fp_cuenta_contable);
                        foreach ($cuenta as $cuenta) {}
                        $arrayDetalleComprobanteContable[]=[
                            'dcc_id_trans'          => $addVenta,
                            'dcc_seq_detalle'       => 0,
                            'dcc_cta_item_det'      => $cuenta->idcodigo,
                            'dcc_det_item_det'      => $cuenta->tipo_codigo,
                            'dcc_cod_art'           => 0,
                            'dcc_cant_item_det'     => $datapago['cdi_stock_ingreso'],
                            'dcc_ter_item_det'      => $datacliente->num_documento,
                            'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                            'dcc_d_c_item_det'      => 'D',
                            'dcc_valor_item'        => $datapago['cdi_precio_total_lote']- $total_retenido,
                            'dcc_base_imp_item'     => 0,
                            'dcc_base_ret_item'     => 0,
                            'dcc_fecha_vcto_item'   => $end_date,
                            'dcc_dato_fact_prove'   => $serieComprobante.zero_fill($ultimoNComprobante,8),
                        ];
                        //generar cartera de proveedores
                        $cartera->setIdventa($addVenta);
                        $cartera->setFecha_pago($end_date);
                        $cartera->setTotal_pago(0);
                        $cartera->setDeuda_total($datapago['cdi_precio_total_lote'] - $total_retenido);
                        $cartera->setContabilidad(1);
                        $cartera->setC_estado("A");
                        $generarCartera = $cartera->generarCarteraCliente();
                    }else{
                        $cuentaPago = $datapago['cdi_idarticulo'];
                        if(isset($formapago->fp_cuenta_contable) && $formapago->fp_cuenta_contable > 0){
                            $cuentaPago = $formapago->fp_cuenta_contable;
                        }
                        $cuenta = $puc->getPucById($cuentaPago);
                        foreach ($cuenta as $cuenta) {}
                        $cuentaContablePago     = $cuenta->tipo_codigo;
                        $idCuentaContablePago   = $cuenta->idcodigo;
                        //se agrega el el codigo contable de la forma de pago ya predefinida en la configuracion
                        $arrayDetalleComprobanteContable[]=[
                            'dcc_id_trans'          => $addVenta,
                            'dcc_seq_detalle'       => 0,
                            'dcc_cta_item_det'      => $idCuentaContablePago,
                            'dcc_det_item_det'      => $cuentaContablePago,
                            'dcc_cod_art'           => 0,
                            'dcc_cant_item_det'     => $datapago['cdi_stock_ingreso'],
                            'dcc_ter_item_det'      => $datacliente->num_documento,
                            'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                            'dcc_d_c_item_det'      => 'D',
                            'dcc_valor_item'        => $datapago['cdi_precio_total_lote'] - $total_retenido,
                            'dcc_base_imp_item'     => 0,
                            'dcc_base_ret_item'     => 0,
                            'dcc_fecha_vcto_item'   => '0001-01-01',
                            'dcc_dato_fact_prove'   => 0,
                        ];
                    }
                }
                }
                foreach($getArticulos as $datainventario){
                    if($datainventario['cdi_type'] == "AR"){
                        $articulo = $dataarticulos->getArticuloById($datainventario['cdi_idarticulo']);
                        foreach($articulo as $articulo){}
                        //codigo de inventario
                        $cuenta_inventario = $puc->getPucById($articulo->cod_inventario);
                        foreach($cuenta_inventario as $cuenta_inventario){}
                        if($cuenta_inventario != null){
                            $arrayDetalleComprobanteContable[]=[
                                'dcc_id_trans'          => $addVenta,
                                'dcc_seq_detalle'       => 0,
                                'dcc_cta_item_det'      => $cuenta_inventario->idcodigo,
                                'dcc_det_item_det'      => $articulo->nombre_articulo,
                                'dcc_cod_art'           => 0,
                                'dcc_cant_item_det'     => $datainventario['cdi_stock_ingreso'],
                                'dcc_ter_item_det'      => $datacliente->num_documento,
                                'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                                'dcc_d_c_item_det'      => 'D',
                                'dcc_valor_item'        => $articulo->costo_producto*$datainventario['cdi_stock_ingreso'],
                                'dcc_base_imp_item'     => 0,
                                'dcc_base_ret_item'     => 0,
                                'dcc_fecha_vcto_item'   => '0001-01-01',
                                'dcc_dato_fact_prove'   => 0,
                            ];
                        }
                        //codigo de costos
                        $cuenta_costos = $puc->getPucById($articulo->cod_costos);
                        foreach($cuenta_costos as $cuenta_costos){}
                        if($cuenta_costos != null){
                            $arrayDetalleComprobanteContable[]=[
                                'dcc_id_trans'          => $addVenta,
                                'dcc_seq_detalle'       => 0,
                                'dcc_cta_item_det'      => $cuenta_costos->idcodigo,
                                'dcc_det_item_det'      => $articulo->nombre_articulo,
                                'dcc_cod_art'           => 0,
                                'dcc_cant_item_det'     => $datainventario['cdi_stock_ingreso'],
                                'dcc_ter_item_det'      => $datacliente->num_documento,
                                'dcc_ccos_item_det'     => $_SESSION["idsucursal"],
                                'dcc_d_c_item_det'      => 'C',
                                'dcc_valor_item'        => $articulo->costo_producto*$datainventario['cdi_stock_ingreso'],
                                'dcc_base_imp_item'     => 0,
                                'dcc_base_ret_item'     => 0,
                                'dcc_fecha_vcto_item'   => '0001-01-01',
                                'dcc_dato_fact_prove'   => 0,
                            ];
                        }
                    }
                }
                if($addVenta){
                    $guardarDetalleComprobante = $this->M_DetalleComprobanteContable->guardarActualizarDetalle($arrayDetalleComprobanteContable);
                    if($guardarDetalleComprobante){
                        $carro->deleteCart();
                        $success    = "print/contabilidad/$addVenta";
                        $type       = "redirect";
                        $response   = "Venta Contable creada correctamente";
                        $alertType  = "success";
                    }else{
                        throw new Exception("Ocurrió un error al almacenar la informacion de los articulos");
                    }
                }else{
                    throw new Exception("Error al crear ésta venta");
                }
        } catch (\Throwable $e) {
            $response   = $e->getMessage();
        }
        echo json_encode([
            'success'   => $success,
            'type'      => $type,
            'response'  => $response,
            'alertType' => $alertType
        ]);
    }

    public function calculoCompra()
    {
        if(isset($_POST["data"]) && !empty($_POST["data"])){

            $getCart    = $this->M_Cart->obtenerUltimoCarrito([
                'idsucursal'    => $_SESSION['idsucursal'],
                'idusuario'     => $_SESSION['usr_uid']
            ]);
            //obtener articulos
            $getArticulos = $this->M_Cart->obtenerArticulos([
                'idsucursal'    => $_SESSION['idsucursal'],
                'idusuario'     => $_SESSION['usr_uid'],
                'ci_id'         => $getCart['ci_id']
            ]);
            $colaretencion = new ColaRetencion($this->adapter);
            $retenciones = $colaretencion->getRetencionBy($getCart['ci_id']);
            $colaimpuestos= new ColaImpuesto($this->adapter);
            $impuestos = $colaimpuestos->getImpuestosBy($getCart['ci_id']);
            $dataretenciones = new Retenciones($this->adapter);
            $dataimpuestos = new Impuestos($this->adapter);
            $totalcart = new ColaIngreso($this->adapter);
            $subtotal = $totalcart->getSubTotal($getCart['ci_id']);
            $totalimpuestos = $totalcart->getImpuestos($getCart['ci_id']);
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
                    $re_nombre = $retencion->re_nombre." ";
                    //calculado $subtotal->cdi_debito*($retencion->re_porcentaje/100)
                    $calc = $total_bruto* ($retencion->re_porcentaje/100);
                    //arreglo
                    $listRetencion[] = array($re_nombre,$calc,$retencion->cdr_id);
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

                        $listRetencion[] = array($re_nombre,$calc,$retencion->cdr_id);
                        /************************RESTANDO RETENCION DEL CALCULO*****************************/
                        $total_neto -= $calc;
                    }else{
                    }
                }
            }
        }
        }
            echo $this->frameview("compras/New/calculoCompra",array(
                "retenciones"=>$listRetencion,
                "impuestos"=>$listImpuesto,
                "total_bruto"=>number_format($total_bruto,0,'.',','),
                "total_neto"=>$total_neto,
            ));
        }else{
        }
    }

    public function calculoVenta2($idcomprobante)
    {
        //models
        $totalcart = new ColaIngreso($this->adapter);
        $dataretenciones = new Retenciones($this->adapter);
        $dataimpuestos= new Impuestos($this->adapter);
        $colaretencion = new ColaRetencion($this->adapter);
        $colaimpuestos= new ColaImpuesto($this->adapter);

            $getCart = $totalcart->getCart();
            foreach($getCart as $getCart){}

            $retenciones = $colaretencion->getRetencionBy($getCart['ci_id']);
            $impuestos = $colaimpuestos->getImpuestosBy($getCart['ci_id']);

            $subtotal = $totalcart->getSubTotal($getCart['ci_id']);
            $totalimpuestos = $totalcart->getImpuestos($getCart['ci_id']);
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
                $subtotalimpuesto += $imp['cdi_debito'] - ($imp['cdi_debito'] / (($imp['cdi_importe']/100)+1));
                foreach($impuestos as $data){}
                if($impuestos){
                    if($data->im_porcentaje == $imp['cdi_importe']){
                    //$total_neto = $subtotalimpuesto;
                    //$total_bruto -= $subtotalimpuesto;
                }
                else{

                }
                }else{
                }
                foreach ($impuestos as $impuesto) {
                    if($imp['cdi_importe'] == $impuesto->im_porcentaje){
                        //calculado
                        $calc = $imp['cdi_debito'] - ($imp['cdi_debito'] / (($imp['cdi_importe']/100)+1));
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
                            $sub = ($imp['cdi_debito'] / (($imp['cdi_importe']/100)+1));
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
                    $re_nombre = $retencion->re_nombre." ";
                    //calculado $subtotal['cdi_debito']*($retencion->re_porcentaje/100)
                    $calc = $total_bruto* ($retencion->re_porcentaje/100);
                    //arreglo
                    $listRetencion[] = array($re_nombre,$calc);
                    /************************RESTANDO RETENCION DEL CALCULO*****************************/
                    $total_neto -= $calc;
                }else{
                foreach ($totalimpuestos as $imp) {
                $impid = $dataimpuestos->getImpuestosById($retencion->re_im_id);
                foreach ($impid as $impid) {
                    if($imp['cdi_importe'] == $impid->im_porcentaje){
                        $re_nombre = $retencion->re_nombre;
                        $iva =$imp['cdi_debito'] - ($imp['cdi_debito'] / (($imp['cdi_importe']/100)+1));

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

    public function delete_venta_contable($idventa,$alert){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >= 3 && !empty($idventa) && $idventa > 0){
            //models
                $ventacontable = new ComprobanteContable($this->adapter);
                $detalleVentaContable = new DetalleComprobanteContable($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $cartera = new Cartera($this->adapter);
                $tokenization = new Tokenization($this->adapter);
                $user = new User($this->adapter);

                $venta = $ventacontable->getComprobanteById($idventa);
                foreach($venta as $venta){}
                if($venta->cc_id_transa){
                    $detalleventa= $detalleVentaContable->getArticulosByComprobante($venta->cc_id_transa);
                            foreach($detalleventa as $detalleventa){
                                    if($detalleventa->dcc_cod_art){
                                        $dataarticulos->addCantStock($detalleventa->dcc_cod_art,$detalleventa->dcc_cant_item_det);
                                    }
                                }
                            $detalleVentaContable ->deleteDetalleComprobante($venta->cc_id_transa);
                            $ventacontable->delete_comprobante($venta->cc_id_transa);
                            $cartera->deleteCarteraCliente($venta->cc_id_transa);
                        }else{
                            if($alert){
                            echo json_encode(array("error"=>"no tienes todos los permisos necesarios"));
                            }
                        }
        }else{
            if($alert){
            echo json_encode(array("error"=>"Forbiden gateway"));
            }
        }
    }

}