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
            'FormatoImpresion/M_FormatoImpresion',
        ],$this->adapter);
    }

    public function index()
    {
        $opciones = [
            [ 'item_id'  => "0", 'item_name'  => 'No' ],
            [ 'item_id'  => "1", 'item_name'  => 'Si' ],
        ];
        $listaPropiedades = [
            [ 'item_id'  => "selected", 'item_name'  => 'Por defecto en POS' ],
        ];
        $this->frameview('v2/documentoSucursal/documentoSucursal',[]);
        $this->load([
            'v2/documentoSucursal/documentoSucursalModals',
            'v2/documentoSucursal/documentoSucursalScript',
            'v2/documentoSucursal/documentoSucursalTables'
        ],[
            'opciones'          => $opciones,
            'listaFormatos'     => $this->M_FormatoImpresion->getFormatoImpresion(),
            'tipoDocumentos'    => $this->M_DocumentoSucursal->getTiposDocumentoSucursal(),
            'listaPropiedades'  => $listaPropiedades
        ]);
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
            $retenciones         =      $this->M_Retenciones->getRetenciones();

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
            $permiso = $this->Verificar->validarPermiso(3);
            $mensajeDocumento   = 'No se pudo actualizar o almacenar éste documento';
            $estadoDocumento    = false;
            try {
                if(isset($_POST['impuestos']))
                    throw new Exception("No se ha enviado ningun impuesto");
                $guardarActualizar = $this->M_DocumentoSucursal->guardarActualizar([
                    'iddetalle_documento_sucursal'  => isset($_POST['iddetalle_documento_sucursal']) && !empty($_POST['iddetalle_documento_sucursal']) ? $_POST['iddetalle_documento_sucursal'] : null,
                    'idsucursal'                    => $_SESSION["idsucursal"],
                    'idtipo_documento'              => $_POST['idtipo_documento'],
                    'ultima_serie'                  => $_POST['ultima_serie'],
                    'ultimo_numero'                 => $_POST['ultimo_numero'],
                    'contabilidad'                  => isset($_POST['contabilidad']) && !empty($_POST['contabilidad']) ? $_POST['contabilidad']: 0,
                    'ddc_impuesto_comprobante'      => 0,
                    'ddc_retencion_comprobante'     => 0,
                    'dds_pri_id'                    => $_POST['dds_pri_id'],
                    'dds_propertie'                 => $_POST['dds_propertie'],
                    'activo'                        => 1,
                    'dds_afecta_inventario'         => $_POST['dds_afecta_inventario'],
                ]);
                if($guardarActualizar){
                       //guardar impuestos
                    $arrayImpuesto = [];
                    $guardarImpuestos = false;
                    if(isset($_POST['impuestos'])){
                        foreach ($_POST['impuestos'] as $key => $value) {
                            if($value > 0){
                                $arrayImpuesto[]=[
                                    'dic_im_id'                     =>  $value,
                                    'dic_det_documento_sucursal'    =>  $guardarActualizar,
                                    'dic_idcomprobante' => 0,
                                ];
                            }
                        }
                        $guardarImpuestos = $this->M_Impuestos->guardarImpuestoDocumento($arrayImpuesto);
                    }
                    //guardar pie de factura
                    $guardarPieFactura = $this->M_DocumentoSucursal->guardarPieFactura([
                        'pf_iddetalle_documento_sucursal'   => $guardarActualizar,
                        'pf_text'                           => isset($_POST['pf_text'])?$_POST['pf_text']:''
                    ]);
                    $estadoDocumento = true;
                    $mensajeDocumento = "Se almacenó correctamente el documento";
                    if(!$guardarImpuestos || !$guardarPieFactura)
                        $mensajeDocumento .= ", pero con algunos errores";
                    }
                    $this->M_DocumentoSucursal->guardarActualizar([
                        'ddc_impuesto_comprobante'      =>   1,
                        'ddc_retencion_comprobante'     =>   0,
                        'iddetalle_documento_sucursal'  => $guardarActualizar,
                    ]);
                //code...
            } catch (\Throwable $e) {
                $mensajeDocumento = $e->getMessage();
            }
            echo json_encode([
                'estadoDocumento'       => $estadoDocumento,
                'mensajeDocumento'      => $mensajeDocumento,
            ]);
    }

    public function obtenerDocumentosAJax()
    {
        if(isset($_POST)){
            $idSucursal = isset($_POST['idSucursal'])?implode(',',$_POST['idSucursal']):$_SESSION['idsucursal'];
            $filter = ['idSucursal' => $idSucursal];
            $documentosSucursal = $this->M_DocumentoSucursal->obtenerDocumentosSucursal($filter);
            $arrayDocumentos = [];
            foreach ($documentosSucursal as $documento) {
                $arrayDocumentos[] = [
                    0   => $documento['iddetalle_documento_sucursal'],
                    1   => $documento['nombreTipoDocumento'],
                    2   => $documento['ultima_serie'],
                    3   => $documento['ultimo_numero'],
                    4   => $documento['contabilidad'] == 1? 'Si':'No',
                    5   => $documento['nombreTipoImpresion'],
                    6   => $documento['dds_afecta_inventario'] == 1? 'Si':'No',
                    7   => $documento['activo'] == 1? 'Activo': 'Inactivo',
                ];
            }
            echo json_encode($arrayDocumentos);
        }
    }

    public function infoDocumento()
    {
        $mensaje = 'No se puede acceder a éste Documento';
        $estado = false;
        $data=[];
        if(!isset($_POST))
            throw new Exception("No se ha enviado el ID de el documento");
            try {
                $filtro = ['idcategoria'=>$_POST['idDocumento']];
                $documento = $this->M_DocumentoSucursal->getDocumentoSucursal($_POST['idDocumento']);
                if($documento){
                    $data=$documento;
                    $mensaje ='';
                    $estado = true;
                }
            } catch (\Throwable $e) {
                $mensaje = $e->getMessage();
            }
        echo json_encode([
            'mensaje'   =>$mensaje,
            'estado'    =>$estado,
            'data'      =>$data
        ]);
    }
}
