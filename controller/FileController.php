<?php
class FileController extends Controladorbase
{

    private $adapter;
    private $conectar;

    public function __construct()
    {
        parent::__construct();

        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }

    public function index()
    {

    }

    public function venta()
    {
        if (isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {

                $idventa = $_GET["data"];
                //configuracion para el modo de visualizacion
                $view = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : "";
                $file_height = (isset($view)) ? "100%" : "92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"])) ? $_GET["t"] : "";
                //recuperando la venta por id
                $ventas = new Ventas($this->adapter);
                $venta = $ventas->getVentaById($idventa);
                if ($venta) {
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                    foreach ($venta as $data) {}
                    //ecuperando la sucursal por factura de venta
                    $sucursales = new Sucursal($this->adapter);
                    $sucursal = $sucursales->getSucursalById($data->idsucursal);

                    //traer la vista del tipo de impresion que se aplica a este comprobante
                    $funcion = $data->pri_conf . "_venta";
                    //configuracion solo para desarrollo
                    //$location = "http://127.0.0.1/app";
                    $location = LOCATION_CLIENT;
                    $redirect = "ventas";

                    $this->frameview("file/pdf/" . $data->pri_nombre, array(
                        "file_height" => $file_height,
                        "conf_print" => $conf_print,
                        "venta" => $venta,
                        "sucursal" => $sucursal,
                        "funcion" => $funcion,
                        "id" => $idventa,
                        "url" => $location,
                        "redirect"=>$redirect
                    ));
                } else {
                    echo "Factura no disponible";
                }
            } else {

            }
        }
    }

    public function caja()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {

                $idreporte = $_GET["data"];
                $view = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : "";
                $file_height = (isset($view)) ? "100%" : "92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"])) ? $_GET["t"] : "";

                //recuperando la venta por id
                $caja = new ReporteCaja($this->adapter);
                $reporte = $caja->getReporteById($idreporte);
                if ($reporte) {
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                    foreach ($reporte as $data) {}
                    //ecuperando la sucursal por factura de venta
                    $sucursales = new Sucursal($this->adapter);
                    $sucursal = $sucursales->getSucursalById($data->rc_idsucursal);

                    //traer la vista del tipo de impresion que se aplica a este comprobante
                    $funcion = "cierre_caja";
                    //configuracion solo para desarrollo
                    //$location = "http://127.0.0.1/app";
                    $location = LOCATION_CLIENT;
                    $redirect = "caja";

                    $this->frameview("file/pdf/reporteCaja", array(
                        "file_height" => $file_height,
                        "con_print" => $conf_print,
                        "sucursal" => $sucursal,
                        "funcion" => $funcion,
                        "id" => $idreporte,
                        "url" => $location,
                        "redirect"=>$redirect
                    ));
                } else {
                    echo "Reporte no disponible";
                }
            } else {

            }
        }
    }

    public function cierre_turno()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {
                $idreporte = $_GET["data"];
                $view = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : "";
                $file_height = (isset($view)) ? "100%" : "92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"])) ? $_GET["t"] : "";

                $cierreturnos = new CierreTurno($this->adapter);
                $cierreturnos->setRct_id($idreporte);
                $cierreturnos->setRct_status(1);
                $cierreturno = $cierreturnos->getCierreTurnoById();
                if($cierreturno){
                    $sucursales = new Sucursal($this->adapter);
                    $sucursal = $sucursales->getSucursalById($data->rc_idsucursal);
                    //traer la vista del tipo de impresion que se aplica a este comprobante
                    $funcion = "pos_cierre_turno";
                    //configuracion solo para desarrollo
                    //$location = "http://127.0.0.1/app";
                    $location = LOCATION_CLIENT;
                    $redirect = "caja";
                    

                }
                $this->frameview("file/pdf/reporteCaja", array(
                    "file_height" => $file_height,
                    "con_print" => $conf_print,
                    "sucursal" => $sucursal,
                    "funcion" => $funcion,
                    "id" => $idreporte,
                    "url" => $location,
                    "redirect"=>$redirect
                ));
            }else{
                echo "Reporte no disponible";
            }
        }else{
            
        }
    }

    public function pos_cierre_turno()
    {
        if (isset($_GET["data"]) && !empty($_GET["data"])) {
            //require_once 'lib/printpdf/fpdf.php';
            $sucursales = new Sucursal($this->adapter);
            $venta = new Ventas($this->adapter);
            $cierreturnos = new CierreTurno($this->adapter);
            $metodopago = new DetalleMetodoPago($this->adapter);
            $idreporte = $_GET["data"];
            //recuperando la venta por id
            $caja = new ReporteCaja($this->adapter);
            $cartera = new Cartera($this->adapter);
            $reporte = $caja->getReporteById($idreporte);
            $cierreturnos->setRct_id($idreporte);
            $cierreturnos->setRct_status(1);
            $cierreturno = $cierreturnos->getCierreTurnoById();
            if ($cierreturno) {
                //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach ($cierreturno as $data) {}
                //ecuperando la sucursal por factura de venta
                $sucursal = $sucursales->getSucursalById($data->rct_idsucursal);
                foreach ($sucursal as $sucursal) {}

                $venta->setIdsucursal($sucursal->idsucursal);
                $venta->setIdusuario($data->rct_idusuario);
                $ventas = $venta->reporte_detallado_categoria_by_user($data->rct_venta_desde, $data->rct_venta_hasta);
                $articulos = $venta->reporte_detallado_articulo_by_user($data->rct_venta_desde, $data->rct_venta_hasta);
                $metodospago = $venta->getDetalleMetodoPagoByVentas($data->rct_venta_desde, $data->rct_venta_hasta);
                $pagocarteras = $cartera->getDetalleMotodoPagoByVentas($data->rct_date, $data->rct_date, $data->rct_idusuario);
                $pagoproveedores = $cartera->getDetalleMotodoPagoByCompras($data->rct_date, $data->rct_date, $data->rct_idusuario);
                $height_pos =0;
                $height_pos += count($articulos)*6;
                $height_pos += count($ventas)*6;
                $height_pos += count($metodospago)*6;
                $height_pos += count($pagocarteras)*6;
                $height_pos += count($pagoproveedores)*6;


                $fecha_reporte = $data->rct_date;
                // ------------------------------------- Instanciar la clase FPDF_POS y Cabecera -------------------------------------------------
                $pdf = new FPDF_POS('P', 'mm', array(80,(170+$height_pos)), $this->adapter);
                $pdf->SetMargins(4, 4, 4); 
                $pdf->setY(2);
                $pdf->setX(2);
                $array = array(
                    "tercero" => "",
                    "documento" => "",
                    "telefono" => "",
                    "direccion" => "",
                    "ciudad" => "",
                    "start_date" => "",
                    "end_date" => "",
                    "tipo_doc" => "",
                    "comprobante" => "",
                );
                $pdf->SetTitle("Pago cartera");
                $pdf->AddPage();
                $pdf->Image(LOCATION_CLIENT . $sucursal->logo_img,26, 5, 29, 29);
                $pos = 24;
                $pdf->Ln(26);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->MultiCell(72, 4, $sucursal->razon_social, 0, 'C');
                $pdf->Ln(1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->MultiCell(72, 4, utf8_decode($sucursal->telefono . " - " . $sucursal->prefijo_documento . " " . $sucursal->num_documento), 0, 'C');
                $pdf->Ln(0.5);
                $pdf->Cell(72, 4, utf8_decode($sucursal->ciudad . " - " . $sucursal->pais), 0, 0, 'C');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);

                // ---------------------------------------------- Categoria --------------------------------------------------------------
                // $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(72, 4, 'Categoria', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 9);
                $total = 0;
                foreach ($ventas as $ventas) {
                    $pdf->Cell(72,4, "Categoria: ".$ventas->nombre_categoria, 0, 0, 'L');
                    $pdf->Ln(4);
                    $pdf->Cell(72,4, "Subtotal:   ".moneda($ventas->precio_categoria), 0, 0, 'L');
                    $pdf->Ln(4);
                    $pdf->Cell(72,4, "IVA:           ".moneda(($ventas->precio_importe_categoria * $ventas->cantidad)), 0, 0, 'L');
                    $pdf->Ln(4);
                    $pdf->Cell(72,4, "Neto:         ".moneda($ventas->precio_categoria + $ventas->precio_importe_categoria), 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Cell(72,4,"________________________________________",0,0,'C');
                    $pdf->Ln(4);
                    $total += $ventas->precio_categoria + $ventas->precio_importe_categoria;
                }

                // ---------------------------------------------- Articulo --------------------------------------------------------------
                // $pdf->Cell(72, 4, 'Pago cartera ' . $_GET["data"],0,0,'C');
                $pdf->Cell(72, 4, 'Articulos', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->Cell(20, 4, 'ARTICULO',0,0,'L',true);
                $pdf->Cell(17, 4,'SUBTOTAL',0,0,'R',true);
                $pdf->Cell(16, 4,'IVA.',0,0,'R',true);
                $pdf->Cell(20, 4,'NETO',0,0,'R',true);
                $pdf->Ln(5);

                $pdf->SetFont('Arial', '', 9);
                $pagos_realizados = 0;
                $off = $pos + 6;
                foreach ($articulos as $articulos) {
                    $pdf->Cell(20, 4, substr($articulos->nombre_articulo, 0, 9), 0, 0, 'L');
                    $pdf->Cell(17, 4, moneda($articulos->precio_categoria) ,0, 0, 'R');
                    $pdf->Cell(16, 4, moneda($articulos->precio_importe_categoria), 0, 0, 'R');
                    $pdf->Cell(20, 4, moneda($articulos->precio_categoria + $articulos->precio_importe_categoria), 0, 0, 'R');
                    $pagos_realizados += $pago->pago_parcial;
                    $off += 6;
                    $pdf->Ln(3);
                }
                $pdf->Ln(1);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);

                // ---------------------------------------------- Reporte por usuario --------------------------------------------------
                $pdf->Cell(72, 4, 'Reporte por Usuario', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 8.8);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->Cell(20, 4, 'EMPLEADO',0,0,'L',true);
                $pdf->Cell(17, 4,'ENTRADA',0,0,'R',true);
                $pdf->Cell(16, 4,'SALIDA.',0,0,'R',true);
                $pdf->Cell(20, 4,'TOTAL',0,0,'R',true);
                $pdf->Ln(5);

                $pdf->SetFont('Arial', '', 9);
                $pagos_realizados = 0;
                $off = $pos + 6;

                foreach($cierreturno as $turno){
                    $total_venta = 0;
                    if($turno->rct_status){
                        //obtener informacion de las ventas por cada usuario
                        $detalle = $venta->getVentasByRangeId($turno->rct_venta_desde,$turno->rct_venta_hasta);
                        foreach($detalle as $detalle){
                            $total_venta += $detalle->total;
                        }

                        $pdf->Cell(20, 4, substr($turno->nombre_empleado, 0, 9), 0, 0, 'L');
                        $fecha_inicio = date_create($turno->rct_fecha_inicio);
                        $pdf->Cell(17, 4, date_format($fecha_inicio,'d/m/Y') ,0, 0, 'R');
                        $pdf->Cell(16, 4, '', 0, 0, 'R');
                        $pdf->Cell(20, 4, moneda($total_venta), 0, 0, 'R');
                        $off += 6;
                        $pdf->Ln(3);
                    }
                }

                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);
                // ---------------------------------------------- Reporte de cartera --------------------------------------------------
                $pdf->Cell(72, 4, 'Reporte Cuentas Por Cobrar', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 8.8);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->Cell(37, 4,'FACTURA',0,0,'L',true);
                $pdf->Cell(16, 4,'METODO.',0,0,'R',true);
                $pdf->Cell(20, 4,'TOTAL',0,0,'R',true);
                $pdf->Ln(5);
                $total_cartera_cliente = 0;
                foreach ($pagocarteras as $pagocartera) {
                    $pdf->Cell(37, 4, utf8_decode($pagocartera->serie_comprobante.$pagocartera->num_comprobante), 0, 0, 'L');
                    $pdf->Cell(16, 4, utf8_decode($pagocartera->mp_nombre) ,0, 0, 'L');
                    $pdf->Cell(20, 4, moneda($pagocartera->pago_parcial), 0, 0, 'R');
                    $pdf->Ln(3);
                    $total_cartera_cliente += $pagocartera->pago_parcial;
                }
                $pdf->Ln(4);

                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);
                // ---------------------------------------------- Reporte de cartera --------------------------------------------------
                $pdf->Cell(72, 4, 'Reporte Cuentas Por Pagar', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 8.8);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->Cell(37, 4,'FACTURA',0,0,'L',true);
                $pdf->Cell(16, 4,'METODO.',0,0,'R',true);
                $pdf->Cell(20, 4,'TOTAL',0,0,'R',true);
                $pdf->Ln(5);
                $total_cartera_proveedor = 0;
                foreach ($pagoproveedores as $pagoproveedore) {
                    $pdf->Cell(37, 4, utf8_decode($pagoproveedore->serie_comprobante.$pagoproveedore->num_comprobante), 0, 0, 'L');
                    $pdf->Cell(16, 4, utf8_decode($pagoproveedore->mp_nombre) ,0, 0, 'L');
                    $pdf->Cell(20, 4, moneda($pagoproveedore->pago_parcial), 0, 0, 'R');
                    $pdf->Ln(3);
                    $total_cartera_proveedor += $pagoproveedore->pago_parcial;
                }
                $pdf->Ln(4);

                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);

                // ---------------------------------------------- Metodos de Pago --------------------------------------------------
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(36,4, "TOTAL VENTAS ",0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36,4, moneda($total), 0, 0, "R");
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(5);
                $total_metodo_pago =0;
                foreach ($metodospago as $detallemetodopago) {
                    $pdf->Cell(36,4, utf8_decode($detallemetodopago->mp_nombre),0,0,'L');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(36,4, moneda($detallemetodopago->total_metodo_pago), 0, 0, "R");
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Ln(4);
                    $total_metodo_pago+=$detallemetodopago->total_metodo_pago;
                }
                $pdf->Cell(36,4, 'Total metodos de pago',0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36,4, moneda($total_metodo_pago), 0, 0, "R");
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(4);
                $pdf->Cell(36,4, 'Total cuentas por cobrar',0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36,4, moneda($total_cartera_cliente), 0, 0, "R");
                $pdf->Ln(4);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(36,4, 'Total cuentas por pagar',0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36,4, moneda($total_cartera_proveedor), 0, 0, "R");
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(2);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(36, 4, "TOTAL ",0,0,'L');
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(36, 4,  moneda(($total+$total_cartera_cliente)-($total_cartera_proveedor)), 0, 0, "R");
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(9);

                $pdf->SetFont('Arial', 'B', 6);
                $pdf->MUltiCell(72, 2, "Software de facturaciom y control Ecounts www.psi-web.co \n info@psi-web.co",0,'C');

                //-------------------------------------------------------- Mostrar PDF -------------------------------------------------
                // $pdf->AutoPrint();
                $pdf->output("Cierre de caja ".$fecha_reporte.".pdf", "I");
            }
        }else{
            echo "No se encuentra este reporte";
        }
    }

    public function impuestos()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3) {
            if (isset($_GET["data"]) && isset($_GET["s"])) {

                ##### CONFIGURACION ####
                $start_date = $_GET["data"];
                $end_date = $_GET["s"];
                $funcion = "reporte_impuestos";
                $url = LOCATION_CLIENT;
                $redirect = "impuestos/reporte";
                $file_height = "92.4%";
                $state = false;
                ##### CONFIGURACION ####

                //Models
                $venta = new Ventas($this->adapter);
                $ventacontable = new VentaContable($this->adapter);
                $detalleventa = new DetalleVenta($this->adapter);
                $detalleventacontable = new DetalleVentaContable($this->adapter);

                //Functions
                $ventas = $venta->reporte_detallado($start_date, $end_date);
                foreach ($ventas as $ventas) {}
                if ($ventas) {$state = true;}

                if ($state) {
                    $this->frameview("file/pdf/impuestos", array(
                        "start_date" => $start_date,
                        "end_date" => $end_date,
                        "funcion" => $funcion,
                        "url" => $url,
                        "redirect" => $redirect,
                        "file_height" => $file_height,
                    ));
                } else {
                    echo "No se encontraron datos para este reporte";
                }

            }
        }
    }

    public function reporte_impuestos()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3) {
            if (isset($_GET["data"]) && isset($_GET["s"])) {
                #### CONFIGURACION ####
                $start_date = $_GET["data"];
                $end_date = $_GET["s"];
                #### CONFIGURACION ####
                //llamando la libreria pdf
                //require_once 'lib/printpdf/fpdf.php';
                //Models
                $venta = new Ventas($this->adapter);
                $compra = new Compras($this->adapter);

                $ventacontable = new VentaContable($this->adapter);
                $detalleventa = new DetalleVenta($this->adapter);
                $detalleventacontable = new DetalleVentaContable($this->adapter);

                //Functions
                $state = false;
                $ventas = $venta->reporte_general($start_date, $end_date);
                $compras = $compra->reporte_general($start_date, $end_date);
                foreach ($ventas as $dataventas) {}
                if ($dataventas) {$state = true;}

                /////recuperando la sucursal por factura de venta
                $sucursales = new Sucursal($this->adapter);
                $global = new sGlobal($this->adapter);
                $sucursal = $sucursales->getSucursalById($_SESSION["idsucursal"]);
                $empresa = $global->getGlobal();
                foreach ($sucursal as $sucursal) {}
                foreach ($empresa as $empresa) {}

                if ($state) {
                    //Modelado de factura pdf
                    $resp = [];
                    $resp2 = [];
                    $resp3 = [];
                    $subtotal = 0;
                    $subtotal_importe = 0;
                    $total = 0;
                    $subtotal_compra = 0;
                    $subtotal_importe_compra = 0;
                    $total_compra = 0;
                    //load data
                    foreach ($ventas as $data) {
                        if ($data->estado == "A") {
                            $resp2[] = array(
                                $data->serie_comprobante,
                                zero_fill($data->num_comprobante, 8),
                                $data->fecha,
                                $data->sub_total,
                                "19",
                                $data->subtotal_importe,
                                $data->total,
                            );
                        }
                        $subtotal += $data->sub_total;
                        $subtotal_importe += $data->subtotal_importe;
                        $total += $data->total;
                    }
                    $resp2[] = array(
                        "",
                        "TOTAL IVA EN VENTAS",
                        "",
                        $subtotal,
                        "",
                        $subtotal_importe,
                        $total,
                    );

                    foreach ($compras as $data_compra) {
                        if ($data_compra->estado == "A") {
                            $resp3[] = array(
                                $data_compra->serie_comprobante,
                                zero_fill($data_compra->num_comprobante, 8),
                                $data_compra->fecha,
                                $data_compra->sub_total,
                                "19",
                                $data_compra->subtotal_importe,
                                $data_compra->total,
                            );
                        }
                        $subtotal_compra += $data_compra->sub_total;
                        $subtotal_importe_compra += $data_compra->subtotal_importe;
                        $total_compra += $data_compra->total;
                    }

                    $resp3[] = array(
                        "",
                        "TOTAL IVA EN COMPRAS",
                        "",
                        $subtotal_compra,
                        "",
                        $subtotal_importe_compra,
                        $total_compra,
                    );

                    $pdf = new FPDF_POS();
                    $pdf->SetTitle("Reporte de impuestos " . $start_date . " hasta " . $end_date);
                    $pdf->AddPage('L', 'A4');

                    //>>>>>>>>>header
                    $x = 10;
                    $y = 0;
                    $pdf->Image(LOCATION_CLIENT . $sucursal->logo_img, 30, 12, 36);
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(0, 6, $sucursal->razon_social, 0, 1, 'C');
                    $pdf->SetFont('Arial', '', 8.5);
                    $pdf->Cell(0, 0, $sucursal->prefijo_documento . " " . $sucursal->num_documento, 0, 1, 'C');
                    $pdf->Cell(0, 6, $sucursal->direccion, 0, 1, 'C');
                    $pdf->Cell(0, 0, "Tel: " . $sucursal->telefono, 0, 1, 'C');
                    $pdf->Cell(0, 5, $empresa->pais . " - " . $empresa->ciudad, 0, 1, 'C');
                    $pdf->Cell(0, 1, $sucursal->email, 0, 1, 'C');

                    $pdf->SetFont('Arial', 'B', 10.5);
                    // $pdf->Cell(0,10,"IVA GENERADO EN VENTAS",0,1,'C');
                    // $pdf->Cell(0,6,"IVA DESCONTABLE EN COMPRAS",0,1,'C');

                    $pdf->SetFont('Arial', '', 8.5);
                    $infofecha = array("Fecha desde", "Fecha hasta");
                    $date = array($start_date, $end_date);

                    //<<<<<<<<<header
                    //>>>>>>>body
                    $pdf->SetY(20);
                    $pdf->SetX(210);
                    $pdf->DateTable($infofecha, $resp);
                    $pdf->SetY(27);
                    $pdf->SetX(210);
                    $pdf->DateTable($date, $resp);

                    $pdf->SetY(40);
                    $pdf->SetX(10);
                    $tablehead = array("TIPO DOC", "CONSECUTIVO", "FECHA PROCESO", "VALOR SUBTOTAL", "% IVA", "VALOR IVA", "NETO");
                    $pdf->FancyTableImpuesto($tablehead, $resp2, "IVA GENERADO EN VENTAS");

                    $pdf->AddPage('L', 'A4');

                    //>>>>>>>>>header
                    $x = 10;
                    $y = 0;
                    $pdf->Image(LOCATION_CLIENT . $sucursal->logo_img, 30, 12, 36);
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(0, 6, $sucursal->razon_social, 0, 1, 'C');
                    $pdf->SetFont('Arial', '', 8.5);
                    $pdf->Cell(0, 0, $sucursal->prefijo_documento . " " . $sucursal->num_documento, 0, 1, 'C');
                    $pdf->Cell(0, 6, $sucursal->direccion, 0, 1, 'C');
                    $pdf->Cell(0, 0, "Tel: " . $sucursal->telefono, 0, 1, 'C');
                    $pdf->Cell(0, 5, $empresa->pais . " - " . $empresa->ciudad, 0, 1, 'C');
                    $pdf->Cell(0, 1, $sucursal->email, 0, 1, 'C');

                    $pdf->FancyTableImpuesto($tablehead, $resp3, "IVA DESCONTABLE EN COMPRAS");

                    //<<<<<<<body

                    $pdf->AutoPrint();
                    $pdf->Output("Reporte de impuestos " . $start_date . " hasta " . $end_date . ".pdf", "I");

                }

            } else {
                echo "Forbidden gateway";
            }
        } else {
            echo "Forbidden gateway";
        }
    }

    public function factura_venta()
    {

        if (isset($_GET["data"]) && !empty($_GET["data"])) {
            //require_once 'lib/printpdf/fpdf.php';
            $cifrasEnLetras = new CifrasEnLetras();
            $pieFactura = new PieFactura($this->adapter);
            $detalleretencion = new DetalleRetencion($this->adapter);
            $detalleimpuesto = new DetalleImpuesto($this->adapter);
            $dataimpuestos = new Impuestos($this->adapter);
            $dataretencion = new Retenciones($this->adapter);
            $idventa = $_GET["data"];
            //recuperando la venta por id
            $ventas = new Ventas($this->adapter);
            $venta = $ventas->getVentaById($idventa);
            if ($venta) {
                //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach ($venta as $data) {}
                $resolucion = $pieFactura->getPieFacturaByComprobanteId($data->iddetalle_documento_sucursal);
                foreach ($resolucion as $res) {}
                $dataarticulos = new DetalleVenta($this->adapter);
                $articulos = $dataarticulos->getArticulosByVenta($idventa);
                //recuperando la sucursal por factura de venta
                $sucursales = new Sucursal($this->adapter);
                $global = new sGlobal($this->adapter);
                $sucursal = $sucursales->getSucursalById($data->idsucursal);
                $empresa = $global->getGlobal();
                foreach ($sucursal as $sucursal) {}
                foreach ($empresa as $empresa) {}
                $resp = [];
                $resp2 = [];
                $pdf = new FPDF('P', 'mm', array(100, 150), $this->adapter);
                $pdf->SetTitle("Factura de venta " . $data->serie_comprobante . zero_fill($data->num_comprobante, 8) . " " . $data->fecha . " Cliente " . $data->nombre_cliente);

                $x = 10;
                $y = 0;

                $array = array(
                    "tercero" => $data->nombre_cliente,
                    "documento" => $data->num_documento,
                    "telefono" => $data->telefono_cliente,
                    "direccion" => $data->direccion_calle,
                    "ciudad" => $data->direccion_provincia,
                    "start_date" => $data->fecha,
                    "end_date" => $data->fecha_final,
                    "tipo_doc" => "FACT. VENTA No.",
                    "comprobante" => $data->serie_comprobante . zero_fill($data->num_comprobante, 8),
                );

                $pdf->setData($array);
                $pdf->AddPage('P', 'A4', '');

                //BODY
                //TABLE HEAD
                foreach ($articulos as $articulo) {
                    $resp2[] = array(
                        $articulo->idarticulo,
                        $articulo->descripcion,
                        $articulo->cantidad,
                        $articulo->precio_unitario,
                        $articulo->iva_compra,
                        $articulo->precio_total_lote,
                    );
                }
                $tablehead = array("Codigo", "Producto", "Cantidad", "Precio U.", "IVA", "Total");
                $pdf->SetY(110);
                $pdf->SetX(10);
                $pdf->FancyTable($tablehead, $resp2);
                //TABLE BODY
                $retenciones = $detalleretencion->getRetencionBy($idventa, 0, 'V');

                $impuestos = $detalleimpuesto->getImpuestosBy($idventa, 0, 'V');

                //$totalcart = new DetalleIngreso($this->adapter);
                $subtotal = $dataarticulos->getSubTotal($data->idventa);
                $totalimpuestos = $dataarticulos->getImpuestos($data->idventa);

                //obter subtotal
                foreach ($subtotal as $subtotal) {}
                //valores a imprimir
                $subtotalimpuesto = 0;
                $listImpuesto = [];
                $listRetencion = [];
                $total_bruto = $subtotal->cdi_debito;
                $total_neto = $subtotal->cdi_debito;
                //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
                foreach ($totalimpuestos as $imp) {
                    $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
                    foreach ($impuestos as $data2) {}
                    if ($impuestos) {

                        $total_neto -= $subtotalimpuesto;
                        $total_bruto -= $subtotalimpuesto;

                    } else {

                    }

                    foreach ($impuestos as $impuesto) {
                        if ($imp->cdi_importe == $impuesto->im_porcentaje) {
                            //calculado
                            $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
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
                        //calculado $subtotal->cdi_debito*($retencion->re_porcentaje/100)
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
                                    $iva = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));

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
                //variables
                $totalenletras = $cifrasEnLetras->convertirNumeroEnLetras($total_neto);
                $text_resolucion = explode('|', $res->pf_text);
                $prices = [];
                $ry = 154;
                $i = 0;
                $pdf->setValorEnLetras("Valor en letras: " . $totalenletras . " Pesos colombianos");
                foreach ($text_resolucion as $content) {
                    $i++;
                }
                $pdf->setResolucion($res->pf_text);

                $prices[] = array("SUBTOTAL:" => "$" . number_format($total_bruto, 2, '.', ','));
                foreach ($listImpuesto as $listImpuesto) {
                    $prices[] = array($listImpuesto[0] . ":" => "$" . number_format($listImpuesto[1], 2, '.', ','));
                }
                foreach ($listRetencion as $listRetencion) {
                    $prices[] = array($listRetencion[0] => "$" . number_format($listRetencion[1], 2, '.', ','));
                }
                $prices[] = array("TOTAL:" => "$" . number_format($total_neto, 2, '.', ','));
                $pdf->setPrices($prices);
                $pdf->AutoPrint();

                $pdf->Output($data->serie_comprobante . zero_fill($data->num_comprobante, 8) . " " . $data->fecha . " Cliente " . $data->nombre_cliente . ".pdf", "I");

            } else {
                echo "Forbidden Gateway";
            }
        }
    }

    public function ingreso()
    {
        if (isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {

                $idcompra = $_GET["data"];
                //configuracion para el modo de visualizacion
                $view = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : "";
                $file_height = (isset($view)) ? "100%" : "92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"])) ? $_GET["t"] : "";

                //recuperando la venta por id
                $compras = new Compras($this->adapter);
                $compra = $compras->getCompraById($idcompra);
                if ($compra) {
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                    foreach ($compra as $data) {}
                    //recuperando la sucursal por factura de venta
                    $sucursales = new Sucursal($this->adapter);
                    $sucursal = $sucursales->getSucursalById($data->idsucursal);

                    //traer la vista del tipo de impresion que se aplica a este comprobante
                    $funcion = $data->pri_conf . "_compra";
                    //configuracion solo para desarrollo
                    //$location = "http://127.0.0.1/app";
                    $location = LOCATION_CLIENT;
                    $redirect ="compras/nuevo_ingreso";

                    $this->frameview("file/pdf/" . $data->pri_nombre, array(
                        "file_height" => $file_height,
                        "conf_print" => $conf_print,
                        "venta" => $compra,
                        "sucursal" => $sucursal,
                        "funcion" => $funcion,
                        "id" => $idcompra,
                        "url" => $location,
                        "redirect"=>$redirect
                    ));
                } else {
                    echo "Factura no disponible";
                }
            } else {

            }
        }
    }

    public function factura_compra()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {
                //require_once 'lib/printpdf/fpdf.php';
                $idcompra = $_GET["data"];
                ######## clases
                //recuperando la venta por id
                $cifrasEnLetras = new CifrasEnLetras();
                $compras = new Compras($this->adapter);
                $dataretenciones = new Retenciones($this->adapter);
                $dataimpuestos = new Impuestos($this->adapter);
                $sucursales = new Sucursal($this->adapter);
                $global = new sGlobal($this->adapter);
                $dataarticulos = new DetalleIngreso($this->adapter);
                $detalleimpuesto = new DetalleImpuesto($this->adapter);
                $detalleretencion = new DetalleRetencion($this->adapter);
                $pdf = new FPDF('P', 'mm', array(216,279), $this->adapter);
                $pieFactura = new PieFactura($this->adapter);

                $compra = $compras->getCompraById($idcompra);
                if ($compra) {
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                    foreach ($compra as $data) {}

                    $articulos = $dataarticulos->getArticulosByCompra($idcompra);
                    //ecuperando la sucursal por factura de venta
                    $resolucion = $pieFactura->getPieFacturaByComprobanteId($data->iddetalle_documento_sucursal);
                    foreach ($resolucion as $res) {}

                    $sucursal = $sucursales->getSucursalById($data->idsucursal);

                    $empresa = $global->getGlobal();
                    foreach ($sucursal as $sucursal) {}
                    foreach ($empresa as $empresa) {}
                    $resp = [];
                    $resp2 = [];
                    foreach ($articulos as $articulo) {
                        $resp2[] = array(
                            $articulo->idarticulo,
                            $articulo->nombre_articulo,
                            $articulo->stock_ingreso,
                            $articulo->precio_compra,
                            $articulo->iva_compra,
                            $articulo->precio_total_lote,
                        );
                    }

                    $pdf->SetTitle("Factura de compra " . $data->serie_comprobante . zero_fill($data->num_comprobante, 8) . " " . $data->fecha . " Proveedor " . $data->nombre_proveedor);
                    $pdf->SetFont('Arial','',9);
                    //header
                    $x = 10;
                    $y = 0;

                    $array = array(
                        "tercero" => $data->nombre_proveedor,
                        "documento" => $data->num_documento,
                        "telefono" => $data->telefono_proveedor,
                        "direccion" => $data->direccion_calle,
                        "ciudad" => $data->direccion_provincia,
                        "start_date" => $data->fecha,
                        "end_date" => $data->fecha_final,
                        "tipo_doc" => "REG. COMPRA No.",
                        "comprobante" => $data->serie_comprobante . zero_fill($data->num_comprobante, 8),
                    );

                    $pdf->setData($array);
                    $pdf->AddPage('P', 'A4', '');

                    $tablehead = array("Codigo", "Producto", "Cantidad", "Precio U.", "IVA", "Total");
                    $pdf->SetY(80);
                    $pdf->SetX(10);
                    $pdf->FancyTable($tablehead, $resp2);

                    $retenciones = $detalleretencion->getRetencionBy($idcompra, 0);
                    $impuestos = $detalleimpuesto->getImpuestosBy($idcompra, 0);

                    //$totalcart = new DetalleIngreso($this->adapter);
                    $subtotal = $dataarticulos->getSubTotal($data->idingreso);
                    $totalimpuestos = $dataarticulos->getImpuestos($data->idingreso);

                    //obter subtotal
                    foreach ($subtotal as $subtotal) {}
                    //valores a imprimir
                    $subtotalimpuesto = 0;
                    $listImpuesto = [];
                    $listRetencion = [];
                    $total_bruto = $subtotal->cdi_debito;
                    $total_neto = $subtotal->cdi_debito;
                    //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
                    foreach ($totalimpuestos as $imp) {
                        $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
                        foreach ($impuestos as $data2) {}
                        if ($impuestos) {
                            if ($data2->im_porcentaje == $imp->cdi_importe) {
                                $total_neto -= $subtotalimpuesto;
                                $total_bruto -= $subtotalimpuesto;
                            } else {

                            }
                        } else {

                        }

                        foreach ($impuestos as $impuesto) {
                            if ($imp->cdi_importe == $impuesto->im_porcentaje) {
                                //calculado
                                $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
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
                            //calculado $subtotal->cdi_debito*($retencion->re_porcentaje/100)
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
                                        $iva = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));

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
                    $prices = [];
                    $y = 160;
                    $x = 205;
                    $totalenletras = $cifrasEnLetras->convertirNumeroEnLetras($total_neto);

                    $prices[] = array("SUBTOTAL:" =>  moneda($total_bruto));
                    foreach ($listImpuesto as $listImpuesto) {
                        $prices[] = array($listImpuesto[0] . ":" =>  moneda($listImpuesto[1]));
                    }
                    foreach ($listRetencion as $listRetencion) {
                        $prices[] = array($listRetencion[0] =>  moneda($listRetencion[1]));
                    }
                    $prices[] = array("TOTAL:" =>  moneda($total_neto));

                    $pdf->setValorEnLetras("Valor en letras: " . $totalenletras . " Pesos colombianos");
                    $pdf->setResolucion($res->pf_text);
                    $pdf->setPrices($prices);
                    $pdf->AutoPrint();
                    $pdf->Output($data->serie_comprobante . zero_fill($data->num_comprobante, 8) . " " . $data->fecha . " Cliente " . $data->nombre_proveedor . ".pdf", "I");

                } else {
                    echo "Forbidden Gateway";
                }
            }
        } else {
            echo "Forbidden Gateway";
        }

    }

    public function pos_venta()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {

                //require_once 'lib/printpdf/fpdf.php';
                $idventa = $_GET["data"];
                ############## modelos
                
                $sucursales = new Sucursal($this->adapter);
                $ventas = new Ventas($this->adapter);
                $detalleventa = new DetalleVenta($this->adapter);
                $dataretenciones = new Retenciones($this->adapter);
                $dataimpuestos = new Impuestos($this->adapter);
                $pieFactura = new PieFactura($this->adapter);
                $cifrasEnLetras = new CifrasEnLetras();
                $detallemetodopago = new DetalleMetodoPago($this->adapter);

                ############## funciones
                $venta = $ventas->getVentaById($idventa);
                foreach ($venta as $data) {}
                $resolucion = $pieFactura->getPieFacturaByComprobanteId($data->iddetalle_documento_sucursal);
                foreach ($resolucion as $resolucion) {}
                $sucursal = $sucursales->getSucursalById($data->idsucursal);
                $detalle = $detalleventa->getArticulosByVenta($idventa);
                $listaMetodo = $detallemetodopago->getDetalleMetodoPagoByComprobante($idventa);
                $retenciones = $dataretenciones->getRetencionesByComprobanteId($data->iddetalle_documento_sucursal);
                $impuestos = $dataimpuestos->getImpuestosByComprobanteId($data->iddetalle_documento_sucursal);
                //detalle venta
                $subtotal = $detalleventa->getSubTotal($data->idventa);
                $totalimpuestos = $detalleventa->getImpuestos($data->idventa);

                ######## obtener solo una vista de la funcion llamada
                foreach ($sucursal as $sucursal) {}
                foreach ($venta as $venta) {}

                $product_height =0;
                foreach ($detalle as $contador) {
                $product_height +=6;
                }

                $pdf = new FPDF_POS($orientation = 'P', $unit = 'mm', array(80, 189+$product_height));
                $pdf->AddPage();
                $pdf->SetMargins(4, 4, 4); 
                $x = 10;
                $y = 0;
                $pdf->setY(2);
                $pdf->setX(2);
                $pdf->Image(LOCATION_CLIENT . $sucursal->logo_img,26, 6, 29, 29);
                $pdf->Ln(28);
                
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->MultiCell(72, 4, utf8_decode($sucursal->razon_social), 0,'C');
                $pdf->Ln(0.5);

                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(72, 4, utf8_decode($sucursal->prefijo_documento . " " . $sucursal->num_documento), 0, 'C');
                $pdf->Ln(0.5);
				
				$pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(72, 4, utf8_decode($sucursal->telefono), 0, 'C');
                $pdf->Ln(0.5);
				
				
                $pdf->Cell(72, 4, utf8_decode($sucursal->direccion . " - " . $sucursal->ciudad ), 0, 0, 'C');
                $pdf->Ln(1);
                $pos=4;
                $pdf->SetFont('Arial', 'B',9);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);
                $pdf->Cell(72, 4, "" . $venta->serie_comprobante . zero_fill($venta->num_comprobante, 8), 0, 0, 'C');
                $pdf->Ln(1.5);
                $pdf->Cell(0,$pos,"________________________________________",0,0,'C');
                $pdf->Ln(5);

                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, $pos, "Fecha:  " . utf8_decode($venta->fecha) . "        Fecha final: " . utf8_decode($venta->fecha_final), 0, 0, 'L');

                $pdf->Ln(3);
                $pdf->Cell(72, 5, "Vendedor:  " . utf8_decode($venta->idusuario), 0, 0, 'L');

                $pdf->Ln(3);
                $pdf->Cell(72, 5, "Tipo de Venta:  " . utf8_decode($venta->tipo_pago), 0, 0, 'L');

                $pdf->Ln(3);
                $pdf->Cell(72,5, "Cliente:  " . utf8_decode($venta->nombre_cliente), 0, 0, 'L');

                $pdf->Ln(3);
                $pdf->Cell(72,5, "Documento:  " . utf8_decode($venta->num_documento), 0, 0, 'L');

                $pdf->Ln(3);
                $pdf->Cell(72,5, "Direccion:  " . utf8_decode($venta->direccion_calle), 0, 0, 'L');

                $pdf->Ln(3);
                $pdf->Cell(72,5, "Telefono:  " . utf8_decode($venta->telefono), 0, 0, 'L');
                $pdf->Ln(3);

                $pdf->SetFont('Arial', '', 9); //Letra Arial, negrita (Bold), tam. 20
                $pdf->Ln(5);
                $pdf->SetFillColor(204, 204, 204); // establece el color del fondo de la celda (en este caso es AZUL  
                $pdf->Cell(9,4,'ID',0,0,'L',true);
                $pdf->Cell(13,4,'Cant.',0,0,'L',true);
                $pdf->Cell(13,4,'V/Uni',0,0,'R',true);
                $pdf->Cell(16,4,'Imp',0,0,'R',true);
                $pdf->Cell(21,4,'Total',0,0,'R',true);
                $pdf->Ln(5);
                $total = 0;
                $off = $pos + 6;
                $i = 0;
                $pdf->SetFont('Arial', '', 8);
                foreach ($detalle as $detalle) {
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->MultiCell(72, 3, strtoupper(substr(utf8_decode($detalle->nombre_articulo), 0, 100)));
                    $pdf->Ln(1);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(9, 4, utf8_decode($detalle->idarticulo),0,0,'L');
                    $pdf->Cell(13, 4,numeric($detalle->cantidad,false) . utf8_decode($detalle->prefijo_medida),0,0,'L');
                    $pdf->Cell(13, 4,moneda($detalle->precio_venta), 0, 0, "R");
                    $pdf->Cell(16, 4,moneda($detalle->iva_compra), 0, 0, "R");
                    $pdf->Cell(21, 4,moneda($detalle->precio_total_lote), 0, 0, "R");
                    $total += $detalle->precio_total_lote;
                    $off += 6;
                    $pdf->Ln(5);
                    $i += $detalle->cantidad;
                   
                }
                $pos = $off + 6;
                $pdf->SetFont('Arial', '', 9);
                // $pdf->setX(2);
                // $pdf->Cell(5,$pos,"TOTAL: " );
                // $pdf->setX(38);
                // $pdf->Cell(5,$pos,"$ ".number_format($total,2,".",","),0,0,"R");
                //$pos+=6;
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(35, 4, utf8_decode("Nº de artículos:"),0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(35, 4, utf8_decode($i),0,0,'R');
                $pdf->Ln(1);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(6);
                //obter subtotal
                foreach ($subtotal as $subtotal) {}
                //valores a imprimir
                $subtotalimpuesto = 0;
                $listImpuesto = [];
                $listRetencion = [];
                $total_bruto = $subtotal->cdi_debito;
                $total_neto = 0;
                //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
                foreach ($totalimpuestos as $imp) {
                    //$subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                    //$subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito * (($imp->cdi_importe/100)+1));
                    $subtotalimpuesto += ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                    foreach($impuestos as $data){}
                    if($impuestos){
                        $total_neto = $subtotalimpuesto;
                        $total_bruto -= $subtotalimpuesto;
                    }else{
                    }
                    $listImpuesto = [];
                    foreach ($impuestos as $impuesto) {
                        
                        //re-buscar si hay un impuesto igual en la cola
                        foreach($totalimpuestos as $imp2){
                            
                        if($imp2->cdi_importe == $impuesto->im_porcentaje){
                            //calculado
                            $calc = $imp2->cdi_debito - ($imp2->cdi_debito / (($imp2->cdi_importe/100)+1));
                            //concatenacion del nombre
                            $im_nombre = " ".$impuesto->im_porcentaje."%";
                            //arreglo
                            $listImpuesto[] = array($im_nombre,$calc);
                            /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                            $total_neto += $calc;
                        }else{
                            //si el impuesto puede afectar al subtotal calcula sobre el subtotal, esto para algunosimpuestos obligatorios
                            //sirve para no afectar a algunos articulos como tal, sino solo sobre el subtotal antes de iva
                            if($impuesto->im_subtotal){
                                $sub = ($imp2->cdi_debito / (($imp2->cdi_importe/100)+1));
                                $calc = $sub *($impuesto->im_porcentaje/100);
                                $im_nombre = " ".$impuesto->im_porcentaje."%";
                                $listImpuesto[] = array($im_nombre,$calc);
                                $total_neto += $calc;
                            }
                        }
                    }
                    }
                }
                foreach ($retenciones as $retencion) {
                
                    if($retencion->re_im_id <= 0){
                        //concatenacion del nombre
                        $re_nombre = $retencion->re_nombre." ";
                        //calculado $subtotal->cdi_debito*($retencion->re_porcentaje/100)
                        $calc = $total_bruto* ($retencion->re_porcentaje/100);
                        //arreglo
                        $listRetencion[] = array($re_nombre,$calc);
                        /************************RESTANDO RETENCION DEL CALCULO*****************************/
                        $total_neto -= $calc;
                    }else{
                    foreach ($totalimpuestos as $imp) {
                    $impid = $dataimpuestos->getImpuestosById($retencion->re_im_id);
                    foreach ($impid as $impid) {
                        if($imp->cdi_importe == $impid->im_porcentaje){
                            $re_nombre = $retencion->re_nombre;
                            $iva =$imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));

                            $calc =$iva*($retencion->re_porcentaje/100);

                            $listRetencion[] = array($re_nombre,$calc);
                            /************************RESTANDO RETENCION DEL CALCULO*****************************/
                            $total_neto -= $calc;
                        }else{
                        }
                    }                    
                }
            }
                
            }

                $pdf->Cell(36, 4, "Subtotal ",0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36, 4, moneda($subtotalimpuesto),0,0,'R');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(3);

                foreach ($listImpuesto as $listImpuesto) {
                    if($listImpuesto[1]>0){
                    $pdf->Cell(36, 4,"IVA ".$listImpuesto[0],0,0,'L');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(36, 4,moneda($listImpuesto[1]),0,0,'R');
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Ln(3);
                    }
                }

                foreach ($listRetencion as $listRetencion) {

                    $pdf->Cell(36,4, "RETENCION ".$listRetencion[0],0,0,'L');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(36,4, moneda($listRetencion[1]), 0, 0, "R");
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Ln(3);
                }

                $pdf->Cell(36, 4, "Total ",0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36, 4, moneda($total_neto),0,0,'R');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(3);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);


                $monto = 0;
                foreach ($listaMetodo as $precalculo) {
                    $monto += $precalculo->dmpg_monto;
                }
                $message = "Faltante";
                $monto_estado = $monto-$total_neto;

                if($monto_estado < 0){
                    $color = 'warning';
                    $message ="Faltante";
                }else{
                    $color = 'success';
                    $message ="Cambio";
                }

                foreach ($listaMetodo as $metodoPago) {
                    $pdf->Cell(36,4, $metodoPago->mp_nombre,0,0,'L');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(36,4, moneda($metodoPago->dmpg_monto), 0, 0, 'R');
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Ln(4);
                }
                $pdf->Ln(3);
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(36, 4, $message,0,0,'L');
                $pdf->Cell(36, 4, moneda($monto_estado),0,0,'R');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(3);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                if($venta->observaciones != ''){
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->MultiCell(72, 3, utf8_decode("*".$venta->observaciones."*"), 0, 'C');
                }
                
                $pdf->Ln(6);
                $pdf->SetFont('Arial', '', 6);
                $pdf->MultiCell(72, 3, utf8_decode($resolucion->pf_text), 0, 'C');
                $pdf->Ln(6);
                $pdf->Cell(72, 4, utf8_decode($sucursal->razon_social), 0, 0, 'C');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', 'B', 6);
                $pdf->MultiCell(72, 3, utf8_decode("Software de facturaciom y control Ecounts www.psi-web.co \n info@psi-web.co"), 0, 'C');
                $pdf->Ln(6);

                $pdf->AutoPrint();
                $pdf->output();

            } else {

            }
        } else {
            echo "Forbidden gateway";
        }
    }

    public function cierre_caja()
    {
        if (isset($_GET["data"]) && !empty($_GET["data"])) {
            //require_once 'lib/printpdf/fpdf.php';

            $idreporte = $_GET["data"];
            //recuperando la venta por id
            $caja = new ReporteCaja($this->adapter);
            $reporte = $caja->getReporteById($idreporte);
            if ($reporte) {
                //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach ($reporte as $data) {}
                //ecuperando la sucursal por factura de venta
                $sucursales = new Sucursal($this->adapter);
                $venta = new Ventas($this->adapter);
                $cierreturno = new CierreTurno($this->adapter);
                $cartera = new Cartera($this->adapter);

                $sucursal = $sucursales->getSucursalById($data->rc_idsucursal);
                foreach ($sucursal as $sucursal) {}
                $ventas = $venta->reporte_detallado_categoria($data->rc_fecha, $data->rc_fecha);
                $articulos = $venta->reporte_detallado_articulo($data->rc_fecha, $data->rc_fecha);
                $venta->setIdsucursal($data->rc_idsucursal);
                $metodospago = $venta->reporte_detallado_metodo_pago($data->rc_fecha, $data->rc_fecha);
                $pagocarteras = $cartera->reporte_pago_cartera_cliente($data->rc_fecha, $data->rc_fecha);
                $pagoproveedores = $cartera->reporte_pago_cartera_proveedor($data->rc_fecha, $data->rc_fecha);
                //reporte de cierre de turnos
                $cierreturno->setRct_idsucursal($data->rc_idsucursal);
                $cierreturno->setRct_date($data->rc_fecha);
                $turnos = $cierreturno->getCierreTurnoAllByDay();
                $fecha_reporte = $data->rc_fecha;
                $height_pos =0;
                $height_pos += count($articulos)*6;
                $height_pos += count($ventas)*6;
                $height_pos += count($metodospago)*6;
                $height_pos += count($pagocarteras)*6;
                $height_pos += count($pagoproveedores)*6;

                // ------------------------------------- Instanciar la clase FPDF_POS y Cabecera -------------------------------------------------
                $pdf = new FPDF_POS('P', 'mm', array(80,(170+$height_pos)), $this->adapter);
                $pdf->SetMargins(4, 4, 4); 
                $pdf->setY(2);
                $pdf->setX(2);
                $array = array(
                    "tercero" => "",
                    "documento" => "",
                    "telefono" => "",
                    "direccion" => "",
                    "ciudad" => "",
                    "start_date" => "",
                    "end_date" => "",
                    "tipo_doc" => "",
                    "comprobante" => "",
                );
                $pdf->SetTitle("Pago cartera");
                $pdf->AddPage();
                $pdf->Image(LOCATION_CLIENT . $sucursal->logo_img,26, 5, 29, 29);
                $pos = 24;
                $pdf->Ln(26);
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->MultiCell(72, 4, $sucursal->razon_social, 0, 'C');
                $pdf->Ln(1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->MultiCell(72, 4, utf8_decode($sucursal->telefono . " - " . $sucursal->prefijo_documento . " " . $sucursal->num_documento), 0, 'C');
                $pdf->Ln(0.5);
                $pdf->Cell(72, 4, utf8_decode($sucursal->ciudad . " - " . $sucursal->pais), 0, 0, 'C');
                $pdf->Ln(4);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);

                // ---------------------------------------------- Categoria --------------------------------------------------------------
                // $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(72, 4, 'Categoria', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 9);
                $total = 0;
                foreach ($ventas as $ventas) {
                    $pdf->Cell(72,4, "Categoria: ".$ventas->nombre_categoria, 0, 0, 'L');
                    $pdf->Ln(4);
                    $pdf->Cell(72,4, "Subtotal:   ".moneda($ventas->precio_categoria), 0, 0, 'L');
                    $pdf->Ln(4);
                    $pdf->Cell(72,4, "IVA:           ".moneda(($ventas->precio_importe_categoria * $ventas->cantidad)), 0, 0, 'L');
                    $pdf->Ln(4);
                    $pdf->Cell(72,4, "Neto:         ".moneda($ventas->precio_categoria + $ventas->precio_importe_categoria), 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Cell(72,4,"________________________________________",0,0,'C');
                    $pdf->Ln(4);
                    $total += $ventas->precio_categoria + $ventas->precio_importe_categoria;
                }

                // ---------------------------------------------- Articulo --------------------------------------------------------------
                // $pdf->Cell(72, 4, 'Pago cartera ' . $_GET["data"],0,0,'C');
                $pdf->Cell(72, 4, 'Articulos', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->Cell(20, 4, 'ARTICULO',0,0,'L',true);
                $pdf->Cell(17, 4,'SUBTOTAL',0,0,'R',true);
                $pdf->Cell(16, 4,'IVA.',0,0,'R',true);
                $pdf->Cell(20, 4,'NETO',0,0,'R',true);
                $pdf->Ln(5);

                $pdf->SetFont('Arial', '', 9);
                $pagos_realizados = 0;
                $off = $pos + 6;
                foreach ($articulos as $articulos) {
                    $pdf->Cell(20, 4, substr($articulos->nombre_articulo, 0, 9), 0, 0, 'L');
                    $pdf->Cell(17, 4, moneda($articulos->precio_categoria) ,0, 0, 'R');
                    $pdf->Cell(16, 4, moneda($articulos->precio_importe_categoria), 0, 0, 'R');
                    $pdf->Cell(20, 4, moneda($articulos->precio_categoria + $articulos->precio_importe_categoria), 0, 0, 'R');
                    $pagos_realizados += $pago->pago_parcial;
                    $off += 6;
                    $pdf->Ln(3);
                }
                $pdf->Ln(1);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);

                // ---------------------------------------------- Reporte por usuario --------------------------------------------------
                $pdf->Cell(72, 4, 'Reporte por Usuario', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 8.8);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->Cell(20, 4, 'EMPLEADO',0,0,'L',true);
                $pdf->Cell(17, 4,'ENTRADA',0,0,'R',true);
                $pdf->Cell(16, 4,'SALIDA.',0,0,'R',true);
                $pdf->Cell(20, 4,'TOTAL',0,0,'R',true);
                $pdf->Ln(5);

                $pdf->SetFont('Arial', '', 9);
                $pagos_realizados = 0;
                $off = $pos + 6;

                foreach($turnos as $turno){
                    $total_venta = 0;
                    if($turno->rct_status){
                        //obtener informacion de las ventas por cada usuario
                        $detalle = $venta->getVentasByRangeId($turno->rct_venta_desde,$turno->rct_venta_hasta);
                        foreach($detalle as $detalle){
                            $total_venta += $detalle->total;
                        }

                        $pdf->Cell(20, 4, substr($turno->nombre_empleado, 0, 9), 0, 0, 'L');
                        $fecha_inicio = date_create($turno->rct_fecha_inicio);
                        $pdf->Cell(17, 4, date_format($fecha_inicio,'d/m/Y') ,0, 0, 'R');
                        $pdf->Cell(16, 4, '', 0, 0, 'R');
                        $pdf->Cell(20, 4, moneda($total_venta), 0, 0, 'R');
                        $off += 6;
                        $pdf->Ln(3);
                    }
                }

                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);
                // ---------------------------------------------- Reporte de cartera --------------------------------------------------
                $pdf->Cell(72, 4, 'Reporte Pagos de cartera', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 8.8);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->Cell(20, 4,'CLIENTE',0,0,'L',true);
                $pdf->Cell(17, 4,'METODO',0,0,'L',true);
                $pdf->Cell(16, 4,'FACTURA.',0,0,'R',true);
                $pdf->Cell(20, 4,'TOTAL',0,0,'R',true);
                $pdf->Ln(5);
                $total_cartera_cliente =0;
                foreach ($pagocarteras as $pagocartera) {
                    $pdf->Cell(37, 4, utf8_decode($pagocartera->serie_comprobante.$pagocartera->num_comprobante), 0, 0, 'L');
                    $pdf->Cell(16, 4, utf8_decode($pagocartera->mp_nombre) ,0, 0, 'L');
                    $pdf->Cell(20, 4, moneda($pagocartera->pago_parcial), 0, 0, 'R');
                    $pdf->Ln(3);
                    $total_cartera_cliente += $pagocartera->pago_parcial;
                }

                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);
                // ---------------------------------------------- Reporte de cartera --------------------------------------------------
                $pdf->Cell(72, 4, 'Reporte Cuentas Por Pagar', 0, 0, 'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 8.8);
                $pdf->SetFillColor(204, 204, 204);
                $pdf->Cell(37, 4,'FACTURA',0,0,'L',true);
                $pdf->Cell(16, 4,'METODO.',0,0,'R',true);
                $pdf->Cell(20, 4,'TOTAL',0,0,'R',true);
                $pdf->Ln(5);
                $total_cartera_proveedor = 0;
                foreach ($pagoproveedores as $pagoproveedore) {
                    $pdf->Cell(37, 4, utf8_decode($pagoproveedore->serie_comprobante.$pagoproveedore->num_comprobante), 0, 0, 'L');
                    $pdf->Cell(16, 4, utf8_decode($pagoproveedore->mp_nombre) ,0, 0, 'L');
                    $pdf->Cell(20, 4, moneda($pagoproveedore->pago_parcial), 0, 0, 'R');
                    $pdf->Ln(3);
                    $total_cartera_proveedor += $pagoproveedore->pago_parcial;
                }

                $pdf->Ln(4);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(4);

                // ---------------------------------------------- Metodos de Pago --------------------------------------------------
               $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(36,4, "TOTAL VENTAS ",0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36,4, moneda($total), 0, 0, "R");
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(5);
                $total_metodo_pago =0;
                foreach ($metodospago as $detallemetodopago) {
                    $pdf->Cell(36,4, utf8_decode($detallemetodopago->mp_nombre),0,0,'L');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(36,4, moneda($detallemetodopago->total_metodo_pago), 0, 0, "R");
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Ln(4);
                    $total_metodo_pago+=$detallemetodopago->total_metodo_pago;
                }
                $pdf->Cell(36,4, 'Total metodos de pago',0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36,4, moneda($total_metodo_pago), 0, 0, "R");
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(4);
                $pdf->Cell(36,4, 'Total cuentas por cobrar',0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36,4, moneda($total_cartera_cliente), 0, 0, "R");
                $pdf->Ln(4);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(36,4, 'Total cuentas por pagar',0,0,'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(36,4, moneda($total_cartera_proveedor), 0, 0, "R");
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(2);
                $pdf->Cell(72,4,"________________________________________",0,0,'C');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(36, 4, "TOTAL ",0,0,'L');
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(36, 4,  moneda(($total+$total_cartera_cliente)-($total_cartera_proveedor)), 0, 0, "R");
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(9);
                $pdf->SetFont('Arial', 'B', 6);
                $pdf->MUltiCell(72, 2, "Software de facturaciom y control Ecounts www.psi-web.co \n info@psi-web.co",0,'C');

                //-------------------------------------------------------- Mostrar PDF -------------------------------------------------
                // $pdf->AutoPrint();
                $pdf->output("Cierre de caja ".$fecha_reporte.".pdf", "I");
            }

        }
    }

    public function cartera()
    {
        if (isset($_GET["data"]) && !empty($_GET["data"]) && isset($_GET["s"]) && !empty($_GET["s"])) {

            //Configuracion solo para desarrollo
            //$location = "http://127.0.0.1/app";
            $location = LOCATION_CLIENT;
            $funcion = "pago_cartera";
            $data = $_GET["data"];
            $s = $_GET["s"];
            $redirect = "cliente/cartera";

            $this->frameview("file/pdf/cartera", array(
                "url" => $location,
                "funcion" => $funcion,
                "data" => $data,
                "s" => $s,
            ));

        } else {

        }
    }

    public function pago_cartera()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3) {
            if (isset($_GET["data"]) && !empty($_GET["data"]) && isset($_GET["s"]) && !empty($_GET["s"])) {
                //require_once 'lib/printpdf/fpdf.php';
                $idcredito = $_GET["s"];
                $cartera = new Cartera($this->adapter);
                $sucursales = new Sucursal($this->adapter);
                $compras = new Compras($this->adapter);
                $ventas = new Ventas($this->adapter);
                $global = new sGlobal($this->adapter);
                $personas = new Persona($this->adapter);
                $comprobantecontable = new ComprobanteContable($this->adapter);
                $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
                $resp2 = [];
                $empresa = $global->getGlobal();
                foreach ($empresa as $empresa) {}

                if ($_GET["data"] == "cliente") {
                    $credito = $cartera->getCreditoClienteById($idcredito);
                    foreach ($credito as $credito) {}
                    $factura = $ventas->getVentaById($credito->idventa);
                    $tercero = $credito->nombre_cliente;
                    $telefono = $credito->telefono;
                    $fecha = $credito->fecha_pago;
                    $deuda_total = $credito->deuda_total;

                } else {
                    $credito = $cartera->getCreditoProveedorById($idcredito);
                    foreach ($credito as $credito) {}
                    $factura = $compras->getCompraById($credito->idingreso);
                    $tercero = $credito->nombre_proveedor;
                    $telefono = $credito->telefono;
                    $fecha = $credito->fecha_pago;
                    $deuda_total = $credito->deuda_total;
                }

                $sucursal = $sucursales->getSucursalById($_SESSION["idsucursal"]);

                foreach ($sucursal as $sucursal) {}
                foreach ($factura as $factura) {}

                if ($credito->contabilidad == 1) {

                    $detallecomprobante = $detallecomprobantecontable->getArticulosByComprobante($credito->cc_id_transa);
                    $persona = $personas->getPersonaById($credito->cc_idproveedor);
                    foreach ($persona as $persona) {}
                    foreach ($detallecomprobante as $detalle) {

                        $datadebito = ($detalle->dcc_d_c_item_det == "D" && $detalle->dcc_valor_item > 0) ? number_format($detalle->dcc_valor_item, 2, '.', ',') : "";
                        $datacredito = ($detalle->dcc_d_c_item_det == "C" && $detalle->dcc_valor_item > 0) ? number_format($detalle->dcc_valor_item, 2, '.', ',') : "";

                        $resp2[] = array(
                            $detalle->dcc_cta_item_det,
                            $detalle->dcc_det_item_det,
                            number_format($detalle->dcc_cant_item_det, 2),
                            $credito->cc_ccos_cpte,
                            $persona->num_documento,
                            $detalle->dcc_dato_fact_prove,
                            '',
                            $detalle->dcc_base_ret_item,
                            $datadebito,
                            $datacredito,
                        );

                    }
                    $pdf = new FPDF_POS('P', 'mm', array(80,150), $this->adapter);
                    $array = array(
                        "tercero" => "",
                        "documento" => "",
                        "telefono" => "",
                        "direccion" => "",
                        "ciudad" => "",
                        "start_date" => "",
                        "end_date" => "",
                        "tipo_doc" => "",
                        "comprobante" => "",
                    );
                    $pdf->SetTitle("Pago cartera");
                    $pdf->setData($array);
                    $pdf->AddPage('P', 'A4', '');

                    $x = 10;
                    $y = 0;
                    //imprimir cabecera
                    $pdf->Image(LOCATION_CLIENT . $sucursal->logo_img, 30, 12, 36);
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(0, 6, $sucursal->razon_social, 0, 1, 'C');
                    $pdf->SetFont('Arial', '', 8.5);
                    $pdf->Cell(0, 0, $sucursal->prefijo_documento . " " . $sucursal->num_documento, 0, 1, 'C');
                    $pdf->Cell(0, 6, $sucursal->direccion, 0, 1, 'C');
                    $pdf->Cell(0, 0, "Tel: " . $sucursal->telefono, 0, 1, 'C');
                    $pdf->Cell(0, 5, $empresa->pais . " - " . $empresa->ciudad, 0, 1, 'C');
                    $pdf->Cell(0, 1, $sucursal->email, 0, 1, 'C');
                    $pdf->SetY(12);
                    $pdf->SetX(140);
                    $pdf->Cell(55, 16, "REG. COMPRA No." . $credito->cc_num_cpte . zero_fill($credito->cc_cons_cpte, 8), 1, 0, 'C');
                    //variables para imprimir sub cabecera de datos de tercero
                    $datatercero = array('Proveedor:', $persona->nombre_persona);
                    $contacto = array('NIT:', $persona->num_documento, "Telefono:", $persona->telefono);
                    $ubicacion = array("Direccion:", $persona->direccion_calle, "Ciudad:", $persona->direccion_provincia);
                    $infofecha = array("Fecha de Factura", "Fecha de Vencimiento");
                    $date = array($credito->cc_fecha_cpte, $credito->cc_fecha_final_cpte);
                    $resp = [];
                    //sub cabecera de datos del tercero
                    $pdf->SetFont('Arial', '', 7);
                    $pdf->SetY(38);
                    $pdf->SetX(10);
                    $pdf->FancyTable($datatercero, $resp);
                    $pdf->SetY(45);
                    $pdf->SetX(10);
                    $pdf->FancyTable($contacto, $resp);
                    $pdf->SetY(52.5);
                    $pdf->SetX(10);
                    $pdf->FancyTable($ubicacion, $resp);
                    // $pdf->SetY(38);
                    // $pdf->SetX(10);
                    // $pdf->DateTable($infofecha,$resp);
                    // $pdf->SetY(38);
                    // $pdf->SetX(140);
                    // $pdf->DateTable($date,$resp);
                    //body

                    $tablehead = array("Cuenta", "Detalle", "Cant.", "C. Costos", "Tercero", "Doc/Detalle", "Fecha Venc.", "Base Retencion", "Debito", "Credito");
                    $pdf->SetY(65);
                    $pdf->SetX(10);
                    $pdf->FancyTableContabilidad($tablehead, $resp2);

                } else {
                    switch ($_GET["data"]) {
                        case 'cliente':
                            $detalle = $cartera->getPagoCarteraCliente($idcredito);

                            break;

                        case 'proveedor':
                            $detalle = $cartera->getPagoCarteraProveedor($idcredito);
                            break;

                        default:
                            $detalle = ["fecha_pago" => "", "pago_parcial" => "", "retencion" => "", "pago" => ""];
                            break;
                    }
                    $file_height = 0;
                    foreach ($detalle as $height) {
                        $file_height +=7;
                    }


                    //$pdf = new FPDF($orientation = 'P', $unit = 'mm', array(45, 350));
                    $pdf = new FPDF_POS('P', 'mm', array(80,125+$file_height), $this->adapter);
                    $pdf->SetMargins(4, 4, 4); 
                    $pdf->setY(2);
                    $pdf->setX(2);
                    $array = array(
                        "tercero" => "",
                        "documento" => "",
                        "telefono" => "",
                        "direccion" => "",
                        "ciudad" => "",
                        "start_date" => "",
                        "end_date" => "",
                        "tipo_doc" => "",
                        "comprobante" => "",
                    );
                    $pdf->SetTitle("Pago cartera");
                    $pdf->AddPage();
                    $pdf->Image(LOCATION_CLIENT . $sucursal->logo_img,26, 6, 29, 29);
                    $pos = 24;
                    $pdf->Ln(26);
                    $pdf->SetFont('Arial', 'B', 11);
                    $pdf->MultiCell(72, 4, $sucursal->razon_social, 0, 'C');
                    $pdf->Ln(1);
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->MultiCell(72, 4, utf8_decode($sucursal->telefono . " - " . $sucursal->prefijo_documento . " " . $sucursal->num_documento), 0, 'C');
                    $pdf->Ln(0.5);
                    $pdf->Cell(72, 4, utf8_decode($sucursal->ciudad . " - " . $sucursal->pais), 0, 0, 'C');
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Cell(72,4,"________________________________________",0,0,'C');
                    $pdf->Ln(5);

                    $pdf->Cell(72,4, "Comprobante: " . $credito->serie_comprobante . zero_fill($credito->num_comprobante, 8), 0, 0, 'L');
                    $pdf->Ln(3);
                    $pdf->Cell(72,4, "Factura No.: " . $credito->det_fact, 0, 0, 'L');
                    $pdf->Ln(3);
                    $pdf->Cell(72,4, "Tercero: " . $tercero, 0, 0, 'L');
                    $pdf->Ln(3);
                    $pdf->Cell(72,4, "Telefono: " . $telefono, 0, 0, 'L');
                    $pdf->Ln(3);
                    $pdf->Cell(72,4, "Fecha limite: " . date_format(date_create($fecha),'Y/m/d'), 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(72,4,"________________________________________",0,0,'C');
                    $pdf->Ln(4);
                    $pdf->Cell(72, 4, 'Pago cartera ' . $_GET["data"],0,0,'C');
                    $pdf->Ln(6);
                    $pdf->SetFont('Arial', '', 8.8);
                    $pdf->SetFillColor(204, 204, 204);
                    $pdf->Cell(13, 4, 'FECHA',0,0,'L',true);
                    $pdf->Cell(25, 4,'P. PARCIAL',0,0,'R',true);
                    $pdf->Cell(15, 4,'RET.',0,0,'R',true);
                    $pdf->Cell(20, 4,'DEVUELTO',0,0,'R',true);
                    $pdf->Ln(5);

                    $pdf->SetFont('Arial', '', 9);
                    $pagos_realizados = 0;
                    $off = $pos + 6;
                    foreach ($detalle as $pago) {
                        
                        $pdf->Cell(13, 4, date_format(date_create($pago->fecha_pago),'Y/m/d'),0,0,'L');
                        $pdf->Cell(25, 4, moneda($pago->pago_parcial),0,0,'R');
                        $pdf->Cell(15, 4, moneda($pago->retencion), 0, 0, 'R');
                        $pdf->Cell(20, 4, moneda($pago->monto - $pago->pago_parcial), 0, 0, 'R');
                        $pagos_realizados += $pago->pago_parcial;
                        $off += 6;
                        $pdf->Ln(3);
                    }
                    $pdf->Ln(1);
                    $pdf->Cell(72,4,"________________________________________",0,0,'C');
                    $pdf->Ln(5);
                    $pdf->Cell(36,4, "PAGOS REALIZADOS ",0,0,'L');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(36,4, moneda($pagos_realizados), 0, 0, "R");
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Ln(3);

                    $pdf->Cell(36, 4, "DEUDA TOTAL ",0,0,'L');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(36, 4, moneda($deuda_total), 0, 0, "R");
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Ln(5);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Cell(36, 4, "DIFERENCIA ",0,0,'L');
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(36, 4,  moneda($deuda_total - $pagos_realizados), 0, 0, "R");
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Ln(9);
                    $pdf->SetFont('Arial', 'B', 6);
                    $pdf->MUltiCell(72, 2, "Software de facturaciom y control Ecounts www.psi-web.co \n 321 9848679 - 301 4109204",0,'C');
                }

                $pdf->AutoPrint();
                $pdf->output();

            } else {
                echo "Factura no disponible";
            }
        } else {
            echo "Forbidden Gateway";
        }
    }

    ##############################

    public function comprobantes()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {
                //models
                $comprobantecontable = new ComprobanteContable($this->adapter);
                $sucursales = new Sucursal($this->adapter);
                //functions
                $comprobanteid = $_GET["data"];
                $view = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : "";
                $file_height = (isset($view)) ? "100%" : "92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"])) ? $_GET["t"] : $view;

                //recuperando el comprobante contable por id
                $comprobante = $comprobantecontable->getComprobanteById($comprobanteid);
                if ($comprobante) {
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                    foreach ($comprobante as $data) {}
                    //ecuperando la sucursal por factura de venta
                    $redirect = ($data->cc_tipo_comprobante == "V" )?"ventas/nuevo":"compras/nuevo";
                    $sucursal = $sucursales->getSucursalById($data->cc_ccos_cpte);

                    //traer la vista del tipo de impresion que se aplica a este comprobante
                    $funcion = $data->pri_conf . "_comprobante";
                    //configuracion solo para desarrollo
                    //$location = "http://127.0.0.1/";
                    $location = LOCATION_CLIENT;

                    $this->frameview("file/pdf/" . $data->pri_nombre, array(
                        "file_height" => $file_height,
                        "conf_print" => $conf_print,
                        "comprobante" => $comprobante,
                        "sucursal" => $sucursal,
                        "funcion" => $funcion,
                        "id" => $comprobanteid,
                        "url" => $location,
                        "redirect"=>$redirect
                    ));

                }
            }
        } else {
            echo "Forbidden Gateway";
        }
    }

    public function factura_comprobante()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1) {
            if (isset($_GET["data"]) && !empty($_GET["data"]) && $_GET["data"] > 0) {
                $conf_print = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : false;
                $comprobanteid = $_GET["data"];
                //require_once 'lib/printpdf/fpdf.php';
                //models
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
                $comprobante = $comprobantecontable->getComprobanteById($comprobanteid);
                foreach ($comprobante as $data) {}
                $articulos = $detallecomprobantecontable->getArticulosByComprobante($comprobanteid);

                $totales = $detallecomprobantecontable->getTotalByCompra($comprobanteid);
                $totalimpuestos = $detallecomprobantecontable->getImpuestos($comprobanteid);
                //
                $retenciones = $detalleretencion->getRetencionBy($comprobanteid, 1,$data->cc_tipo_comprobante);
                $impuestos = $detalleimpuesto->getImpuestosBy($comprobanteid, 1, $data->cc_tipo_comprobante);

                //ecuperando la sucursal por factura de venta
                //recuperar sucursal
                $sucursal = $sucursales->getSucursalById($data->cc_ccos_cpte);
                //recuperar empresa
                $empresa = $global->getGlobal();
                //setear sucursal en variable sucursal
                foreach ($sucursal as $sucursal) {}
                //setear empresa en variable empresa
                foreach ($empresa as $empresa) {}
                //impuestos y retenciones de esta venta

                $resp = [];
                $resp2 = [];
                $pdf = new FPDF('P', 'mm', array(100, 150), $this->adapter);
                $pdf->SetTitle("Registro de comprobante ");
                $x = 10;
                $y = 0;

                $array = array(
                    "tercero" => $data->nombre_tercero,
                    "documento" => $data->documento_proveedor,
                    "telefono" => $data->telefono_proveedor,
                    "direccion" => $data->direccion_calle,
                    "ciudad" => $data->direccion_provincia,
                    "start_date" => $data->cc_fecha_cpte,
                    "end_date" => $data->cc_fecha_final_cpte,
                    "tipo_doc" => $data->prefijo,
                    "comprobante" => $data->serie_comprobante . zero_fill($data->num_comprobante, 8),
                );
                $pdf->setData($array);
                $resolucion = $pieFactura->getPieFacturaByComprobanteId($data->iddetalle_documento_sucursal);
                foreach ($resolucion as $res) {}
                $pdf->AddPage('P', 'A4', '');
//                $pdf->AddPage('P','A4','',$array);
                $subtotal_credito = 0;
                $subtotal_debito = 0;
                $subtotal_cuentas = 0;
                foreach ($articulos as $articulos) {
                    $getPuc = $puc->getPucById($articulos->dcc_cta_item_det);
                    $gertArticulo = $articulo->getArticuloById($articulos->dcc_cod_art);

                    $debito = ($articulos->dcc_d_c_item_det == "D" && $articulos->dcc_valor_item > 0) ? number_format($articulos->dcc_valor_item, 0, '.', ',') : "";
                    $credito = ($articulos->dcc_d_c_item_det == "C" && $articulos->dcc_valor_item > 0) ? number_format($articulos->dcc_valor_item, 0, '.', ',') : "";
                    $subtotal_credito += ($articulos->dcc_d_c_item_det == "C" && $articulos->dcc_valor_item > 0) ? $articulos->dcc_valor_item : 0;
                    $subtotal_debito += ($articulos->dcc_d_c_item_det == "D" && $articulos->dcc_valor_item > 0) ? $articulos->dcc_valor_item : 0;

                    $persona = $personas->getPersonaById($data->cc_idproveedor);
                    foreach ($persona as $tercero) {}

                    if ($gertArticulo) {
                        foreach ($gertArticulo as $articuloitem) {}
                        $fecha_vcto = ($articulos->dcc_fecha_vcto_item != "0000-00-00") ? $articulos->dcc_fecha_vcto_item : "";
                        $resp2[] = array(
                            $articulos->dcc_cta_item_det,
                            $articuloitem->descripcion,
                            $articulos->dcc_cant_item_det,
                            $data->cc_ccos_cpte,
                            $tercero->num_documento,
                            $articulos->dcc_dato_fact_prove,
                            $fecha_vcto,
                            number_format(round($articulos->dcc_base_ret_item), 0, ',', '.'),
                            $debito,
                            $credito,
                        );
                        $respStandard[] = array(
                            $articuloitem->idarticulo,
                            $articuloitem->descripcion,
                            number_format($articulos->dcc_cant_item_det, 2, ',', '.'),
                            number_format(round($articulos->dcc_valor_item / $articulos->dcc_cant_item_det), 0, ',', '.'),
                            number_format(round($articulos->dcc_valor_item * ($articulos->dcc_base_imp_item / 100)), 0, ',', '.'),
                            number_format(round($articulos->dcc_valor_item * (($articulos->dcc_base_imp_item / 100) + 1)), 0, ',', '.'),
                        );
                    } elseif ($getPuc) {
                        foreach ($getPuc as $pucitem) {}
                        $fecha_vcto = ($articulos->dcc_fecha_vcto_item != "0000-00-00") ? $articulos->dcc_fecha_vcto_item : "";
                        $resp2[] = array(
                            $pucitem->idcodigo,
                            $articulos->dcc_det_item_det,
                            $articulos->dcc_cant_item_det,
                            $data->cc_ccos_cpte,
                            $tercero->num_documento,
                            $articulos->dcc_dato_fact_prove,
                            $fecha_vcto,
                            number_format(round($articulos->dcc_base_ret_item), 0, ',', '.'),
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

                foreach ($totales as $subtotal) {}
                //valores a imprimir
                $subtotalimpuesto = 0;
                $listImpuesto = [];
                $listRetencion = [];
                $total_bruto = $subtotal->cdi_debito;
                $total_neto = $subtotal->cdi_debito;
                //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)

                foreach ($totalimpuestos as $imp) {
                    $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1)); //aqui
                    foreach ($impuestos as $data2) {}
                    if ($impuestos) {
                        if ($data2->im_porcentaje == $imp->cdi_importe) {
                            $total_neto -= $subtotalimpuesto;
                            $total_bruto -= $subtotalimpuesto;
                        } else {

                        }
                    } else {

                    }

                    foreach ($impuestos as $impuesto) {
                        if ($imp->cdi_importe == $impuesto->im_porcentaje) {
                            //calculado
                            $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
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
                        //calculado $subtotal->cdi_debito*($retencion->re_porcentaje/100)
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
                                    $iva = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));

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
    }

    public function informe()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {
                //models
                $sucursales = new Sucursal($this->adapter);
                $reportecontable = new ReporteContable($this->adapter);
                //functions
                $idreporte = $_GET["data"];
                //configuracion para el modo de visualizacion
                $view = (isset($_GET["s"]) && !empty($_GET["s"])) ? $_GET["s"] : "";
                $file_height = (isset($view)) ? "100%" : "92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"])) ? $_GET["t"] : "";
                //recuperando informe por id
                $informe = $reportecontable->getReporteConableById($idreporte);
                if ($informe) {
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                    foreach ($informe as $data) {}
                    //ecuperando la sucursal por factura de venta
                    $sucursal = $sucursales->getSucursalById($_SESSION["idsucursal"]);
                    //traer la vista del tipo de impresion que se aplica a este comprobante
                    $funcion = $data->rcc_type . "_reporte";
                    //configuracion solo para desarrollo
                    //$location = "http://127.0.0.1/app";
                    $location = LOCATION_CLIENT;

                    $this->frameview("file/pdf/reporte", array(
                        "file_height" => $file_height,
                        "conf_print" => $conf_print,
                        "sucursal" => $sucursal,
                        "funcion" => $funcion,
                        "id" => $data->rcc_id,
                        "url" => $location,
                        "redirect" => "#comprobantes/menu",
                    ));
                } else {
                    echo "Factura no disponible";
                }
            } else {

            }
        }
    }
    public function BC_reporte()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            if (isset($_GET["data"]) && !empty($_GET["data"]) && $_GET["data"] > 0) {
                require_once 'lib/PHPExcel/PHPExcel.php';
                //models
                $comprobantecontable = new ComprobanteContable($this->adapter);
                $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
                $reportecontable = new ReporteContable($this->adapter);
                $excel = new PHPExcel();
                $articulo = new Articulo($this->adapter);
                $compra = new Compras($this->adapter);
                $detallecompra = new DetalleIngreso($this->adapter);
                $venta = new Ventas($this->adapter);
                $detalleventa = new DetalleVenta($this->adapter);
                $puc = new PUC($this->adapter);
                $sucursales = new Sucursal($this->adapter);
                $empleados = new Empleado($this->adapter);
                //functions
                $idreporte = $_GET["data"];
                $informe = $reportecontable->getReporteConableById($idreporte);
                foreach ($informe as $informe) {}
                $empleado = $empleados->getEmpleadoByUserId($_SESSION["usr_uid"]);
                foreach ($empleado as $empleado);
                if ($informe) {
                    $sucursal = $sucursales->getSucursalById($informe->rcc_idsucursal);
                    foreach ($sucursal as $sucursal) {}
                    //start settings
                    $start_at = 8;
                    $reg_list = [];
                    $fill_config = [];
                    $folder_master = "files/xls/";
                    $filename = "Balance de comprobacion " . $idreporte;
                    $properties = [];
                    $column_used = [];
                    $cell_config = [];
                    $scope = false;
                    ///////////////////////////
                    $properties[] = array("A1:j1", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A2:j2", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A3:j3", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A4:j4", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A5:j5", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A6:j6", "4691a5", "FFFFFF", "merge");

                    $properties[] = array("A1", "4691a5", "FFFFFF", "value", "$sucursal->razon_social");
                    $properties[] = array("A2", "4691a5", "FFFFFF", "value", "$sucursal->num_documento");
                    $properties[] = array("A3", "4691a5", "FFFFFF", "value", "$sucursal->direccion $sucursal->telefono");
                    $properties[] = array("A4", "4691a5", "FFFFFF", "value", "Reporte Balance de comprobacion");
                    $properties[] = array("A5", "4691a5", "FFFFFF", "value", "$informe->rcc_start_date / $informe->rcc_end_date");
                    $properties[] = array("A6", "4691a5", "FFFFFF", "value", "Reporte solicitado por usuario " . $empleado->nombre . " " . $empleado->apellidos);

                    $cell_config[] = array("A", "267084", "FFFFFF", "10");
                    $cell_config[] = array("B", "267084", "FFFFFF", "10");
                    $cell_config[] = array("C", "267084", "FFFFFF", "10");
                    $cell_config[] = array("D", "267084", "FFFFFF", "10");
                    $cell_config[] = array("E", "267084", "FFFFFF", "36");
                    $cell_config[] = array("F", "267084", "FFFFFF", "14");
                    $cell_config[] = array("G", "267084", "FFFFFF", "18");
                    $cell_config[] = array("H", "267084", "FFFFFF", "18");
                    $cell_config[] = array("I", "267084", "FFFFFF", "18");
                    $cell_config[] = array("J", "267084", "FFFFFF", "18");
                    //////////////////////////
                    //end settings
                    $header_list = array(
                        "A7" => "Grupo",
                        "B7" => "Cuenta",
                        "C7" => "Subcuenta",
                        "D7" => "Auxiliar",
                        "E7" => "Descripccion",
                        "F7" => "Ult. Mov.",
                        "G7" => "Slado Anterior",
                        "H7" => "Debitos",
                        "I7" => "Creditos",
                        "J7" => "Nuevo Saldo");

                    $clases = $puc->getClase();

                    $t_saldo_anterior = 0;
                    $t_debito = 0;
                    $t_credito = 0;
                    $t_nuevo_saldo = 0;
                    foreach ($clases as $clase) {
                        $grupos = $puc->getGrupo($clase->idcodigo);
                        foreach ($grupos as $grupo) {
                            //recuperar datos
                            $debitogrupo = 0;
                            $creditogrupo = 0;
                            $ultimo_movimiento = "";
                            $saldoanteriorgrupo = 0;
                            $detallegrupos = $detallecomprobantecontable->getTotalByCuenta($grupo->idcodigo);
                            foreach ($detallegrupos as $detallegrupo) {
                                //saldo anterior
                                if ($detallegrupo->cc_fecha_cpte <= $informe->rcc_start_date) {
                                    if ($detallegrupo->dcc_d_c_item_det == "D") {
                                        $saldoanteriorgrupo += $detallegrupo->dcc_valor_item;
                                        $t_saldo_anterior += $detallegrupo->dcc_valor_item;
                                    } else {
                                        $saldoanteriorgrupo -= $detallegrupo->dcc_valor_item;
                                        $t_saldo_anterior -= $detallegrupo->dcc_valor_item;
                                    }

                                }
                                if ($detallegrupo->cc_fecha_cpte >= $informe->rcc_start_date && $detallegrupo->cc_fecha_cpte <= $informe->rcc_end_date) {
                                    if ($detallegrupo->dcc_d_c_item_det == "D") {
                                        $debitogrupo += $detallegrupo->dcc_valor_item;
                                        $t_debito += $detallegrupo->dcc_valor_item;
                                    } else if ($detallegrupo->dcc_d_c_item_det == "C") {
                                        $creditogrupo += $detallegrupo->dcc_valor_item;
                                        $t_credito += $detallegrupo->dcc_valor_item;
                                    }
                                    $ultimo_movimiento = $detallegrupo->cc_fecha_cpte;
                                }
                            }

                            if ($debitogrupo > 0 || $creditogrupo > 0) {
                                $reg_list[] = array($grupo->idcodigo, $grupo->tipo_codigo, $ultimo_movimiento, $saldoanteriorgrupo, $debitogrupo, $creditogrupo);
                            }

                            $cuentas = $puc->getCuenta($grupo->idcodigo);
                            foreach ($cuentas as $cuenta) {
                                $debitocuenta = 0;
                                $creditocuenta = 0;
                                $saldoanteriorcuenta = 0;
                                $ultimo_movimiento = "";
                                $detallecuentas = $detallecomprobantecontable->getTotalByCuenta($cuenta->idcodigo);

                                foreach ($detallecuentas as $detallecuenta) {
                                    if ($detallecuenta->cc_fecha_cpte <= $informe->rcc_start_date) {
                                        if ($detallecuenta->dcc_d_c_item_det == "D") {
                                            $saldoanteriorcuenta += $detallecuenta->dcc_valor_item;
                                            $t_saldo_anterior += $detallecuenta->dcc_valor_item;
                                        } else {
                                            $saldoanteriorcuenta -= $detallecuenta->dcc_valor_item;
                                            $t_saldo_anterior -= $detallecuenta->dcc_valor_item;
                                        }

                                    }
                                    if ($detallecuenta->cc_fecha_cpte >= $informe->rcc_start_date && $detallecuenta->cc_fecha_cpte <= $informe->rcc_end_date) {
                                        if ($detallecuenta->dcc_d_c_item_det == "D") {
                                            $debitocuenta += $detallecuenta->dcc_valor_item;
                                            $t_debito += $detallecuenta->dcc_valor_item;
                                        } else if ($detallecuenta->dcc_d_c_item_det == "C") {
                                            $creditocuenta += $detallecuenta->dcc_valor_item;
                                            $d_credito += $detallecuenta->dcc_valor_item;
                                        }
                                        $ultimo_movimiento = $detallecuenta->cc_fecha_cpte;
                                    }
                                }
                                if ($debitocuenta > 0 || $creditocuenta > 0) {
                                    $reg_list[] = array($cuenta->idcodigo, $cuenta->tipo_codigo, $ultimo_movimiento, $saldoanteriorcuenta, $debitocuenta, $creditocuenta);
                                }
                                $subcuentas = $puc->getSubCuenta($cuenta->idcodigo);
                                foreach ($subcuentas as $subcuenta) {
                                    $debitosubcuenta = 0;
                                    $creditosubcuenta = 0;
                                    $saldoanteriorsubcuenta = 0;
                                    $ultimo_movimiento = "";
                                    $detallesubcuentas = $detallecomprobantecontable->getTotalByCuenta($subcuenta->idcodigo);

                                    foreach ($detallesubcuentas as $detallesubcuenta) {
                                        if ($detallesubcuenta->cc_fecha_cpte <= $informe->rcc_start_date) {
                                            if ($detallesubcuenta->dcc_d_c_item_det == "D") {
                                                $saldoanteriorsubcuenta += $detallesubcuenta->dcc_valor_item;
                                                $t_saldo_anterior += $detallesubcuenta->dcc_valor_item;
                                            } else {
                                                $saldoanteriorsubcuenta -= $detallesubcuenta->dcc_valor_item;
                                                $t_saldo_anterior -= $detallesubcuenta->dcc_valor_item;
                                            }
                                        }
                                        if ($detallesubcuenta->cc_fecha_cpte >= $informe->rcc_start_date && $detallesubcuenta->cc_fecha_cpte <= $informe->rcc_end_date) {
                                            if ($detallesubcuenta->dcc_d_c_item_det == "D") {
                                                $debitosubcuenta += $detallesubcuenta->dcc_valor_item;
                                                $t_debito += $detallesubcuenta->dcc_valor_item;
                                            } else if ($detallesubcuenta->dcc_d_c_item_det == "C") {
                                                $creditosubcuenta += $detallesubcuenta->dcc_valor_item;
                                                $t_credito += $detallesubcuenta->dcc_valor_item;
                                            }
                                            $ultimo_movimiento = $informe->rcc_start_date;
                                        }
                                    }

                                    if ($debitosubcuenta > 0 || $creditosubcuenta > 0) {
                                        $reg_list[] = array($subcuenta->idcodigo, $subcuenta->tipo_codigo, $ultimo_movimiento, $saldoanteriorsubcuenta, $debitosubcuenta, $creditosubcuenta);
                                    }
                                    $auxiliarsubcuentas = $puc->getAuxSubCuenta($subcuenta->idcodigo);
                                    foreach ($auxiliarsubcuentas as $auxiliarsubcuenta) {
                                        $debitoauxiliarsubcuenta = 0;
                                        $creditoauxiliarsubcuenta = 0;
                                        $saldoanteriorauxiliarsubcuenta = 0;
                                        $ultimo_movimiento = "";
                                        $detalleauxiliarsubcuentas = $detallecomprobantecontable->getTotalByCuenta($auxiliarsubcuenta->idcodigo);
                                        foreach ($detalleauxiliarsubcuentas as $detalleauxiliarsubcuenta) {
                                            if ($detalleauxiliarsubcuenta->cc_fecha_cpte <= $informe->rcc_start_date) {
                                                if ($detalleauxiliarsubcuenta->dcc_d_c_item_det) {
                                                    $saldoanteriorauxiliarsubcuenta += $detalleauxiliarsubcuenta->dcc_valor_item;
                                                    $t_saldo_anterior += $detalleauxiliarsubcuenta->dcc_valor_item;
                                                } else {
                                                    $saldoanteriorauxiliarsubcuenta -= $detalleauxiliarsubcuenta->dcc_valor_item;
                                                    $t_saldo_anterior -= $detalleauxiliarsubcuenta->dcc_valor_item;
                                                }

                                            }
                                            if ($detalleauxiliarsubcuenta->cc_fecha_cpte >= $informe->rcc_start_date && $detalleauxiliarsubcuenta->cc_fecha_cpte <= $informe->rcc_end_date) {
                                                if ($detalleauxiliarsubcuenta->dcc_d_c_item_det == "D") {
                                                    $debitoauxiliarsubcuenta += $detalleauxiliarsubcuenta->dcc_valor_item;
                                                    $t_debito += $detalleauxiliarsubcuenta->dcc_valor_item;
                                                } else if ($detalleauxiliarsubcuenta->dcc_d_c_item_det == "C") {
                                                    $creditoauxiliarsubcuenta += $detalleauxiliarsubcuenta->dcc_valor_item;
                                                    $t_credito += $detalleauxiliarsubcuenta->dcc_valor_item;
                                                }
                                                $ultimo_movimiento = $detalleauxiliarsubcuenta->cc_fecha_cpte;
                                            }
                                        }
                                        if ($debitoauxiliarsubcuenta > 0 || $creditoauxiliarsubcuenta > 0) {
                                            $reg_list[] = array($auxiliarsubcuenta->idcodigo, $auxiliarsubcuenta->tipo_codigo, $ultimo_movimiento, $saldoanteriorauxiliarsubcuenta, $debitoauxiliarsubcuenta, $creditoauxiliarsubcuenta);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $sheet = $excel->getActiveSheet();
                    foreach ($header_list as $header => $value) {

                        $sheet->setCellValue("$header", "$value");
                        $sheet->getStyle("$header")->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->getColor()->setRGB("FFFFFF");
                        $sheet->getStyle("$header")->getBorders()->applyFromArray(array('allBorders' => 'thin'));
                        $sheet->getStyle("$header")->getAlignment()->setVertical('center')->setHorizontal('center');
                        $excel->getActiveSheet()
                            ->getStyle("$header")
                            ->getFill()
                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('4691a5');
                        $excel->getActiveSheet()->getColumnDimension(substr($header, 0, 1))->setWidth(20);
                    }
                    foreach ($reg_list as $reg_list) {
                        //echo $reg_list[0]."  ".$reg_list[1]."  ".$reg_list[2]."  ".$reg_list[3]."  ".$reg_list[4]."  ".$reg_list[5]."<br>";
                        $code = str_split($reg_list[0], 2);

                        $sheet->setCellValue('A' . $start_at, "$code[0]");

                        $sheet->setCellValue('B' . $start_at, (isset($code[1]) ? "$code[1]" : ""));
                        $sheet->setCellValue('C' . $start_at, (isset($code[2]) ? "$code[2]" : ""));
                        $sheet->setCellValue('D' . $start_at, (isset($code[3]) ? "$code[3]" : ""));

                        $sheet->setCellValue('E' . $start_at, "$reg_list[1]");
                        $sheet->setCellValue('F' . $start_at, "$reg_list[2]");

                        $reg_list3 = number_format($reg_list[3], 0, '.', ',');
                        $reg_list4 = number_format($reg_list[4], 0, '.', ',');
                        $reg_list5 = number_format($reg_list[5], 0, '.', ',');

                        $sheet->setCellValue('G' . $start_at, "$reg_list3");
                        $sheet->setCellValue('H' . $start_at, "$reg_list4");
                        $sheet->setCellValue('I' . $start_at, "$reg_list5");

                        $newvalue = number_format(($reg_list[3] + $reg_list[4]) - $reg_list[5], 0, '.', ',');
                        $sheet->setCellValue('J' . $start_at, "$newvalue");

                        //CELL SETTING
                        $sheet->getStyle("A" . $start_at . ":F" . $start_at)->getAlignment()->applyFromArray(
                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                        );
                        $sheet->getStyle("G" . $start_at . ":J" . $start_at)->getAlignment()->applyFromArray(
                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                        );
                        //END CELL SETTING
                        $scope = ($scope == false) ? true : false;
                        $color = ($scope == false) ? "d9f6ff" : "85b5c3";

                        $excel->getActiveSheet()
                            ->getStyle("A" . $start_at . ":J" . $start_at)
                            ->getFill()
                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB("$color");
                        $start_at++;
                        $column_used[] = $start_at;

                    }
                    //totales
                    $tsa = (isset($t_saldo_anterior) && $t_saldo_anterior > 0) ? number_format($t_saldo_anterior, 0, '.', ',') : 0;
                    $sheet->setCellValue('G' . $start_at, "$tsa");

                    $td = (isset($t_debito) && $t_debito > 0) ? number_format($t_debito, 0, '.', ',') : 0;
                    $sheet->setCellValue('H' . $start_at, "$td");

                    $tc = (isset($t_credito) && $t_credito > 0) ? number_format($t_credito, 0, '.', ',') : 0;
                    $sheet->setCellValue('I' . $start_at, "$tc");

                    $tns = ($tsa || $td || $tc) ? number_format(($t_saldo_anterior + $t_debito) - $t_credito, 0, '.', ',') : 0;

                    $sheet->setCellValue('J' . $start_at, "$tns");
                    $sheet->getStyle("G" . $start_at . ":J" . $start_at)->getAlignment()->applyFromArray(
                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                    );
                    //end_totales

                    foreach ($column_used as $column_use) {
                        $properties[] = array("A" . $start_at . ":G" . ($column_use - 1), "5baabf", "FFFFFF");
                    }
                    foreach ($cell_config as $config) {
                        $sheet->getColumnDimension("$config[0]")->setAutoSize(false);
                        $sheet->getColumnDimension("$config[0]")->setWidth($config[3]);
                    }
                    foreach ($properties as $propertie) {
                        $excel->getActiveSheet()
                            ->getStyle("$propertie[0]")
                            ->getFill()
                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB("$propertie[1]");
                        $excel->getActiveSheet()->getStyle("$propertie[0]")->getFont()->setBold(false)->getColor()->setRGB("$propertie[2]");
                        if (isset($propertie[3])) {
                            switch ($propertie[3]) {
                                case 'merge':
                                    $excel->getActiveSheet()->mergeCells("$propertie[0]");
                                    $sheet->getStyle("$propertie[0]")->getAlignment()->applyFromArray(
                                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    );
                                    break;
                                case 'value':
                                    $sheet->setCellValue("$propertie[0]", "$propertie[4]");
                                    break;
                                default:

                                    break;
                            }
                        }
                    }

                    $writer = new PHPExcel_Writer_Excel5($excel);
                    $writer->save($folder_master . $filename . '.xls');
                    $file_height = "95.5%";
                    $location = $folder_master . $filename . '.xls';
                    $this->frameview("file/xls/Excel", array(
                        "file_height" => $file_height,
                        "url" => $location,
                        "redirect" => "#comprobantes/menu",
                    ));
                } else {
                    echo "Este reporte no es valido";
                }
                //
            }
        }
    }

    public function MC_reporte()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            if (isset($_GET["data"]) && !empty($_GET["data"]) && $_GET["data"] > 0) {
                require_once 'lib/PHPExcel/PHPExcel.php';
                //models
                $excel = new PHPExcel();
                $comprobantecontable = new ComprobanteContable($this->adapter);
                $reportecontable = new ReporteContable($this->adapter);
                $sucursales = new Sucursal($this->adapter);
                $empleados = new Empleado($this->adapter);
                //functions
                $idreporte = $_GET["data"];
                $informe = $reportecontable->getReporteConableById($idreporte);
                foreach ($informe as $informe) {}
                $empleado = $empleados->getEmpleadoByUserId($_SESSION["usr_uid"]);
                foreach ($empleado as $empleado);
                if ($informe) {
                    $sucursal = $sucursales->getSucursalById($informe->rcc_idsucursal);
                    foreach ($sucursal as $sucursal) {}

                    $comprobantes = $comprobantecontable->reporte_detallado_comprobante_por_cuenta($informe->rcc_start_date, $informe->rcc_end_date, $informe->rcc_param1, $informe->rcc_param2);
                    $start_last_mont = date("Y-m-d", strtotime($informe->rcc_start_date . "- 1 month"));
                    $end_last_mont = date("Y-m-d", strtotime($informe->rcc_end_date . "- 1 month"));
                    $instance = [];
                    $start_at = 8;
                    $init_at = $start_at;
                    $reg_list = [];
                    $fill_config = [];
                    $folder_master = "files/xls/";
                    $filename = "Movimiento de cuentas " . $idreporte;
                    $properties = [];
                    $column_used = [];
                    $cell_config = [];
                    $scope = false;
                    ///////////////////////////
                    $properties[] = array("A1:H1", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A2:H2", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A3:H3", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A4:H4", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A5:H5", "4691a5", "FFFFFF", "merge");
                    $properties[] = array("A6:H6", "4691a5", "FFFFFF", "merge");

                    $properties[] = array("A1", "4691a5", "FFFFFF", "value", "$sucursal->razon_social");
                    $properties[] = array("A2", "4691a5", "FFFFFF", "value", "$sucursal->num_documento");
                    $properties[] = array("A3", "4691a5", "FFFFFF", "value", "$sucursal->direccion $sucursal->telefono");
                    $properties[] = array("A4", "4691a5", "FFFFFF", "value", "Reporte Movimiento de cuentas");
                    $properties[] = array("A5", "4691a5", "FFFFFF", "value", "$informe->rcc_start_date / $informe->rcc_end_date");
                    $properties[] = array("A6", "4691a5", "FFFFFF", "value", "Reporte solicitado por usuario " . $empleado->nombre . " " . $empleado->apellidos);

                    $cell_config[] = array("A", "267084", "FFFFFF", "10");
                    $cell_config[] = array("B", "267084", "FFFFFF", "10");
                    $cell_config[] = array("C", "267084", "FFFFFF", "10");
                    $cell_config[] = array("D", "267084", "FFFFFF", "10");
                    $cell_config[] = array("E", "267084", "FFFFFF", "14");
                    $cell_config[] = array("F", "267084", "FFFFFF", "36");
                    $cell_config[] = array("G", "267084", "FFFFFF", "25");
                    $cell_config[] = array("H", "267084", "FFFFFF", "25");
                    //////////////////////////
                    //end settings
                    $header_list = array(
                        "A7" => "Grupo",
                        "B7" => "Cuenta",
                        "C7" => "Subcuenta",
                        "D7" => "Auxiliar",
                        "E7" => "Comprobante",
                        "F7" => "Consecutivo",
                        "G7" => "Debito",
                        "H7" => "Credito");

                    $debito = 0;
                    $credito = 0;
                    $totalinstance = [];
                    foreach ($comprobantes as $comprobantes) {
                        $subinstance = [];
                        //llamar la misma query pero limitando los codigos solo al actual en el array
                        $total_cuenta = $comprobantecontable->reporte_total_comprobante_por_cuenta($start_last_mont, $end_last_mont, $comprobantes->dcc_cta_item_det, $comprobantes->dcc_d_c_item_det);
                        foreach ($total_cuenta as $total_cuenta) {}
                        //recuperar todas los comprobantes con esta cuenta
                        $registros = $comprobantecontable->reporte_general_comprobante_por_cuenta($informe->rcc_start_date, $informe->rcc_end_date, $comprobantes->dcc_cta_item_det);
                        foreach ($registros as $registro) {
                            $debito_s = ($registro->dcc_d_c_item_det == "D")?$registro->dcc_valor_item:0;
                            $credito_s = ($registro->dcc_d_c_item_det == "C")?$registro->dcc_valor_item:0;
                            $subinstance[] = array(
                                $registro->cc_num_cpte,
                                $registro->cc_cons_cpte,
                                $debito_s,
                                $credito_s,
                            );
                            $debito += $debito_s;
                            $credito += $credito_s;
                        }

                        $instance[] = array(
                            $comprobantes->dcc_cta_item_det,
                            $subinstance,
                        );

                    }

                    $sheet = $excel->getActiveSheet();
                    foreach ($header_list as $header => $value) {

                        $sheet->setCellValue("$header", "$value");
                        $sheet->getStyle("$header")->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->getColor()->setRGB("FFFFFF");
                        $sheet->getStyle("$header")->getBorders()->applyFromArray(array('allBorders' => 'thin'));
                        $sheet->getStyle("$header")->getAlignment()->setVertical('center')->setHorizontal('center');
                        $excel->getActiveSheet()
                            ->getStyle("$header")
                            ->getFill()
                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('4691a5');
                        $excel->getActiveSheet()->getColumnDimension(substr($header, 0, 1))->setWidth(20);
                    }

                    // $pdf->FancyTableReporte2($tablehead,$instance);
                    foreach ($instance as $instance) {
                        //echo $instance[0]."  ".$instance[1]."  ".$instance[2]."  ".$instance[3]."  ".$instance[4]."  ".$instance[5]."<br>";
                        $code = str_split($instance[0], 2);

                        $sheet->setCellValue('A' . $start_at, "$code[0]");

                        $sheet->setCellValue('B' . $start_at, (isset($code[1]) ? "$code[1]" : ""));
                        $sheet->setCellValue('C' . $start_at, (isset($code[2]) ? "$code[2]" : ""));
                        $sheet->setCellValue('D' . $start_at, (isset($code[3]) ? "$code[3]" : ""));

                        foreach ($instance[1] as $subinstance) {
                            $sheet->setCellValue('E' . $start_at, "$subinstance[0]");
                            $sheet->setCellValue('F' . $start_at, "$subinstance[1]");

                            $instance3 = ($subinstance[2]);
                            $instance4 = ($subinstance[3]);
                            $sheet->setCellValue('G' . $start_at, "$instance3");
                            $sheet->setCellValue('H' . $start_at, "$instance4");

                            $sheet->getStyle("A" . $start_at . ":F" . $start_at)->getAlignment()->applyFromArray(
                                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                            );
                            $sheet->getStyle("G" . $start_at . ":H" . $start_at)->getAlignment()->applyFromArray(
                                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                            );
                            $start_at++;
                            $column_used[] = $start_at;
                        }

                        //CELL SETTING
                        $sheet->getStyle("A" . $start_at . ":F" . $start_at)->getAlignment()->applyFromArray(
                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                        );
                        $sheet->getStyle("G" . $start_at . ":H" . $start_at)->getAlignment()->applyFromArray(
                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                        );
                        //END CELL SETTING
                        $scope = ($scope == false) ? true : false;
                        $color = ($scope == false) ? "FFFFFF" : "85b5c3";

                        $excel->getActiveSheet()
                            ->getStyle("A" . $start_at . ":H" . $start_at)
                            ->getFill()
                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB("$color");
                        $start_at++;
                        $column_used[] = $start_at;

                    }

                    //totales
                    $sheet->setCellValue('F' . $start_at, "totales ====>");
                    $d = ($debito > 0) ? number_format($debito, 0, '.', ',') : 0;
                    $sheet->setCellValue('G' . $start_at, "$d");
                    $c = ($credito > 0) ? number_format($credito, 0, '.', ',') : 0;
                    $sheet->setCellValue('H' . $start_at, "$c");
                    $sheet->getStyle("G" . $start_at . ":H" . $start_at)->getAlignment()->applyFromArray(
                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
                    );

                    foreach ($column_used as $column_use) {
                        $properties[] = array("A" . $start_at . ":H" . ($column_use - 1), "5baabf", "FFFFFF");
                    }
                    foreach ($cell_config as $config) {
                        $sheet->getColumnDimension("$config[0]")->setAutoSize(false);
                        $sheet->getColumnDimension("$config[0]")->setWidth($config[3]);
                    }
                    foreach ($properties as $propertie) {
                        $excel->getActiveSheet()
                            ->getStyle("$propertie[0]")
                            ->getFill()
                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB("$propertie[1]");
                        $excel->getActiveSheet()->getStyle("$propertie[0]")->getFont()->setBold(false)->getColor()->setRGB("$propertie[2]");
                        if (isset($propertie[3])) {
                            switch ($propertie[3]) {
                                case 'merge':
                                    $excel->getActiveSheet()->mergeCells("$propertie[0]");
                                    $sheet->getStyle("$propertie[0]")->getAlignment()->applyFromArray(
                                        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                    );
                                    break;
                                case 'value':
                                    $sheet->setCellValue("$propertie[0]", "$propertie[4]");
                                    break;
                                default:

                                    break;
                            }
                        }
                    }

                    $writer = new PHPExcel_Writer_Excel5($excel);
                    $writer->save($folder_master . $filename . '.xls');
                    $file_height = "95.5%";
                    $location = $folder_master . $filename . '.xls';
                    $this->frameview("file/xls/Excel", array(
                        "file_height" => $file_height,
                        "url" => $location,
                        "redirect" => "#comprobantes/menu",
                    ));
                } else {echo "Este reporte no es valido";}
            } else {}
        } else {}
    }

    public function XLS_reporte()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            if (isset($_GET["data"]) && !empty($_GET["data"])) {
                $reportecontable = new ReporteContable($this->adapter);
                //functions
                $idreporte = $_GET["data"];
                $informe = $reportecontable->getReporteConableById($idreporte);
                foreach ($informe as $informe) {}
                if ($informe) {
                    require_once 'lib/PHPExcel/PHPExcel.php';
                    //models
                    $excel = new PHPExcel();
                    $articulo = new Articulo($this->adapter);
                    $compra = new Compras($this->adapter);
                    $detallecompra = new DetalleIngreso($this->adapter);
                    $venta = new Ventas($this->adapter);
                    $detalleventa = new DetalleVenta($this->adapter);
                    $sucursales = new Sucursal($this->adapter);
                    //functions
                    $sucursal = $sucursales->getSucursalById($informe->rcc_idsucursal);
                    foreach ($sucursal as $sucursal) {}

                    $column_start = 6;
                    $listCompra = [];
                    $detailCompra = [];
                    $detailVenta = [];
                    $detailKardex = [];
                    $detailSaldo = [];
                    $sheets = array("Kardex compras", "Kardex ventas");
                    $start_date = $informe->rcc_start_date;
                    $end_date = $informe->rcc_end_date;
                    $idarticulo = $informe->rcc_param1;
                    $column_used = [];

                    $folder_master = "files/xls/";
                    $filename = "KARDEX S" . $_SESSION["idsucursal"] . " " . $start_date . " - " . $end_date . " " . $idarticulo . "-" . $idreporte;
                    $compras = $compra->getDetalleComprasByDay($start_date, $end_date, 'a.idarticulo', $idarticulo);
                    $ventas = $venta->getDetalleVentasByDay($start_date, $end_date, 'a.idarticulo', $idarticulo);
                    $comprasAll = $detallecompra->getDetalleAllByDay();
                    $ventasAll = $detalleventa->getDetalleAll();
                    $articulos = $articulo->getArticuloById($idarticulo);
                    foreach ($articulos as $articulos) {}

                    //calculos extras
                    $total_stock_compra = 0;
                    $total_precio_compra = 0;
                    $promedio_precio_compra_actual = 0;
                    foreach ($compras as $calc_compra) {
                        $total_stock_compra += $calc_compra->stock_total_compras;
                        $total_precio_compra += $calc_compra->precio_total_compras;
                        $promedio_precio_compra_actual += ($calc_compra->precio_total_compras / $calc_compra->stock_total_compras);
                    }
                    $detailCompra[] = array(
                        $total_stock_compra,
                        $total_precio_compra,
                    );
                    $total_stock_venta = 0;
                    $total_precio_venta = 0;
                    foreach ($ventas as $calc_venta) {
                        $total_stock_venta += $calc_venta->stock_total_ventas;
                        $total_precio_venta += $calc_venta->precio_total_ventas;
                    }
                    $detailVenta[] = array(
                        $total_stock_venta,
                        $total_precio_venta,
                    );

                    ################## saldo anterior
                    $mes_anterior = date("m", strtotime($start_date)) - 1;
                    $stock_anterior = 0;
                    $precio_compra_anterior = 0;
                    $promedio_precio_compra_anterior = 0;
                    $precio_venta_anterior = 0;

                    foreach ($comprasAll as $compra_anterior) {
                        $mes_compra = date("m", strtotime($compra_anterior->fecha));
                        $ano_compra = date("Y", strtotime($compra_anterior->fecha));
                        if ($mes_compra == $mes_anterior && $compra_anterior->idarticulo == $idarticulo) {
                            $stock_anterior += $compra_anterior->stock_total_compras;
                            $precio_compra_anterior += $compra_anterior->precio_total_lote; ##junto con impuesto
                        }
                    }
                    $promedio_precio_compra_anterior = ($stock_anterior) ? $precio_compra_anterior / $stock_anterior : 0;

                    foreach ($ventasAll as $venta_anterior) {
                        $mes_venta = date("m", strtotime($venta_anterior->fecha));
                        $ano_venta = date("Y", strtotime($venta_anterior->fecha));
                        if ($mes_venta == $mes_anterior && $venta_anterior->idarticulo == $idarticulo) {
                            $stock_anterior -= $venta_anterior->cantidad;
                            $precio_venta_anterior += $venta_anterior->precio_total_lote;
                            $precio_compra_anterior -= $promedio_precio_compra_anterior;

                        }
                    }

                    $salida_consecutiva = $total_stock_compra + $stock_anterior;
                    $precio_anterior_descendiendo = $precio_compra_anterior;

                    foreach ($ventas as $calc_venta2) {
                        $salida_consecutiva -= $calc_venta2->stock_total_ventas;
                        $detailSaldo[] = array(
                            $salida_consecutiva,
                            $precio_compra_anterior + $calc_venta2->stock_total_ventas,
                        );
                    }
                    //Usamos el worsheet por defecto
                    $sheet = $excel->getActiveSheet();

                    $header = array(
                        "A5" => "Fecha",
                        "B5" => "Comprobante",
                        "C5" => "CC/NIT",
                        "D5" => "Nombre",
                        "E5" => "Cantidad",
                        "F5" => "Costo/Precio",
                        "G5" => "Total",
                        "H5" => "",
                    );
                    //set header properties
                    $properties = [];
                    $properties[] = array("A1:G1", "267084", "FFFFFF");
                    $properties[] = array("A2:G2", "267084", "FFFFFF");
                    $properties[] = array("A3:G3", "267084", "FFFFFF");
                    $properties[] = array("A4:G4", "267084", "FFFFFF");
                    $properties[] = array("A5:G5", "267084", "FFFFFF");
                    $properties[] = array("A6:G6", "267084", "FFFFFF");
                    $properties[] = array("A1:G1", "267084", "FFFFFF", "merge");
                    $properties[] = array("A2:G2", "267084", "FFFFFF", "merge");
                    $properties[] = array("E3:G3", "267084", "FFFFFF", "merge");
                    $properties[] = array("A1", "267084", "FFFFFF", "value", "$sucursal->razon_social");
                    $properties[] = array("A2", "267084", "FFFFFF", "value", "Reporte Kardex Valorizado");

                    //setear propiedades a la cabecera
                    $compra_start_at = $column_start;

                    foreach ($compras as $compra) {
                        $promedio_total_compra = number_format($compra->precio_total_compras / $compra->stock_total, 2, '.', '');
                        $stock_total = number_format($compra->stock_total, 2, '.', '');
                        $sheet->setCellValue('A' . $compra_start_at, $compra->fecha);
                        $sheet->setCellValue('B' . $compra_start_at, $compra->serie_comprobante . "" . zero_fill($compra->num_comprobante, 8));
                        $sheet->setCellValue('C' . $compra_start_at, $compra->documento_tercero);
                        $sheet->setCellValue('D' . $compra_start_at, $compra->nombre_proveedor);
                        $sheet->setCellValue('E' . $compra_start_at, "$stock_total");
                        $sheet->setCellValue('F' . $compra_start_at, "$promedio_total_compra");
                        $sheet->setCellValue('G' . $compra_start_at, $compra->precio_total_compras);
                        $sheet->getStyle('A' . $compra_start_at . ":G" . $compra_start_at)->getAlignment()->setVertical('center')->setHorizontal('right');
                        $compra_start_at++;
                    }
                    $column_used[] = $compra_start_at;

                    $ventas_start_at = $column_start;

                    if ($ventas) {
                        $sheet = $excel->createSheet(1);
                        $sheet->setTitle("Kardex ventas");
                        foreach ($ventas as $venta) {
                            if (isset($venta->fecha) && !empty($venta->fecha)) {
                                $precio_total_ventas = ($venta->precio_total_ventas);
                                $stock_total = intval(($venta->stock_total));
                                $promedio_total = ($venta->precio_total_ventas / $venta->stock_total);

                                $sheet->setCellValue('A' . $ventas_start_at, $venta->fecha);
                                $sheet->setCellValue('B' . $ventas_start_at, $venta->serie_comprobante . "" . zero_fill($venta->num_comprobante, 8));
                                $sheet->setCellValue('C' . $ventas_start_at, $venta->documento_tercero);
                                $sheet->setCellValue('D' . $ventas_start_at, $venta->nombre_cliente);
                                $sheet->setCellValue('E' . $ventas_start_at, "$stock_total");
                                $sheet->setCellValue('F' . $ventas_start_at, "$promedio_total");
                                $sheet->setCellValue('G' . $ventas_start_at, "$precio_total_ventas");
                                $sheet->getStyle('A' . $ventas_start_at . ":G" . $ventas_start_at)->getAlignment()->setVertical('center')->setHorizontal('right');
                                $ventas_start_at++;
                            }
                        }
                    }
                    $column_used[] = $ventas_start_at;

                    foreach ($column_used as $column_use) {
                        $properties[] = array("A" . $column_start . ":G" . ($column_use - 1), "5baabf", "FFFFFF");
                    }

                    //descubrir el maximo de columnas usadas en vertical desde la cabecera de la tabla
                    $max_column = max($column_used);
                    $v = 0;
                    for ($i = 0; $i <= 0; $i++) {
                        //imprimir totales dependiendo de la fila
                        $sheet = $excel->getSheet($v);

                        //HEADER
                        $sheet->setCellValue('A3', "Fecha inicio");
                        $sheet->setCellValue('A4', $start_date);
                        $sheet->setCellValue('C3', "Fecha fin");
                        $sheet->setCellValue('C4', $end_date);
                        $sheet->setCellValue('D3', "Articulo");
                        $sheet->setCellValue('E3', $articulos->nombre_articulo);
                        $sheet->setCellValue('D4', "Saldo anterior");
                        intval($stock_anterior);
                        $sheet->setCellValue('E4', "$stock_anterior");
                        $sheet->setCellValue('F4', "$promedio_precio_compra_anterior");
                        intval($precio_compra_anterior);
                        $sheet->setCellValue('G4', "$precio_compra_anterior");

                        foreach ($header as $head => $value) {

                            $sheet->setCellValue("$head", "$value");
                            $sheet->getStyle("$head")->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->getColor()->setRGB("FFFFFF");
                            $sheet->getStyle("$head")->getBorders()->applyFromArray(array('allBorders' => 'thin'));
                            $sheet->getStyle("$head")->getAlignment()->setVertical('center')->setHorizontal('center');
                            if (!empty($value)) {
                                $excel->getActiveSheet()
                                    ->getStyle("$head")
                                    ->getFill()
                                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                    ->getStartColor()
                                    ->setRGB('4691a5');
                            }
                            $excel->getActiveSheet()->getColumnDimension(substr($head, 0, 1))->setWidth(20);
                        }

                        $sheet->setCellValue('E' . $max_column, "=SUM(E" . $column_start . ":E" . ($max_column - 1) . ",E4)");
                        $excel->getActiveSheet()->getStyle('E' . $max_column)->getFont()->setBold(true);
                        $sheet->setCellValue('F' . $max_column, "=SUM(F" . $column_start . ":F" . ($max_column - 1) . ")");
                        $excel->getActiveSheet()->getStyle('F' . $max_column)->getFont()->setBold(true);
                        $sheet->setCellValue('G' . $max_column, "=SUM(G" . $column_start . ":G" . ($max_column - 1) . ",G4)");
                        $excel->getActiveSheet()->getStyle('G' . $max_column)->getFont()->setBold(true);
                        $sheet->setCellValue('H' . $max_column, "=G" . $max_column . "/E" . ($max_column) . "");
                        $excel->getActiveSheet()->getStyle('H' . $max_column)->getFont()->setBold(true);
                        $properties[] = array("A" . $max_column . ":H" . $max_column, "79cbe1", "FFFFFF");

                        //do properties
                        foreach ($properties as $propertie) {
                            $excel->getActiveSheet()
                                ->getStyle("$propertie[0]")
                                ->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                ->getStartColor()
                                ->setRGB("$propertie[1]");
                            $excel->getActiveSheet()->getStyle("$propertie[0]")->getFont()->setBold(false)->getColor()->setRGB("$propertie[2]");
                            if (isset($propertie[3])) {
                                switch ($propertie[3]) {
                                    case 'merge':
                                        $excel->getActiveSheet()->mergeCells("$propertie[0]");
                                        $sheet->getStyle("$propertie[0]")->getAlignment()->applyFromArray(
                                            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                        );
                                        break;
                                    case 'value':
                                        $sheet->setCellValue("$propertie[0]", "$propertie[4]");
                                        break;
                                    default:

                                        break;
                                }
                            }
                        }
                        $v++;
                    }

                    $writer = new PHPExcel_Writer_Excel5($excel);
                    $writer->save($folder_master . $filename . '.xls');
                    $file_height = "95.5%";
                    $location = $folder_master . $filename . '.xls';
                    $this->frameview("file/xls/Excel", array(
                        "file_height" => $file_height,
                        "url" => $location,
                        "redirect" => "#comprobantes/menu",
                    ));
                }
            }

        }
    }

    public function informe_kardex()
    {

    }

}
