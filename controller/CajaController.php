<?php
class CajaController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2){
        date_default_timezone_set('UTC');
        $date = date("Y-m-d");
        //modelo de ingresos, ventas y caja
        $compra = new Compras($this->adapter);
        $venta = new Ventas($this->adapter);
        $caja = new ReporteCaja($this->adapter);
        $reporteActual = $caja->getReporteByDate($date);
        foreach ($reporteActual as $reporte) {}
        if($reporteActual){

            if($reporte->rc_idusuario >0){
                $this->frameview("caja/reporteExistente",array("idreporte"=>$reporte->idreporte));
            }else{}
        }else{
            $ventas = $venta->reporte_detallado_categoria($date,$date);
            $articulos = $venta->reporte_detallado_articulo($date,$date);
            $this->frameview("caja/index",array(
                "fecha"=>$date,
                "ventas"=>$ventas,
                "articulos"=>$articulos
                ));
        }
    }
    }

    public function cierre()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2){
            date_default_timezone_set('UTC');
            $date = date("Y-m-d");
            $compra = new Compras($this->adapter);
            $venta = new Ventas($this->adapter);
            $caja = new ReporteCaja($this->adapter);
            $total=0;
            $efectivo=0;
            $credito=0;
            $debito=0;
            $pagos =0;
            //calcular cada denominacion y sumarla a un total
            foreach($_POST as $data => $value){
                $val = ($value !="")?$value:0;
                if($data == "debito"){
                    $total += $val;
                    $debito +=$val;
                }elseif($data == "credito"){
                    $total += $val;
                    $credito += $val;
                }elseif($data == "pagos"){
                    $total -= $val;
                    $pagos += $val;
                }else{
                    $total += $data*$val;
                    $efectivo += $data*$val;
                }
            }

            $caja->setRc_descripcion("Cierre de Caja ".$date);
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
            $saveRegistro = $caja->addRegistro();
            echo $saveRegistro;

        }else{
            echo "Forbidden Gateway";
        }
    }

    public function reportes()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            $caja = new ReporteCaja($this->adapter);
            $reportes = $caja->getReportesAll();

            $this->frameview("caja/reportes/list",array(
                "reportes"=>$reportes
            ));
        }else{

        }
    }

}