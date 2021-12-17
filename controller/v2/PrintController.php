<?php
use Carbon\Carbon;
use Mpdf\Mpdf;
class PrintController extends Controladorbase{

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
            'Compras/M_ReporteCompras',
            'Compras/M_DetalladoCompras'
        ],
            $this->adapter);
    }

    public function index(){
    }

    public function contabilidad()
    {
        $location = LOCATION_CLIENT.'Print?action=comprobanteContable&data='.$_GET['data'];
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
    public function comprobanteContable()
    {
        $this->loadModel(['ComprobanteContable/M_DetalleComprobanteContable','ComprobanteContable/M_ComprobanteContable']);
        //config pdf
        $mpdf = new \Mpdf\Mpdf([
			'mode'        => 'utf-8',
			'format'    => [216, 330],
			'orientation' => 'P',
			'default_font' => 'Arial',
            'tempDir' => 'files/comprobantes'
		]);

        $comprobanteContable = $this->M_ComprobanteContable->obtenerComprobanteContable(['cc_id_transa' => $_GET['data']]);
        $mpdf->WriteHTML($this->load('v2/Print/Contabilidad/ComprobanteContable',[
            'data'                  => $_GET['data'],
            'comprobante'           => (object)$comprobanteContable[0],
            'detalleComprobante'    => $this->M_DetalleComprobanteContable->obtenerDetalleComprobanteContable(['dcc_id_trans' => $_GET['data']]),
        ],true));

		$mpdf->Output();
    }

    public function textView()
    {
        $this->loadModel(['ComprobanteContable/M_DetalleComprobanteContable','ComprobanteContable/M_ComprobanteContable']);
        $comprobanteContable = $this->M_ComprobanteContable->obtenerComprobanteContable(['cc_id_transa' => $_GET['data']]);
        $this->load('v2/Print/Contabilidad/ComprobanteContable',[
            'data'  => 1,
            'comprobante'           => (object)$comprobanteContable[0],
            'detalleComprobante'    => $this->M_DetalleComprobanteContable->obtenerDetalleComprobanteContable(['dcc_id_trans' => $_GET['data']]),
        ]);
    }

}