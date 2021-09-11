<?php
class DocumentoSucursalController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->loadModel([
            'DocumentoSucursal/M_DocumentoSucursal',
            'Impuestos/M_Impuestos',
            'Retenciones/M_Retenciones',
            'FormatoImpresion/M_FormatoImpresion'
        ],$this->adapter);
    }

    public function index()
    {

    }

    public function getDocumentoSucursal()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            //tipos de documentos
            $tipos_documentos = $this->M_DocumentoSucursal->getTiposDocumentos();
        }else{
            //$this->redirect("Index","");
        }
    }

    public function editarDocumento()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcomprobante = $_GET["data"];
                $documentoSucursal =  $this->M_DocumentoSucursal->getDocumentoSucursal($idcomprobante);

                $this->frameview("v2/documentosucursal/edit",array(
                    "documentoSucursal"=>$documentoSucursal,
                   // "conf_print" =>$conf_print,
                ));

            }else{
               // $this->redirect("Index","");
            }
        }else{
            $this->redirect("Index","");
        }
    }
    public function newDocumentoSucursal()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $documentosSucursal =       $this->M_DocumentoSucursal->getTiposDocumentoSucursal();
            $formatosImpresion  =       $this->M_FormatoImpresion->getFormatoImpresion();

            $this->frameview("v2/documentosucursal/new",[
                "documentosSucursal"        => $documentosSucursal,
                "formatosImpresion"         => $formatosImpresion,    
            ]);

        }else{
            $this->redirect("Index","");
        }
    }


    public function guardarActualizar()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

            $guardarActualizar = $this->M_DocumentoSucursal->guardarActualizar([
                'idsucursal'        => $_SESSION["idsucursal"],
                'idtipo_documento'  =>1,
                'ultima_serie'      =>1,
                'ultimo_numero'     =>0,
                'contabilidad'      =>0,
                'dds_pri_id'        =>1,
                'dds_propertie'     =>''
            ]);
        }

    }
}