<?php
use Carbon\Carbon;
class ReporteVentasController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel(['Ventas/M_DetalladoVentas','Ventas/M_ReporteVentas', 'Sucursales/M_Sucursales', 'Personas/M_Personas'],$this->adapter);
    }

    public function index()
    {}

    public function detallado()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            //obtener sucursales
            $sucursales = $this->M_Sucursales->getSucursales();
            //obtener clientes
            $clientes = $this->M_Personas->obtenerPersonas(['idTipoPersona' => 'Cliente']);
            $this->load('v2/reporteVentas/reporteVentasModals',[
                'sucursales'    => $sucursales,
                'clientes'      => $clientes
            ]);
            $this->frameview('v2/reporteVentas/detallado/detallado',[]);
            $this->load('v2/reporteVentas/reporteVentasScript',[
                'uLInk' => ''
            ]);
            $this->load(['v2/reporteVentas/detallado/detalladoTable'],[]);

        }else{
            $this->redirect('index','');
        }
    }
    public function detalladoAjax()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $filter = [];
            //filtro por sucursales
            if(isset($_SESSION['filtroSucursalReporte']) && !empty($_SESSION['filtroSucursalReporte'])){
                $filter = array_merge($filter,['filtroSucursalReporte' => $_SESSION['filtroSucursalReporte']]);
            }
            //filtro por fechas
            if(isset($_SESSION['filtroFechaReporte']) && !empty($_SESSION['filtroFechaReporte'])){
                $fechas = explode(' to ',$_SESSION['filtroFechaReporte']);
                $filter = array_merge($filter,['inicioFiltroFechaReporte' => $fechas[0]]);
                if(isset($fechas[1])){
                    $filter = array_merge($filter,['finFiltroFechaReporte' => $fechas[1]]);
                }
            }

            //filtro por comprobantes
            if(isset($_SESSION['filtroComprobanteReporte']) && !empty($_SESSION['filtroComprobanteReporte'])){
                $filter = array_merge($filter,['filtroComprobanteReporte' => $_SESSION['filtroComprobanteReporte']]);
            }

            //filtro por tipo de pago
            if(isset($_SESSION['filtroTipoPagoReporte']) && !empty($_SESSION['filtroTipoPagoReporte'])){
                $filter = array_merge($filter,['filtroTipoPagoReporte' => $_SESSION['filtroTipoPagoReporte']]);
            }
            //filtro por estado
            if(isset($_SESSION['filtroEstadoReporte']) && !empty($_SESSION['filtroEstadoReporte'])){
                $filter = array_merge($filter,['filtroEstadoReporte' => $_SESSION['filtroEstadoReporte']]);
            }

            //filtro por clientes
            if(isset($_SESSION['filtroClienteReporte']) && !empty($_SESSION['filtroClienteReporte'])){
                $filter = array_merge($filter, ['filtroClienteReporte' => $_SESSION['filtroClienteReporte']]);
            }

            $listDetallado=[];
            $detalladoVentas = $this->M_DetalladoVentas->getDetalleVentas($filter);
            foreach ($detalladoVentas as $detalle) {
                $fechaVenta = Carbon::createFromFormat('Y-m-d', $detalle['fecha'])->format('Y, m d');
                $listDetallado[] = [
                    'id'          =>    $detalle['idventa'],
                    'fecha'       =>    $fechaVenta,
                    'sucursal'    =>    $detalle['nombreSucursal'],
                    'empleado'    =>    $detalle['nombreEmpleado'],
                    'cliente'     =>    $detalle['nombreCliente'],
                    'comprobante' =>    $detalle['serie_comprobante'].$detalle['num_comprobante'],
                    'impuesto'    =>    $detalle['iva_compra'],
                    'articulo'    =>    $detalle['nombreArticulo'],
                    'codigo'      =>    $detalle['idarticulo'],
                    'cantidad'    =>    $detalle['cantidadVenta'],
                    'p_unitario'  =>    $detalle['precio_venta'],
                    'p_total'     =>    $detalle['precio_venta'] * $detalle['cantidadVenta'],
                ];
            }

            $response = [
                "result"=> true,
                "data"=> [
                    "contents"=> $listDetallado,
                    "pagination"=> [
                        "page"=> 1,
                        "totalCount"=> 100
                    ]
                ]
            ];
            echo json_encode($response);

        }else{
            return false;
        }
    }

    public function general()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){

            $sucursales = $this->M_Sucursales->getSucursales();
            //obtener clientes
            $clientes = $this->M_Personas->obtenerPersonas(['idTipoPersona' => 'Cliente']);
            $this->load('v2/reporteVentas/reporteVentasModals',[
                'sucursales'    => $sucursales,
                'clientes'      => $clientes
            ]);
            $this->frameview('v2/reporteVentas/general/general',[]);
            $this->load('v2/reporteVentas/reporteVentasScript',[
                'uLInk' => ''
            ]);
            $this->load(['v2/reporteVentas/general/generalTable'],[]);

        }else{
            $this->redirect('index','');
        }
    }

    public function generalAjax()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $listVentas=[];
            $filter = [];

            //filtro por sucursales
            if(isset($_SESSION['filtroSucursalReporte']) && !empty($_SESSION['filtroSucursalReporte'])){
                $filter = array_merge($filter,['filtroSucursalReporte' => $_SESSION['filtroSucursalReporte']]);
            }

            //filtro por fechas
            if(isset($_SESSION['filtroFechaReporte']) && !empty($_SESSION['filtroFechaReporte'])){
                $fechas = explode(' to ',$_SESSION['filtroFechaReporte']);
                $filter = array_merge($filter,['inicioFiltroFechaReporte' => $fechas[0]]);
                if(isset($fechas[1])){
                    $filter = array_merge($filter,['finFiltroFechaReporte' => $fechas[1]]);
                }
            }

            //filtro por comprobantes
            if(isset($_SESSION['filtroComprobanteReporte']) && !empty($_SESSION['filtroComprobanteReporte'])){
                $filter = array_merge($filter,['filtroComprobanteReporte' => $_SESSION['filtroComprobanteReporte']]);
            }

            //filtro por tipo de pago
            if(isset($_SESSION['filtroTipoPagoReporte']) && !empty($_SESSION['filtroTipoPagoReporte'])){
                $filter = array_merge($filter,['filtroTipoPagoReporte' => $_SESSION['filtroTipoPagoReporte']]);
            }
            //filtro por estado
            if(isset($_SESSION['filtroEstadoReporte']) && !empty($_SESSION['filtroEstadoReporte'])){
                $filter = array_merge($filter,['filtroEstadoReporte' => $_SESSION['filtroEstadoReporte']]);
            }

             //filtro por clientes
            if(isset($_SESSION['filtroClienteReporte']) && !empty($_SESSION['filtroClienteReporte'])){
                $filter = array_merge($filter, ['filtroClienteReporte' => $_SESSION['filtroClienteReporte']]);
            }
            $reporteVentas = $this->M_ReporteVentas->getReporteVentas($filter);
            foreach ($reporteVentas as $venta) {
                $fechaVenta = Carbon::createFromFormat('Y-m-d', $venta['fecha'])->format('Y, m d');
                $listVentas[] = [
                    'id'          => $venta['idventa'],
                    'fecha'       => $fechaVenta,
                    'sucursal'    => $venta['nombreSucursal'],
                    'empleado'    => $venta['nombreEmpleado'],
                    'cliente'     => $venta['nombreCliente'],
                    'comprobante' => $venta['serie_comprobante'].$venta['num_comprobante'],
                    'impuesto'    => $venta['totalImpuesto'],
                    'subtotal'    => $venta['subtotalVenta'],
                    'total'       => $venta['totalVenta'],
                ];
            }
            $response = [
                "result"=> true,
                "data"=> [
                    "contents"=> $listVentas,
                    "pagination"=> [
                        "page"=> 1,
                        "totalCount"=> 100
                    ]
                ]
            ];
            echo json_encode($response);
        }else{
            return false;
        }
    }
}
