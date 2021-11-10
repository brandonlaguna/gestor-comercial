<?php
class CajaController extends Controladorbase
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
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            date_default_timezone_set('UTC');
            $date = date("Y-m-d");
            //modelo de ingresos, ventas y caja
            $compra = new Compras($this->adapter);
            $venta = new Ventas($this->adapter);
            $caja = new ReporteCaja($this->adapter);
            $reporteActual = $caja->getReporteByDate($date);
            foreach ($reporteActual as $reporte) {}
            if ($reporteActual) {

                if ($reporte->rc_idusuario > 0) {
                    $this->frameview("caja/reporteExistente", array("idreporte" => $reporte->idreporte));
                } else {}
            } else {
                $ventas = $venta->reporte_detallado_categoria($date, $date);
                $articulos = $venta->reporte_detallado_articulo($date, $date);
                $this->frameview("caja/index", array(
                    "fecha" => $date,
                    "ventas" => $ventas,
                    "articulos" => $articulos,
                ));
            }
        }
    }

    public function cierre()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            date_default_timezone_set('UTC');
            $date = date("Y-m-d");
            $compra = new Compras($this->adapter);
            $venta = new Ventas($this->adapter);
            $caja = new ReporteCaja($this->adapter);
            $sucursales =new Sucursal($this->adapter);
            $total = 0;
            $efectivo = 0;
            $credito = 0;
            $debito = 0;
            $pagos = 0;
            //calcular cada denominacion y sumarla a un total
            foreach ($_POST as $data => $value) {
                $val = ($value != "") ? $value : 0;
                if ($data == "debito") {
                    $total += $val;
                    $debito += $val;
                } elseif ($data == "credito") {
                    $total += $val;
                    $credito += $val;
                } elseif ($data == "pagos") {
                    $total -= $val;
                    $pagos += $val;
                } else {
                    $total += (is_numeric($data) && is_numeric($val))?$data * $val:0;
					$efectivo = (is_numeric($data) && is_numeric($val))?$data * $val:0;
                    //$efectivo += $data * $val;
                }
            }

            $sucursal = $sucursales->getSucursalById($_SESSION["idsucursal"]);
            foreach ($sucursal as $sucursal){}
            $fecha = date('Y-m-d');
            $caja->setRc_descripcion("Cierre de Caja " . $date);
            $caja->setRc_monto($total);
            $caja->setRc_idsucursal($_SESSION["idsucursal"]);
            $caja->setRc_idusuario($_SESSION["usr_uid"]);
            $caja->setRc_efectivo($efectivo);
            $caja->setRc_credito($credito);
            $caja->setRc_debito($debito);
            $caja->setRc_pagos($pagos);
            $caja->setRc_accion("0");
            $caja->setRc_id_descripcion("0");
            $caja->setRc_fecha($date);
            $caja->setRc_base_diaria($sucursal->base_diaria);
            $caja->setRc_fecha_diaria($fecha);
            $saveRegistro = $caja->addRegistro();

            echo json_encode(array("redirect"=>"#file/caja/".$saveRegistro,));


        } else {
            echo "Forbidden Gateway";
        }
    }

    public function reportes()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            $caja = new ReporteCaja($this->adapter);
            $reportes = $caja->getReportesAll();

            $this->frameview("caja/reportes/list", array(
                "reportes" => $reportes,
            ));
        } else {

        }
    }

    public function cierre_turno()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            //models
            $venta = new Ventas($this->adapter);
            $comprobantecontable = new ComprobanteContable($this->adapter);
            $detalleVenta = new DetalleVenta($this->adapter);
            $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
            $sucursal = new Sucursal($this->adapter);
            $cierreturno = new CierreTurno($this->adapter);

            $start_date = date("Y-m-d");
            $start_time = date("H:i:s");
            $cierreturno->setRct_idsucursal($_SESSION["idsucursal"]);
            $cierreturno->setRct_idusuario($_SESSION["usr_uid"]);
            $cierreturno->setRct_date($start_date);

            $authInicio = $cierreturno->authInicio();
            if ($authInicio) {
                foreach ($authInicio as $authInicio) {}

                if ($authInicio->rct_idusuario == $_SESSION['usr_uid'] && $authInicio->rct_date == $start_date && $authInicio->rct_status == 0 && $authInicio->rct_venta_desde > 0) {
                    //obtener ventas desde la primera generada
                    //ventas normales
                    $ventas = $venta->getVentasByRange($authInicio->rct_venta_desde);
                    //ventas contables
                    $ventas_contables = $comprobantecontable->getComprobanteByRange($authInicio->rct_comprobante_desde, 'V');

                    //obtener total de articulos vendido por categoria 
                    $detalle = $detalleVenta->getGraphicByCategiryAndTimeToday($start_date);

                    $this->frameview("caja/cierre_turno/index", array());

                    javascript(array("lib/highlightjs/highlight.pack.min", "lib/raphael/raphael.min", "lib/morris.js/morris.min", "js/controller/chart.morris","lib/rickshaw/vendor/d3.min","lib/rickshaw/vendor/d3.layout.min","lib/rickshaw/rickshaw.min","/js/controller/ResizeSensor"));
                    
                    morrisChart(array(
                        "type" => "Donut",
                        "morrisId" => "morrisDonut2",
                        "colors" => array('#3D449C', '#268FB2', '#74DE00', '#5058AB', '#14A0C1', '#14A0C1'),
                    ));

                    morrisChart(array(
                        "type" => "Line",
                        "morrisId" => "morrisLine2",
                    ));

                    rickshawChart(array(
                        "type" => "Graph",
                        "morrisId" => "rickshaw1",
                    ));

                } else {}

            } else {

            }

        } else {
            echo "forbidden gateway";
        }
    }

    public function cierra_caja()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            $venta = new Ventas($this->adapter);
            $comprobantecontable = new ComprobanteContable($this->adapter);
            $detalleVenta = new DetalleVenta($this->adapter);
            $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
            $sucursal = new Sucursal($this->adapter);
            $cierreturno = new CierreTurno($this->adapter);

            $start_date = date("Y-m-d");
            $start_time = date("H:i:s");
            $cierreturno->setRct_idsucursal($_SESSION["idsucursal"]);
            $cierreturno->setRct_idusuario($_SESSION["usr_uid"]);
            $cierreturno->setRct_date($start_date);

            $authInicio = $cierreturno->authInicio();
            if ($authInicio) {
                
                foreach ($authInicio as $authInicio) {}
                if ($authInicio->rct_idusuario == $_SESSION['usr_uid'] && $authInicio->rct_date == $start_date) {
                    
                    //almacenar la ultima venta y el ultimo comprobante contable tipoventa
                    $venta->setFecha($start_date);
                    $venta->setIdusuario($_SESSION["usr_uid"]);
                    $venta->setIdsucursal($_SESSION["idsucursal"]);
                    $venta->setEstado('A');

                    $last_venta = $venta->getLastVentaByUser();

                    foreach($last_venta as $last_venta){
                        echo $last_venta->idventa;
                    }
                    
                    $cierreturno->setRct_venta_hasta($last_venta->idventa);

                    if($last_venta->idventa){
						$function = [];
                        $cierreturno->addFinalVenta();

                        $type = "Mensaje del sistema";
                        $legend = "Todo ok!";
                        $message = "Cierre de caja realizado con exito";
                        $function = array(
                            "reaction" => "actionToReaction('reaction','modalSystem',[]); return false;",
                            "inyectHmtl" => "finish='login/logout&s=true'",
                            "functionMessage" => "Ok.",
                        );

                        $this->frameview("modal/index", array(
                            "type" => $type,
                            "legend" => $legend,
                            "message" => $message,
                            "function" => $function,
                        ));
                    }
                }


            }



        }else{

            echo "forbidden gateway";

        }
        
    }

    public function monto_inicial() {
        echo "<script> console.log('Monto Inicial Metodo') </script>";
        $this->frameview("caja/monto_inicial/index", array("datos" => "No hay datos"));
    }

    public function form_monto_inicial() {
        if (isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            if (isset($_POST["monto"]) && !empty($_POST["monto"]) && is_numeric($_POST["monto"]) && isset($_POST["fecha_monto"]) && !empty($_POST["fecha_monto"])) {

                $monto_inicial = (int) cln_str($_POST["monto"]);
                $fecha = cln_str($_POST["fecha_monto"]);

                $sucursal = new Sucursal($this->adapter);
                $sucursales = $sucursal->getSucursalAll();
                
                $sucursal->setIdsucursal($sucursales[0]->idsucursal);
                $sucursal->setBase_diaria($monto_inicial);
                $sucursal->setFecha_diaria($fecha);
                $sucursal->updateBaseDiaria();
                
                echo json_encode(array(
                    "alert" => "success",
                    "title" => "Listo.",
                    "message" => "Monto de $".$monto_inicial." el ".$fecha,
                ));

            } else {
                echo json_encode(array(
                    "alert" => "error",
                    "title" => "Error.",
                    "message" => "El MONTO Y LA FECHA NO PUEDEN ESTAR VACIOS Y EL MONTO DEBE SER NUMERICO.",
                ));

            }
        } else {
            echo "Error de Privilegios";
        }
    }

}
