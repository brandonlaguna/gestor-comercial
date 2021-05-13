<?php
class PortfolioController extends Controladorbase
{

    private $adapter;
    private $conectar;

    public function __construct()
    {
        parent::__construct();

        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    public function index()
    {
        
        $this->publicview('index',array());
    }
}
?>