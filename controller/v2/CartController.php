<?php
class CartController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Cart/M_Cart',
            'Articulos/M_Articulos',
            'Impuestos/M_ColaImpuesto',
            'Retenciones/M_ColaRetencion'
        ],$this->adapter);

    }
    public function index(){}

    public function sendItem()
    {
        if($_POST["data"] && !empty($_POST["data"]) && !empty($_POST["pos"])){
            $cart = new ColaIngreso($this->adapter);
            $pos                = $_POST["pos"];
            $tercero            = $_POST["tercero"];
            $item               = $_POST["data"];
            $credito =0;
            $debito=0;
            $validate_stock = false;
            $continue_without_stock = ($validate_stock)?false:true;
            $listArticulo=[];
            if(isset($item["iditem"]) && $item["iditem"] > 0){
                $articulo = $this->M_Articulos->getArticuloBy(['iditem' =>$item["iditem"]]);
                foreach($articulo as $articulo){}
                $getCart = $cart->getCart();
                foreach ($getCart as $getCart) {}
                if($articulo['idarticulo']){
                    $idarticulo = $item["iditem"];
                    $cantidad = $item["cantidad"];
                    $ivaarticulo = isset($item["imp_venta"])?$item["imp_venta"]:$item["imp_compra"];
                    $costo_producto= isset($item["precio_venta"])?$item["precio_venta"]:$item["costo_producto"];
                    $cod_costos =$item["cod_costos"];
                    //calcular
                    $total_iva = ($costo_producto * $cantidad) *(($ivaarticulo/100));
                    $total_lote = ($costo_producto * $cantidad) + $total_iva;
                    //posicion de pagina
                    $debito=$total_lote;
                    $credito =$total_lote;

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

    public function agregarImpuesto()
    {
        if(isset($_POST["data"]) && !empty($_POST["data"])){
            $estado = false;
            $mensaje ='No es posible acceder al carrito, regresa e intentalo nuevamente';
            try {
                $cart = $this->M_Cart->obtenerUltimoCarrito([
                    'idsucursal'    => $_SESSION['idsucursal'],
                    'idusuario'     => $_SESSION['usr_uid'],
                ]);
                if($cart){
                    $guardarImpuesto = $this->M_ColaImpuesto->guardarActualizar([
                        'cdim_ci_id'            => $cart['ci_id'],
                        'cdim_idcomprobante'    => 0,
                        'cdim_im_id'            => $_POST["data"],
                        'cdim_contabilidad'     => $_POST['proceso'] == 'Contable'?1:0,
                    ]);
                    if($guardarImpuesto){
                        $estado     = true;
                        $mensaje    = "Impuesto agregado";
                    }
                }
            } catch (\Throwable $e) {
                $mensaje = $e->getMessage();
            }
            echo json_encode([
                'estado'    => $estado,
                'mensaje'   => $mensaje
            ]);
        }
    }

    public function agregarRetencion()
    {
        if(isset($_POST["data"]) && !empty($_POST["data"])){
            $estado = false;
            $mensaje ='No es posible acceder al carrito, regresa e intentalo nuevamente';
            try {
                $cart = $this->M_Cart->obtenerUltimoCarrito([
                    'idsucursal'    => $_SESSION['idsucursal'],
                    'idusuario'     => $_SESSION['usr_uid'],
                ]);
                if($cart){
                    $guardarRetencion = $this->M_ColaRetencion->guardarActualizar([
                        'cdr_ci_id'            => $cart['ci_id'],
                        'cdr_re_id'            => $_POST["data"],
                        'cdr_contabilidad'     => $_POST['proceso'] == 'Contable'?1:0,
                    ]);
                    if($guardarRetencion){
                        $estado     = true;
                        $mensaje    = "Retencion agregada";
                    }
                }
            } catch (\Throwable $e) {
                $mensaje = $e->getMessage();
            }
            echo json_encode([
                'estado'    => $estado,
                'mensaje'   => $mensaje
            ]);
        }
    }

    public function calcularCarrito()
    {
        $estado = false;
        $mensaje = 'No se pudo acceder a este carrito';
        try {
            if(isset($_POST)){
                
            }else{
                throw new Exception("No se enviaron datos ");
            }
        } catch (\Throwable $e) {
            $mensaje = $e->getMessage();
        }
        echo json_encode([
            'estado'    => $estado,
            'mensaje'   => $mensaje
        ]);
    }
}