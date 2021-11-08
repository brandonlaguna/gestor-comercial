<?php
class InformeController extends ControladorBase{
    public $conectar;
	public $adapter;
	
    public function __construct() {
        parent::__construct();
		 
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        
    }

    public function index()
    {
    }

    public function kardex()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $articulo = new Articulo($this->adapter);
            $articulos = $articulo->getArticuloAll();
            $pos= "reportekardex";
            $control="informe";
            $this->frameview("informe/kardex/kardex",array(
                "articulos"=>$articulos,
                "pos"=>$pos,
                "control"=>$control
            ));
        }else{
            echo "Forbidden gateway";
        }
    }

    public function reportekardex()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            if(isset($_POST)){
                
                $idarticulo = (isset($_POST["idarticulo"]) && !empty($_POST["idarticulo"]))?$_POST["idarticulo"]:false;
                $start_date = (isset($_POST["start_date"]) && !empty($_POST["start_date"]))?date_format_calendar($_POST["start_date"],"/"):false;
                $end_date = (isset($_POST["end_date"]) && !empty($_POST["end_date"]))?date_format_calendar($_POST["end_date"],"/"):false;
                if($idarticulo){
                    
                    //models
                    $articulo = new Articulo($this->adapter);
                    $compra = new Compras($this->adapter);
                    $detallecompra = new DetalleIngreso($this->adapter);
                    $venta = new Ventas($this->adapter);
                    $detalleventa = new DetalleVenta($this->adapter);
                    //functions
                    $listCompra=[];
                    $detailCompra=[];
                    $detailVenta=[];
                    $detailKardex=[];
                    $detailSaldo=[];
                    $compras = $compra->getDetalleComprasByDay($start_date,$end_date,'a.idarticulo',$idarticulo);
                    $ventas = $venta->getDetalleVentasByDay($start_date,$end_date,'a.idarticulo',$idarticulo);
                    $comprasAll = $detallecompra->getDetalleAllByDay();
                    $ventasAll = $detalleventa->getDetalleAll();

                    //calculos extras
                    $total_stock_compra =0;
                    $total_precio_compra =0;
                    $promedio_precio_compra_actual =0; 
                    foreach ($compras as $calc_compra) {
                        $total_stock_compra += $calc_compra->stock_total_compras;
                        $total_precio_compra += $calc_compra->precio_total_compras;
                        $promedio_precio_compra_actual += ($calc_compra->precio_total_compras /$calc_compra->stock_total_compras);
                    }
                    $detailCompra[] = array(
                        $total_stock_compra,
                        $total_precio_compra,
                    );
                    $total_stock_venta =0;
                    $total_precio_venta =0;
                    foreach ($ventas as $calc_venta) {
                        $total_stock_venta +=  $calc_venta->stock_total_ventas;
                        $total_precio_venta += $calc_venta->precio_total_ventas;
                    }
                    $detailVenta[] =array(
                        $total_stock_venta,
                        $total_precio_venta,
                    );


                    ################## saldo anterior
                    $mes_anterior = date("m",strtotime($start_date)) - 1;
                    $stock_anterior = 0;
                    $precio_compra_anterior = 0;
                    $promedio_precio_compra_anterior =0;
                    $precio_venta_anterior =0;
                    foreach ($comprasAll as $compra_anterior){
                        $mes_compra = date("m",strtotime($compra_anterior->fecha));
                        if($mes_compra == $mes_anterior && $compra_anterior->idarticulo == $idarticulo){
                            $stock_anterior += $compra_anterior->stock_total_compras;
                            $precio_compra_anterior  += $compra_anterior->precio_total_lote * $compra_anterior->stock_total_compras ; ##junto con impuesto
                        }
                    }
                    $promedio_precio_compra_anterior =$stock_anterior?$precio_compra_anterior / $stock_anterior:$precio_compra_anterior;

                    foreach ($ventasAll as $venta_anterior){
                        $mes_venta = date("m",strtotime($venta_anterior->fecha));
                        
                        if($mes_venta == $mes_anterior && $venta_anterior->idarticulo == $idarticulo){
                            $stock_anterior -= $venta_anterior->cantidad;
                            $precio_venta_anterior += $venta_anterior->precio_total_lote;
                            $precio_compra_anterior -= $promedio_precio_compra_anterior;
                            
                        }
                    }
                    $salida_consecutiva = $total_stock_compra + $stock_anterior;
                    $precio_anterior_descendiendo = $precio_compra_anterior;


                    foreach ($ventas as $calc_venta2) {
                        $salida_consecutiva -= $calc_venta2->stock_total_ventas;
                        $detailSaldo[]=array(  
                            $salida_consecutiva,
                            $precio_compra_anterior + $calc_venta2->stock_total_ventas ,
                        );
                    }
                    // echo "stock mes anterior: $".$stock_anterior."</br>";
                    // echo "promedio precio compra mes anterior: $".number_format($promedio_precio_compra_anterior,2,'.',',')."</br>";
                    // echo "total precio compra mes anterior: $".$precio_compra_anterior;

                    $this->frameview("informe/kardex/tableKardex",array(
                        "listCompra"=>$compras,
                        "detailCompra"=>$detailCompra,
                        "listVenta" => $ventas,
                        "detailVenta"=>$detailVenta,
                        "listSaldo"=>$detailSaldo,
                    ));

                }else{}
            }else{}
        }else{
            echo "Forbidden gateway";
        }
    }

    public function balance_comprobacion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){

            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idreporte = $_GET["data"];
                $reportecontable = new ReporteContable($this->adapter);
                $comprobantecontable = new ComprobanteContable($this->adapter);
                $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
                $getreporte= $reportecontable->getReporteConableById($idreporte);
                foreach ($getreporte as $getreporte) {}
                if($getreporte){

                $comprobantes = $comprobantecontable->reporte_detallado_comprobante_por_cuenta($getreporte->rcc_start_date,$getreporte->rcc_end_date,$getreporte->rcc_param1,$getreporte->rcc_param2);
                $start_last_mont =date("Y-m-d",strtotime($getreporte->rcc_start_date."- 1 month"));
                $end_last_mont =date("Y-m-d",strtotime($getreporte->rcc_end_date."- 1 month"));
                $instance =[];

                foreach($comprobantes as $comprobantes){
                    //llamar la misma query pero limitando los codigos solo al actual en el array
                        $total_cuenta = $comprobantecontable->reporte_total_comprobante_por_cuenta($start_last_mont,$end_last_mont,$comprobantes->dcc_cta_item_det,$comprobantes->dcc_d_c_item_det);
                        foreach ($total_cuenta as $total_cuenta) {}

                    echo json_encode(array(
                        $total_cuenta->total_cuenta,
                        $comprobantes->dcc_cta_item_det,
                        $comprobantes->debito,
                        $comprobantes->credito
                    ));
                }


                }else{
                    echo "Este reporte no existe";
                }

            }else{
                $puc = new PUC($this->adapter);

                $cuentas= $puc->getAllPucBy("movimiento",1);
                $rcc_type ="BC";
                $this->frameview("informe/balance_comprobacion/index",array(
                    "cuentas"=>$cuentas,
                    "rcc_type"=>$rcc_type
                ));
            }
        }
    }

    public function movimiento_cuenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idreporte = $_GET["data"];
                $reportecontable = new ReporteContable($this->adapter);
                $comprobantecontable = new ComprobanteContable($this->adapter);
                $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
                $getreporte= $reportecontable->getReporteConableById($idreporte);
                foreach ($getreporte as $getreporte) {}
                if($getreporte){

                $comprobantes = $comprobantecontable->reporte_detallado_comprobante_por_cuenta($getreporte->rcc_start_date,$getreporte->rcc_end_date,$getreporte->rcc_param1,$getreporte->rcc_param2);
                $start_last_mont =date("Y-m-d",strtotime($getreporte->rcc_start_date."- 1 month"));
                $end_last_mont =date("Y-m-d",strtotime($getreporte->rcc_end_date."- 1 month"));
                $instance =[];

                foreach($comprobantes as $comprobantes){
                    $subinstance=[];
                    //llamar la misma query pero limitando los codigos solo al actual en el array
                        $total_cuenta = $comprobantecontable->reporte_total_comprobante_por_cuenta($start_last_mont,$end_last_mont,$comprobantes->dcc_cta_item_det,$comprobantes->dcc_d_c_item_det);
                        foreach ($total_cuenta as $total_cuenta) {}
                        //recuperar todas los comprobantes con esta cuenta
                            $registros = $comprobantecontable->reporte_general_comprobante_por_cuenta($getreporte->rcc_start_date,$getreporte->rcc_end_date,$comprobantes->dcc_cta_item_det);
                            foreach ($registros as $registro) {
                                $subinstance[]=array(
                                    "comprobante"=>$registro->cc_num_cpte,
                                    "consecutivo"=>$registro->cc_cons_cpte,
                                    "debito"=>$registro->debito,
                                    "credito"=>$registro->credito
                                );
                            }
                    $arr= json_encode(array(
                        "cuenta"=>$comprobantes->dcc_cta_item_det,
                        "values"=>$subinstance
                    ));
                    echo $comprobantes->dcc_cta_item_det."</br>";
                foreach ($subinstance as $subinstance) {
                    echo $subinstance["comprobante"].$subinstance["consecutivo"]." D:".$subinstance["debito"]. "C:".$subinstance["credito"]."</br>";
                }
                }
                
            }
            }else{
                $puc = new PUC($this->adapter);
                $cuentas= $puc->getAllPucBy("movimiento",1);
                $rcc_type ="MC";
                $this->frameview("informe/balance_comprobacion/index",array(
                    "cuentas"=>$cuentas,
                    "rcc_type"=>$rcc_type
                ));
            }
        }else{}
    }

    public function gen_balance_comprobacion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $alert="";
            if(isset($_POST)){
            $param1 = ($_POST["cuenta_desde"])?$_POST["cuenta_desde"]:false; 
            $param2 = ($_POST["cuenta_hasta"])?$_POST["cuenta_hasta"]:false;
            $filetype = (isset($_POST["filetype"]) && !empty($_POST["filetype"]))?$_POST["filetype"]:'pdf';
            $param3= 0;
            $rcc_type = ($_POST["rcc_type"])?$_POST["rcc_type"]:"BC";
            $start_date = ($_POST["start_date"])?date_format_calendar($_POST["start_date"],"/"):false; 
            $end_date = ($_POST["end_date"])?date_format_calendar($_POST["end_date"],"/"):false; 
            if($start_date && $end_date){
                $reportecontable = new ReporteContable($this->adapter);

                $reportecontable->setRcc_title("Reporte");
                $reportecontable->setRcc_idsucursal($_SESSION["idsucursal"]);
                $reportecontable->setRcc_param1($param1);
                $reportecontable->setRcc_param2($param2);
                $reportecontable->setRcc_param3($param3);
                $reportecontable->setRcc_type($rcc_type);
                $reportecontable->setRcc_filetype($filetype);
                $reportecontable->setRcc_start_date($start_date);
                $reportecontable->setRcc_end_date($end_date);
                $idreporte= $reportecontable->addReport();

                $alert= [
                    'typealert'     => 'toast',
                    'message'       => 'Reporte creado correctamente',
                    'title'         => 'Realizado',
                    'alert'         => 'success',
                    "redirect"      =>"#file/informe/$idreporte"
                ];
            }else{
                $alert=array("title"=>"error","message"=>"Las fechas son obligatorias","alert"=>"error");
            }
            }else{
                $alert=array("title"=>"error","message"=>"Hay datos obligatorios que no pasaron la consulta","alert"=>"error");
            }
        }else{$alert=array("title"=>"error","message"=>"Error inesperado","alert"=>"error");}

        echo json_encode($alert);
    }

    

    public function gen_movimiento_cuenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $alert="";
            if(isset($_POST)){
                $param1 = ($_POST["cuenta_desde"])?$_POST["cuenta_desde"]:false; 
                $param2 = ($_POST["cuenta_hasta"])?$_POST["cuenta_hasta"]:false;
                $param3= 0;
                $start_date = ($_POST["start_date"])?date_format_calendar($_POST["start_date"],"/"):false; 
                $end_date = ($_POST["end_date"])?date_format_calendar($_POST["end_date"],"/"):false; 
                if($start_date && $end_date){
                    $alert=array("title"=>"success","message"=>"pasaron los datos","alert"=>"success");
                }
            }else{
                $alert=array("title"=>"error","message"=>"Hay datos obligatorios que no pasaron la consulta","alert"=>"error");
            }
        }
    }
    


}