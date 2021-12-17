<?php
class CartController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->loadModel(['Cart/M_Cart', 'Articulos/M_Articulos', 'Cart/M_CartImpuesto', 'Cart/M_CartRetencion', 'Cart/M_CartMetodoPago'],$this->adapter);
    }
    public function index(){}

    public function sendItem()
    {
        if($_POST["data"] && !empty($_POST["data"]) && !empty($_POST["pos"])){
            $cart = new ColaIngreso($this->adapter);
            $pos                    = $_POST["pos"];
            $tercero                = $_POST["tercero"];
            $item                   = $_POST["data"];
            $credito                =0;
            $debito                 =0;
            $validate_stock         = false;
            $continue_without_stock = ($validate_stock)?false:true;
            $listArticulo=[];
            if(isset($item["iditem"]) && $item["iditem"] > 0){
                $idarticulo     = $item["iditem"];
                $cantidad       = $item["cantidad"];
                $ivaarticulo    = $item["imp_venta"];
                $costo_producto = $item["precio_venta"];
                $cod_costos     = $item["cod_costos"];
                $articulo       = $this->M_Articulos->getArticuloBy(['iditem' =>$item["iditem"]]);

                foreach($articulo as $articulo){}
                $getCart = $cart->getCart();
                foreach ($getCart as $getCart) {}
                if($articulo['idarticulo']){
                    $idarticulo     = $item["iditem"];
                    $cantidad       = $item["cantidad"];
                    $ivaarticulo    = isset($item["imp_venta"])?$item["imp_venta"]:$item["imp_compra"];
                    $costo_producto = isset($item["precio_venta"])?$item["precio_venta"]:$item["costo_producto"];
                    $cod_costos     = $item["cod_costos"];
                    $total_iva      = ($costo_producto * $cantidad) *(($ivaarticulo/100));
                    $total_lote     = ($costo_producto * $cantidad) + $total_iva;
                    $debito         = $total_lote;
                    $credito        = $total_lote;
                    $listArticulo = [
                        'cdi_ci_id'                     => $getCart->ci_id,
                        'cdi_idsucursal'                => $_SESSION["idsucursal"],
                        'cdi_idusuraio'                 => $_SESSION["usr_uid"],
                        'cdi_tercero'                   => $tercero,
                        'cdi_idarticulo'                => $idarticulo,
                        'cdi_stock_ingreso'             => $cantidad,
                        'cdi_precio_unitario'           => $costo_producto,
                        'cdi_importe'                   => $ivaarticulo,
                        'cdi_im_id'                     => 0,
                        'cdi_precio_total_lote'         => $total_lote,
                        'cdi_credito'                   => $credito,
                        'cdi_debito'                    => $debito,
                        'cdi_cod_costos'                => $cod_costos,
                        'cdi_type'                      => "AR",
                        'cdi_detalle'                   => 0,
                        'cdi_base_ret'                  => 0
                    ];

                }

                $sendItem = $this->M_Cart->sendItem($listArticulo);
            }elseif($item["idservicio"] >0){

            }elseif ($item["idcodigo"] >0) {
                $idcodigo = $item["idcodigo"];
                $cantidad = $item["cantidad"];
                $ivacodigo = isset($item["imp_venta"])?$item["imp_venta"]:$item["imp_compra"];
                $costo_producto = isset($item["total_venta"])?$item["total_venta"]:$item["total_compra"];
                $cod_costos =0;
                $getCart = $cart->getCart();
                foreach ($getCart as $getCart) {}
                if($pos =="Ingreso"){$credito=$costo_producto;}
                elseif($pos=="Venta"){$debito =$costo_producto;}
                else{}
                $listArticulo = [
                    'cdi_ci_id'                     => $getCart->ci_id,
                    'cdi_idsucursal'                => $_SESSION["idsucursal"],
                    'cdi_idusuraio'                 => $_SESSION["usr_uid"],
                    'cdi_tercero'                   => $tercero,
                    'cdi_idarticulo'                => $idcodigo,
                    'cdi_stock_ingreso'             => $cantidad,
                    'cdi_precio_unitario'           => $costo_producto,
                    'cdi_importe'                   => $ivacodigo,
                    'cdi_im_id'                     => 0,
                    'cdi_precio_total_lote'         => $costo_producto,
                    'cdi_credito'                   => $credito,
                    'cdi_debito'                    => $debito,
                    'cdi_cod_costos'                => $cod_costos,
                    'cdi_type'                      => "CO",
                    'cdi_detalle'                   => 0,
                    'cdi_base_ret'                  => 0
                ];
                $sendItem = $this->M_Cart->sendItem($listArticulo);
            }

        }
    }

    public function addImpuestoToCart()
    {
        $estadoImpuesto = false;
        $mensajeImpuesto = "Error al almacenar este impuesto";
        try {
        if(!isset($_POST["data"]) && empty($_POST["data"]))
            throw new Exception("Ingresa un impuesto valido ");
        $proceso = isset($_POST['proceso']) && $_POST['proceso'] == 'Contable' ? 1 : 0 ;
        $getCart = $this->M_Cart->obtenerUltimoCarrito([
            'idsucursal'    => $_SESSION['idsucursal'],
            'idusuario'     => $_SESSION['usr_uid']
        ]);
        $agregarImpuesto = $this->M_CartImpuesto->guardarCartImpuestos([
            'cdim_ci_id'            => $getCart['ci_id'],
            'cdim_idcomprobante'    => 0,
            'cdim_im_id'            => $_POST["data"],
            'cdim_contabilidad'     => $proceso,
        ]);
        if($agregarImpuesto)
            $estadoImpuesto     = true;
            $mensajeImpuesto    = 'Impuesto almacenado';

        } catch (\Throwable $th) {
            $mensajeImpuesto    = $th->getMessage();
        }
        echo json_encode([
            'estadoImpuesto'    => $estadoImpuesto,
            'mensajeImpuesto'   => $mensajeImpuesto
        ]);
    }

    public function addRetencionToCart()
    {
        $estadoRetencion = false;
        $mensajeRetencion = "Error al almacenar esta retencion";
        try {
            if(!isset($_POST["data"]) && empty($_POST["data"]))
                throw new Exception("Ingresa una retencion valida");
            $proceso = (isset($_POST['proceso']) && $_POST['proceso'] == 'Contable')?1:0;
            $getCart = $this->M_Cart->obtenerUltimoCarrito([
                'idsucursal'    => $_SESSION['idsucursal'],
                'idusuario'     => $_SESSION['usr_uid']
            ]);
            $agregarRetencion = $this->M_CartRetencion->guardarCartRetencion([
                'cdr_ci_id'         => $getCart['ci_id'],
                'cdr_re_id'         => $_POST["data"],
                'cdr_contabilidad'  => $proceso
            ]);

            if($agregarRetencion)
                $estadoRetencion    = true;
                $mensajeRetencion   = 'Retencion almacenada correctamente';
        } catch (\Throwable $e) {
            $mensajeRetencion   = $e->getMessage();
        }
        echo json_encode([
            'estadoRetencion'   => $estadoRetencion,
            'mensajeRetencion'  => $mensajeRetencion,
        ]);
    }

    public function addMetodoPagoToCart()
    {
        $estadoMetodoPago = false;
        $mensajeMetodoPago = 'No se pudo almacenar éste método de pago';
        try {
            if(!isset($_POST["data"]) && empty($_POST["data"])){
                throw new Exception("Agrega un método de pago valido");
            }
                $getCart = $this->M_Cart->obtenerUltimoCarrito([
                    'idsucursal'    => $_SESSION['idsucursal'],
                    'idusuario'     => $_SESSION['usr_uid']
                ]);
                $metodoPago = $this->M_CartMetodoPago->guardarMetodoPago([
                    'cdmp_ci_id'            => $getCart['ci_id'],
                    'cdmp_idcomprobante'    => 0,
                    'cdmp_mp_id'            => $_POST["data"],
                    'cdmp_contabilidad'     => 0,
                    'cdmp_monto'            => 0
                ]);
                if($metodoPago){
                    $estadoMetodoPago   = true;
                    $mensajeMetodoPago  = "Método de pago almacenado";
                }
        } catch (\Throwable $e) {
            $mensajeMetodoPago = $e->getMessage();
        }
        echo json_encode([
            'estadoMetodoPago'  => $estadoMetodoPago,
            'mensajeMetodoPago' => $mensajeMetodoPago
        ]);
    }

    public function mostrarMetodosPago()
    {
        $estadoMetodoPago = false;
        $mensajeMetodoPago  = "Error al calcular los metodos de pagos";
        try {
            $getCart = $this->M_Cart->obtenerUltimoCarrito([
                'idsucursal'    => $_SESSION['idsucursal'],
                'idusuario'     => $_SESSION['usr_uid']
            ]);
            $arrayMetodosPago   =   $this->M_CartMetodoPago->obtenerMetodosPagos([ 'cdmp_ci_id' => $getCart['ci_id']]);
            $totalesCarrito     =   $this->M_Cart->getSubTotal($getCart['ci_id']);
            if(!$arrayMetodosPago)
                throw new Exception("No hay métodos de pagos agregados al carrito");

            $monto = 0;
            foreach ($arrayMetodosPago as $precalculo) {
                $monto += $precalculo['cdmp_monto'];
            }

            $color          = 'warning';
            $message        = "Agregar monto";
            $monto_estado   = $monto-$totalesCarrito['totalCart'];
            if($monto_estado < 0){
                $color      = 'warning';
                $message    ="Faltante";
            }else{
                $color      = 'success';
                $message    ="Cambio";
            }

            $monto_properties = array(
                "monto_estado"      => $monto_estado,
                "color"             => $color,
                "message"           => $message
            );
            if($arrayMetodosPago)
                $estadoMetodoPago = true;

            $this->frameview("v2/Cart/metodoPago/metodosPago",array(
                "listaMetodo"       => $arrayMetodosPago,
                "monto_properties"  => $monto_properties,
            ));
        } catch (\Throwable $e) {
            $mensajeMetodoPago = $e->getMessage();
        }
        if($estadoMetodoPago == false){
            echo json_encode([
                'estadoMetodoPago'  => $estadoMetodoPago,
                'mensajeMetodoPago' => $mensajeMetodoPago
            ]);
        }
    }

}
