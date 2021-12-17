<?php
class ImpuestosController extends ControladorBase{
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

    public function reporte()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $this->frameview("impuestos/index",array());
        }
    }

    public function generar_reporte()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($_POST)){
                $start_date = (isset($_POST["start_date"]) && !empty($_POST["start_date"]))?date_format_calendar($_POST["start_date"],"/"):date("Y-m-d");
                $end_date = (isset($_POST["end_date"]) && !empty($_POST["end_date"]))?date_format_calendar($_POST["end_date"],"/"):date("Y-m-d");
                echo json_encode(array("redirect"=>"#file/impuestos/".$start_date."/".$end_date));
            }
        }
    }

    public function getImpuesto()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
            if(isset($_POST["imp"]) && !empty($_POST["imp"])){
                //models
                $impuestos = new Impuestos($this->adapter);
                $imp = $_POST["imp"];

                //funtions
                $impuesto = $impuestos->getImpuesto($imp);
                foreach ($impuesto as $impuesto) {}

                echo json_encode($impuesto);

            }else{}
        }
    }

    public function addImpuestoToCart()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            if(isset($_POST["data"]) && !empty($_POST["data"])){
                $proceso = ($_POST['proceso'] == 'Contable')?1:0;
                $carro = new ColaIngreso($this->adapter);
                $colaimpuesto = new ColaImpuesto($this->adapter);
                $getCart = $carro->getCart();
                foreach($getCart as $getCart){}
                $colaimpuesto->setCdim_ci_id($getCart->ci_id);
                $colaimpuesto->setCdim_idcomprobante(0);
                $colaimpuesto->setCdim_im_id($_POST["data"]);
                $colaimpuesto->setCdim_contabilidad($proceso);
                $colaimpuesto->addImpuesto();
                echo json_encode("ingresado");

            }else{}
        }else{}
    }

    public function removeCartImpuesto()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            if(isset($_POST["id"]) && !empty($_POST["id"])){
                $cdim_id = cln_str($_POST['id']);
                $colaimpuesto = new ColaImpuesto($this->adapter);
                $colaimpuesto->setCdim_id($cdim_id);
                $delete = $colaimpuesto->deleteColaImpuesto();
                echo json_encode($delete);
            }
        }
    }



}