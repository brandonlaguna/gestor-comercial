<?php
class ProveedorController extends ControladorBase{
    public $conectar;
	public $adapter;
	
    public function __construct() {
        parent::__construct();
		 
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        
    }

    public function index()
    {
    
    }

    public function deudas()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $cartera = new Cartera($this->adapter);
            $carteras = $cartera->getCreditoProveedorAll();
            $carterascontables =$cartera->getCreditoProveedorContableAll();
        $pago = 0;
        $por_pagar = 0;
        $vencidas = 0;
        $total=0;
        $color = 'danger'; 
        foreach ($carteras as $calculo) {
           $pago += $calculo->total_pago;
           $por_pagar += ($calculo->deuda_total - $calculo->total_pago);
           $total += $calculo->deuda_total;
            if($calculo->fecha_pago < date("Y-m-d")){
                $vencidas +=($calculo->deuda_total - $calculo->total_pago);
            }
        } 
        $porcentaje_vencido = bcdiv(($vencidas / $total)*100,'1',1);
        $porcentaje_pago = bcdiv(($pago / $total)*100,'1',1);

        if($porcentaje_pago < 30.0){
            $color = "danger";
        }elseif($porcentaje_pago > 30.1 && $porcentaje_pago < 80.0){
            $color = "primary";
        }elseif($porcentaje_pago > 80.1){
            $color = "success";
        }
        

        
        $this->frameview("proveedor/deudas/deudas",array(
            "cartera"=>$carteras,
            "carterascontables"=>$carterascontables,
            "deuda_total"=>$total,
            "deuda_pagada"=>$pago,
            "vencidas"=> $vencidas,
            "prcentaje_pago"=>$porcentaje_pago,
            "porcentaje_vencido"=>$porcentaje_vencido,
            "color"=>$color

        ));
        }
    }

    public function pagar_deuda()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($_GET["data"]) && !empty($_GET["data"]) && $_GET["data"] >0){
                $idcredito = $_GET["data"];

                $cartera = new Cartera($this->adapter);
                $credito= $cartera->getCreditoProveedorById($idcredito);

                $pagos = $cartera->getPagoCarteraProveedor($idcredito);

                $this->frameview("proveedor/deudas/pagarDeuda",array(
                    "credito"=>$credito,
                    "pagos"=>$pagos,
                ));


            }else{
                echo "Forbidden Gateway";
            }
        }else{
            echo "Forbidden Gateway";
        }
    }

    public function generar_pago_proveedor()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"]> 1){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                //forma_pago en una variable
                $forma_pago = $_GET["data"];
                //idcredito en una variable
                $idcredito = $_POST["id_credito_proveedor"];
                $pos = "proveedor";
                //obtener informacion de este credito
                $cartera = new Cartera($this->adapter);
                $credito = $cartera->getCreditoProveedorById($idcredito);
                //traer lista de retenciones
                $retencion = new Retenciones($this->adapter);
                $retenciones = $retencion->getAll();

                //funcion de opciones de prcio rapidas
                    foreach ($credito as $data) {}
                    $total = round($data->deuda_total-$data->total_pago);
                    $idcartera = $data->idcredito_proveedor;
                    //cantidad de caracteres
                    $listPrice = [];
                    $caracteres = strlen($total);
                    $max = substr($total,0,1) +1;
                    $aprox = substr($total,0,2) +1;
                    for($i=0;$i<$caracteres-1;$i++){
                        $max .="0";
                    }

                    for($i=0;$i<$caracteres-2;$i++){
                        $aprox .="0";
                    }

                    $listPrice[] = $total;
                    $listPrice[] = $aprox;
                    $listPrice[] = $max;
                
                switch ($forma_pago) {
                    case '1':
                        # vista para pago en efectivo

                        $this->frameview("cartera/efectivo/efectivo",array(
                            "credito"=>$credito,
                            "listPrice"=>$listPrice,
                            "retenciones"=>$retenciones,
                            "idcredito" =>$idcartera,
                            "pos"=>$pos,
                        ));
                        break;
                    case '2':
                        # code...
                        break;
                    case '3':
                        # code...
                        break;
                    
                    default:
                        # code...
                        break;
                }

            }
        }else{
            echo "No tienes permisos";
        }
    }

    public function calcularCartera()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"]> 1){
            if(isset($_POST["pago"])) {
                $msg="Por pagar";
                $attr = true;
                $color="text-success";
                $credito = $_POST["credito"];
                $idretencion = $_POST["retencion"];
                
                $pago = (isset($_POST["pago"]) && $_POST["pago"]>0 || $_POST["pago"] !="")?$_POST["pago"]:0;
                //llamar clases para calcular el total
                $retenciones = new Retenciones($this->adapter);
                $cartera = new Cartera($this->adapter);
                //llamar funciones para calcular total
                $credito = $cartera->getCreditoProveedorById($credito);
                $retencion = $retenciones->getRetencionesById($idretencion);
                //obteniendo resultados en variables
                foreach ($retencion as $retencion) {}
                foreach ($credito as $credito) {}
                //
                $deuda = round($credito->deuda_total - $credito->total_pago);
                if($idretencion > 0){
                 if($retencion){
                    $total = round($deuda / (($retencion->re_porcentaje/100)+1) - $pago);
                    $reteinfo = $retencion->re_porcentaje;
                 }
                }else{
                    $reteinfo = false;
                    $total = round($deuda - $pago);
                }

                if($deuda <= $pago){
                    $msg="Cambio";
                    $color="text-danger";
                }

                if($pago > 0){
                    $attr =false;
                }

                echo json_encode(array("total"=>abs($total),"msg"=>$msg,"color"=>$color,"attr"=>$attr));
            }
        }
    }

    public function pago_autorizado()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"]> 1 && $_POST["pago"] > 0){
            //almacenando variables
            $pago = $_POST["pago"];
            $idcredito = $_POST["idcredito"];
            $idretencion = $_POST["retenciones"];
            $tipo_pago = $_POST["tipo_pago"];
            //llamar clases
            $retenciones = new Retenciones($this->adapter);
            $cartera = new Cartera($this->adapter);
            //llamar funciones para calcular total
            $credito = $cartera->getCreditoProveedorById($idcredito);
            $retencion = $retenciones->getRetencionesById($idretencion);
            //obteniendo resultados en variables
            foreach ($retencion as $retencion) {}
            foreach ($credito as $credito) {}
            $deuda_actual = round($credito->deuda_total - $credito->total_pago);

            if($idretencion >0){
                if($retencion){
                    if($pago <= $deuda_actual){
                    $precio_retenido = round($pago / (($retencion->re_porcentaje/100)+1) - $pago);
                    $pago_parcial = $pago;
                    $deuda = $deuda_actual - $pago_parcial;
                    }else{
                    $precio_retenido = round($deuda_actual / (($retencion->re_porcentaje/100)+1) - $deuda_actual);
                    $pago_parcial = $deuda_actual;
                    $deuda = 0;
                    }
                }
            }else{
                if($pago <= $deuda_actual){
                    $pago_parcial = $pago;
                    $deuda = $deuda_actual - $pago_parcial;
                    $precio_retenido =0;
                }else{
                    $pago_parcial = $deuda_actual;
                    $deuda = 0;
                    $precio_retenido =0;
                }
            } 


                $cartera = new Cartera($this->adapter);
                $cartera->setIdcredito_proveedor($idcredito);
                $cartera->setPago_parcial($pago_parcial);
                $cartera->setDeuda_parcial($deuda);
                $cartera->setMonto($pago);
                $cartera->setTipo_pago($tipo_pago);
                $cartera->setRetencion($precio_retenido);
                $cartera->setEstado(1);
                if($pago_parcial>0){
                $pago = $cartera->generar_pago_proveedor();
                echo json_encode(array("success"=>$idcredito));
                }


        }else{
            echo "No se puede realizar este pago";
        }
    }
}
