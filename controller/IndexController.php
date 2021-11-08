<?php
class IndexController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
    public function Index() {
        if(!isset($_COOKIE['Token']) && empty($_COOKIE['Token'])){
                $this->redirect("Index","");
        }else{

            //sucursales
            $header = new Header($this->adapter);
            $sucursal = new Sucursal($this->adapter);
            $sucursales = $sucursal->getSucursalAll();
            $this->view("index",array(
                "sucursales"=>$sucursales,
                "header"=>$header
            ));
    }
    }
}
?>