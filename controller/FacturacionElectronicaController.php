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
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
        $fe = new FacturacionElectronica($this->adapter);
        $sucursales = new Sucursal($this->adapter);
        $sucursal = $sucursales->getSucursalById($_SESSION["idsucursal"]);
        $permission = "readonly";
        if($fe->status()){
            $this->frameview("FE/index",array(
                "sucursal"=>$sucursal,
                "permission"=>$permission
            ));

        }else{

        }
    }
        
    }

    public function generateInvoice()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2){
            
        }else{
            return(json_encode(["error"=>"error encontrado"]));
        }
    }

    private function params(){
        
        return "parametro oculto";
    }
}
?>