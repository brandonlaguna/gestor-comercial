<?php
class page404 extends ControladorBase{
    private $adapter;
    private $conectar;
    public function __construct() {
    parent::__construct();
    $this->conectar=new Conectar();  
    $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        
        $this->frameview("404",array(
        ));
    }
}
?>