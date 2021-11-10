<?php
class CartController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();

        $this->loadModel(['Cart/M_Cart', 'Articulos/M_Articulos'],$this->adapter);

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
                $idarticulo = $item["iditem"];
                $cantidad = $item["cantidad"];
                $ivaarticulo = $item["imp_venta"];
                $costo_producto= $item["precio_venta"];
                $cod_costos =$item["cod_costos"];
                $articulo = $this->M_Articulos->getArticuloBy(['iditem' =>$item["iditem"]]);

                foreach($articulo as $articulo){}
                $getCart = $cart->getCart();
                foreach ($getCart as $getCart) {}
                if($articulo['idarticulo']){
                    $idarticulo = $item["iditem"];
                    $cantidad = $item["cantidad"];
                    $ivaarticulo = $item["imp_venta"];
                    $costo_producto= $item["precio_venta"];
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
                $ivacodigo = $item["imp_venta"];
                $costo_producto = $item["total_venta"];
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
}