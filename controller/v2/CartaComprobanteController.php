<?php
use Carbon\Carbon;
class CartaComprobanteController extends ControladorBase{
    public $conectar;
	public $adapter;
    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Impuestos/M_Impuestos',
            'Permisos/M_Permisos',
            'ComprobanteContable/M_ComprobanteContable',
            'ComprobanteContable/M_DetalleComprobanteContable',
            'Impuestos/M_DetalleImpuestoGeneral',
            'Retenciones/M_DetalleRetencionGeneral'
        ],$this->adapter);
    }

    public function index()
    {
        if (isset($_GET["data"]) && !empty($_GET["data"])) {
            $view = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : "";
            $file_height = (isset($view)) ? "100%" : "92.4%";
            $conf_print = (isset($_GET["t"]) && !empty($_GET["t"])) ? $_GET["t"] : $view;
            $redirect = ("V" == "V" )?"ventas/nuevo":"compras/nuevo";
            $location = LOCATION_CLIENT.'/CartaComprobante?action=carta&data='.$_GET["data"];

            $this->frameview("file/pdf/carta", array(
                "file_height"   => $file_height,
                "conf_print"    => $conf_print,
                "id"            => $_GET["data"],
                "url"           => $location,
                "redirect"      =>$redirect,
                'view'          => $view
            ));
        }
    }

    public function carta()
    {
        $conf_print = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : false;
        $comprobanteid = $_GET["data"];
        $comprobantecontable = new ComprobanteContable($this->adapter);
        $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
        $sucursales = new Sucursal($this->adapter);
        $global = new sGlobal($this->adapter);
        $puc = new PUC($this->adapter);
        $articulo = new Articulo($this->adapter);
        $personas = new Persona($this->adapter);
        $detalleretencion = new DetalleRetencion($this->adapter);
        $detalleimpuesto = new DetalleImpuesto($this->adapter);
        $dataretenciones = new Retenciones($this->adapter);
        $dataimpuestos = new Impuestos($this->adapter);
        $cifrasEnLetras = new CifrasEnLetras();
        $pieFactura = new PieFactura($this->adapter);

        ############# funciones
        $comprobante = $this->M_ComprobanteContable->obtenerComprobanteContable([
            'cc_id_transa'      => $_GET["data"],
            'idsucursal'        => $_SESSION["idsucursal"],
        ]);
        foreach ($comprobante as $data) {}
        $articulos = $this->M_DetalleComprobanteContable->obtenerDetalleComprobanteContable([
            'dcc_id_trans'  => $comprobanteid
        ]);
        $totalimpuestos = $detallecomprobantecontable->getImpuestos($comprobanteid);
        $retenciones = $detalleretencion->getRetencionBy($comprobanteid, 1,$data['cc_tipo_comprobante']);
        $impuestos = $detalleimpuesto->getImpuestosBy($comprobanteid, 1, $data['cc_tipo_comprobante']);
        //ecuperando la sucursal por factura de venta
        //recuperar sucursal
        $sucursal = $sucursales->getSucursalById($data['cc_ccos_cpte']);
        //recuperar empresa
        $empresa = $global->getGlobal();
        //setear sucursal en variable sucursal
        foreach ($sucursal as $sucursal) {}
        //setear empresa en variable empresa
        foreach ($empresa as $empresa) {}
        //impuestos y retenciones de esta venta
        ///////////////////////
        //obtener informacion del comprobante
        $comprobante = $this->M_ComprobanteContable->obtenerComprobanteContable([
            'cc_id_transa'      => $_GET["data"],
            'idsucursal'        => $_SESSION["idsucursal"],
        ]);
        $resp = [];
        $resp2 = [];
        $pdf = new FPDF('P', 'mm', array(216,279), $this->adapter);
        $pdf->SetTitle("Registro de comprobante ");
        $x = 10;
        $y = 0;
        $array = array(
            "tercero" => $data['nombre_tercero'],
            "documento" => $data['documento_proveedor'],
            "telefono" => $data['telefono_proveedor'],
            "direccion" => $data['direccion_calle'],
            "ciudad" => $data['direccion_provincia'],
            "start_date" => $data['cc_fecha_cpte'],
            "end_date" => $data['cc_fecha_final_cpte'],
            "tipo_doc" => $data['prefijo'],
            "comprobante" => $data['serie_comprobante'] . zero_fill($data['num_comprobante'], 8),
        );
        $pdf->setData($array);
        $resolucion = $pieFactura->getPieFacturaByComprobanteId($data['iddetalle_documento_sucursal']);
        foreach ($resolucion as $res) {}
        $pdf->AddPage();
        $subtotal_credito = 0;
        $subtotal_debito = 0;
        $subtotal_cuentas = 0;
        foreach ($articulos as $articulos) {
            $getPuc = $puc->getPucById($articulos['dcc_cta_item_det']);
            $gertArticulo = $articulo->getArticuloById($articulos['dcc_cod_art']);

            $debito = ($articulos['dcc_d_c_item_det'] == "D" && $articulos['dcc_valor_item'] > 0) ? number_format($articulos['dcc_valor_item'], 0, '.', ',') : "";
            $credito = ($articulos['dcc_d_c_item_det'] == "C" && $articulos['dcc_valor_item'] > 0) ? number_format($articulos['dcc_valor_item'], 0, '.', ',') : "";
            $subtotal_credito += ($articulos['dcc_d_c_item_det'] == "C" && $articulos['dcc_valor_item'] > 0) ? $articulos['dcc_valor_item'] : 0;
            $subtotal_debito += ($articulos['dcc_d_c_item_det'] == "D" && $articulos['dcc_valor_item'] > 0) ? $articulos['dcc_valor_item'] : 0;

            $persona = $personas->getPersonaById($data['cc_idproveedor']);
            foreach ($persona as $tercero) {}

            if ($gertArticulo) {
                foreach ($gertArticulo as $articuloitem) {}
                $fecha_vcto = ($articulos['dcc_fecha_vcto_item'] != "0000-00-00") ? $articulos['dcc_fecha_vcto_item'] : "";
                $resp2[] = array(
                    $articulos['dcc_cta_item_det'],
                    $articuloitem->descripcion,
                    $articulos['dcc_cant_item_det'],
                    $data['cc_ccos_cpte'],
                    $tercero->num_documento,
                    $articulos['dcc_dato_fact_prove'],
                    $fecha_vcto,
                    number_format(round($articulos['dcc_base_ret_item']), 0, ',', '.'),
                    $debito,
                    $credito,
                );
                $respStandard[] = array(
                    $articuloitem->idarticulo,
                    $articuloitem->descripcion,
                    number_format($articulos['dcc_cant_item_det'], 2, ',', '.'),
                    number_format(round($articulos['dcc_valor_item'] / $articulos['dcc_cant_item_det']), 0, ',', '.'),
                    number_format(round($articulos['dcc_valor_item'] * ($articulos['dcc_base_imp_item'] / 100)), 0, ',', '.'),
                    number_format(round($articulos['dcc_valor_item'] * (($articulos['dcc_base_imp_item'] / 100) + 1)), 0, ',', '.'),
                );
            } elseif ($getPuc) {
                foreach ($getPuc as $pucitem) {}
                $fecha_vcto = ($articulos['dcc_fecha_vcto_item'] != "0000-00-00") ? $articulos['dcc_fecha_vcto_item'] : "";
                $resp2[] = array(
                    $pucitem->idcodigo,
                    $articulos['dcc_det_item_det'],
                    $articulos['dcc_cant_item_det'],
                    $data['cc_ccos_cpte'],
                    $tercero->num_documento,
                    $articulos['dcc_dato_fact_prove'],
                    $fecha_vcto,
                    number_format(round($articulos['dcc_base_ret_item']), 0, ',', '.'),
                    $debito,
                    $credito,
                );
            }
        $subtotal_cuentas++;
        }
        $resp2[] = array(
            "",
            $subtotal_cuentas . " Cuentas contables",
            "",
            "",
            "",
            "",
            "",
            "Total general:",
            number_format(round($subtotal_debito)),
            number_format(round($subtotal_credito)),
        );
        //valores a imprimir
        $subtotalimpuesto = 0;
        $listImpuesto = [];
        $listRetencion = [];
        $total_bruto = $data['cdi_debito'];
        $total_neto = $data['cdi_debito'];
        //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
        foreach ($totalimpuestos as $imp) {
            $subtotalimpuesto += $imp['cdi_debito'] - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1)); //aqui
            foreach ($impuestos as $data2) {}
            if ($impuestos) {
                if ($data2->im_porcentaje == $imp['cdi_importe']) {
                    $total_neto -= $subtotalimpuesto;
                    $total_bruto -= $subtotalimpuesto;
                }
            }

            foreach ($impuestos as $impuesto) {
                if ($imp->cdi_importe == $impuesto->im_porcentaje) {
                    //calculado
                    $calc = $imp['cdi_debito'] - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
                    //concatenacion del nombre
                    $im_nombre = $impuesto->im_nombre . " " . $impuesto->im_porcentaje . "%";
                    //arreglo
                    $listImpuesto[] = array($im_nombre, $calc);
                    /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                    $total_neto += $calc;
                } else {

                }
            }
        }
        foreach ($retenciones as $retencion) {
            if ($retencion->re_im_id <= 0) {
                //concatenacion del nombre
                $re_nombre = $retencion->re_nombre . " " . $retencion->re_porcentaje . "%";
                //calculado $subtotal['cdi_debito']*($retencion->re_porcentaje/100)
                $calc = $total_bruto * ($retencion->re_porcentaje / 100);
                //arreglo
                $listRetencion[] = array($re_nombre, $calc);
                /************************RESTANDO RETENCION DEL CALCULO*****************************/
                $total_neto -= $calc;
            } else {
                foreach ($totalimpuestos as $imp) {
                    $impid = $dataimpuestos->getImpuestosById($retencion->re_im_id);
                    foreach ($impid as $impid) {
                        if ($imp->cdi_importe == $impid->im_porcentaje) {
                            $re_nombre = $retencion->re_nombre . " (" . $retencion->re_porcentaje . "%)";
                            $iva = $imp['cdi_debito'] - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));

                            $calc = $iva * ($retencion->re_porcentaje / 100);

                            $listRetencion[] = array($re_nombre, $calc);
                            /************************RESTANDO RETENCION DEL CALCULO*****************************/
                            $total_neto -= $calc;
                        } else {
                        }
                    }
                }
            }
        }
        if ($conf_print == "standard") {
            //articulos
            $tablehead = array("Codigo", "Producto", "Cantidad", "Precio U.", "IVA", "Subtotal");
            $pdf->SetY(110);
            $pdf->SetX(10);
            $pdf->FancyTable($tablehead, $respStandard);
            ///cifras en letra
            $totalenletras = $cifrasEnLetras->convertirNumeroEnLetras(round($total_neto));
            //subtotal
            $prices[] = array("SUBTOTAL:" => "$" . number_format(round($total_bruto), 0, '.', ','));
            //impuestos
            foreach ($listImpuesto as $listImpuesto) {
                $prices[] = array($listImpuesto[0] . ":" => "$" . number_format(round($listImpuesto[1]), 0, '.', ','));
            }
            //retenciones
            foreach ($listRetencion as $listRetencion) {
                $prices[] = array($listRetencion[0] => "$" . number_format(round($listRetencion[1]), 0, '.', ','));
            }
            //total
            $prices[] = array("TOTAL:" => "$" . number_format(round($total_neto), 0, '.', ','));
            //setear articulos, y precios
            $pdf->setResolucion($res->pf_text);
            $pdf->setValorEnLetras("Valor en letras: " . $totalenletras . " pesos Colombianos");
            $pdf->setPrices($prices);

        } else {
            //cuentas contables
            $tablehead = array("Cuenta", "Detalle", "Cant.", "C. Costos", "Tercero", "Doc/Detalle", "Fecha Venc.", "Base Retencion", "Debito", "Credito");
            $pdf->SetY(110);
            $pdf->SetX(10);
            $pdf->FancyTableContabilidad($tablehead, $resp2);
        }
        $pdf->AutoPrint();
        $pdf->output();
    }
}