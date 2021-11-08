<?php
class FacturacionElectronicaController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        
        echo "Configuracion factura electronica";
        
    }

    public function generateInvoice()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2){
            echo "Configuracion factura electronica";
        }else{
            echo "Forbidden Gateway";
        }
    }
}
?>