<?php
class ReporteVentasController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();

        $this->loadModel([
            'Ventas/M_ReporteVentas',
        ],$this->adapter);

    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >= 3){
            $this->frameview("v2/reporteVentas/reporteVentas",[]);

            $this->load("v2/reporteVentas/reporteVentasModal",[]);
            $this->load("v2/reporteVentas/reporteVentasScript",[]);
            $this->load("v2/reporteVentas/reporteVentasTable",[]);
            javascript([
                'node_modules/@popperjs/core/dist/umd/popper.min',
                'node_modules/tippy.js/dist/tippy-bundle.umd.min',
                "js/controller/tooltip-colored",
                "js/controller/popover-colored",
                "lib/datatablesV1.0.0/datatables.min",
            ]);

        }else{
            $this->redirect("Index","");
        }

    }

    public function getReporteVentasAjax()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >= 3){
            $filter = [
                'su_id'     => $_SESSION["idsucursal"],
                'usr_uid'    => $_SESSION["usr_uid"]
            ];
            $ventas = $this->M_ReporteVentas->getReporteVentas($filter);
            $listVenta = [];
            foreach ($ventas as $venta) {
                $listVenta[] = [
                    0   => $venta['idventa'],
                    1   => $venta['nombreCliente'],
                    2   => $venta['tipo_pago'],
                    3   => $venta['tipo_comprobante'],
                    4   => $venta['serie_comprobante'],
                    5   => $venta['num_comprobante'],
                    6   => $venta['fecha'],
                    7   => $venta['sub_total'],
                    8   => $venta['subtotal_importe'],
                    9   => $venta['total'],
                    10  => status($venta['estado'])
                ];
            }
            echo json_encode($listVenta);
        }else{
            $this->redirect("Index","");
        }
    }

}