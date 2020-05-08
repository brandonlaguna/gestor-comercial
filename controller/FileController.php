<?php
class FileController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
       
    }

    public function venta()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                
                $idventa = $_GET["data"];
                //configuracion para el modo de visualizacion
                $view = (isset($_GET["s"])&&!empty($_GET["s"]))?$_GET["s"]:"";
                $file_height = (isset($view))?"100%":"92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"]))?$_GET["t"]:"";
                //recuperando la venta por id
                $ventas = new Ventas($this->adapter);
                $venta = $ventas->getVentaById($idventa);
                if($venta){
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach($venta as $data){}
                //ecuperando la sucursal por factura de venta
                $sucursales = new Sucursal($this->adapter);
                $sucursal = $sucursales->getSucursalById($data->idsucursal);
               
                //traer la vista del tipo de impresion que se aplica a este comprobante
                $funcion = $data->pri_conf."_venta";
                //configuracion solo para desarrollo
                //$location = "http://127.0.0.1/app";
                $location = LOCATION_CLIENT;
                

              $this->frameview("file/pdf/".$data->pri_nombre,array(
                   "file_height"=>$file_height,
                   "conf_print"=>$conf_print,
                   "venta"=>$venta,
                   "sucursal"=>$sucursal,
                   "funcion"=>$funcion,
                   "id"=>$idventa,
                   "url"=>$location
               ));
                }else{
                    echo "Factura no disponible";
                }
            }else{
                
            }
        }
    }

    public function ventaContable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            
        }
    }



    public function caja()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                
                $idreporte = $_GET["data"];
                $view = (isset($_GET["s"])&&!empty($_GET["s"]))?$_GET["s"]:"";
                $file_height = (isset($view))?"100%":"92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"]))?$_GET["t"]:"";

                //recuperando la venta por id
                $caja = new ReporteCaja($this->adapter);
                $reporte = $caja->getReporteById($idreporte);
                if($reporte){
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach($reporte as $data){}
                //ecuperando la sucursal por factura de venta
                $sucursales = new Sucursal($this->adapter);
                $sucursal = $sucursales->getSucursalById($data->rc_idsucursal);
               
                //traer la vista del tipo de impresion que se aplica a este comprobante
                $funcion = "cierre_caja";
                //configuracion solo para desarrollo
                //$location = "http://127.0.0.1/app";
                $location = LOCATION_CLIENT;
                

              $this->frameview("file/pdf/reporteCaja",array(
                    "file_height"=>$file_height,
                    "con_print"=>$conf_print,
                   "sucursal"=>$sucursal,
                   "funcion"=>$funcion,
                   "id"=>$idreporte,
                   "url"=>$location
               ));
                }else{
                    echo "Reporte no disponible";
                }
            }else{
                
            }
        }
    }

    public function factura_venta()
    {

        if(isset($_GET["data"]) && !empty($_GET["data"])){
            require_once 'lib/printpdf/fpdf.php';
                $cifrasEnLetras = new CifrasEnLetras();
                $pieFactura = new PieFactura($this->adapter);
                $idventa = $_GET["data"];
                //recuperando la venta por id
                $ventas = new Ventas($this->adapter);
                $venta = $ventas->getVentaById($idventa);
                if($venta){
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach($venta as $data){}
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
                $resp=[];
                $resp2=[];
                $pdf = new FPDF();
                $pdf->SetTitle("Factura de venta ".$data->serie_comprobante.zero_fill($data->num_comprobante,8)." ".$data->fecha." Cliente ".$data->nombre_cliente);
                $pdf->AddPage('L','A4');

                $x =10; 
                $y =0;
                $pdf->Image(LOCATION_CLIENT.$sucursal->logo_img,30,12,36);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(0,6,$sucursal->razon_social,0,1,'C');
                $pdf->SetFont('Arial','',8.5);
                $pdf->Cell(0,0,$sucursal->prefijo_documento." ".$sucursal->num_documento,0,1,'C');
                $pdf->Cell(0,6,$sucursal->direccion,0,1,'C');
                $pdf->Cell(0,0,"Tel: ".$sucursal->telefono,0,1,'C');
                $pdf->Cell(0,5,$empresa->pais." - ".$empresa->ciudad,0,1,'C');
                $pdf->Cell(0,1,$sucursal->email,0,1,'C');
                $pdf->SetY(12);
                $pdf->SetX(230);
                $pdf->Cell(55,16,"FACT. VENTA No.".$data->serie_comprobante.zero_fill($data->num_comprobante,8),1,0,'C');
                $tercero = array('Proveedor:', $data->nombre_cliente);
                $contacto = array('NIT:',$data->num_documento,"Telefono:",$data->telefono_cliente);
                $ubicacion = array("Direccion:",$data->direccion_calle,"Ciudad:",$data->direccion_provincia);
                $infofecha = array("Fecha de Factura","Fecha de Vencimiento");
                $date = array($data->fecha,$data->fecha_final);
                //output
                $pdf->SetFont('Arial','',7);
                $pdf->SetY(38);
                $pdf->SetX(10);
                $pdf->FancyTable($tercero,$resp);
                $pdf->SetY(45);
                $pdf->SetX(10);
                $pdf->FancyTable($contacto,$resp);
                $pdf->SetY(52.5);
                $pdf->SetX(10);
                $pdf->FancyTable($ubicacion,$resp);
                $pdf->SetY(38);
                $pdf->SetX(210);
                $pdf->DateTable($infofecha,$resp);
                $pdf->SetY(45);
                $pdf->SetX(210);
                $pdf->DateTable($date,$resp);

                //BODY
                //TABLE HEAD
                foreach ($articulos as $articulo) {
                    $resp2[] = array(
                        $articulo->idarticulo,
                        $articulo->nombre_articulo,
                        $articulo->cantidad,
                        $articulo->precio_unitario,
                        $articulo->iva_compra,
                        $articulo->precio_total_lote
                    );
                }
                $tablehead = array("Codigo","Producto","Cantidad","Precio U.","IVA","Total");
                $pdf->SetY(65);
                $pdf->SetX(10);
                $pdf->FancyTable($tablehead,$resp2);
                //TABLE BODY
                $dataretenciones = new Retenciones($this->adapter);
                $retenciones = $dataretenciones->getRetencionesByComprobanteId($data->iddetalle_documento_sucursal);

                $dataimpuestos= new Impuestos($this->adapter);
                $impuestos = $dataimpuestos->getImpuestosByComprobanteId($data->iddetalle_documento_sucursal);

                //$totalcart = new DetalleIngreso($this->adapter);
                $subtotal = $dataarticulos->getSubTotal($data->idventa);
                $totalimpuestos = $dataarticulos->getImpuestos($data->idventa);

             //obter subtotal
            foreach ($subtotal as $subtotal) {}
            //valores a imprimir
            $subtotalimpuesto = 0;
            $listImpuesto = [];
            $listRetencion =[];
            $total_bruto = $subtotal->cdi_debito;
            $total_neto = $subtotal->cdi_debito;
            //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
            foreach ($totalimpuestos as $imp) {
                $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                foreach($impuestos as $data2){}
                if($impuestos){
                   
                    $total_neto -= $subtotalimpuesto;
                    $total_bruto -= $subtotalimpuesto;
                
                }else{
                    
                }
                
                foreach ($impuestos as $impuesto) {
                    if($imp->cdi_importe == $impuesto->im_porcentaje){
                        //calculado
                        $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                        //concatenacion del nombre
                        $im_nombre = $impuesto->im_nombre." ".$impuesto->im_porcentaje."%";
                        //arreglo
                        $listImpuesto[] = array($im_nombre,$calc);
                        /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                        $total_neto += $calc;
                    }else{
                       
                    }
                }
            }
                foreach ($retenciones as $retencion) {
                
                    if($retencion->re_im_id <= 0){
                        //concatenacion del nombre
                        $re_nombre = $retencion->re_nombre." ".$retencion->re_porcentaje."%";
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
                            $re_nombre = $retencion->re_nombre." (".$retencion->re_porcentaje."%)";
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
            $totalenletras =$cifrasEnLetras->convertirNumeroEnLetras($total_neto);
            $text_resolucion = explode('|', $res->pf_text);
            $pdf->SetFont('Arial','',8);
            $pdf->SetTextColor(20,20,20);
            $pdf->SetY(154);
            $pdf->SetX(-350);
            $pdf->Cell(0,10,"Valor en letras: ".$totalenletras." Pesos colombianos",1,0,'C');
            $ry=154;
            $i=0;
            foreach ($text_resolucion as $content) {
                $pdf->SetY($ry+=3);
                $pdf->SetX(-350);
                $pdf->Cell(0,10,utf8_decode($text_resolucion[$i]),1,0,'C');
                $i++;
            }
            $y=160;
            $x=205;
            $pdf->SetFont('Arial','',7);
            $pdf->SetY($y);
            $pdf->SetX($x);
            //$pdf->Cell(10,10,"SUBTOTAL:".number_format($total_bruto),0,1,'C');
            $subtotal = array("SUBTOTAL:", "$".number_format($total_bruto,2,'.',','));
            $pdf->DateTable($subtotal,$resp);
            foreach($listImpuesto as $listImpuesto){
            $pdf->SetY($y+=7);
            $pdf->SetX($x);
            $impuestos=array($listImpuesto[0].":","$".number_format($listImpuesto[1],2,'.',',')); 
            $pdf->DateTable($impuestos,$resp);
            }
            foreach ($listRetencion as $listRetencion) {
                $pdf->SetY($y+=7);
                $pdf->SetX($x);
                $retenciones=array($listRetencion[0],"$".number_format($listRetencion[1],2,'.',',')); 
                $pdf->DateTable($retenciones,$resp);
            }
            $pdf->SetY($y+=7);
            $pdf->SetX($x);
            $total=array("TOTAL:","$".number_format($total_neto,2,'.',','));
            $pdf->DateTable($total,$resp);
            
            
            $pdf->AutoPrint();
            




                $pdf->Output($data->serie_comprobante.zero_fill($data->num_comprobante,8)." ".$data->fecha." Cliente ".$data->nombre_cliente.".pdf","I");

        }else{
            echo "Forbidden Gateway";
        }
    }
    }

    public function ingreso()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                
                $idcompra = $_GET["data"];
                //configuracion para el modo de visualizacion
                $view = (isset($_GET["s"])&&!empty($_GET["s"]))?$_GET["s"]:"";
                $file_height = (isset($view))?"100%":"92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"]))?$_GET["t"]:"";
            
                //recuperando la venta por id
                $compras = new Compras($this->adapter);
                $compra = $compras->getCompraById($idcompra);
                if($compra){
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach($compra as $data){}
                //recuperando la sucursal por factura de venta
                $sucursales = new Sucursal($this->adapter);
                $sucursal = $sucursales->getSucursalById($data->idsucursal);
               
                //traer la vista del tipo de impresion que se aplica a este comprobante
                $funcion = $data->pri_conf."_compra";
                //configuracion solo para desarrollo
                //$location = "http://127.0.0.1/app";
                $location = LOCATION_CLIENT;

              $this->frameview("file/pdf/".$data->pri_nombre,array(
                "file_height"=>$file_height,
                "conf_print"=>$conf_print,
                "venta"=>$compra,
                "sucursal"=>$sucursal,
                "funcion"=>$funcion,
                "id"=>$idcompra,
                "url"=>$location
               ));
                }else{
                    echo "Factura no disponible";
                }
            }else{
                
            }
        }
    }

    public function ingresoContable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcompra = $_GET["data"];
                $view = (isset($_GET["s"])&&!empty($_GET["s"]))?$_GET["s"]:"";
                $file_height = (isset($view))?"100%":"92.4%";
                $conf_print = (isset($_GET["t"]) && !empty($_GET["t"]))?$_GET["t"]:"";
                $compracontable = new ingresoContable($this->adapter);
                $sucursales = new Sucursal($this->adapter);
                $compra = $compracontable->getCompraById($idcompra);
                if($compra){
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                    foreach ($compra as $data) {}
                    //recuperando la sucursal por medio de la compra
                    $sucursal = $sucursales->getSucursalById($data->ic_ccos_cpte);
                    //traer la vista del tipo de impresion que se aplica a este comprobante
                    $funcion = $data->pri_conf."_compraContable";
                    //configuracion solo para desarrollo
                    //$location = "http://127.0.0.1/app";
                    $location = LOCATION_CLIENT;
                    //recuperar vista inicial
                    $this->frameview("file/pdf/".$data->pri_nombre,array(
                        "file_height"=>$file_height,
                        "conf_print"=>$conf_print,
                        "venta"=>$compra,
                        "sucursal"=>$sucursal,
                        "funcion"=>$funcion,
                        "id"=>$idcompra,
                        "url"=>$location,
                        "view"=>$view
                       ));
                }else{
                    echo "Factura no disponible";
                }
            }else{
            }
        }
    }

    public function factura_compra()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
        if(isset($_GET["data"]) && !empty($_GET["data"])){
            require_once 'lib/printpdf/fpdf.php';
            $idcompra = $_GET["data"];
                ######## clases
                //recuperando la venta por id
                $compras = new Compras($this->adapter);
                $pdf = new FPDF();
                $compra = $compras->getCompraById($idcompra);
                if($compra){
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach($compra as $data){}
                $dataarticulos = new DetalleIngreso($this->adapter);
                $articulos = $dataarticulos->getArticulosByCompra($idcompra);
                //ecuperando la sucursal por factura de venta
                $sucursales = new Sucursal($this->adapter);
                $sucursal = $sucursales->getSucursalById($data->idsucursal);
                $global = new sGlobal($this->adapter);
                $empresa = $global->getGlobal();
                foreach ($sucursal as $sucursal) {}
                foreach ($empresa as $empresa) {}
                $resp=[];
                $resp2=[];
                foreach ($articulos as $articulo) {
                    $resp2[] = array(
                        $articulo->idarticulo,
                        $articulo->nombre_articulo,
                        $articulo->stock_ingreso,
                        $articulo->precio_compra,
                        $articulo->iva_compra,
                        $articulo->precio_total_lote
                    );
                }

                $pdf->SetTitle("Factura de compra ".$data->serie_comprobante.zero_fill($data->num_comprobante,8)." ".$data->fecha." Proveedor ".$data->nombre_proveedor);
                $pdf->AddPage('L','A4');
                //header
                $x =10;
                $y =0;
                $pdf->Image(LOCATION_CLIENT.$sucursal->logo_img,30,12,36);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(0,6,$sucursal->razon_social,0,1,'C');
                $pdf->SetFont('Arial','',8.5);
                $pdf->Cell(0,0,$sucursal->prefijo_documento." ".$sucursal->num_documento,0,1,'C');
                $pdf->Cell(0,6,$sucursal->direccion,0,1,'C');
                $pdf->Cell(0,0,"Tel: ".$sucursal->telefono,0,1,'C');
                $pdf->Cell(0,5,$empresa->pais." - ".$empresa->ciudad,0,1,'C');
                $pdf->Cell(0,1,$sucursal->email,0,1,'C');
                $pdf->SetY(12);
                $pdf->SetX(230);
                $pdf->Cell(55,16,"REG. COMPRA No.".zero_fill($data->num_comprobante,8),1,0,'C');
                $tercero = array('Proveedor:', $data->nombre_proveedor);
                $contacto = array('NIT:',$data->num_documento,"Telefono:",$data->telefono_proveedor);
                $ubicacion = array("Direccion:",$data->direccion_calle,"Ciudad:",$data->direccion_provincia);
                $infofecha = array("Fecha de Factura","Fecha de Vencimiento");
                $date = array($data->fecha,$data->fecha_final);
                //output
                $pdf->SetFont('Arial','',7);
                $pdf->SetY(38);
                $pdf->SetX(10);
                $pdf->FancyTable($tercero,$resp);
                $pdf->SetY(45);
                $pdf->SetX(10);
                $pdf->FancyTable($contacto,$resp);
                $pdf->SetY(52.5);
                $pdf->SetX(10);
                $pdf->FancyTable($ubicacion,$resp);
                $pdf->SetY(38);
                $pdf->SetX(210);
                $pdf->DateTable($infofecha,$resp);
                $pdf->SetY(45);
                $pdf->SetX(210);
                $pdf->DateTable($date,$resp);

                //tabla de articulos
                $tablehead = array("Codigo","Producto","Cantidad","Precio U.","IVA","Total");
                $pdf->SetY(65);
                $pdf->SetX(10);
                $pdf->FancyTable($tablehead,$resp2);

            $dataretenciones = new Retenciones($this->adapter);
            $retenciones = $dataretenciones->getRetencionesByComprobanteId($data->iddetalle_documento_sucursal);

            $dataimpuestos= new Impuestos($this->adapter);
            $impuestos = $dataimpuestos->getImpuestosByComprobanteId($data->iddetalle_documento_sucursal);

            //$totalcart = new DetalleIngreso($this->adapter);
            $subtotal = $dataarticulos->getSubTotal($data->idingreso);
            $totalimpuestos = $dataarticulos->getImpuestos($data->idingreso);
            
            //obter subtotal
            foreach ($subtotal as $subtotal) {}
            //valores a imprimir
            $subtotalimpuesto = 0;
            $listImpuesto = [];
            $listRetencion =[];
            $total_bruto = $subtotal->cdi_debito;
            $total_neto = $subtotal->cdi_debito;
            //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
            foreach ($totalimpuestos as $imp) {
                $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                foreach($impuestos as $data2){}
                if($impuestos){
                   if($data2->im_porcentaje == $imp->cdi_importe){
                    $total_neto -= $subtotalimpuesto;
                    $total_bruto -= $subtotalimpuesto;
                   }
                   else{

                   }
                }else{
                
                }
                
                foreach ($impuestos as $impuesto) {
                    if($imp->cdi_importe == $impuesto->im_porcentaje){
                        //calculado
                        $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                        //concatenacion del nombre
                        $im_nombre = $impuesto->im_nombre." ".$impuesto->im_porcentaje."%";
                        //arreglo
                        $listImpuesto[] = array($im_nombre,$calc);
                        /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                        $total_neto += $calc;
                    }else{
                       
                    }
                }
            }
                foreach ($retenciones as $retencion) {
                
                    if($retencion->re_im_id <= 0){
                        //concatenacion del nombre
                        $re_nombre = $retencion->re_nombre." ".$retencion->re_porcentaje."%";
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
                            $re_nombre = $retencion->re_nombre." (".$retencion->re_porcentaje."%)";
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

            $y=160;
            $x=205;
            $pdf->SetFont('Arial','',6.7);
            $pdf->SetY($y);
            $pdf->SetX($x);
            //$pdf->Cell(10,10,"SUBTOTAL:".number_format($total_bruto),0,1,'C');
            $subtotal = array("SUBTOTAL:", "$".number_format($total_bruto,2,'.',','));
            $pdf->DateTable($subtotal,$resp);
            foreach($listImpuesto as $listImpuesto){
            $pdf->SetY($y+=7);
            $pdf->SetX($x);
            $impuestos=array($listImpuesto[0].":","$".number_format($listImpuesto[1],2,'.',',')); 
            $pdf->DateTable($impuestos,$resp);
            }
            foreach ($listRetencion as $listRetencion) {
                $pdf->SetY($y+=7);
                $pdf->SetX($x);
                $retenciones=array($listRetencion[0],"$".number_format($listRetencion[1],2,'.',',')); 
                $pdf->DateTable($retenciones,$resp);
            }
            $pdf->SetY($y+=7);
            $pdf->SetX($x);
            $total=array("TOTAL:","$".number_format($total_neto,2,'.',','));
            $pdf->DateTable($total,$resp);
            $pdf->AutoPrint();
            $pdf->Output($data->serie_comprobante.zero_fill($data->num_comprobante,8)." ".$data->fecha." Cliente ".$data->nombre_proveedor.".pdf","I");

        }else{
            echo "Forbidden Gateway";
        }
    }
    }else{
        echo "Forbidden Gateway";
    }

    }

    public function factura_compraContable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $conf_print = (isset($_GET["s"]) && !empty($_GET["s"]))?$_GET["s"]:false;
                require_once 'lib/printpdf/fpdf.php';
                $idcompra = $_GET["data"];
                ############## modelos
                $pdf = new FPDF();
                $compracontable = new ingresoContable($this->adapter);
                $dataarticulos = new DetalleIngresoContable($this->adapter);
                $sucursales = new Sucursal($this->adapter);
                $global = new sGlobal($this->adapter);
                $puc = new PUC($this->adapter);
                $articulo = new Articulo($this->adapter);
                $personas = new Persona($this->adapter);
                ############# funciones
                $compra = $compracontable->getCompraById($idcompra);

                foreach($compra as $data){}
                
                $articulos = $dataarticulos->getArticulosByCompra($idcompra);
                //ecuperando la sucursal por factura de venta
                //recuperar sucursal
                $sucursal = $sucursales->getSucursalById($data->ic_ccos_cpte);
                //recuperar empresa 
                $empresa = $global->getGlobal();
                //setear sucursal en variable sucursal
                foreach ($sucursal as $sucursal) {}
                //setear empresa en variable empresa
                foreach ($empresa as $empresa) {}
                //si hay articulos
                $resp=[];
                $resp2=[];
                if($articulos){
                    //recorrer cada articulo y buscar en tablas puc o articulo
                    $subtotal_credito =0;
                    $subtotal_debito =0;
                    $subtotal_cuentas =0;
                    foreach ($articulos as $articulos) {
                        $getPuc = $puc->getPucById($articulos->dic_cta_item_det);
                        $gertArticulo = $articulo->getArticuloById($articulos->dic_cta_item_det);
                        //recuperar el centro de costos que previamente es la sucursal
                        $getCentroCostos = $sucursales->getSucursalById($articulos->dic_ccos_item_det);
                        if($getCentroCostos){
                            foreach ($getCentroCostos as $dataCentroCostos) {}
                        }
                        $centroCostos = (!empty($dataCentroCostos->razon_social))?$dataCentroCostos->razon_social:"No";
                        //setear en variable credito y debito
                        $debito = ($articulos->dic_d_c_item_det=="D"&& $articulos->dic_valor_item > 0)?number_format($articulos->dic_valor_item,2,'.',','):"";
                        $credito = ($articulos->dic_d_c_item_det=="C"&& $articulos->dic_valor_item > 0)?number_format($articulos->dic_valor_item,2,'.',','):"";
                        $subtotal_credito += ($articulos->dic_d_c_item_det=="D"&& $articulos->dic_valor_item > 0)?$articulos->dic_valor_item:0;
                        $subtotal_debito += ($articulos->dic_d_c_item_det=="C"&& $articulos->dic_valor_item > 0)?$articulos->dic_valor_item:0;
                        //buscar el tercero por el numero de documento o id
                        $persona = $personas->getPersonaById($data->ic_idproveedor);
                        foreach ($persona as $tercero) {}

                        if($gertArticulo){
                            foreach ($gertArticulo as $articuloitem) {}
                            $fecha_vcto = ($articulos->dic_fecha_vcto_item !="0000-00-00")?$articulos->dic_fecha_vcto_item:"";
                            $resp2[] = array(
                                $articuloitem->idarticulo,
                                $articulos->dic_det_item_det,
                                $articulos->dic_cant_item_det,
                                $data->ic_ccos_cpte,
                                $tercero->num_documento,
                                $articulos->dic_dato_fact_prove,
                                $fecha_vcto,
                                $articulos->dic_base_ret_item,
                                $credito,
                                $debito,
                            );
                        }elseif($getPuc){
                            foreach ($getPuc as $pucitem) {}
                            $fecha_vcto = ($articulos->dic_fecha_vcto_item !="0000-00-00")?$articulos->dic_fecha_vcto_item:"";
                            $resp2[] = array(
                                $pucitem->idcodigo,
                                $articulos->dic_det_item_det,
                                $articulos->dic_cant_item_det,
                                $data->ic_ccos_cpte,
                                $tercero->num_documento,
                                $articulos->dic_dato_fact_prove,
                                $fecha_vcto,
                                $articulos->dic_base_ret_item,
                                $credito,
                                $debito,
                            );
                        }
                        $subtotal_cuentas++;    
                    }
                    $resp2[] = array(
                        "",
                        $subtotal_cuentas." Cuentas contables",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "Total general:",
                        number_format(round($subtotal_debito)),
                        number_format(round($subtotal_credito)),
                    );
                    $pdf->SetTitle("Factura de compra contable ".$data->ic_num_cpte.zero_fill($data->ic_cons_cpte,8)." ".$data->ic_fecha_cpte." Proveedor ".$data->nombre_tercero);
                    $pdf->AddPage('L','A4');
                    //header
                    $x =10;
                    $y =0;
                    //imprimir cabecera
                    $pdf->Image(LOCATION_CLIENT.$sucursal->logo_img,30,12,36);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(0,6,$sucursal->razon_social,0,1,'C');
                    $pdf->SetFont('Arial','',8.5);
                    $pdf->Cell(0,0,$sucursal->prefijo_documento." ".$sucursal->num_documento,0,1,'C');
                    $pdf->Cell(0,6,$sucursal->direccion,0,1,'C');
                    $pdf->Cell(0,0,"Tel: ".$sucursal->telefono,0,1,'C');
                    $pdf->Cell(0,5,$empresa->pais." - ".$empresa->ciudad,0,1,'C');
                    $pdf->Cell(0,1,$sucursal->email,0,1,'C');
                    $pdf->SetY(12);
                    $pdf->SetX(230);
                    $pdf->Cell(55,16,"REG. COMPRA No.".zero_fill($data->ic_cons_cpte,8),1,0,'C');
                    //variables para imprimir sub cabecera de datos de tercero
                    $tercero = array('Proveedor:', $data->nombre_tercero);
                    $contacto = array('NIT:',$data->num_documento,"Telefono:",$data->telefono_proveedor);
                    $ubicacion = array("Direccion:",$data->direccion_calle,"Ciudad:",$data->direccion_provincia);
                    $infofecha = array("Fecha de Factura","Fecha de Vencimiento");
                    $date = array($data->ic_fecha_cpte,"0000-00-00");
                    //sub cabecera de datos del tercero
                    $pdf->SetFont('Arial','',7);
                    $pdf->SetY(38);
                    $pdf->SetX(10);
                    $pdf->FancyTable($tercero,$resp);
                    $pdf->SetY(45);
                    $pdf->SetX(10);
                    $pdf->FancyTable($contacto,$resp);
                    $pdf->SetY(52.5);
                    $pdf->SetX(10);
                    $pdf->FancyTable($ubicacion,$resp);
                    $pdf->SetY(38);
                    $pdf->SetX(210);
                    $pdf->DateTable($infofecha,$resp);
                    $pdf->SetY(45);
                    $pdf->SetX(210);
                    $pdf->DateTable($date,$resp);

                     //tabla de articulos
                    $tablehead = array("Cuenta","Detalle","Cant.","C. Costos","Tercero","Doc/Detalle","Fecha Venc.","Base Retencion","Credito","Debito");
                    $pdf->SetY(65);
                    $pdf->SetX(10);
                    $pdf->FancyTableContabilidad($tablehead,$resp2);
                    //detalle
                    

                    //impresion
                    if($conf_print && $conf_print == "print_off"){}else{
                        $pdf->AutoPrint();
                    }
                    $pdf->Output($data->ic_num_cpte.zero_fill($data->ic_cons_cpte,8)." ".$data->ic_fecha_cpte." Cliente ".$data->nombre_tercero.".pdf","I");
                }else{

                }
            }else{
                echo "Forbidden Gateway";
            }
        }else{
            echo "Forbidden Gateway";
        }
    }
    public function pos_venta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
        if(isset($_GET["data"]) && !empty($_GET["data"])){
        
        require_once 'lib/printpdf/fpdf.php';
        $idventa = $_GET["data"];
        ############## modelos
        $pdf = new FPDF($orientation='P',$unit='mm', array(45,350));
        $sucursales = new Sucursal($this->adapter);
        $ventas = new Ventas($this->adapter);
        $detalleventa = new DetalleVenta($this->adapter);
        $dataretenciones = new Retenciones($this->adapter);
        $dataimpuestos= new Impuestos($this->adapter);
        $pieFactura = new PieFactura($this->adapter);
        $cifrasEnLetras = new CifrasEnLetras();
        ############## funciones
        $venta = $ventas->getVentaById($idventa);
        foreach($venta as $data){}
        $resolucion = $pieFactura->getPieFacturaByComprobanteId($data->iddetalle_documento_sucursal);
        foreach ($resolucion as $resolucion) {}
        $sucursal = $sucursales->getSucursalById($data->idsucursal); 
        $detalle = $detalleventa->getArticulosByVenta($idventa);

        $retenciones = $dataretenciones->getRetencionesByComprobanteId($data->iddetalle_documento_sucursal);
        $impuestos = $dataimpuestos->getImpuestosByComprobanteId($data->iddetalle_documento_sucursal);
        //detalle venta
        $subtotal = $detalleventa->getSubTotal($data->idventa);
        $totalimpuestos = $detalleventa->getImpuestos($data->idventa);

        ######## obtener solo una vista de la funcion llamada
        foreach ($sucursal as $sucursal) {}
        foreach ($venta as $venta) {}
        
        
        $pdf->AddPage();
        $x =10;
        $y =0;
        $pdf->Image(LOCATION_CLIENT.$sucursal->logo_img,12,2,22);
        $pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
        $textypos = 10;
        $pdf->setY(2);
        $pdf->setX(2);
        $pdf->SetFont('Arial','B',4.5);
        
        $pos = 0;
        $pdf->SetXY(0, 18);
        $pdf->SetX(10);
        $pdf->Cell(0,$pos,utf8_decode($sucursal->razon_social),0,0,'C');

        $pdf->SetFont('Arial','',4); 

        $pdf->SetX(11.5);
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,utf8_decode($sucursal->telefono." - ".$sucursal->prefijo_documento." ".$sucursal->num_documento),0,0,'C');

        $pdf->SetX(11.5);
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,utf8_decode($sucursal->ciudad." - ".$sucursal->pais),0,0,'C');

        $pdf->SetFont('Arial','B',4);
        $pdf->SetX(11.5);
        $pos = $pos + 5;
        $pdf->Cell(0,$pos,"Ticket de venta  ".$venta->serie_comprobante.zero_fill($venta->num_comprobante,8),0,0,'C');

        $pdf->SetFont('Arial','',4); 
        $pdf->SetX(1);
        $pos = $pos + 5;
        $pdf->Cell(0,$pos,"Fecha:  ".utf8_decode($venta->fecha)."                           Fecha final: ".utf8_decode($venta->fecha_final),0,0,'L');

        $pdf->SetX(1);
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,"Vendedor:  ".utf8_decode($venta->idusuario),0,0,'L');

        $pdf->SetX(1);
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,"Tipo de Venta:  ".utf8_decode($venta->tipo_pago),0,0,'L');

        $pdf->SetX(1);
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,"Cliente:  ".utf8_decode($venta->nombre_cliente),0,0,'L');

        $pdf->SetX(1);
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,"Documento:  ".utf8_decode($venta->num_documento),0,0,'L');

        $pdf->SetX(1);
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,"Direccion:  ".utf8_decode($venta->direccion_calle),0,0,'L');

        $pdf->SetX(1);
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,"Telefono:  ".utf8_decode($venta->telefono),0,0,'L');

        $pdf->SetFont('Arial','',3);    //Letra Arial, negrita (Bold), tam. 20
        $pos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$pos,'-----------------------------------------------------------------------------------------------------------------');
        $pos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$pos,'CANT.           DESCRIPCION                                IMPORTE                            TOTAL');
        
        $total =0;
        $off = $pos+6;

        $i=0;
        foreach($detalle as $detalle){
        $pdf->setX(2);
        $pdf->Cell(5,$off,$detalle->cantidad.utf8_decode($detalle->prefijo_medida));
        $pdf->setX(8);
        $pdf->Cell(35,$off,  strtoupper(substr(utf8_decode($detalle->nombre_articulo), 0,25)) );
        $pdf->setX(20);
        $pdf->Cell(12,$off,  "$".number_format($detalle->iva_compra,2,".",",") ,0,0,"R");
        $pdf->setX(32);
        $pdf->Cell(11,$off,  "$ ".number_format($detalle->precio_total_lote,2,".",",") ,0,0,"R");
        
        $total += $detalle->precio_total_lote;
        $off+=6;
        $i++;
        }
        $pos=$off+6;

        // $pdf->setX(2);
        // $pdf->Cell(5,$pos,"TOTAL: " );
        // $pdf->setX(38);
        // $pdf->Cell(5,$pos,"$ ".number_format($total,2,".",","),0,0,"R");
        //$pos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$pos,utf8_decode("Nº de artículos: ".$i));
        $pdf->setX(38);
        //obter subtotal
        foreach ($subtotal as $subtotal) {}
        //valores a imprimir
        $subtotalimpuesto = 0;
        $listImpuesto = [];
        $listRetencion =[];
        $total_bruto = $subtotal->cdi_debito;
        $total_neto = $subtotal->cdi_debito;
        //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
        foreach ($totalimpuestos as $imp) {
            $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
            foreach($impuestos as $data){}
            if($impuestos){
               
                $total_neto -= $subtotalimpuesto;
                $total_bruto -= $subtotalimpuesto;
            
            }else{
                
            }
            
            foreach ($impuestos as $impuesto) {
                if($imp->cdi_importe == $impuesto->im_porcentaje){
                    //calculado
                    $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                    //concatenacion del nombre
                    $im_nombre = $impuesto->im_nombre." ".$impuesto->im_porcentaje."%";
                    //arreglo
                    $listImpuesto[] = array($im_nombre,$calc);
                    /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                    $total_neto += $calc;
                }else{
                   
                }
            }
        }
            foreach ($retenciones as $retencion) {
            
                if($retencion->re_im_id <= 0){
                    //concatenacion del nombre
                    $re_nombre = $retencion->re_nombre." ".$retencion->re_porcentaje."%";
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
                        $re_nombre = $retencion->re_nombre." (".$retencion->re_porcentaje."%)";
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

        $pos+=5;
        $pdf->setX(2);
        $pdf->Cell(5,$pos,"Subtotal: " );
        $pdf->setX(38);
        $pdf->Cell(5,$pos,"$ ".number_format($total_bruto,2,".",","),0,0,"R");
        foreach($listImpuesto as $listImpuesto){
            $pos+=5;
            $pdf->setX(2);
            $pdf->Cell(5,$pos,$listImpuesto[0]);
            $pdf->setX(38);
            $pdf->Cell(5,$pos,"$ ".number_format($listImpuesto[1],2,".",","),0,0,"R");
        }
        foreach ($listRetencion as $listRetencion) {
            $pos+=5;
            $pdf->setX(2);
            $pdf->Cell(5,$pos,$listRetencion[0]);
            $pdf->setX(38);
            $pdf->Cell(5,$pos,"$ ".number_format($listRetencion[1],2,".",","),0,0,"R");
        }
        $pos+=5;
        $pdf->setX(2);
        $pdf->Cell(5,$pos,"Total: " );
        $pdf->setX(38);
        $pdf->Cell(5,$pos,"$ ".number_format($total_neto,2,".",","),0,0,"R");

        $text_resolucion = explode('|', $resolucion->pf_text);
            $pdf->SetFont('Arial','',3); 
            $pdf->SetX(0);
            $pos = $pos + 12;
            
            $ry=$pos+=5;
            $i=0;
            foreach ($text_resolucion as $content) {
                $pdf->SetX(15);
                $pdf->SetY(15);
                $pos = $pos + 3;
                $pdf->Cell(0,$pos,utf8_decode($text_resolucion[$i]),0,0,'C');
                $i++;
            }
            $pdf->SetX(15);
            $pdf->SetY(17);
            $pos = $pos + 3;
            $pdf->Cell(0,$pos,utf8_decode("*GRACIAS POR SU COMPRA*"),0,0,'C');
            $pdf->SetX(15);
            $pdf->SetY(17);
            $pos = $pos + 3;
            $pdf->Cell(0,$pos,utf8_decode($sucursal->razon_social),0,0,'C');

        $pdf->AutoPrint();
        $pdf->output();

    }else{

    }
    }else{
        echo "Forbidden gateway";
    }
    } 

    public function cierre_caja()
    {
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                require_once 'lib/printpdf/fpdf.php';

                $idreporte = $_GET["data"];
                //recuperando la venta por id
                $caja = new ReporteCaja($this->adapter);
                $reporte = $caja->getReporteById($idreporte);
                if($reporte){
                    //agregando ciclo de una sola vuelta para recuperar datos de la venta
                foreach($reporte as $data){}
                //ecuperando la sucursal por factura de venta
                $sucursales = new Sucursal($this->adapter);
                $venta = new Ventas($this->adapter);

                $sucursal = $sucursales->getSucursalById($data->rc_idsucursal);
                foreach ($sucursal as $sucursal) {}
                $ventas = $venta->reporte_detallado_categoria($data->rc_fecha,$data->rc_fecha);
                $articulos = $venta->reporte_detallado_articulo($data->rc_fecha,$data->rc_fecha);
                $fecha_reporte =$data->rc_fecha;


        
        $pdf = new FPDF($orientation='P',$unit='mm', array(45,350));
        $pdf->SetTitle("Cierre de caja ".$data->rc_fecha);
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',4.5);
        
        $pdf->Image(LOCATION_CLIENT.$sucursal->logo_img,12,2,22);
        $textypos = 5;
        $pos = 24;
        $pdf->SetXY(0, 5);
        $pdf->SetX(10);
        $pdf->Cell(0,$pos,$sucursal->razon_social,0,0,'C');
        $pdf->SetX(11.5);

        $pdf->SetFont('Arial','',4); 
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,$sucursal->ciudad." - ".$sucursal->pais,0,0,'C');

        $pdf->SetX(11.5);
        $pdf->SetFont('Arial','',3.5); 
        $pos = $pos + 3;
        $pdf->Cell(0,$pos,$sucursal->prefijo_documento." ".$sucursal->num_documento,0,0,'C');


        $pdf->SetX(11.5);
        $pdf->SetFont('Arial','B',3.7); 
        $pos = $pos + 5;
        $pdf->Cell(0,$pos,"CIERRE DE CAJA ".$data->rc_fecha,0,0,'C');

        $pdf->SetFont('Arial','B',3.5); 
        $pos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$pos,'----------------------------------------Categoria---------------------------------------------');
        $pos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$pos,'CATEGORIA                   SUBTOTAL                IVA                 NETO');
        
        $total =0;
        $off = $pos+6;
        foreach($ventas as $ventas){
        $pdf->setX(2);
        $pdf->Cell(5,$off,$ventas->nombre_categoria);
        $pdf->setX(16);
        $pdf->Cell(35,$off,  number_format($ventas->precio_categoria,2,',','.') );
        $pdf->setX(22);
        $pdf->Cell(11,$off,  "$".number_format($ventas->precio_importe_categoria,2,',','.') ,0,0,"R");
        $pdf->setX(32);
        $pdf->Cell(11,$off,  "$ ".number_format($ventas->precio_categoria+$ventas->precio_importe_categoria,2,',','.') ,0,0,"R");
        
        $total += $ventas->precio_categoria+$ventas->precio_importe_categoria;
        $off+=6;
        }
        $pdf->SetFont('Arial','B',3.5); 
        $off+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$off,'------------------------------------------Articulo-------------------------------------------');
        $off+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$off,'ARTICULO                   SUBTOTAL                IVA                 NETO');
        $off+=6;
        foreach($articulos as $articulos){
            $pdf->setX(2);
            $pdf->Cell(5,$off,substr($articulos->nombre_articulo,0,16));
            $pdf->setX(16);
            $pdf->Cell(35,$off,  number_format($articulos->precio_categoria,2,',','.') );
            $pdf->setX(22);
            $pdf->Cell(11,$off,  "$".number_format($articulos->precio_importe_categoria,2,',','.') ,0,0,"R");
            $pdf->setX(32);
            $pdf->Cell(11,$off,  "$ ".number_format($articulos->precio_categoria+$articulos->precio_importe_categoria,2,',','.') ,0,0,"R");
        
            $off+=6;
            }
        $textypos=$off+6;
        
        $pdf->setX(2);
        $pdf->Cell(5,$textypos,"TOTAL VENTAS: " );
        $pdf->setX(38);
        $pdf->Cell(5,$textypos,"$ ".number_format($total,2,",","."),0,0,"R");

        $textypos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos+6,'EFECTIVO:');
        $pdf->setX(38);
        $pdf->Cell(5,$textypos+6,"$ ".number_format($data->rc_efectivo,2,",","."),0,0,"R");

        $textypos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos+6,'CREDITO:');
        $pdf->setX(38);
        $pdf->Cell(5,$textypos+6,"$ ".number_format($data->rc_credito,2,",","."),0,0,"R");

        $textypos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos+6,'DEBITO:');
        $pdf->setX(38);
        $pdf->Cell(5,$textypos+6,"$ ".number_format($data->rc_debito,2,",","."),0,0,"R");

        $textypos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos+6,'PAGOS:');
        $pdf->setX(38);
        $pdf->Cell(5,$textypos+6,"$ -".number_format($data->rc_pagos,2,",","."),0,0,"R");

        $textypos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos+6,'TOTAL CAJA:');
        $pdf->setX(38);
        $pdf->Cell(5,$textypos+6,"$ ".number_format($data->rc_monto,2,",","."),0,0,"R");

        $textypos+=6;
        $pdf->setX(2);
        $pdf->Cell(5,$textypos+6,'DIFERENCIA:');
        $pdf->setX(38);
        $pdf->Cell(5,$textypos+6,"$ ".number_format(($data->rc_monto+$data->rc_pagos)-$total,2,",","."),0,0,"R");

        $pdf->AutoPrint();
        $pdf->output("Cierre de caja ".$fecha_reporte.".pdf","I");
        }

        

        }
    }

    public function cartera()
    {
        if(isset($_GET["data"]) && !empty($_GET["data"]) && isset($_GET["s"]) && !empty($_GET["s"])){

        //Configuracion solo para desarrollo 
        //$location = "http://127.0.0.1/app";
        $location = LOCATION_CLIENT;
        $funcion = "pago_cartera";
        $data = $_GET["data"];
        $s = $_GET["s"];

        $this->frameview("file/pdf/cartera",array(
            "url"=>$location,
            "funcion"=>$funcion,
            "data"=>$data,
            "s"=>$s
        ));

        }else{

        }
    }

    public function pago_cartera()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_GET["data"]) && !empty($_GET["data"]) && isset($_GET["s"]) && !empty($_GET["s"])){
                require_once 'lib/printpdf/fpdf.php';
                $idcredito = $_GET["s"];
                $cartera = new Cartera($this->adapter);
                $sucursales = new Sucursal($this->adapter);
                $compras = new Compras($this->adapter);
                $ventas = new Ventas($this->adapter);

                if($_GET["data"] == "cliente"){
                    $credito = $cartera->getCreditoClienteById($idcredito);
                    foreach ($credito as $credito) {}
                    $factura = $ventas->getVentaById($credito->idventa);
                    $tercero = $credito->nombre_cliente;
                    $telefono = $credito->telefono;
                    $fecha = $credito->fecha_pago;
                    $deuda_total =$credito->deuda_total;
                    
                }else{
                    $credito = $cartera->getCreditoProveedorById($idcredito);
                    foreach ($credito as $credito) {}
                    $factura = $compras->getCompraById($credito->idingreso);
                    $tercero = $credito->nombre_proveedor;
                    $telefono = $credito->telefono;
                    $fecha = $credito->fecha_pago;
                    $deuda_total =$credito->deuda_total;
                }
                

                $sucursal = $sucursales->getSucursalById($_SESSION["idsucursal"]);

                foreach ($sucursal as $sucursal) {}
                foreach ($factura as $factura) {}
                $pdf = new FPDF($orientation='P',$unit='mm', array(45,350));
                $pdf->SetTitle("Pago cartera");
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',4.5);
                $pdf->Image(LOCATION_CLIENT.$sucursal->logo_img,12,2,22);
                $textypos = 5;
                $pos = 24;

                $pdf->SetXY(0, 5);
                $pdf->SetX(10);
                $pdf->Cell(0,$pos,$sucursal->razon_social,0,0,'C');
                $pdf->SetX(11.5);

                $pdf->SetFont('Arial','',4); 
                $pos = $pos + 3;
                $pdf->Cell(0,$pos,$sucursal->ciudad." - ".$sucursal->pais,0,0,'C');

                $pdf->SetFont('Arial','',3.5); 

                $pdf->SetX(1);
                $pos = $pos + 4;
                $pdf->Cell(0,$pos,"Comprobante: ".$factura->serie_comprobante.zero_fill($factura->num_comprobante,8),0,0,'L');

                $pdf->SetX(1);
                $pos = $pos + 4;
                $pdf->Cell(0,$pos,"Factura proveedor: ".$factura->factura_proveedor,0,0,'L');

                $pdf->SetX(1);
                $pos = $pos + 4;
                $pdf->Cell(0,$pos,"Tercero: ".$tercero,0,0,'L');

                $pdf->SetX(1);
                $pos = $pos + 4;
                $pdf->Cell(0,$pos,"Telefono: ".$telefono,0,0,'L');

                $pdf->SetX(1);
                $pos = $pos + 4;
                $pdf->Cell(0,$pos,"Fecha limite: ".$fecha,0,0,'L');

                $pdf->SetFont('Arial','B',3.5); 
                $pos+=6;
                $pdf->setX(2);
                $pdf->Cell(5,$pos,'--------------------------------Pago cartera '.$_GET["data"].'-----------------------------------');
                $pos+=6;
                $pdf->setX(2);
                $pdf->Cell(5,$pos,'FECHA               PAGO PARCIAL           RETENCION           DEVUELTO');
                $pdf->SetFont('Arial','B',2.5); 
                switch ($_GET["data"]) {
                    case 'cliente':
                        $detalle = $cartera->getPagoCarteraCliente($idcredito);

                        break;

                    case 'proveedor':
                        $detalle = $cartera->getPagoCarteraProveedor($idcredito);
                        break;
                    
                    default:
                        $detalle = ["fecha_pago"=>"","pago_parcial"=>"","retencion"=>"","pago"=>""];
                        break;
                }
                        $pagos_realizados =0;
                        $off = $pos+6;
                        foreach ($detalle as $pago) {
                            $pdf->setX(2);
                            $pdf->Cell(5,$off,$pago->fecha_pago);
                            $pdf->setX(14);
                            $pdf->Cell(35,$off,  "$".number_format($pago->pago_parcial,2,',','.') );
                            $pdf->setX(22);
                            $pdf->Cell(11,$off,  "$".number_format($pago->retencion,2,',','.') ,0,0,"R");
                            $pdf->setX(32);
                            $pdf->Cell(11,$off,  "$ ".number_format($pago->monto-$pago->pago_parcial,2,',','.') ,0,0,"R");
                            $pagos_realizados +=$pago->pago_parcial;
                            $off+=6;
                        }

                    $pos=$off+6;
                    $pdf->setX(2);
                    $pdf->Cell(5,$pos,"PAGOS REALIZADOS: " );
                    $pdf->setX(38);
                    $pdf->Cell(5,$pos,"$ ".number_format($pagos_realizados,2,".",","),0,0,"R");

                    $pos+=6;
                    $pdf->setX(2);
                    $pdf->Cell(5,$pos,"DEUDA TOTAL: " );
                    $pdf->setX(38);
                    $pdf->Cell(5,$pos,"$ ".number_format($deuda_total,2,".",","),0,0,"R");

                    $pos+=6;
                    $pdf->setX(2);
                    $pdf->Cell(5,$pos,"DIFERENCIA: " );
                    $pdf->setX(38);
                    $pdf->Cell(5,$pos,"$ ".number_format($deuda_total - $pagos_realizados,2,".",","),0,0,"R");

                $pdf->AutoPrint();
                $pdf->output();

            }else{
                echo "Factura no disponible";
            }
        }else{
            echo "Forbidden Gateway";
        }
    }


}