<?php
class DocumentoSucursalController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->loadModel([
            'DocumentoSucursal/DocumentoSucursal',
            'Impuestos/M_Impuestos'
        ]);
    }

    public function index()
    {

    }

    public function getDocumentoSucursal()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            //tipos de documentos
            $documentosSucursal = new DocumentoSucursal($this->adapter);
            $tipos_documentos = $documentosSucursal->getTiposDocumentos();

            



        }else{
            $this->redirect("Index","");
        }
    }

    public function editarDocumento()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcomprobante = $_GET["data"];
                $documentosSucursal = new DocumentoSucursal($this->adapter);
                $documentoSucursal = $documentosSucursal->getDocumentoSucursal($idcomprobante);

                $impuesto = new M_Impuestos($this->adapter);
                $impuestos = $impuesto->getImpuestos();

                $tipos_documentos   =   $documentosSucursal

                $impuestos=[];
                $retenciones =[];
                $conf_print =[]
                $this->frameview("v2/documentosucursal/new",array(
                    "documentos"=>$documentos,
                    "impuestos"=>$impuestos,
                    "retenciones"=>$retenciones,
                    "conf_print" =>$conf_print,
                ));

            }else{
                $this->redirect("Index","");
            }
        }else{
            $this->redirect("Index","");
        }
    }
}