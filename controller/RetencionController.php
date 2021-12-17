<?php
class RetencionController extends ControladorBase{
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

    public function addRetencionToCart()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            if(isset($_POST["data"]) && !empty($_POST["data"])){
                $proceso = ($_POST['proceso'] == 'Contable')?1:0;
                $carro = new ColaIngreso($this->adapter);
                $colaretencion = new ColaRetencion($this->adapter);
                $getCart = $carro->getCart();
                foreach($getCart as $getCart){}
                $colaretencion->setCdr_ci_id($getCart->ci_id);
                $colaretencion->setCdr_re_id($_POST["data"]);
                $colaretencion->setCdr_contabilidad($proceso);
                $colaretencion->addRetencion();
                echo json_encode("ingresado");

            }else{}
        }else{}
    }

    public function removeCartRetencion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            if(isset($_POST["id"]) && !empty($_POST["id"])){
                $cdr_id = cln_str($_POST['id']);
                $colaretencion = new ColaRetencion($this->adapter);
                $colaretencion->setCdr_id($cdr_id);
                $delete = $colaretencion->deleteColaRetencion();
                echo json_encode($delete);
            }
        }
    }
}