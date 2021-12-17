<?php
class MetodoPagoController extends ControladorBase{
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

    public function addMetodoPagoToCart()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            if(isset($_POST["data"]) && !empty($_POST["data"])){

                require_once 'VentasController.php';
                $carro = new ColaIngreso($this->adapter);
                $colametodopago = new ColaMetodoPago($this->adapter);
                $ventascontroller =new VentasController();

                $totales= $ventascontroller->calculoVenta2(null);

                //$filter= $colametodopago->
                $getCart = $carro->getCart();
                foreach($getCart as $getCart){}
                $colametodopago->setCdmp_ci_id($getCart->ci_id);
                $colametodopago->setCdmp_idcomprobante(0);
                $colametodopago->setCdmp_mp_id($_POST["data"]);
                $colametodopago->setCdmp_contabilidad(0);
                $colametodopago->addMetodoPago();

                //recuperar de nuevo la lista de metodos de pago
                $listaMetodo = $colametodopago->getMetodoPagoBy($getCart->ci_id);

                $monto = 0;
                foreach ($listaMetodo as $precalculo) {
                    $monto += $precalculo->cdmp_monto;
                }

                $color ='warning';
                $message = "Agregar monto";
                $monto_estado = $monto-$totales;
                if($monto_estado < 0){
                    $color = 'warning';
                    $message ="Faltante";
                }else{
                    $color = 'success';
                    $message ="Cambio";
                }

                $monto_properties = array(
                    "monto_estado"=>$monto_estado,
                    "color"=>$color,
                    "message"=>$message
                );
                $this->frameview("metodopago/cart/listMetodoPago",array(
                    "listaMetodo"=>$listaMetodo,
                    "monto_properties"=>$monto_properties,
                ));
            }else{}
        }else{}
    }

    public function addMontoCarro()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
                $carro = new ColaIngreso($this->adapter);
                $colametodopago = new ColaMetodoPago($this->adapter);
                $getCart = $carro->getCart();
                foreach($getCart as $getCart){}
                foreach ($_POST as $key => $value) {
                    $colametodopago->setCdmp_mp_id($key);
                    $colametodopago->setCdmp_monto($value);
                    $colametodopago->setCdmp_ci_id($getCart->ci_id);   
                    $colametodopago->addMontoMetodoPago();
                }
        }
    }

    public function loadMetodoPagoCart()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            require_once 'VentasController.php';
                $carro = new ColaIngreso($this->adapter);
                $colametodopago = new ColaMetodoPago($this->adapter);
                $ventascontroller =new VentasController();
                $totales= $ventascontroller->calculoVenta2(null);
                $getCart = $carro->getCart();
                foreach($getCart as $getCart){}
                $totales= $ventascontroller->calculoVenta2(null);
                $listaMetodo = $colametodopago->getMetodoPagoBy($getCart->ci_id);

                $monto = 0;
                foreach ($listaMetodo as $precalculo) {
                    $monto += $precalculo->cdmp_monto;
                }

                $color ='warning';
                $message = "Agregar monto";
                $monto_estado = $monto-$totales;
                if($monto_estado < 0){
                    $color = 'warning';
                    $message ="Faltante";
                }else{
                    $color = 'success';
                    $message ="Cambio";
                }

                $monto_properties = array(
                    "monto_estado"=>$monto_estado,
                    "color"=>$color,
                    "message"=>$message
                );
            

            $this->frameview("metodopago/cart/listMetodoPago",array(
                "listaMetodo"=>$listaMetodo,
                "monto_properties"=>$monto_properties,
            ));
        }
    }
}
