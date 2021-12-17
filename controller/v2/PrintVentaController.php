<?php
use Carbon\Carbon;
use Mpdf\Mpdf;
class PrintVentaController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Personas/M_Personas',
            'Sucursales/M_Sucursales',
            'Ventas/M_VentasInfo',
            'Ventas/M_DetalladoVentas'
        ],
            $this->adapter);
    }

    public function index()
    {
        $location = LOCATION_CLIENT.'PrintVenta?action=pos&data='.$_GET['data'];
        $redirect = "ventas";

        $this->frameview("file/pdf/Print", array(
            "file_height" => '92%',
            "conf_print" => '',
            "venta" => '',
            "sucursal" => '',
            "url" => $location,
            "redirect"=> $redirect
        ));
    }

    public function pos()
    {
        //config pdf
        $mpdf = new \Mpdf\Mpdf([
			'mode'        => 'utf-8',
			'format'    => [80, 550],
			'orientation' => 'P',
			'default_font' => 'Arial',
            'tempDir' => 'files/comprobantes',
            'default_font_size' => 0,
			'default_font' => '',
			'margin_left' => 15,
			'margin_right' => 15,
			'margin_top' => 3,
			'margin_bottom' => 16,
			'margin_header' => 9,
			'margin_footer' => 9,
			'orientation' => 'P',
		]);

        $venta = $this->M_VentasInfo->obtenerVenta(['idventa' => $_GET['data']]);
        $mpdf->WriteHTML($this->load('v2/Print/Ventas/pos',[
            'data'            => $_GET['data'],
            'venta'           => (object)$venta,
            'detalleVenta'    => $this->M_DetalladoVentas->getDetalleVentas(['idventa' => $_GET['data']]),
        ],true));

		$mpdf->Output();
    }

    public function preview()
    {
        $this->load('v2/Print/Ventas/pos',[]);
    }

}