<?php
use Carbon\Carbon;
class ReporteComprasController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel(['Personas/M_Personas', 'Sucursales/M_Sucursales', 'Compras/M_ReporteCompras', 'Compras/M_DetalladoCompras'],$this->adapter);
    }
    public function index()
    {
    }

    public function general()
    {
        //obtener sucursales
        $sucursales = $this->M_Sucursales->getSucursales();
        //obtener clientes
        $clientes = $this->M_Personas->obtenerPersonas(['idTipoPersona' => 'Cliente']);
        $this->load('v2/reporteCompras/reporteComprasModals',[
            'sucursales'    => $sucursales,
            'clientes'      => $clientes
        ]);
        $this->frameview('v2/reporteCompras/general/general',[]);
        $this->load('v2/reporteCompras/reporteComprasScript',[
            'uLInk' => ''
        ]);
        $this->load(['v2/reporteCompras/general/generalTable'],[]);
    }

    public function generalAjax()
    {

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
        $compras = $this->M_ReporteCompras->getReporteCompras($filter);
        $listCompras = [];
        foreach ($compras as $compra) {
            $fechaCompra = Carbon::createFromFormat('Y-m-d', $compra['fecha'])->format('Y, m d');
            $listCompras[] = [
                'id'            => $compra['idingreso'],
                'fecha'         => $fechaCompra,
                'sucursal'      => $compra['nombreSucursal'],
                'empleado'      => $compra['nombreEmpleado'],
                'proveedor'     => $compra['nombreProveedor'],
                'comprobante'   => $compra['serie_comprobante'].zero_fill($compra['num_comprobante'],8),
                'impuesto'      => $compra['totalImpuesto'],
                'subtotal'      => $compra['subtotalCompra'],
                'total'         => $compra['totalCompra'],
            ];
        }

        $response = [
            "result"=> true,
            "data"=> [
                "contents"=> $listCompras,
                "pagination"=> [
                    "page"=> 1,
                    "totalCount"=> 100
                ]
            ]
        ];
        echo json_encode($response);
    }

    public function detallado()
    {
        //obtener sucursales
        $sucursales = $this->M_Sucursales->getSucursales();
        //obtener clientes
        $clientes = $this->M_Personas->obtenerPersonas(['idTipoPersona' => 'Cliente']);
        $this->load('v2/reporteCompras/reporteComprasModals',[
            'sucursales'    => $sucursales,
            'clientes'      => $clientes
        ]);
        $this->frameview('v2/reporteCompras/detallado/detallado',[]);
        $this->load('v2/reporteCompras/reporteComprasScript',[
            'uLInk' => ''
        ]);
        $this->load(['v2/reporteCompras/detallado/detalladoTable'],[]);
    }

    public function detalladoAjax()
    {
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
            $detalladoCompras = $this->M_DetalladoCompras->getDetalleCompras($filter);
            foreach ($detalladoCompras as $detalle) {
                $fechaVenta = Carbon::createFromFormat('Y-m-d', $detalle['fecha'])->format('Y, m d');
                $listDetallado[] = [
                    'id'          =>    $detalle['idingreso'],
                    'fecha'       =>    $fechaVenta,
                    'sucursal'    =>    $detalle['nombreSucursal'],
                    'empleado'    =>    $detalle['nombreEmpleado'],
                    'cliente'     =>    $detalle['nombreCliente'],
                    'comprobante' =>    $detalle['serie_comprobante'].$detalle['num_comprobante'],
                    'impuesto'    =>    $detalle['iva_compra'],
                    'articulo'    =>    $detalle['nombreArticulo'],
                    'codigo'      =>    $detalle['idarticulo'],
                    'cantidad'    =>    $detalle['cantidadCompra'],
                    'p_unitario'  =>    $detalle['precio_compra'],
                    'p_total'     =>    $detalle['precio_compra'] * $detalle['cantidadCompra'],
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
    }
}