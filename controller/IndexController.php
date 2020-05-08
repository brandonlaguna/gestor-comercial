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
            $sucursales = $sucursal->getAll();
        $this->view("index",array(
           "sucursales"=>$sucursales,
           "header"=>$header
        ));
        //validacion de alerta de compra
        if(isset($_COOKIE['CheckedExist']) && !empty($_COOKIE['CheckedExist'])){
            $checked = new Check($this->adapter);
            $notification = $checked->getCheckAlert($_COOKIE['NoTouch'],$_COOKIE['CheckedExist']);
                foreach ($notification as $alert) {}
        }
        
    }
    
    }
}
?>