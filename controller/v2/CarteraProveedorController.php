<?php
use Carbon\Carbon;
class CarteraProveedorController extends ControladorBase{
    public $conectar;
	public $adapter;
    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Cartera/M_CarteraProveedor'
        ],$this->adapter);
    }
    public function index(){
        $this->frameview('v2/CarteraProveedor/carteraProveedor',[]);
        $this->load('v2/CarteraProveedor/carteraProveedorModals',[]);
        $this->load('v2/CarteraProveedor/carteraProveedorScript',[]);
        $this->load('v2/CarteraProveedor/carteraProveedorTable',[]);
    }

    public function carteraProveedoresAjax(){
        $carteraProveedor = $this->M_CarteraProveedor->obtenerCarteraProveedor();
        $carteraProveedorContable = $this->M_CarteraProveedor->obtenerCarteraProveedorContable();
        $arrayCarteraProveedor = [];
        foreach ($carteraProveedor as $cartera) {
            $infoCartera = '';
            $arrayCarteraProveedor[] = [
                0   => $cartera['idcredito_proveedor'],
                1   => $cartera['fecha_pago'],
                2   => '<div class="row">
                            <div class="col-10">
                                <b>Proveedor:</b> '.$cartera['nombreProveedor'].'
                                <br><b>Comprobante:</b> '.$cartera['comprobanteCompra'].'
                                <br><b>Tipo Compra:</b> '.($cartera['contabilidad']==1?'Contable':'Convencional').'
                                <br><b>Estado Compra:</b> '.status($cartera['estadoCompra']).'
                            </div>
                            <div class="col-2 text-right">
                                <div class="btn-group-vertical btn-group-sm">
                                    '.$infoCartera.'
                                </div>
                            </div>
                        </div>',
                3   => $cartera['fecha_pago'],
                4   => 4,
                5   => $cartera['deuda_total'],
                6   => 6,
                7   => 7,
                8   => 8
            ];
        }

        foreach ($carteraProveedorContable as $carteraContable) {
            $infoCarteraContable = '';
            $arrayCarteraProveedor[] = [
                0   => $carteraContable['idcredito_proveedor'],
                1   => $carteraContable['cc_fecha_cpte'],
                2   => '<div class="row">
                            <div class="col-10">
                                <b>Proveedor:</b> '.$carteraContable['nombreProveedor'].'
                                <br><b>Comprobante:</b> '.$carteraContable['comprobanteCompra'].'
                                <br><b>Tipo Compra:</b> '.($carteraContable['contabilidad'] == 1?'Contable':'Convencional') .'
                                <br><b>Estado Compra:</b> '.status($carteraContable['estadoCompra']).'
                            </div>
                            <div class="col-2 text-right">
                                <div class="btn-group-vertical btn-group-sm">
                                    '.$infoCarteraContable.'
                                </div>
                            </div>
                        </div>',
                3   => $carteraContable['fecha_pago'],
                4   => moneda($carteraContable['totalAbonoCartera'],0,'$'),
                5   => moneda($carteraContable['deuda_total'],0,'$'),
                6   => moneda($carteraContable['deuda_total']-$carteraContable['totalAbonoCartera'],0,'$'),
                7   => "<a href='#proveedor/pagar_deuda/".$carteraContable['idcredito_proveedor']."'><i class='fas fa-file-invoice-dollar text-success'></i></a>",
                8   => status($carteraContable['estadoCartera'])
            ];
        }
        echo json_encode($arrayCarteraProveedor);
    }

}