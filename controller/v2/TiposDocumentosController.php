<?php
use Carbon\Carbon;
class TiposDocumentosController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel(['DocumentoSucursal/M_TiposDocumentos'],$this->adapter);
    }

    public function index(){
        $listOperaciones = [
            [ 'item_id'  => 'Persona', 'item_name'  => 'Persona' ],
            [ 'item_id'  => 'Comprobante', 'item_name'  => 'Comprobante' ]
        ];
        $listProcesos = [
            [ 'item_id'  => 'Venta', 'item_name'  => 'Venta' ],
            [ 'item_id'  => 'Ingreso', 'item_name'  => 'Ingreso' ],
            [ 'item_id'  => 'Contabilidad', 'item_name'  => 'Contabilidad' ]
        ];
        $this->frameView('v2/TiposDocumentos/tiposDocumentos',[]);
        $this->load('v2/TiposDocumentos/tiposDocumentosModals',[
            'listOperaciones'   => $listOperaciones,
            'listProcesos'      => $listProcesos,
        ]);
        $this->load('v2/TiposDocumentos/tiposDocumentosScript',[]);
        $this->load('v2/TiposDocumentos/tiposDocumentosTable',[]);
    }

    public function obtenerTiposDocumentosAjax()
    {
        $tiposDocumentos = $this->M_TiposDocumentos->obtenerTiposDocumentos();
        $arrayTiposDocumentos = [];
        foreach ($tiposDocumentos as $tipoDocumento) {
            $arrayTiposDocumentos[] = [
                0   => $tipoDocumento['idtipo_documento'],
                1   => $tipoDocumento['nombre'],
                2   => $tipoDocumento['prefijo'],
                3   => $tipoDocumento['operacion'],
                4   => $tipoDocumento['proceso'],
                5   => $tipoDocumento['type'],
                6   => 'Activo'
            ];
        }
        echo json_encode($arrayTiposDocumentos);
    }

    public function guardarActualizarTipoDocumento()
    {
        $mensajeTipoDocumento   = 'Error al almacenar éste tipo de documento';
        $estadoTipoDocumento    = false;
        try {
            if(!isset($_POST))
                throw new Exception("No se ha enviado ninguna informacion");

            $guardarActualizar = $this->M_TiposDocumentos->guardarActualizarTipoDocumento([
                'idtipo_documento'  => isset($_POST['idtipo_documento']) && !empty($_POST['idtipo_documento'])? $_POST['idtipo_documento'] : null,
                'nombre'            => $_POST['nombre'],
                'prefijo'           => $_POST['prefijo'],
                'type'              => $_POST['type'],
                'operacion'         => $_POST['operacion'],
                'proceso'           => $_POST['proceso'],
            ]);
            if($guardarActualizar){
                $estadoTipoDocumento = true;
                $mensajeTipoDocumento = 'Tipo de documento guardado o actualizado correctamente';
            }
        } catch (\Throwable $e) {
            $mensajeTipoDocumento = $e->getMessage();
        }
        echo json_encode([
            'mensajeTipoDocumento'  => $mensajeTipoDocumento,
            'estadoTipoDocumento'   => $estadoTipoDocumento
        ]);
    }

}