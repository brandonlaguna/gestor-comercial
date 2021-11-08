<?php
class DashboardController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        //models
        $detalleventa = new DetalleVenta($this->adapter);
        $venta = new Ventas($this->adapter);
        $sucursal = new Sucursal($this->adapter);

        //iniciadores
        //today
        $todaySales=[];
        $totalTodaySales=0;
        $totalTodayBruto=0;
        $totalTodayImpuesto = 0;
        $totalTodayArticulo =0;
        $todayDate= date("Y-m-d");
        //week
        $weekSales=[];
        $actualWeek =date("W");
        $totalWeekSales = 0;
        $totalWeekBruto=0;
        $totalWeekImpuesto=0;
        $totalWeekArticulo=0;
        $totalWeekVentas=0;
        //month
        $monthSales=[];
        $actualMonth =date("m");
        $totalMonthSales = 0;
        $totalMonthBruto=0;
        $totalMonthImpuesto=0;
        $totalMonthArticulo=0;
        $totalMonthVentas=0;
        //overall
        $overallSales=[];
        $overallTotal = 0;
        $overallBruto=0;
        $overallImpuesto=0;
        $overallArticulo=0;
        $overallVentas=0;
        //
        $actualYear =date("Y");
        //functiones
        $detalleventas = $detalleventa->getDetalleAll();
        foreach ($detalleventas as $detalle) {
           if($detalle->fecha == $todayDate){
                $totalTodaySales += $detalle->precio_total_lote;
                $totalTodayBruto += $detalle->precio_venta;
                $totalTodayImpuesto += $detalle->iva_compra;
                $totalTodayArticulo+=$detalle->cantidad;
           }
        }
        //array 0 para datos 'Today'
        $todaySales[] = array(
            "total"=>$totalTodaySales,
            "total_bruto" =>$totalTodayBruto-$totalTodayImpuesto,
            "total_impuestos"=>$totalTodayImpuesto,
            "total_articulo"=>$totalTodayArticulo,
        );
        //actual week
        foreach ($detalleventas as $week) {
            $number_week = date("W", strtotime($week->fecha));
            if($number_week == $actualWeek){
                $totalWeekSales += $week->precio_total_lote;
                $totalWeekBruto += $week->precio_venta;
                $totalWeekImpuesto += $week->iva_compra;
                $totalWeekArticulo += $week->cantidad;
            }
        }
        $ventas = $venta->getVentasAll();
        
        foreach ($ventas as $ventaweek) {
            $number_week = date("W", strtotime($ventaweek->fecha));
            if($number_week == $actualWeek){
                $totalWeekVentas ++;
            }
        }

        //array 1 para datos 'Week'
        $weekSales[] = array(
            "total"=>$totalWeekSales,
            "total_bruto" =>$totalWeekBruto-$totalWeekImpuesto,
            "total_impuestos"=>$totalWeekImpuesto,
            "total_articulo"=>$totalWeekArticulo,
            "total_ventas"=>$totalWeekVentas,
        ); 

        //actual month
        foreach ($detalleventas as $month) {
            $number_month = date("m", strtotime($week->fecha));
            if($number_month == $actualMonth){
                $totalMonthSales += $month->precio_total_lote;
                $totalMonthBruto += $month->precio_venta;
                $totalMonthImpuesto += $month->iva_compra;
                $totalMonthArticulo += $month->iva_compra;
                
            }
        }
        foreach ($ventas as $ventaMonth) {
            $number_month = date("m", strtotime($week->fecha));
            if($number_month == $actualMonth){
                $totalMonthVentas++;
            }
        }
        //array 2 para 'month'
        $monthSales[] = array(
            "total"=>$totalMonthSales,
            "total_bruto" =>$totalMonthBruto-$totalMonthImpuesto,
            "total_impuestos"=>$totalMonthImpuesto,
            "total_articulo"=>$totalMonthArticulo,
            "total_ventas"=>$totalMonthVentas,
        ); 

        //overall
        foreach ($detalleventas as $overall) {
            $overallTotal += $overall->precio_total_lote;
            $overallBruto += $overall->precio_venta;
            $overallImpuesto += $overall->iva_compra;
            $overallArticulo += $overall->iva_compra;
        }
        foreach ($ventas as $overall) {
                $overallVentas++;
        }
        //array 3 para 'overall'
        $overallSales[]=array(
            "total"=>$overallTotal,
            "total_bruto" =>$overallBruto-$overallImpuesto,
            "total_impuestos"=>$overallImpuesto,
            "total_articulo"=>$overallArticulo,
            "total_ventas"=>$overallVentas,
        );
        ################################### monitoreo de la empresa por sucursales
        $sucursales = $sucursal->getSucursalAll();
        $dataEmpresa = [];
        $ventaGlobal=0;
        $ventasG = $venta->getVentas();

        foreach ($ventasG as $global) {
            if($global){
                $number_month = date("m", strtotime($global->fecha));
                if($number_month == $actualMonth){
                    $ventaGlobal+=$global->total;
                }
            }
        }

        foreach ($sucursales as $sucursales) {
            $dataSucursal=[];
            $totalVentas=0;
            $ventassucursales = $venta->getVentasBySucursal($sucursales->idsucursal);
            foreach ($ventassucursales as $ventassucursal) {
                if($ventassucursal){
                    $number_month = date("m", strtotime($ventassucursal->fecha));
                if($number_month == $actualMonth){
                    $totalVentas+= $ventassucursal->total; 
                }
                }
            }
            
            $color= random_color();
            $dataSucursal[]=array(
                "idsucursal"=>$sucursales->idsucursal,
                "razon_social"=>$sucursales->razon_social,
                "total_ventas"=>$totalVentas,
                "porcentaje"=> bcdiv(($totalVentas / $ventaGlobal)*100,'1',1),
                "color"=>$color,
            );
            $dataEmpresa[] = $dataSucursal;

        }
        ################################## monitoreo de ventas pendientes
        $dataDeudaVenta =[];
        $cartera = new Cartera($this->adapter);
            $carteras = $cartera->getCreditoClienteAll();
            $pago = 0;
            $por_pagar = 0;
            $vencidas = 0;
            $total=0;
            
        foreach ($carteras as $calculo) {
            if($calculo){
                $year = date("Y", strtotime($calculo->fecha));
                if($year == $actualYear){
                    $pago += $calculo->total_pago;
                    $por_pagar += ($calculo->deuda_total - $calculo->total_pago);
                    $total += $calculo->deuda_total;
                        if($calculo->fecha_pago < date("Y-m-d")){
                            $vencidas +=($calculo->deuda_total - $calculo->total_pago);
                        }
            }
            }
        }

        $dataDeudaVenta[0] = bcdiv(($vencidas / $total)*100,'1',1);
        $dataDeudaVenta[1] = bcdiv(($pago / $total)*100,'1',1);
        ################################ monitoreo de compras pendientes
        $cartera = new Cartera($this->adapter);
        $carteras = $cartera->getCreditoProveedorAll();

        $dataDeudaCompra =[];
        $pago = 0;
        $por_pagar = 0;
        $vencidas = 0;
        $total=0;
        foreach ($carteras as $calculo) {
            $year = date("Y", strtotime($calculo->fecha));
            if($year == $actualYear){
                $pago += $calculo->total_pago;
                $por_pagar += ($calculo->deuda_total - $calculo->total_pago);
                $total += $calculo->deuda_total;
                if($calculo->fecha_pago < date("Y-m-d")){
                    $vencidas +=($calculo->deuda_total - $calculo->total_pago);
                }
            }
        } 
        $dataDeudaCompra[0] = bcdiv(($vencidas / $total)*100,'1',1);
        $dataDeudaCompra[1] = bcdiv(($pago / $total)*100,'1',1);

        ################################# historial de pago cartera cliente

        $cartera = new Cartera($this->adapter);
        $historialPagoCliente = $cartera->getPagoCarteraAll(5);

        ################################ detalle de articulo mas vendido
        $bestSell = $detalleventa->getBestSellArticulo(3);


        $this->frameview("dashboard/index",array(
            "todaySales"=>$todaySales,
            "weekSales"=>$weekSales,
            "monthSales"=>$monthSales,
            "overallSales"=>$overallSales,
            "dataEmpresa"=>$dataEmpresa,
            "dataDeudaVenta"=>$dataDeudaVenta,
            "dataDeudaCompra" =>$dataDeudaCompra,
            "historialPagoCliente"=> $historialPagoCliente,
            "bestSell"=>$bestSell,
        ));
    }
 
}
