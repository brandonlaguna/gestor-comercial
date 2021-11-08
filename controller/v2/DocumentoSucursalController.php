<?php
class DocumentoSucursalController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();

        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'DocumentoSucursal/M_DocumentoSucursal',
            'Impuestos/M_Impuestos',
            'Retenciones/M_Retenciones',
            'FormatoImpresion/M_FormatoImpresion'
        ],$this->adapter);
    }

    public function index()
    {
        $this->frameview('v2/documentoSucursal/documentoSucursal',[]);
        $this->load([
            'v2/documentoSucursal/documentoSucursalModals',
            'v2/documentoSucursal/documentoSucursalScript',
            'v2/documentoSucursal/documentoSucursalTables'
        ],[]);
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

                $this->frameview("v2/documentoSucursal/edit",array(
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
            $impuestos          =       $this->M_Impuestos->getImpuestos();
            $retenciones         =       $this->M_Retenciones->getRetenciones();

            $this->frameview("v2/documentoSucursal/new",[
                "documentosSucursal"        => $documentosSucursal,
                "formatosImpresion"         => $formatosImpresion,
                "impuestos"                 => $impuestos,
                "retenciones"                => $retenciones
            ]);

        }else{
            $this->redirect("Index","");
        }
    }


    public function guardarActualizar()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

            $validar_impuesto = false;
            $arrayImpuesto = [];

            if($_POST['impuestos']){
                $validar_impuesto = true;
            }

            if($validar_impuesto){
                $guardarActualizar = $this->M_DocumentoSucursal->guardarActualizar([
                    'idsucursal'                   =>   $_SESSION["idsucursal"],
                    'idtipo_documento'             =>   $_POST['documento'],
                    'ultima_serie'                 =>   $_POST['serie'],
                    'ultimo_numero'                =>   $_POST['consecutivo'],
                    'contabilidad'                 =>   $_POST['contabilidad'],
                    'ddc_impuesto_comprobante'     =>   0,
                    'ddc_retencion_comprobante'    =>   0,
                    'dds_pri_id'                   =>   $_POST['formato'],
                    'dds_propertie'                =>   $_POST['properties'],
                    'activo'                       =>   1
            ]);

            if($guardarActualizar){
                   //guardar impuestos
                foreach ($_POST['impuestos'] as $key => $value) {
                    if($value > 0){
                        $arrayImpuesto[]=[
                            'dic_im_id'                     =>  $value,
                            'dic_det_documento_sucursal'    =>  $guardarActualizar,
                            'dic_idcomprobante' => 0,
                        ];
                        $validar_impuesto = true;
                    }
                }
                    $guardarImpuestos = $this->M_Impuestos->guardarImpuestoDocumento($arrayImpuesto);
                }else{
                    $guardarImpuestos = false;
                }
            }
            exit(json_encode($guardarImpuestos));
        }

    }

    public function obtenerDocumentosAJax()
    {
        if(isset($_POST)){
            $filter = ['idSucursal' => implode(',',$_POST['idSucursal'])];
            $documentosSucursal = $this->M_DocumentoSucursal->obtenerDocumentosSucursal($filter);
            echo json_encode($documentosSucursal);
        }
    }
}