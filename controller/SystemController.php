<?php
class SystemController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
    }

    public function backup()
    {
       if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
        echo "respaldos";
       }else{
           echo "Forbidden gateway";
       }
    }



}