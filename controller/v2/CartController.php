<?php
class CategoriasController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();

        $this->loadModel([
            'Cart/M_Cart'
        ],$this->adapter);

    }
    public function sendItem()
    {
        
    }
}