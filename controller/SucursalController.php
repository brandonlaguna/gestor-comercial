<?php
class SucursalController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
       if(!isset($_COOKIE['sucursal'])){

            $this->view("sucursal/index",array());
       }
       else{
           $this->redirect("Index","");
       }
    }

    public function getclients()
    {
        if(isset($_POST["data"])){
            $clientes = new Persona($this->adapter);
            $auto_complete = $clientes->autoComplete($_POST["data"]);
            $response = [];
            foreach ($auto_complete as $client) {
                $response[]= $client->nombre." - ".$client->num_documento;
                $response[]= $client->num_documento." - ".$client->nombre;
            }
            echo json_encode($response);
        }
    }

    public function getPuc()
    {
        if(isset($_POST["data"])){
            //obtener puc por default o de la propia tabla de puc
            $codigos= new PUC($this->adapter);
            $auto_complete = $codigos->getAllPuc($_POST["data"]);
            //obtener articulos o servicios que contengan puc
            $articulo = new Articulo($this->adapter);
            $articulos = $articulo->getArticuloAll();
            $response = [];
            //codigos almacenados en puc por default
            foreach ($auto_complete as $codigos) {
                if($codigos->centro_costos && $codigos->movimiento){
                $response[]=$codigos->idcodigo." - ".$codigos->tipo_codigo."";
                $response[]=$codigos->tipo_codigo." - ".$codigos->idcodigo;
                }
            }
            //lista de articulos con puc
            foreach($articulos as $articulos){
                $response[]=$articulos->idarticulo." - ".$articulos->nombre_articulo;
                $response[]=$articulos->nombre_articulo." - ".$articulos->idarticulo;
            }

            echo json_encode($response);
        }
    }

}