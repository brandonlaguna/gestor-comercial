<?php
use Carbon\Carbon;
class KardexController extends Controladorbase{
    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Compras/M_DetalladoCompras',
            'ComprobanteContable/M_DetalleComprobanteContable',
            'Ventas/M_DetalladoVentas'
        ]);
    }

    public function index()
    {
        $this->load('v2/Kardex/kardexModals',[]);
        $this->load('v2/Kardex/kardexScript',[]);
        $this->frameview('v2/Kardex/kardex',[]);
        $this->load('v2/Kardex/kardexTable',[]);
    }

    public function getKardexAjax()
    {
        $kardexAjax =[];
        //registro de movimientos por compras
        $compras = $this->M_DetalladoCompras->getDetalleCompras([]);
        foreach ($compras as $kCompra) {
            $kardexAjax[] =[
                0   => $kCompra['fecha'],
                1   => 'Ingreso',
                2   => $kCompra['nombreArticulo'],
                3   => $kCompra['serie_comprobante'].'-'.$kCompra['num_comprobante'],
                4   => $kCompra['cantidadCompra'],
                5   => precio($kCompra['precio_total_lote'] / $kCompra['cantidadCompra'],0,'$'),
                6   => precio($kCompra['precio_total_lote'],0,'$'),
                7   => '',
                8   => '',
                9   => '',
            ];
        }

        //registro de ingreso por compras contables
        $comprasContables = $this->M_DetalleComprobanteContable->kardexComprobanteContable(['cc_tipo_comprobante' => 'I']);
        foreach ($comprasContables as $kComprasContables) {
            $kardexAjax[] =[
                0   => $kComprasContables['cc_fecha_cpte'],
                1   => 'Ingreso Contable',
                2   => $kComprasContables['nombreArticulo'],
                3   => $kComprasContables['cc_num_cpte'].'-'.$kComprasContables['cc_cons_cpte'],
                4   => $kComprasContables['dcc_cant_item_det'],
                5   => precio($kComprasContables['dcc_valor_item'] / $kComprasContables['dcc_cant_item_det'],0,'$'),
                6   => precio($kComprasContables['dcc_valor_item'],0,'$'),
                7   => '',
                8   => '',
                9   => '',
            ];
        }

        //registro de salidas por ventas
        $ventas = $this->M_DetalladoVentas->getDetalleVentas([]);
        foreach ($ventas as $kVentas) {
            $kardexAjax[] =[
                0   => $kVentas['fecha'],
                1   => 'Ventas',
                2   => $kVentas['nombreArticulo'],
                3   => $kVentas['serie_comprobante'].'-'.$kVentas['num_comprobante'],
                4   => '',
                5   => '',
                6   => '',
                7   => $kVentas['cantidadVenta'],
                8   => precio($kVentas['precio_total_lote'] / $kVentas['cantidadVenta'],0,'$'),
                9   => precio($kVentas['precio_total_lote'],0,'$'),
            ];
        }

        $comprasContables = $this->M_DetalleComprobanteContable->kardexComprobanteContable(['cc_tipo_comprobante' => 'V']);
        foreach ($comprasContables as $kComprasContables) {
            $kardexAjax[] =[
                0   => $kComprasContables['cc_fecha_cpte'],
                1   => 'Venta Contable',
                2   => $kComprasContables['nombreArticulo'],
                3   => $kComprasContables['cc_num_cpte'].'-'.$kComprasContables['cc_cons_cpte'],
                4   => '',
                5   => '',
                6   => '',
                7   => $kComprasContables['dcc_cant_item_det'],
                8   => precio($kComprasContables['dcc_valor_item'] / $kComprasContables['dcc_cant_item_det'],0,'$'),
                9   => precio($kComprasContables['dcc_valor_item'],0,'$'),
            ];
        }

        //registro de salidas por ventas contables
        echo json_encode($kardexAjax);
    }

}