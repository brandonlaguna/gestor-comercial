<?php
class RefacturacionController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
        $tokenization = new Tokenization($this->adapter);
        $user = new User($this->adapter);
        if(isset($_POST) && !empty($_POST)){
            $code = (!empty($_POST["code"]))?$_POST["code"]:"";
            $redirect = (!empty($_POST["redirection"]))?$_POST["redirection"]:"";
            if($code && $redirect){ 
                    $datatokenizaation = $tokenization->getTokenization();
                    foreach ($datatokenizaation as $token) {}

                    //parseando el codigo y la token actual del usuario ingresado a md2
                    $parsecode = hash("md2",$code);
                    $parsetoken = $_COOKIE["Token"];
                    $parsedatacenter = hash("md2",$_SESSION["sucursal"]);

                    if($token->tz_ju_uid == $_SESSION["usr_uid"] && $token->tz_token == $parsetoken && $token->tz_reg_code == $parsecode){
                        if($token->tz_datacenter == $parsedatacenter && $token->tz_validation ==1){
                            $sequence = $token->tz_datacenter.$token->tz_uid.$token->tz_token.$token->tz_reg_code;
                            setcookie("refacturacion",$sequence);
                            echo json_encode(array("redirect"=>$redirect."/tokenized"));
                        }else{
                            echo json_encode(array("error"=>"error en validacion"));
                        }
                    }else{
                        echo json_encode(array("error"=>"hubo un error con el codigo"));
                    }

                //echo $redirect;
            }else{echo json_encode(array("error"=>"verifica los datos enviados"));}
        }else{
            //esta seccion es para generar una token y validar el usuario
        
        $userdata = $user->getUserById($_SESSION["usr_uid"]);
        foreach ($userdata as $userdatata) {}
        date_default_timezone_set("UTC");

        //lista de recursos
        $main_url = "refacturacion?action=index";

        $menu = array(
            "ventas"=>array(
                "url"=>"#refacturacion/ventas/tokenized"
            ),
            "compras"=>array(
                "url"=>"#refacturacion/compras/tokenized"
            ),  
        );
        
        $Uid=hash("md2",(string)microtime());
        $DataCenter = hash("md2",$_SESSION["sucursal"]);
        $code = hash("md2",$userdatata->rc_id);
        //actualizando data de la tokenizacion
        //la consulta verifica si se esta usando, lo actualiza si se vencieron los datos
        $tokenization->setTz_ju_uid($_SESSION["usr_uid"]);
        $tokenization->setTz_datacenter($DataCenter);
        $tokenization->setTz_uid($Uid);
        $tokenization->setTz_token($_COOKIE["Token"]);
        $tokenization->setTz_reg_code($code);

        $tokenized = $tokenization->setTokenization();

        $this->frameview("login/form/sublogin",array(
            "main_url"=>$main_url
        ));

        }
        }else{
            echo "Forbidden gateway";
        }
    }

    public function ventas()
    {
        if(isset($_COOKIE["refacturacion"]) && !empty($_COOKIE["refacturacion"])){
            
            //modelos
            $ventas = new Ventas($this->adapter);
            $detalleventa = new DetalleVenta($this->adapter);
            $pos = "run_ventas";

            $this->frameview("refacturacion/ventas",array(
                "pos"=>$pos
            ));

            javascript(array("controller/script/RefacturacionController"));

        }else{
            return $this->index();
        }
    }

    public function run_ventas()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $tokenization = new Tokenization($this->adapter);
            $user = new User($this->adapter);

            $datatokenizaation = $tokenization->getTokenization();
            foreach ($datatokenizaation as $token) {}
            $sequence= $token->tz_datacenter.$token->tz_uid.$token->tz_token.$token->tz_reg_code;
            if($sequence == $_COOKIE["refacturacion"]){
                if(isset($_POST)){
                    //models on
                    $venta = new Ventas($this->adapter);
                    $detalleventas = new DetalleVenta($this->adapter);
                    $reportescaja = new ReporteCaja($this->adapter);

                    //obtener todas las ventas
                    $start_date =($_POST["start_date"] != null)? date_format_calendar($_POST["start_date"],"/"):false;
                    $end_date =($_POST["end_date"] != null)? date_format_calendar($_POST["end_date"],"/"):false;
                    $porcentaje = ($_POST["porcentaje"])? (intval($_POST["porcentaje"]) / 100):false;

                    if($start_date&&$end_date&&$porcentaje){
                        $ventas = $venta->reporte_general($start_date,$end_date);
                        $totaldescuento = 0;
                        foreach ($ventas as $ventas) {
                            $detalleventa = $detalleventas->getArticulosByVenta($ventas->idventa);
                            //afectar descuento a la venta
                            //  si no esta afectada     si la venta es activa      si el comprobante esta disponible para modificar
                            if($ventas->affected == 0 && $ventas->estado == "A" && $ventas->pri_affected){
                                $sub_total = $ventas->sub_total - ($ventas->sub_total * $porcentaje);
                                $subtotal_importe = $ventas->subtotal_importe - ($ventas->subtotal_importe * $porcentaje);
                                $total = $ventas->total -($ventas->total * $porcentaje);
                                $importe_pagado = $ventas->importe_pagado - ($ventas->importe_pagado * $porcentaje);
                                
                                $venta->setIdsucursal($ventas->idsucursal);
                                $venta->setIdCliente($ventas->idCliente);
                                $venta->setIdusuario($ventas->idusuario);
                                $venta->setTipo_venta($ventas->tipo_venta);
                                $venta->setTipo_pago($ventas->tipo_pago);
                                $venta->setFecha($ventas->fecha);
                                $venta->setFecha_final($ventas->fecha_final);
                                $venta->setImpuesto($ventas->impuesto);
                                $venta->setSub_total($sub_total);
                                $venta->setSubtotal_importe($subtotal_importe);
                                $venta->setTotal($total);
                                $venta->setImporte_pagado($importe_pagado);
                                $venta->setAffected(1);
                                $venta->setEstado("A");
                                $venta->updateVenta($ventas->idventa);

                                //obtener reporte de caja 
                                $reporteactual = $reportescaja->getReporteByDate($ventas->fecha);
                                foreach ($reporteactual as $reporteactual) {}
                                //actualizar reporte de caja dependiento de la fecha de la venta
                                $reportescaja->setRc_monto($reporteactual->rc_monto - ($ventas->total * $porcentaje));
                                $reportescaja->setRc_efectivo($reporteactual->rc_efectivo - ($ventas->total * $porcentaje));
                                $reportescaja->setRc_credito($reporteactual->rc_credito);
                                $reportescaja->setRc_debito($reporteactual->rc_debito);
                                $reportescaja->setRc_pagos($reporteactual->rc_pagos);
                                $reportescaja->updateReporte($ventas->fecha);

                                foreach ($detalleventa as $articulos) {

                                    $precio_venta = $articulos->precio_unitario - ($articulos->precio_unitario * $porcentaje);
                                    $iva_compra = $articulos->iva_compra - ($articulos->iva_compra * $porcentaje);
                                    $precio_total_lote = $articulos->precio_total_lote - ($articulos->precio_total_lote * $porcentaje);

                                    $detalleventas->setCantidad($articulos->cantidad);
                                    $detalleventas->setPrecio_venta($precio_venta);
                                    $detalleventas->setIva_compra($iva_compra);
                                    $detalleventas->setImporte_categoria($articulos->importe_categoria);
                                    $detalleventas->setPrecio_total_lote($precio_total_lote);
                                    $detalleventas->setEstado($articulos->estado);
                                    $detalleventas->updateArticulos($ventas->idventa,$articulos->idarticulo);

                                }
                            }
                        }

                    }else{
                        echo "Hubo un error en alguno de los parametros, intentalo nuevamente";
                    }
                }
            }
        }else{}
    }

}