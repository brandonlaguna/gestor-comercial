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
            $data = ($_POST["data"] != '')?$_POST["data"]:null;
            $auto_complete = $clientes->autoComplete($data);
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
                if($codigos->centro_costos || $codigos->movimiento){
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

    public function getPucBy()
    {
        if(isset($_POST["param"]) && isset($_POST["attr"])){
            //obtener puc por default o de la propia tabla de puc
            $param = cln_str($_POST["param"]);
            $attr = cln_str($_POST["attr"]);
            $codigos= new PUC($this->adapter);
            $auto_complete = $codigos->getAllPucBy($attr,$param);
            //obtener articulos o servicios que contengan puc
            $response = [];
            //codigos almacenados en puc por default
            foreach ($auto_complete as $codigos) {
                if($codigos->movimiento){
                $response[]=$codigos->idcodigo." - ".$codigos->tipo_codigo."";
                $response[]=$codigos->tipo_codigo." - ".$codigos->idcodigo;
                }
            }

            echo json_encode($response);
        }
    }

    public function switch()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $sucursalid = $_GET["data"];
                $sucursales = new Sucursal($this->adapter);
                $sucursal = $sucursales->getSucursalById($sucursalid);
                foreach ($sucursal as $sucursal) {}
                if($sucursal){
                    if($sucursal->idsucursal){
                        $_SESSION["idsucursal"] = $sucursalid;

                        
                        // echo json_encode(array(
                        //     "alert"=>"success",
                        //     "message"=>"Estas ahora en la sucursal ".$sucursalid,
                        // ));
                    }else{
                        echo "No ha sido posible conectarse a esta sucursal";
                    }
                }
                
            }else{
                echo "Forbidden gateway";
            }
        }else{
            echo "Forbidden gateway";
        }

    }
}