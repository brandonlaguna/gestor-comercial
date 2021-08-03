<?php
class UploadFileController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        echo "Forbidden gateway error 403";
    }

    public function imporfile()
    {
        # code...
    }


}