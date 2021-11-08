<?php
use Carbon\Carbon;
class FiltroReporteController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
    }

    public function filtroReporte()
    {
        if(isset($_POST)){
            if(isset($_POST['filtroSucursal']) && !empty($_POST['filtroSucursal'])){
                $_SESSION['filtroSucursalReporte'] = $_POST['filtroSucursal'];
            }else{
                unset($_SESSION['filtroSucursalReporte']);
            }

            if(isset($_POST['filtroComprobante']) && !empty($_POST['filtroComprobante'])){
                $arrayFIltro = explode(',',$_POST['filtroComprobante']);
                $comma_separated = implode("','", $arrayFIltro);
                $comma_separated = "'".$comma_separated."'";
                $_SESSION['filtroComprobanteReporte'] = $comma_separated;
            }else{
                unset($_SESSION['filtroComprobanteReporte']);
            }

            if(isset($_POST['filtroTipoPago']) && !empty($_POST['filtroTipoPago'])){
                $_SESSION['filtroTipoPagoReporte'] = $_POST['filtroTipoPago'];
            }else{
                unset($_SESSION['filtroTipoPagoReporte']);
            }

            if(isset($_POST['filtroEstado']) && !empty($_POST['filtroEstado'])){
                $arrayFIltro = explode(',',$_POST['filtroEstado']);
                $comma_separated = implode("','", $arrayFIltro);
                $comma_separated = "'".$comma_separated."'";
                $_SESSION['filtroEstadoReporte'] = $comma_separated;
            }else{
                unset($_SESSION['filtroEstadoReporte']);
            }

            //filtro de clientes
            if(isset($_POST['filtroCliente']) && !empty($_POST['filtroCliente'])){
                $_SESSION['filtroClienteReporte'] = $_POST['filtroCliente'];
            }else{
                unset($_SESSION['filtroClienteReporte']);
            }
            //filtro por proveedores
            if(isset($_POST['filtroProveedor']) && !empty($_POST['filtroProveedor'])){
                $_SESSION['filtroProveedorReporte'] = $_POST['filtroProveedor'];
            }else{
                unset($_SESSION['filtroProveedorReporte']);
            }

            echo json_encode(['estado'=>true]);
        }
    }
    public function filtroFecha()
    {
        if(isset($_POST)){
            $listSucursales = $_POST['filtroFecha'];
            if(isset($listSucursales) && !empty($_POST['filtroFecha'])){
                $_SESSION['filtroFechaReporte'] = $listSucursales;
            }else{
                unset($_SESSION['filtroFechaReporte']);
            }
            echo json_encode(['estado'=>true]);
        }
    }

    public function limpiarFiltroReporte()
    {
        unset(
            $_SESSION['filtroSucursalReporte'],
            $_SESSION['filtroComprobanteReporte'],
            $_SESSION['filtroTipoPagoReporte'],
            $_SESSION['filtroEstadoReporte'],
            $_SESSION['filtroFechaReporte'],
            $_SESSION['filtroProveedorReporte']
        );

        echo json_encode(['estado'=>true]);
    }
}