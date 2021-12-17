<?php
class VentasController extends ControladorBase{
    public $conectar;
	public $adapter;
	
    public function __construct() {
        parent::__construct();
		 
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        
    }

    public function index()
    {
        //limpiamos el registro del carro donde se almacenan los articulos
        $carro = new ColaIngreso($this->adapter);
        $carro->deleteCart();
        
        $venta = new Ventas($this->adapter);
        $ventas = $venta->getVentasAll();

        $this->frameview("ventas/index",array("ventas"=>$ventas));
    }

    public function reg_contable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1){
            $carro = new ColaIngreso($this->adapter);
            $venta = new ComprobanteContable($this->adapter);
            $detalle = new DetalleComprobanteContable($this->adapter);
            //limpiamos el registro del carro donde se almacenan los articulos
            $carro->deleteCart();
            //recuperar ventas contables
            $ventas = $venta->getComprobanteAllBy("cc_tipo_comprobante","V");
            foreach ($ventas as $data) {}

            //recuperar artiuclos de esta venta
            // $detalleingreso = $detalle->getArticulosByComprobante($data->cc_id_transa);
            // foreach ($detalleingreso as $articulos) {
            // }
            $this->frameview("ventas/contable/index",array(
                "ventas"=>$ventas
            ));
        }else{
            echo "Forbidden gateway";
        }
    }

    public function detail()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1){
            if(!empty($_GET["data"])){
                $idventa= $_GET["data"];
                $dataventas = new Ventas($this->adapter);
                $venta = $dataventas->getVentaById($idventa);

                $detalleVenta = new DetalleVenta($this->adapter);
                $articulos =$detalleVenta->getArticulosByVenta($idventa);

                $subtotal = $detalleVenta->totalVenta($idventa);

                $this->frameview("ventas/Detail/index",array(
                    "venta"=>$venta,
                    "articulos"=>$articulos,
                    "calculo"=>$subtotal
                ));


            }else{
                echo "Parse Error";
            }
        }else{
            echo "forbidden Gateway";
        }
    }

    public function delete(){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

        }else{
            echo "No tienes permisos para anular esta venta";
        }
    }

    public function nueva_venta()
    {
        $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
        if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] >= 3){
        //models
        $sucursal = new Sucursal($this->adapter);
        $comprobante = new Comprobante($this->adapter);
        $formapago= new FormaPago($this->adapter);
        $cart = new ColaIngreso($this->adapter);
        $cartimpuesto = new ColaImpuesto($this->adapter);
        $impuesto = new Impuestos($this->adapter);
        $retencion = new Retenciones($this->adapter);
        $empleados = new Empleado($this->adapter);
        $metodopago =new MetodoPago($this->adapter);

        $type_pos = true;
        //functions
        $getsucursal= $sucursal->getSucursalById($idsucursal);
        $empleado = $empleados->getEmpleadoByUserId($_SESSION["usr_uid"]);
        $metodospago = $metodopago->getAllMetodoPago();
        //obtener datos de usuario
        $idusuario = $_SESSION["usr_uid"];
        //ubicacion
        $pos_proceso ="Venta";
        $contabilidad = "";
        $control_proceso="";
        $autocomplete= "nombre_articulo";
        //comprobante
        //cargar lista de impuestos y retenciones
        $impuestos = $impuesto->getImpuestosAll();
        $retenciones = $retencion->getRetencionesAll();
        
        $comprobantes = $comprobante->getComprobante($pos_proceso);
        //formas de pago
        
        $formaspago = $formapago->getFormaPago($pos_proceso);

        
        $cart->setCi_usuario($_SESSION["usr_uid"]);
        $cart->setCi_idsucursal($_SESSION["idsucursal"]);
        $cart->setCi_idproveedor(0);
        $cart->setCi_tipo_pago(0);
        $cart->setCi_comprobante(0);
        $cart->setCi_fecha(date("Y-m-d"));
        $cart->setCi_fecha_final(date("Y-m-d"));
        $addCart = $cart->createCart();

        //si esta cuenta usa venta por pos, o la tiene activa procede a buscar impuestos por defecto
        $getCart = $cart->getCart();
        foreach($getCart as $getCart){}
        if($type_pos){

            foreach($comprobantes as $default){
                //si hay un comprobante pordefecto guarda los impuestos y los almacena para las ventas tipo pos
                    if($default->dds_propertie == 'selected'){
                        //obtener impuestos del comprobante
                        $impuesto_default = $impuesto->getImpuestosByComprobanteId($default->iddetalle_documento_sucursal);
                        foreach($impuesto_default as $impuesto_default){
                            $cartimpuesto->setCdim_ci_id($getCart->ci_id);
                            $cartimpuesto->setCdim_idcomprobante(0);
                            $cartimpuesto->setCdim_im_id($impuesto_default->im_id);
                            $cartimpuesto->setCdim_contabilidad(0);
                            $cartimpuesto->addImpuesto();
                        }
                    }
                }

            //other properties
            $hidden= "default_hidden";
            }

        $items = $cart->loadArtByCart($addCart);

        $this->frameview("ventas/New/POS/newPOS",array(
            "contabilidad"=>$contabilidad,
            "sucursal"=>$getsucursal,
            "idusuario"=>$idusuario,
            "autocomplete"=>$autocomplete,
            "pos"=>$pos_proceso,
            "control"=>$control_proceso,
            "comprobantes" => $comprobantes,
            "formaspago" => $formaspago,
            "items"=>$items,
            "impuestos"=>$impuestos,
            "retenciones"=>$retenciones,
            "metodospago"=>$metodospago,
            "empleado"=>$empleado,
            "hidden"=>$hidden,
        ));

        }else{
            echo "Forbidden gateway";
        }
    }

    public function nuevo()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            //models
            $sucursal = new Sucursal($this->adapter);
            $comprobante = new Comprobante($this->adapter);
            $formapago= new FormaPago($this->adapter);
            $cart = new ColaIngreso($this->adapter);
            $impuesto = new Impuestos($this->adapter);
            $retencion = new Retenciones($this->adapter);
            $metodopago =new MetodoPago($this->adapter);
            $empleados = new Empleado($this->adapter);

            //functions
            $metodospago = $metodopago->getAllMetodoPago();
            $empleado = $empleados->getEmpleadoByUserId($_SESSION["usr_uid"]);
            //ubicacion
            $idusuario = $_SESSION["usr_uid"];
            $pos_proceso ="Venta";
            $contabilidad = "Contable";
            $autocomplete= "codigo_contable";
            $control_proceso="";
            //obtener sucursal
            $idsucursal = $_SESSION["idsucursal"];
            $getsucursal= $sucursal->getSucursalById($idsucursal);
            $formaspago = $formapago->getFormaPago($pos_proceso);
            $comprobantes = $comprobante->getComprobanteContable($pos_proceso);
            //cargar impuestos y retenciones
            $impuestos = $impuesto->getImpuestosAll();
            $retenciones = $retencion->getRetencionesAll();
            //
            $cart->setCi_usuario($_SESSION["usr_uid"]);
            $cart->setCi_idsucursal($_SESSION["idsucursal"]);
            $cart->setCi_idproveedor(0);
            $cart->setCi_tipo_pago(0);
            $cart->setCi_comprobante(0);
            $cart->setCi_fecha(date("Y-m-d"));
            $cart->setCi_fecha_final(date("Y-m-d"));
            $addCart = $cart->createCart();
    
            $items = $cart->loadArtByCart($addCart);

            $hidden= "default_hidden";

            $this->frameview("ventas/New/new",array(
                "contabilidad"=>$contabilidad,
                "sucursal"=>$getsucursal,
                "idusuario"=>$idusuario,
                "pos"=>$pos_proceso,
                "autocomplete"=>$autocomplete,
                "control"=>$control_proceso,
                "comprobantes" => $comprobantes,
                "formaspago" => $formaspago,
                "items"=>$items,
                "impuestos"=>$impuestos,
                "retenciones"=>$retenciones,
                "metodospago"=>$metodospago,
                "empleado"=>$empleado,
                "hidden"=>$hidden,
            ));

        }else{echo "Forbidden gateway";}
    }

    public function sendItem()
    {
        if($_POST["data"] && !empty($_POST["data"]) && !empty($_POST["tercero"]) && !empty($_POST["pos"])){
            $articulos= new Articulo($this->adapter);
            $cart = new ColaIngreso($this->adapter);
            $pos = $_POST["pos"];
            $tercero = $_POST["tercero"];
            $item = $_POST["data"];
            //obtener item o servicio
            $credito =0;
            $debito=0;
            /**********************************Si el item es un producto**************************************/
            //setear datos en variables 
            if($item["iditem"] > 0){
            
            $idarticulo = $item["iditem"];
            $cantidad = $item["cantidad"];
            $ivaarticulo = $item["imp_venta"];
            $costo_producto= $item["precio_venta"];
            $cod_costos =$item["cod_costos"];

            $articulo = $articulos->getArticuloById($idarticulo);
            foreach($articulo as $articulo){}
            if($articulo->idarticulo){
                
            //calcular 
            $total_iva = ($costo_producto * $cantidad) *(($ivaarticulo/100));
            $total_lote = ($costo_producto * $cantidad) + $total_iva;
            //posicion de pagina
            $debito=$total_lote;
            $credito =$total_lote;
            
            //agregar articulo al carro
            if($cantidad <= $articulo->stock){

            $getCart = $cart->getCart();
            foreach($getCart as $getCart);
            $cart->setCdi_ci_id($getCart->ci_id);
            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
            $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
            $cart->setCdi_tercero($tercero);
            $cart->setCdi_idarticulo($idarticulo);
            $cart->setCdi_stock_ingreso($cantidad);
            $cart->setCdi_precio_unitario($costo_producto);
            $cart->setCdi_importe($ivaarticulo);
            $cart->setCdi_im_id("");
            $cart->setCdi_precio_total_lote($total_lote); 
            $cart->setCdi_credito($credito);
            $cart->setCdi_debito($debito);
            $cart->setCdi_cod_costos($cod_costos);
            $cart->setCdi_type("AR");
            $cart->addItemToCart();
            }else{
                $result = array("error"=>"No hay la cantidad suficiente para vender este articulo. En estock existen $articulo->stock $articulo->prefijo");
                echo json_encode($result);
            }
            }else{
                $result = array("error"=>"Este articulo no existe");
                echo json_encode($result);
            }

            }elseif($item["idservicio"] >0){
            /**********************************Si el item es un servicio**************************************/

            }elseif($item["idcodigo"] >0){
             /**********************************Si el item es un codigo contable**************************************/
            $idcodigo = $item["idcodigo"];
            $cantidad = $item["cantidad"];
            $ivacodigo = $item["imp_venta"];
            $costo_producto = $item["total_venta"];
            $cod_costos =0;
            if($pos =="Ingreso"){$credito=$costo_producto;}
            elseif($pos=="Venta"){$debito =$costo_producto;}
            else{}
            $cart = new ColaIngreso($this->adapter);
            $getCart = $cart->getCart();
            foreach($getCart as $getCart);
            $cart->setCdi_ci_id($getCart->ci_id);
            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
            $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
            $cart->setCdi_tercero($_SESSION["sucursal"]);
            $cart->setCdi_idarticulo($idcodigo);
            $cart->setCdi_stock_ingreso($cantidad);
            $cart->setCdi_precio_unitario($costo_producto);
            $cart->setCdi_importe($ivacodigo);
            $cart->setCdi_im_id("");
            $cart->setCdi_precio_total_lote($costo_producto);
            $cart->setCdi_credito($credito);
            $cart->setCdi_debito($debito);
            $cart->setCdi_cod_costos($cod_costos);
            $cart->setCdi_type("CO");
            $cart->addItemToCart();
            }

        }else{
            $result = array("warning"=>"El articulo y el Cliente son obligatorios");
            echo json_encode($result);
        }
        
    }

    public function calculoVenta()
    {
        if(isset($_POST["data"]) && !empty($_POST["data"])){

            $contabilidad = $_POST["contabilidad"];
            $dataretencionesdataretenciones = new Retenciones($this->adapter);
            $dataimpuestos= new Impuestos($this->adapter);
            $colaretencion = new Colaretencion($this->adapter);
            $colaimpuestos= new ColaImpuesto($this->adapter);
            $colaingreso = new ColaIngreso($this->adapter);
            //obtener carro actual
            $getCart = $colaingreso->getCart();
            foreach($getCart as $getCart){}

            //cargar impuestos y retenciones
            $retenciones = $colaretencion->getRetencionBy($getCart->ci_id);
            $impuestos = $colaimpuestos->getImpuestosBy($getCart->ci_id);
        
            $subtotal = $colaingreso->getSubTotal($getCart->ci_id);
            $totalimpuestos = $colaingreso->getImpuestos($getCart->ci_id);
            
            //obter subtotal
            foreach ($subtotal as $subtotal) {}
            //valores a imprimir
            $subtotalimpuesto = 0;
            $listRetencion =[];
            $listImpuesto=[];
            $total_bruto = $subtotal->subtotal;
            $total_neto = $subtotal->subtotal;
            //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
            foreach ($totalimpuestos as $imp) {
                $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                foreach($impuestos as $data){}
                if($impuestos){
                   if($data->im_porcentaje == $imp->cdi_importe){
                    //$total_neto = $subtotalimpuesto;
                    //$total_bruto -= $subtotalimpuesto;
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
                        $listImpuesto[] = array($im_nombre,$calc,$impuesto->cdim_id);
                        /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                        $total_neto += $calc;
                    }else{
                        //si el impuesto puede afectar al subtotal calcula sobre el subtotal, esto para algunosimpuestos obligatorios
                        //sirve para no afectar a algunos articulos como tal, sino solo sobre el subtotal antes de iva
                        if($impuesto->im_subtotal){
                            $sub = ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                            $calc = $sub *($impuesto->im_porcentaje/100);
                            $im_nombre = $impuesto->im_nombre." ".$impuesto->im_porcentaje."%";
                            $listImpuesto[] = array($im_nombre,$calc,$impuesto->cdim_id);
                            $total_neto += $calc;
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
                        $listRetencion[] = array($re_nombre,$calc,$retencion->cdr_id);
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

                            $listRetencion[] = array($re_nombre,$calc,$retencion->cdr_id);
                            /************************RESTANDO RETENCION DEL CALCULO*****************************/
                            $total_neto -= $calc;
                        }else{
                        }
                    }                    
                }
            }
                
            }
            echo $this->frameview("ventas/New/POS/calculoVenta",array(
                "retenciones"=>$listRetencion,
                "impuestos"=>$listImpuesto,
                "total_bruto"=>number_format($total_bruto,0,'.',','),
                "total_neto"=>$total_neto,
            ));
        
        }else{
            
        }
    }
    public function calculoVenta2($idcomprobante)
    {
        //models
        $totalcart = new ColaIngreso($this->adapter);
        $dataretenciones = new Retenciones($this->adapter);
        $dataimpuestos= new Impuestos($this->adapter);
        $colaretencion = new ColaRetencion($this->adapter);
        $colaimpuestos= new ColaImpuesto($this->adapter);
            
            $getCart = $totalcart->getCart();
            foreach($getCart as $getCart){}
            
            $retenciones = $colaretencion->getRetencionBy($getCart->ci_id);
            $impuestos = $colaimpuestos->getImpuestosBy($getCart->ci_id);

            $subtotal = $totalcart->getSubTotal($getCart->ci_id);
            $totalimpuestos = $totalcart->getImpuestos($getCart->ci_id);
            
            //obter subtotal
            foreach ($subtotal as $subtotal) {}
            //valores a imprimir
            $subtotalimpuesto = 0;
            $listImpuesto = [];
            $listRetencion =[];
            $total_bruto = $subtotal->subtotal;
            $total_neto = $subtotal->subtotal;
            //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
            foreach ($totalimpuestos as $imp) {
                $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                foreach($impuestos as $data){}
                if($impuestos){
                   if($data->im_porcentaje == $imp->cdi_importe){
                    //$total_neto = $subtotalimpuesto;
                    //$total_bruto -= $subtotalimpuesto;
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
                        $listImpuesto[] = array($im_nombre,$calc,$impuesto->cdim_id);
                        /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                        $total_neto += $calc;
                    }else{
                        //si el impuesto puede afectar al subtotal calcula sobre el subtotal, esto para algunosimpuestos obligatorios
                        //sirve para no afectar a algunos articulos como tal, sino solo sobre el subtotal antes de iva
                        if($impuesto->im_subtotal){
                            $sub = ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                            $calc = $sub *($impuesto->im_porcentaje/100);
                            $im_nombre = $impuesto->im_nombre." ".$impuesto->im_porcentaje."%";
                            $listImpuesto[] = array($im_nombre,$calc,$impuesto->cdim_id);
                            $total_neto += $calc;
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

            return $total_neto;
            
    }

    public function calculoVenta3($idcomprobante)
    {

            $dataretenciones = new Retenciones($this->adapter);
            $retenciones = $dataretenciones->getRetencionesByComprobanteId($idcomprobante);

            $dataimpuestos= new Impuestos($this->adapter);
            $impuestos = $dataimpuestos->getImpuestosByComprobanteId($idcomprobante);

            $totalcart = new ColaIngreso($this->adapter);
            $subtotal = $totalcart->getSubTotal();
            $totalimpuestos = $totalcart->getImpuestos();
            
            //obter subtotal
            foreach ($subtotal as $subtotal) {}
            //valores a imprimir
            $subtotalimpuesto = 0;
            $listImpuesto = [];
            $listRetencion =[];
            $total_bruto = $subtotal->cdi_debito;
            $total_neto = $subtotal->cdi_debito;
            $total_impuesto = 0;
            $total_retencion = 0;
            //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
            foreach ($totalimpuestos as $imp) {
                $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                foreach($impuestos as $data){}
                if($impuestos){
                   
                    $total_neto -= $subtotalimpuesto;
                    $total_bruto -= $subtotalimpuesto;
                    $total_impuesto += $subtotalimpuesto;
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
                        $total_impuesto += $calc;
                    }else{
                        //si el impuesto puede afectar al subtotal calcula sobre el subtotal, esto para algunosimpuestos obligatorios
                        //sirve para no afectar a algunos articulos como tal, sino solo sobre el subtotal antes de iva
                        if($impuesto->im_subtotal){
                            $sub = ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                            $calc = $sub *($impuesto->im_porcentaje/100);
                            $im_nombre = $impuesto->im_nombre." ".$impuesto->im_porcentaje."%";
                            $listImpuesto[] = array($im_nombre,$calc);
                            $total_neto += $calc;
                            $total_impuesto += $calc;
                        }
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
                        $total_retencion +=$calc;
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
                            $total_retencion +=$calc;
                        }else{
                        }
                    }                    
                }
            }
                
            }

            return array(
                "total"=>$total_neto,
                "subtotal"=>$total_bruto,
                "total_retencion"=>$total_retencion,
                "total_impuesto"=>$total_impuesto,
            );
            
    }

    public function crearVenta()
    {
        //ver que los datos se enviaron
        if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 0){
            
            //models
            $venta = new Ventas($this->adapter);
            $dataarticulos = new Articulo($this->adapter);
            $colaimpuesto = new Colaimpuesto($this->adapter);
            $colaretencion = new Colaretencion($this->adapter);
            $detalleimpuesto = new DetalleImpuesto($this->adapter);
            $detalleretencion = new DetalleRetencion($this->adapter);
            $detallemetodopago = new DetalleMetodoPago($this->adapter);
            $comprobantes = new Comprobante($this->adapter);
            $cliente = new Persona($this->adapter);
            $carro = new ColaIngreso($this->adapter);
            $dataformapago = new FormaPago($this->adapter);
            $facturacionelectronica = new FacturacionElectronica($this->adapter);
            $colametodopago = new ColaMetodoPago($this->adapter);
            $metodopago = new MetodoPago($this->adapter);
            $cierreturno = new CierreTurno($this->adapter);

            $fe = $facturacionelectronica->status();
            /******************************************************************************************/
            //dividir estring
            $array = explode(" - ", $_POST["proveedor"]);
            
            $i =0;
            foreach ($array as $search) {$getCliente = $cliente->getClienteByDocument($array[$i]);
                //si se encontro algo en proveedores lo retorna
            foreach ($getCliente as $datacliente) {}
            $i++;
            }
            //idproveedor recuperado
            $idproveedor = $datacliente->idpersona;
            if($idproveedor > 0){
            /******************************************************************************************/
            /***********************************configuracion del comprobante***************************************/
            $idcomprobante = $_POST["comprobante"];
            
            $usarcomprobante = $comprobantes->usarComprobante($idcomprobante);
            //recuperar comprobante
            $getComprobanteByid = $comprobantes->getComprobanteById($idcomprobante);

            foreach ($getComprobanteByid as $comprobante) {}
            /*************info comprobante***********/
            $tipoComprobante =$comprobante->nombre;
            $serieComprobante = $comprobante->ultima_serie;
            $ultimoNComprobante = $comprobante->ultimo_numero;
            /***********fin info comprobante*********/
            //obtener carro de articulos
            
            $getCart = $carro->getCart();
            foreach ($getCart as $getCart){}
            $getArticulos = $carro->getArtByCart($getCart->ci_id);
            //obtener tipo y forma de pago
            //tipo
            $listaMetodo = $colametodopago->getMetodoPagoBy($getCart->ci_id);
            //forma
            $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
            foreach ($formapago as $formapago) {}
            $formapago = $formapago->fp_nombre;
            //articulos recuperados
            $subtotal = $carro->getSubTotal($getCart->ci_id);

            foreach ($subtotal as $subtotal) {}
            $calcsubtotal = $subtotal->subtotal;
            $total = $this->calculoVenta2($idcomprobante);
            //start date configuracion
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate = $_POST["end_date"];
                if($enddate != null){
                    $array_end_date = explode("/", $enddate);
                    foreach ($array_end_date as $enddate) {}
                    $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
                }else{$end_date = "0000-00-00";}

            $monto = 0;
            $status_pago = false;

            if($listaMetodo){
                foreach($listaMetodo as $metodo){
                    $monto += $metodo->cdmp_monto;
                    $status_pago =true;
                }
            }

            if($monto >= $total || $status_pago == false){

                $venta->setIdsucursal($_SESSION['idsucursal']);
                $venta->setIdCliente($idproveedor);
                $venta->setIdusuario($_SESSION['usr_uid']);
                $venta->setTipo_pago($formapago);
                $venta->setTipo_comprobante($tipoComprobante);
                $venta->setSerie_comprobante($serieComprobante);
                $venta->setNum_comprobante(zero_fill($ultimoNComprobante,8));
                $venta->setFecha($start_date);
                $venta->setFecha_final($end_date);
                $venta->setImpuesto("0");
                $venta->setSub_total($calcsubtotal);
                $venta->setSubtotal_importe("0");
                $venta->setTotal($total);
                $venta->setImporte_pagado($total); 
                $venta->setEstado("A");
                $saveVenta = $venta->saveVenta();
            if($saveVenta){

                if($status_pago){
                    foreach($listaMetodo as $metodopago){
                        $detallemetodopago->setDmpg_registro_comprobante($saveVenta);
                        $detallemetodopago->setDmpg_detalle_registro("V");
                        $detallemetodopago->setDmpg_contabilidad(0);
                        $detallemetodopago->setDmpg_mp_id($metodopago->mp_id);
                        $detallemetodopago->setDmpg_monto($metodopago->cdmp_monto);
                        $detallemetodopago->addDetalleMetodoPago();
                    }
                }else{
                    $metodopagodefault = $metodopago->getMetodoPagoBy('mp_default',1);
                    foreach($metodopagodefault as $mpdefault){}
                    $detallemetodopago->setDmpg_registro_comprobante($saveVenta);
                    $detallemetodopago->setDmpg_detalle_registro("V");
                    $detallemetodopago->setDmpg_contabilidad(0);
                    $detallemetodopago->setDmpg_mp_id($mpdefault->mp_id);
                    $detallemetodopago->setDmpg_monto($total);
                    $detallemetodopago->addDetalleMetodoPago();
                }

                //si la forma de pago es cartera o credito
                if($formapago == "Credito"){
                    //generar cartera
                    $cartera = new Cartera($this->adapter);
                    $cartera->setIdventa($saveVenta);
                    $cartera->setFecha_pago($end_date);
                    $cartera->setTotal_pago(0);
                    $cartera->setDeuda_total($total);
                    $cartera->setContabilidad(0);
                    $cartera->setC_estado("A");
                    $generarCartera = $cartera->generarCarteraCliente();
                   

                }else{}

                $impuestos = $colaimpuesto->getImpuestosBy($getCart->ci_id);
                $retenciones = $colaretencion->getRetencionBy($getCart->ci_id);
                /***********fin congifuracion de impuestos y retenciones ************/

                //registrar impuestos
                foreach($impuestos as $impuestos){
                    $detalleimpuesto->setDig_registro_comprobante($saveVenta);
                    $detalleimpuesto->setDig_detalle_registro("V");
                    $detalleimpuesto->setDig_contabilidad(0);
                    $detalleimpuesto->setDig_im_id($impuestos->cdim_im_id);
                    $detalleimpuesto->addImpuesto();
                }
                //registrar retenciones
                foreach($retenciones as $retenciones){
                    $detalleretencion->setDrg_registro_comprobante($saveVenta);
                    $detalleretencion->setDrg_detalle_registro("V");
                    $detalleretencion->setDrg_contabilidad(0);
                    $detalleretencion->setDrg_re_id($retenciones->cdr_re_id);
                    $detalleretencion->addRetencion();
                }

                $detalleVenta = new DetalleVenta($this->adapter);
                $impuesto_venta =0;
                foreach ($getArticulos as $articulos) {
                    //guardando en variables
                    $idarticulo = $articulos->cdi_idarticulo;
                    $stock_ingreso =$articulos->cdi_stock_ingreso;
                    $impuesto = $articulos->cdi_importe;
                    $precio_unitario = $articulos->cdi_precio_unitario;
                    $total =$articulos->cdi_precio_total_lote;
                    $cod_costos =$articulos->cdi_cod_costos;
                    $idventa = $saveVenta;
                    $impuesto_venta += $total -($total /(($impuesto/100)+1));
                    //guardar cada articulo en el ingreso
                    $detalleVenta->setIdventa($idventa);
                    $detalleVenta->setIdarticulo($idarticulo);
                    $detalleVenta->setCantidad($stock_ingreso);
                    $detalleVenta->setPrecio_venta($precio_unitario);
                    $detalleVenta->setIva_compra($total -($total /(($impuesto/100)+1)));
                    $detalleVenta->setImporte_categoria($impuesto);
                    $detalleVenta->setPrecio_total_lote($total);
                    $detalleVenta->setEstado("A");
                    $addArticulos = $detalleVenta->addArticulos();
                    //scar cantidad de stock 
                    $articulo = $dataarticulos->removeCantStock($idarticulo,$stock_ingreso);
                    //eliminar carro
                    $carro->deleteCart();

                }
                if($addArticulos){
                    //agregar el impuesto total de la venta
                    //$venta->setImpuesto($impuesto_venta);
                    $venta->setSubtotal_importe($impuesto_venta);
                    $impuestoventa = $venta->addImpuestoVenta($idventa);

                    //almacenar primer registro de venta al reporte de ventas
                    //preparar datos de turnos
                    $start_date = date("Y-m-d");
                    $start_time = date("H:i:s");
                    $cierreturno->setRct_idsucursal($_SESSION['idsucursal']);
                    $cierreturno->setRct_idusuario($_SESSION['usr_uid']);
                    $cierreturno->setRct_date($start_date);
                    $cierreturno->setRct_venta_desde($idventa);
                    //por si no existe el reporte 
                    $cierreturno->setRct_descripcion("Reporte usuario ".$_SESSION['usr_uid']);
                    $cierreturno->setRct_fecha_inicio($start_date . " " . $start_time);

                    $authInicio = $cierreturno->authInicio();
                    if($authInicio){
                        foreach($authInicio as $authInicio){}
                        if($authInicio->rct_venta_desde == 0 && $authInicio->rct_date == $start_date && $authInicio->rct_idsucursal == $_SESSION['idsucursal'] && $authInicio->rct_idusuario == $_SESSION['usr_uid']){
                            $cierreturno->addInicioVenta();
                        }
                        //procede aqui si existe el registro para reporte, seria solo actualizar
                        
                    }else{
                        
                    }
                    
                    if($fe){

                        $facturacionelectronica->setIdventa($idventa);
                        $facturacionelectronica->setFe_type('V');
                        $facturacionelectronica->setInvoiceType("FACTURA");
                        $facturacionelectronica->setFe_code(10);
                        $response = $facturacionelectronica->generate($this->adapter);

                        echo json_encode(array("success"=>"#file/venta/$idventa","type"=>"message","response"=>$response,"alertType"=>"success"));
                    }else{
                        echo json_encode(array("success"=>"file/venta/$idventa","type"=>"redirect","response"=>""));
                    }                  
                }
            } else{
                echo json_encode(array("error"=>"Error al ingresar la compra. refresca la pagina e intentalo de nuevo "));
            } 
            
        } else{
            echo json_encode(array("error"=>"El monto ingresado es menor a el total de la factura"));
        } 

        }else{
            echo json_encode(array("error"=>"Debe agregar un cliente valido"));
        }

        }else{
            echo json_encode(array("error"=>"El cliente y el comprobante son importantes"));
        }
    }

    public function crearVentaContable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            //ver que los datos se enviaron
            if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 0){
                //lista de modelos
                $ventacontable = new ComprobanteContable($this->adapter);
                $detalleVentaContable = new DetalleComprobanteContable($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $puc = new PUC($this->adapter);
                $impuestos = new Impuestos($this->adapter);
                $retenciones = new Retenciones($this->adapter);
                $categorias = new Categoria($this->adapter);
                $cartera = new Cartera($this->adapter);
                $mymediafiles= new MyMediaFiles($this->adapter);
                $clientes = new Persona($this->adapter);
                $comprobantes = new Comprobante($this->adapter);
                $colaimpuesto = new Colaimpuesto($this->adapter);
                $colaretencion = new Colaretencion($this->adapter);
                $detalleimpuesto = new DetalleImpuesto($this->adapter);
                $detalleretencion = new DetalleRetencion($this->adapter);
                $carro = new ColaIngreso($this->adapter);
                $cierreturno = new CierreTurno($this->adapter);

                $facturacionelectronica = new FacturacionElectronica($this->adapter);
                $fe = $facturacionelectronica->status();

                //funciones
                $forma_pago =$_POST["formaPago"];
                //dividir estring
                //capurar datos del cliente
                $array = explode(" - ", $_POST["proveedor"]);
                $i =0;
                //se recupera datos del cliente
                foreach ($array as $search) {$getClientes = $clientes->getClienteByDocument($array[$i]);
                    //si se encontro algo en proveedores lo retorna
                    foreach ($getClientes as $datacliente) {}
                        $i++;
                }
                //
                //idproveedor recuperado
                if($datacliente){
                    $idproveedor = $datacliente->idpersona;
                    $idcomprobante = $_POST["comprobante"];

                    //recuperar comprobante
                    $getComprobanteByid = $comprobantes->getComprobanteById($idcomprobante);
                    foreach ($getComprobanteByid as $comprobante) {}
                    /*************info comprobante***********/
                    $tipoComprobante =$comprobante->iddetalle_documento_sucursal;
                    $serieComprobante = $comprobante->ultima_serie;
                    $ultimoNComprobante = $comprobante->ultimo_numero+1;
                    $comprobanteContable = $comprobante->contabilidad;
                    /***********fin info comprobante*********/

                     //obtener carro de articulos
                    $getCart = $carro->getCart();
                    foreach($getCart as $getCart){}
                    //obtener articulos
                    $getArticulos = $carro->getArtByCart($getCart->ci_id);

                    //obtener tipo de pago
                    $dataformapago = new FormaPago($this->adapter);
                    $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
                    foreach ($formapago as $formapago) {}
                    $formadepago = $formapago->fp_nombre;

                    //articulos recuperados
                    $subtotal = $carro->getSubTotal($getCart->ci_id);
                    $total = $this->calculoVenta2($_POST["comprobante"]); //revision
                    foreach ($subtotal as $subtotal) {}
                    $calcsubtotal = $subtotal->cdi_debito;
                    $start_date = date_format_calendar($_POST["start_date"],"/");
                    $end_date = date_format_calendar($_POST["end_date"],"/");

                    
                    //si hay articulos en el carro de lo contrario cancela el proceso
                    $state =0;
                    if($getArticulos != null){

                        foreach ($getArticulos as $filter) {
                            //si se agrego una cuenta contable para pago de factura
                            if($filter->cdi_type == "CO"){
                                //verificar el tipo de cuenta si es de movimiento y centro de costos
                                #### verificar aqui
                                //indicamos que el estado es activo
                                $state =1;
                            }
                        }

                        if($state == 1){
                            
                            //verificar si es una afectacion de otra factura
                            if(isset($_POST["idventa"]) && !empty($_POST["idventa"]) && $_POST["idventa"] >0){
                                $idventa = $_POST["idventa"];
                                //ella verificara si tiene todos los permisos necesarios para anularla, sino. solo creara otra factura y no hara afectacion
                                //a la anterior 
                                $lastventa = $ventacontable->getComprobanteById($idventa);
                                foreach($lastventa as $lastventa){}
                                $tipoComprobante = $lastventa->cc_id_tipo_cpte;
                                $serieComprobante = $lastventa->cc_num_cpte;
                                $ultimoNComprobante = $lastventa->cc_cons_cpte;
                                $this->delete_venta_contable($idventa,false);
                            }else{
                                 //usar comprobante
                               $usarcomprobante = $comprobantes->usarComprobante($idcomprobante);
                                
                            }
                            

                        $ventacontable->setCc_idusuario($_SESSION["usr_uid"]);
                        $ventacontable->setCc_idproveedor($datacliente->idpersona);
                        $ventacontable->setCc_tipo_comprobante("V");
                        $ventacontable->setCc_id_forma_pago($forma_pago);
                        $ventacontable->setCc_id_tipo_cpte($tipoComprobante);
                        $ventacontable->setCc_num_cpte($serieComprobante);
                        $ventacontable->setCc_cons_cpte(zero_fill($ultimoNComprobante,8));
                        $ventacontable->setCc_det_fact_prov($serieComprobante.zero_fill($ultimoNComprobante,8));
                        $ventacontable->setCc_fecha_cpte($start_date);
                        $ventacontable->setCc_fecha_final_cpte($end_date);
                        $ventacontable->setCc_nit_cpte($datacliente->num_documento);
                        $ventacontable->setCc_dig_verifi(0);
                        $ventacontable->setCc_ccos_cpte($_SESSION["idsucursal"]);
                        $ventacontable->setCc_fp_cpte($formadepago);
                        $ventacontable->setCc_estado("A");
                        $ventacontable->setCc_log_reg($_SESSION['usr_uid']."_".$start_date."_".date("h:i:s"));
                        $addVenta = $ventacontable->saveComprobanteContable();
                        
                        $listImpuestos = $colaimpuesto->getImpuestosBy($getCart->ci_id);
                        $listRetenciones = $colaretencion->getRetencionBy($getCart->ci_id);
                        /***********fin congifuracion de impuestos y retenciones ************/
    
                        //registrar impuestos
                        foreach($listImpuestos as $dataImp){
                            $detalleimpuesto->setDig_registro_comprobante($addVenta);
                            $detalleimpuesto->setDig_detalle_registro("V");
                            $detalleimpuesto->setDig_contabilidad(1);
                            $detalleimpuesto->setDig_im_id($dataImp->cdim_im_id);
                            $detalleimpuesto->addImpuesto();
                        }
                        //registrar retenciones
                        foreach($listRetenciones as $dataRe){
                            $detalleretencion->setDrg_registro_comprobante($addVenta);
                            $detalleretencion->setDrg_detalle_registro("V");
                            $detalleretencion->setDrg_contabilidad(1);
                            $detalleretencion->setDrg_re_id($dataRe->cdr_re_id);
                            $detalleretencion->addRetencion();
                        }
                        $total_retenido =0;
                        foreach ($getArticulos as $item) {
                            if($item->cdi_type == "AR"){

                                $articulo = $dataarticulos->getArticuloById($item->cdi_idarticulo);
                                foreach ($articulo as $articulo) {}
                                //almacenando articulos
                                //$addStock = $dataarticulos->removeCantStock($item->cdi_idarticulo,$item->cdi_stock_ingreso);
                                //obtener la categoria de este articulo
                                $categoria = $categorias->getCategoriaById($articulo->idcategoria);
                                foreach ($categoria as $categoria) {}
                                //obtener codigo contable de la categoria -> codigo de inventario
                                $cuenta_inventario = $puc->getPucById($categoria->cod_venta);

                                foreach ($cuenta_inventario as $cuenta_inventario) {}
                                $inventario = ($cuenta_inventario !=null)? $cuenta_inventario->idcodigo:$categoria->cod_venta;
        
                                $detalleVentaContable->setDcc_id_trans($addVenta);
                                $detalleVentaContable->setDcc_seq_detalle(0);
                                $detalleVentaContable->setDcc_cta_item_det($inventario);
                                $detalleVentaContable->setDcc_det_item_det($articulo->nombre_articulo);
                                $detalleVentaContable->setDcc_cod_art($articulo->idarticulo);
                                $detalleVentaContable->setDcc_cant_item_det($item->cdi_stock_ingreso);
                                $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                                $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                $detalleVentaContable->setDcc_d_c_item_det("C");
                                $detalleVentaContable->setDcc_valor_item($item->cdi_precio_unitario*$item->cdi_stock_ingreso);
                                $detalleVentaContable->setDcc_base_imp_item($item->cdi_importe);
                                $detalleVentaContable->setDcc_base_ret_item(0);
                                $detalleVentaContable->setDcc_fecha_vcto_item(0);
                                $detalleVentaContable->setDcc_dato_fact_prove("");
                                $addItem=$detalleVentaContable->addArticulos(false);

                                $dataarticulos->removeCantStock($articulo->idarticulo,$item->cdi_stock_ingreso);

                            }else{}
                            
                        }
                        //recorrer cada articulo y buscar sus impuestos
                        foreach ($getArticulos as $dataimpuestos) {
                            if($dataimpuestos->cdi_type == "AR"){
                            //$listImpuestos = $impuestos->getImpuestosByComprobanteId($tipoComprobante);
                            foreach ($listImpuestos as $listImpuesto) {
                                if($listImpuesto->im_porcentaje == $dataimpuestos->cdi_importe){
                                    $cuenta = $puc->getPucById($listImpuesto->im_cta_contable);
                                    foreach($cuenta as $cuenta){}
                                    if($cuenta != null){
                                        $precio_total_lote_sin_iva = $dataimpuestos->cdi_precio_total_lote / (($listImpuesto->im_porcentaje /100)+1);
                                        $detalleVentaContable->setDcc_id_trans($addVenta);
                                        $detalleVentaContable->setDcc_seq_detalle(0);
                                        $detalleVentaContable->setDcc_cta_item_det($cuenta->idcodigo);
                                        $detalleVentaContable->setDcc_det_item_det($cuenta->tipo_codigo);
                                        $detalleVentaContable->setDcc_cod_art(0);
                                        $detalleVentaContable->setDcc_cant_item_det($dataimpuestos->cdi_stock_ingreso);
                                        $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                                        $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                        $detalleVentaContable->setDcc_d_c_item_det("C");
                                        $detalleVentaContable->setDcc_valor_item($precio_total_lote_sin_iva * ($listImpuesto->im_porcentaje/100));
                                        $detalleVentaContable->setDcc_base_imp_item(0);
                                        $detalleVentaContable->setDcc_base_ret_item(0);
                                        $detalleVentaContable->setDcc_fecha_vcto_item(0);
                                        $detalleVentaContable->setDcc_dato_fact_prove("");
    
                                        $addImpuesto=$detalleVentaContable->addArticulos();
                                    }
                                }else{
                                    if($cuenta !=null){
                                    if($listImpuesto->im_subtotal){
                                        $cuenta = $puc->getPucById($listImpuesto->im_cta_contable);
                                        foreach($cuenta as $cuenta){}

                                        $precio_total_lote_sin_iva = $dataimpuestos->cdi_precio_total_lote / (($dataimpuestos->cdi_importe /100)+1);

                                        $detalleVentaContable->setDcc_id_trans($addVenta);
                                        $detalleVentaContable->setDcc_seq_detalle(0);
                                        $detalleVentaContable->setDcc_cta_item_det($cuenta->idcodigo);
                                        $detalleVentaContable->setDcc_det_item_det($cuenta->tipo_codigo);
                                        $detalleVentaContable->setDcc_cod_art(0);
                                        $detalleVentaContable->setDcc_cant_item_det($dataimpuestos->cdi_stock_ingreso);
                                        $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                                        $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                        $detalleVentaContable->setDcc_d_c_item_det("C");
                                        $detalleVentaContable->setDcc_valor_item($precio_total_lote_sin_iva * ($listImpuesto->im_porcentaje/100));
                                        $detalleVentaContable->setDcc_base_imp_item(0);
                                        $detalleVentaContable->setDcc_base_ret_item(0);
                                        $detalleVentaContable->setDcc_fecha_vcto_item(0);
                                        $detalleVentaContable->setDcc_dato_fact_prove("");
    
                                        $addImpuesto=$detalleVentaContable->addArticulos();
                                    }else{}
                                }
                                }
                            }
    
                        }
                    }
                  //recorrer cada articulo y buscar sus retenciones
                foreach ($getArticulos as $dataretenciones) {
                    //filtro por articulo, marcadoen tipo 'AR'
                    if($dataretenciones->cdi_type == "AR"){
                        //recupero la lista de retenciones
                        $retencion = $colaretencion->getRetencionBy($getCart->ci_id);
                        //preparo lista de impuestos
                        $dataimpuestos = $colaimpuesto->getImpuestosBy($getCart->ci_id);
                        //recorro los impuestos en busqueda del correspondiente
                        
                        if($dataimpuestos && $dataretenciones->cdi_importe >0){
                            foreach($dataimpuestos as $cola_imp){
                            if($cola_imp->im_porcentaje == $dataretenciones->cdi_importe){
                                //guardo este impuesto en una variable
                                $impuesto_actual = $impuestos->getImpuestoBy('im_id',$cola_imp->im_id,"");
                                //$impuesto_actual = $cola_imp;
                            }
                        }
                    }

                        //recorro las retenciones
                         //[0]=> retencion al subtotal, [1]=> retencion a impuestos 
                        $retencion_actual = [];
                        $tipo_proceso = 0;

                        foreach($retencion as $cola_ret){
                            foreach($impuesto_actual as $imp_act){}

                            if($cola_ret->re_im_id == $imp_act->im_id && $impuesto_actual && $dataretenciones->cdi_importe>0){
                                $cuenta_retencion = $puc->getPucById($cola_ret->re_cta_contable);
                                foreach ($cuenta_retencion as $cuenta_retencion){}
                                // if($cola_ret->re_im_id == $imp_act->im_id){
                                    $impuesto_articulo = ($dataretenciones->cdi_importe>0)?$dataretenciones->cdi_importe:1;
                                    $total = $dataretenciones->cdi_precio_total_lote / (($impuesto_articulo/100)+1);
                                    $total_impuesto = $total * ($impuesto_articulo/100);
                                    $calculo_retencion = $total_impuesto * ($cola_ret->re_porcentaje/100);
                                    $total_retenido += $calculo_retencion;
                                    $detalleVentaContable->setDcc_id_trans($addVenta);
                                    $detalleVentaContable->setDcc_seq_detalle(0);
                                    $detalleVentaContable->setDcc_cta_item_det($cuenta_retencion->idcodigo);
                                    $detalleVentaContable->setDcc_det_item_det($cuenta_retencion->tipo_codigo);
                                    $detalleVentaContable->setDcc_cod_art(0);
                                    $detalleVentaContable->setDcc_cant_item_det($dataretenciones->cdi_stock_ingreso);
                                    $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                                    $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                    $detalleVentaContable->setDcc_d_c_item_det("D");
                                    $detalleVentaContable->setDcc_valor_item($calculo_retencion);
                                    $detalleVentaContable->setDcc_base_imp_item(0);
                                    $detalleVentaContable->setDcc_base_ret_item($total_impuesto);
                                    $detalleVentaContable->setDcc_fecha_vcto_item(0);
                                    $detalleVentaContable->setDcc_dato_fact_prove("");
                                    $addCodCostos=$detalleVentaContable->addArticulos();
                                    //}

                            }elseif($cola_ret->re_im_id ==0){
                                $cuenta_retencion = $puc->getPucById($cola_ret->re_cta_contable);
                                foreach ($cuenta_retencion as $cuenta_retencion){}
                                $impuesto_articulo = ($dataretenciones->cdi_importe>0)?$dataretenciones->cdi_importe:0;
                                if($impuesto_articulo > 0){
                                    $total = $dataretenciones->cdi_precio_total_lote / (($impuesto_articulo/100)+1);
                                }else{
                                    $total = $dataretenciones->cdi_precio_total_lote ;
                                }
                                $retencion_total = $total * ($cola_ret->re_porcentaje/100);
                                $total_retenido +=$retencion_total;
                                $retencion_this = $retencion_total;
                                $detalleVentaContable->setDcc_id_trans($addVenta);
                                $detalleVentaContable->setDcc_seq_detalle(0);
                                $detalleVentaContable->setDcc_cta_item_det($cuenta_retencion->idcodigo);
                                $detalleVentaContable->setDcc_det_item_det($cuenta_retencion->tipo_codigo);
                                $detalleVentaContable->setDcc_cod_art(0);
                                $detalleVentaContable->setDcc_cant_item_det($dataretenciones->cdi_stock_ingreso);
                                $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                                $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                $detalleVentaContable->setDcc_d_c_item_det("D");
                                $detalleVentaContable->setDcc_valor_item($retencion_this);
                                $detalleVentaContable->setDcc_base_imp_item(0);
                                $detalleVentaContable->setDcc_base_ret_item($total);
                                $detalleVentaContable->setDcc_fecha_vcto_item(0);
                                $detalleVentaContable->setDcc_dato_fact_prove("");
                                $addCodCostos=$detalleVentaContable->addArticulos();
                            }
                        }
                        

                        
                    }
                }
                // foreach ($getArticulos as $dataretenciones) {
                //     if($dataretenciones->cdi_type == "AR"){
                //         //recorrer cada retencion
                //         foreach ($listRetenciones as $retencion) {
                //             $dataimpuestos = $colaimpuesto->getImpuestosBy($getCart->ci_id);
                //             if($retencion != null){
                //                 $cuenta_retencion = $puc->getPucById($retencion->re_cta_contable);
                //                 foreach ($cuenta_retencion as $cuenta_retencion) {}
                //                 if($cuenta_retencion != null){
                //                     foreach($dataimpuestos as $dataimpuestos){}
                //                     //verificar el impuesto de esta venta
                //                     #revision
                //                     if($retencion->re_im_id == $dataimpuestos->im_id){ ##revisar para proxima actualizacion 6/11/2020
                //                         $impuesto_articulo = ($dataretenciones->cdi_importe>0)?$dataretenciones->cdi_importe:1;
                //                         $total = $dataretenciones->cdi_precio_total_lote / (($impuesto_articulo/100)+1);
                //                         $total_impuesto = $total * ($impuesto_articulo/100);
                //                         $calculo_retencion = $total_impuesto * ($retencion->re_porcentaje/100);

                //                         $total_retenido += $calculo_retencion;
                //                         $detalleVentaContable->setDcc_id_trans($addVenta);
                //                         $detalleVentaContable->setDcc_seq_detalle(0);
                //                         $detalleVentaContable->setDcc_cta_item_det($cuenta_retencion->idcodigo);
                //                         $detalleVentaContable->setDcc_det_item_det($cuenta_retencion->tipo_codigo);
                //                         $detalleVentaContable->setDcc_cod_art(0);
                //                         $detalleVentaContable->setDcc_cant_item_det($dataretenciones->cdi_stock_ingreso);
                //                         $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                //                         $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                //                         $detalleVentaContable->setDcc_d_c_item_det("D");
                //                         $detalleVentaContable->setDcc_valor_item($calculo_retencion);
                //                         $detalleVentaContable->setDcc_base_imp_item(0);
                //                         $detalleVentaContable->setDcc_base_ret_item($total_impuesto);
                //                         $detalleVentaContable->setDcc_fecha_vcto_item(0);
                //                         $detalleVentaContable->setDcc_dato_fact_prove("");
                //                         $addCodCostos=$detalleVentaContable->addArticulos();
                //                     }else{

                //                         $impuesto_articulo = ($dataretenciones->cdi_importe>0)?$dataretenciones->cdi_importe:1;

                //                         $total = $dataretenciones->cdi_precio_total_lote / (($impuesto_articulo/100)+1);
                //                         $retencion_total = $total * ($retencion->re_porcentaje/100);

                //                         $total_retenido +=$retencion_total;
                //                         $detalleVentaContable->setDcc_id_trans($addVenta);
                //                         $detalleVentaContable->setDcc_seq_detalle(0);
                //                         $detalleVentaContable->setDcc_cta_item_det($cuenta_retencion->idcodigo);
                //                         $detalleVentaContable->setDcc_det_item_det($cuenta_retencion->tipo_codigo);
                //                         $detalleVentaContable->setDcc_cod_art(0);
                //                         $detalleVentaContable->setDcc_cant_item_det($dataretenciones->cdi_stock_ingreso);
                //                         $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                //                         $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                //                         $detalleVentaContable->setDcc_d_c_item_det("D");
                //                         $detalleVentaContable->setDcc_valor_item($retencion_total);
                //                         $detalleVentaContable->setDcc_base_imp_item(0);
                //                         $detalleVentaContable->setDcc_base_ret_item($total);
                //                         $detalleVentaContable->setDcc_fecha_vcto_item(0);
                //                         $detalleVentaContable->setDcc_dato_fact_prove("");
                //                         $addCodCostos=$detalleVentaContable->addArticulos();
                //                     }
                                
                //                 }else{}
                //             }else{}    
                //         }
                //     }
                // }

                //recuperar cuenta de pago
                foreach ($getArticulos as $datapago) {
                    if($datapago->cdi_type == "CO"){
                        //cargaremos ahora de donde va a salir el dinero
                        if($formadepago == "Credito"){
                            
                            $cuenta = $puc->getPucById($formapago->fp_cuenta_contable);
                            foreach ($cuenta as $cuenta) {}

                            $detalleVentaContable->setDcc_id_trans($addVenta);
                            $detalleVentaContable->setDcc_seq_detalle(0);
                            $detalleVentaContable->setDcc_cta_item_det($cuenta->idcodigo);
                            $detalleVentaContable->setDcc_det_item_det($cuenta->tipo_codigo);
                            $detalleVentaContable->setDcc_cod_art(0);
                            $detalleVentaContable->setDcc_cant_item_det($datapago->cdi_stock_ingreso);
                            $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                            $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                            $detalleVentaContable->setDcc_d_c_item_det("D");
                            $detalleVentaContable->setDcc_valor_item($datapago->cdi_precio_total_lote- $total_retenido);
                            $detalleVentaContable->setDcc_base_imp_item(0);
                            $detalleVentaContable->setDcc_base_ret_item(0);
                            $detalleVentaContable->setDcc_fecha_vcto_item($end_date);
                            $detalleVentaContable->setDcc_dato_fact_prove($serieComprobante.zero_fill($ultimoNComprobante,8));
                            
                            $addCodigo=$detalleVentaContable->addArticulos();

                            //generar cartera de proveedores
                            
                            $cartera->setIdventa($addVenta);
                            $cartera->setFecha_pago($end_date);
                            $cartera->setTotal_pago(0);
                            $cartera->setDeuda_total($datapago->cdi_precio_total_lote - $total_retenido);
                            $cartera->setContabilidad(1);
                            $cartera->setC_estado("A");
                            $generarCartera = $cartera->generarCarteraCliente();

                        }else{

                            $cuenta = $puc->getPucById($datapago->cdi_idarticulo);
                            foreach ($cuenta as $cuenta) {}
    
                            //se agrega el el codigo contable de la forma de pago ya predefinida en la configuracion
                            $detalleVentaContable->setDcc_id_trans($addVenta);
                            $detalleVentaContable->setDcc_seq_detalle(0);
                            $detalleVentaContable->setDcc_cta_item_det($datapago->cdi_idarticulo);
                            $detalleVentaContable->setDcc_det_item_det($cuenta->tipo_codigo);
                            $detalleVentaContable->setDcc_cod_art(0);
                            $detalleVentaContable->setDcc_cant_item_det($datapago->cdi_stock_ingreso);
                            $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                            $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                            $detalleVentaContable->setDcc_d_c_item_det("D");
                            $detalleVentaContable->setDcc_valor_item($datapago->cdi_precio_total_lote - $total_retenido);
                            $detalleVentaContable->setDcc_base_imp_item(0);
                            $detalleVentaContable->setDcc_base_ret_item(0);
                            $detalleVentaContable->setDcc_fecha_vcto_item(0);
                            $detalleVentaContable->setDcc_dato_fact_prove(0);
                            $addCodigo=$detalleVentaContable->addArticulos();
                        }
                    }
                    }


                    foreach($getArticulos as $datainventario){
                        if($datainventario->cdi_type == "AR"){
                            $articulo = $dataarticulos->getArticuloById($datainventario->cdi_idarticulo);
                            foreach($articulo as $articulo){}
                            //codigo de inventario
                            $cuenta_inventario = $puc->getPucById($articulo->cod_inventario);
                            foreach($cuenta_inventario as $cuenta_inventario){}
                            if($cuenta_inventario != null){
                                $detalleVentaContable->setDcc_id_trans($addVenta);
                                $detalleVentaContable->setDcc_seq_detalle(0);
                                $detalleVentaContable->setDcc_cta_item_det($cuenta_inventario->idcodigo);
                                $detalleVentaContable->setDcc_det_item_det($articulo->nombre_articulo);
                                $detalleVentaContable->setDcc_cod_art(0);
                                $detalleVentaContable->setDcc_cant_item_det($datainventario->cdi_stock_ingreso);
                                $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                                $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                $detalleVentaContable->setDcc_d_c_item_det("C");
                                $detalleVentaContable->setDcc_valor_item($articulo->costo_producto*$datainventario->cdi_stock_ingreso);
                                $detalleVentaContable->setDcc_base_imp_item(0);
                                $detalleVentaContable->setDcc_base_ret_item(0);
                                $detalleVentaContable->setDcc_fecha_vcto_item(0);
                                $detalleVentaContable->setDcc_dato_fact_prove(0);
                                $addInventario=$detalleVentaContable->addArticulos(false);
                            }
                            //codigo de costos
                            $cuenta_costos = $puc->getPucById($articulo->cod_costos);
                            foreach($cuenta_costos as $cuenta_costos){}
                            if($cuenta_costos != null){
                                $detalleVentaContable->setDcc_id_trans($addVenta);
                                $detalleVentaContable->setDcc_seq_detalle(0);
                                $detalleVentaContable->setDcc_cta_item_det($cuenta_costos->idcodigo);
                                $detalleVentaContable->setDcc_det_item_det($articulo->nombre_articulo);
                                $detalleVentaContable->setDcc_cod_art(0);
                                $detalleVentaContable->setDcc_cant_item_det($datainventario->cdi_stock_ingreso);
                                $detalleVentaContable->setDcc_ter_item_det($datacliente->num_documento);
                                $detalleVentaContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                $detalleVentaContable->setDcc_d_c_item_det("D");
                                $detalleVentaContable->setDcc_valor_item($articulo->costo_producto * $datainventario->cdi_stock_ingreso);
                                $detalleVentaContable->setDcc_base_imp_item(0);
                                $detalleVentaContable->setDcc_base_ret_item(0);
                                $detalleVentaContable->setDcc_fecha_vcto_item(0);
                                $detalleVentaContable->setDcc_dato_fact_prove(0);
                                $addCostos=$detalleVentaContable->addArticulos(false);
                            }
                        }
                    }

                    if($addVenta){

                        //almacenar primer registro de venta al reporte de ventas
                        //preparar datos de turnos
                        $start_date = date("Y-m-d");
                        $start_time = date("H:i:s");
                        $cierreturno->setRct_idsucursal($_SESSION['idsucursal']);
                        $cierreturno->setRct_idusuario($_SESSION['usr_uid']);
                        $cierreturno->setRct_date($start_date);
                        $cierreturno->setRct_venta_desde($addVenta);
                        //por si no existe el reporte 
                        $cierreturno->setRct_descripcion("Reporte usuario ".$_SESSION['usr_uid']);
                        $cierreturno->setRct_fecha_inicio($start_date . " " . $start_time);
                        //limpiar carro de compras
                        $carro->deleteCart();
                        
                        $authInicio = $cierreturno->authInicio();
                        if($authInicio){
                            foreach($authInicio as $authInicio){}
                            if($authInicio->rct_venta_desde == 0 && $authInicio->rct_date == $start_date && $authInicio->rct_idsucursal == $_SESSION['idsucursal'] && $authInicio->rct_idusuario == $_SESSION['usr_uid']){
                                $cierreturno->addInicioVenta();
                            }
                        //procede aqui si existe el registro para reporte, seria solo actualizar
                        
                        }else{
                        
                        }

                    if($fe){

                        $facturacionelectronica->setIdventa($addVenta);
                        $facturacionelectronica->setFe_type('C');
                        $facturacionelectronica->setInvoiceType("FACTURA");
                        $facturacionelectronica->setFe_code(10);
                        $response = $facturacionelectronica->generate($this->adapter);

                            echo json_encode(array("success"=>"file/comprobantes/$addVenta","type"=>"message","response"=>$response,"alertType"=>"success"));
                        }else{
                            echo json_encode(array("success"=>"file/comprobantes/$addVenta","type"=>"redirect","response"=>""));
                        }
                    }else{
                        echo json_encode(array("error"=>"Error al crear esta venta","type"=>"message","response"=>"Error al crear esta venta"));
                    }
                    }else{
                        echo json_encode(array("error"=>"Debe indicar una cartera o cuenta de pago","type"=>"message","response"=>"Error al crear esta venta"));
    
                    }
                    }

                }else{
                    echo json_encode(array("error"=>"Cliente inexistente"));
                }

            }else{
                echo json_encode(array("error"=>"debe ingresar algun cliente"));
            }
        }else{
            echo json_encode(array("error"=>"Error desconocido, salga de la venta e intentelo de nuevo"));
        }
    }

    public function edit_venta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
            if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] > 4){
                $sucursal = new Sucursal($this->adapter);
                $comprobante = new Comprobante($this->adapter);
                $formapago= new FormaPago($this->adapter);
                $ventas = new Ventas($this->adapter);
                $detalleventa = new DetalleVenta($this->adapter);
                $cart = new ColaIngreso($this->adapter);
                $impuesto = new Impuestos($this->adapter);
                $retencion = new Retenciones($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $detalleimpuesto = new DetalleImpuesto($this->adapter);
                $detalleretencion = new DetalleRetencion($this->adapter);
                $colaimpuesto = new ColaImpuesto($this->adapter);
                $colaretencion = new ColaRetencion($this->adapter);

                //functions
                $impuestos = $impuesto->getImpuestosAll();
                $retenciones = $retencion->getRetencionesAll();

                $getsucursal= $sucursal->getSucursalById($idsucursal);
                //obtener datos de usuario
                $idusuario = $_SESSION["usr_uid"];
                //ubicacion
                $pos_proceso ="Venta";
                $contabilidad = 0;
                $control_proceso="";
                //comprobante
                
                $comprobantes = $comprobante->getComprobante($pos_proceso);
                //formas de pago
                $formaspago = $formapago->getFormaPago($pos_proceso);
                $idventa = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
                if($idventa){
                    
                    $venta = $ventas->getVentaById($idventa);
                    foreach ($venta as $dataventa) {}
                    
                    $detalle = $detalleventa->getArticulosByVenta($idventa);
                    $detalleimpuestos = $detalleimpuesto->getImpuestosBy($idventa,'0','V');
                    $detalleretenciones = $detalleretencion->getRetencionBy($idventa,'0','V');
                    
                    //crear carro padre
                    $cart->setCi_usuario($_SESSION["usr_uid"]);
                    $cart->setCi_idsucursal($_SESSION["idsucursal"]);
                    $cart->setCi_idproveedor(0);
                    $cart->setCi_tipo_pago(0);
                    $cart->setCi_comprobante(0);
                    $cart->setCi_fecha(date("Y-m-d"));
                    $cart->setCi_fecha_final(date("Y-m-d"));
                    $addCart = $cart->createCart();

                    foreach ($detalle as $dataitems) {
                        $cart->setCdi_ci_id($addCart);
                        $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                        $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                        $cart->setCdi_tercero($dataventa->nombre_cliente);
                        $cart->setCdi_idarticulo($dataitems->idarticulo);
                        $cart->setCdi_stock_ingreso($dataitems->cantidad);
                        $cart->setCdi_precio_unitario($dataitems->precio_unitario); 
                        $cart->setCdi_importe($dataitems->importe_categoria);
                        $cart->setCdi_precio_total_lote($dataitems->precio_total_lote);
                        $cart->setCdi_credito($dataitems->precio_total_lote);
                        $cart->setCdi_debito($dataitems->precio_total_lote);
                        $cart->setCdi_cod_costos("0");
                        $cart->setCdi_type("AR");
                        $result = $cart->addItemToCart();
                    }

                    //almacenar impuestos y retenciones al carro
                    foreach($detalleimpuestos as $impuestoactual){
                        $colaimpuesto->setCdim_ci_id($addCart);
                        $colaimpuesto->setCdim_idcomprobante(0);
                        $colaimpuesto->setCdim_im_id($impuestoactual->dig_im_id);
                        $colaimpuesto->setCdim_contabilidad(0);
                        $loadImpuestos = $colaimpuesto->addImpuesto();
                    }
                    foreach($detalleretenciones as $retencionactual){
                        $colaretencion->setCdr_ci_id($addCart);
                        $colaretencion->setCdr_re_id($retencionactual->drg_re_id);
                        $colaretencion->setCdr_contabilidad(0);
                        $loadRetenciones = $colaretencion->addRetencion();
                    }

                    //recuperar el carrito previamente creado
                    $items = $cart->loadCart();

                    $this->frameview("ventas/Edit/index",array(
                        "venta"=>$venta,
                        "contabilidad"=>$contabilidad,
                        "sucursal"=>$getsucursal,
                        "idusuario"=>$idusuario,
                        "pos"=>$pos_proceso,
                        "control"=>$control_proceso,
                        "comprobantes" => $comprobantes,
                        "formaspago" => $formaspago,
                        "items"=>$items,
                        "impuestos"=>$impuestos,
                        "retenciones"=>$retenciones
                    ));

                }
            }
        }else{

        }
    }

    public function edit_venta_contable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
            if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] > 4){
                //models
                $sucursal = new Sucursal($this->adapter);
                $ventas = new ComprobanteContable($this->adapter);
                $detalleventa = new DetalleComprobanteContable($this->adapter);
                $comprobante = new Comprobante($this->adapter);
                $formapago= new FormaPago($this->adapter);
                $articulos = new Articulo($this->adapter);
                $impuesto = new Impuestos($this->adapter);
                $retencion = new Retenciones($this->adapter);
                $detalleimpuesto = new DetalleImpuesto($this->adapter);
                $detalleretencion = new DetalleRetencion($this->adapter);
                $colaimpuesto = new ColaImpuesto($this->adapter);
                $colaretencion = new ColaRetencion($this->adapter);
                $puc = new PUC($this->adapter);
                //functions
                $getsucursal= $sucursal->getSucursalById($idsucursal);
                $impuestos = $impuesto->getImpuestosAll();
                $retenciones = $retencion->getRetencionesAll();
                //obtener datos de usuario
                $idusuario = $_SESSION["usr_uid"];
                //ubicacion
                $pos_proceso ="Venta";
                $contabilidad = "Contable";
                $autocomplete= "codigo_contable";
                $control_proceso="";
                //comprobante
                
                $comprobantes = $comprobante->getComprobante($pos_proceso);
                //formas de pago
                
                $formaspago = $formapago->getFormaPago($pos_proceso);
                $idventa = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
                if($idventa){
                    $venta = $ventas->getComprobanteById($idventa);
                    foreach ($venta as $dataventa) {}

                    $detalle = $detalleventa->getArticulosByComprobante($idventa);
                    $detalleimpuestos = $detalleimpuesto->getImpuestosBy($idventa,'1','V');
                    $detalleretenciones = $detalleretencion->getRetencionBy($idventa,'1','V');

                    $cart = new ColaIngreso($this->adapter);
                    $cart->setCi_usuario($_SESSION["usr_uid"]);
                    $cart->setCi_idsucursal($_SESSION["idsucursal"]);
                    $cart->setCi_idproveedor(0);
                    $cart->setCi_tipo_pago(0);
                    $cart->setCi_comprobante(0);
                    $cart->setCi_fecha(date("Y-m-d"));
                    $cart->setCi_fecha_final(date("Y-m-d"));
                    $addCart = $cart->createCart();
                    $costo_total_articulos=0;
                    foreach ($detalle as $dataitems) {
                        //obtener articulo  y categoria
                        if($dataitems->dcc_cod_art){
                            $articulo = $articulos->getArticuloById($dataitems->dcc_cod_art);
                            foreach($articulo as $articulo){}
                            $cart->setCdi_ci_id($addCart);
                            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                            $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                            $cart->setCdi_tercero($dataventa->nombre_tercero);
                            $cart->setCdi_idarticulo($dataitems->dcc_cod_art);
                            $cart->setCdi_stock_ingreso($dataitems->dcc_cant_item_det);
                            $cart->setCdi_precio_unitario($dataitems->dcc_valor_item / $dataitems->dcc_cant_item_det); 
                            $cart->setCdi_importe($dataitems->dcc_base_imp_item);
                            $cart->setCdi_precio_total_lote($dataitems->dcc_valor_item *(($dataitems->dcc_base_imp_item /100)+1));
                            $cart->setCdi_credito($dataitems->dcc_valor_item *(($dataitems->dcc_base_imp_item /100)+1));
                            $cart->setCdi_debito($dataitems->dcc_valor_item *(($dataitems->dcc_base_imp_item /100)+1));
                            $cart->setCdi_cod_costos("0");
                            $cart->setCdi_type("AR");
                            $result = $cart->addItemToCart();
                            $costo_total_articulos += $dataitems->dcc_valor_item *(($dataitems->dcc_base_imp_item /100)+1);
                        }else{
                            //filtro para pasar solo las cuentas con centro de costos activo
                            $readPUC = $puc->getPucById($dataitems->dcc_cta_item_det);
                            if($readPUC){
                                foreach($readPUC as $cuenta){}
                                if($cuenta->centro_costos ==1 && $cuenta->movimiento){
                                    $cart->setCdi_ci_id($addCart);
                                    $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                                    $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                                    $cart->setCdi_tercero($dataventa->nombre_tercero);
                                    $cart->setCdi_idarticulo($dataitems->dcc_cta_item_det);
                                    $cart->setCdi_stock_ingreso($dataitems->dcc_cant_item_det);
                                    $cart->setCdi_precio_unitario($costo_total_articulos); 
                                    $cart->setCdi_importe($dataitems->dcc_base_imp_item);
                                    $cart->setCdi_precio_total_lote($costo_total_articulos);
                                    $cart->setCdi_credito($costo_total_articulos);
                                    $cart->setCdi_debito($costo_total_articulos);
                                    $cart->setCdi_cod_costos("0");
                                    $cart->setCdi_type("CO");
                                    $result = $cart->addItemToCart();
                                }
                            }
                        }
                    }
                    //recuperar impuestos y retenciones

                    foreach($detalleimpuestos as $impuestoactual){
                        $colaimpuesto->setCdim_ci_id($addCart);
                        $colaimpuesto->setCdim_idcomprobante(0);
                        $colaimpuesto->setCdim_im_id($impuestoactual->dig_im_id);
                        $colaimpuesto->setCdim_contabilidad(1);
                        $loadImpuestos = $colaimpuesto->addImpuesto();
                    }
                    foreach($detalleretenciones as $retencionactual){
                        $colaretencion->setCdr_ci_id($addCart);
                        $colaretencion->setCdr_re_id($retencionactual->drg_re_id);
                        $colaretencion->setCdr_contabilidad(1);
                        $loadRetenciones = $colaretencion->addRetencion();
                    }

                    //recuperar el carrito previamente creado

                    $cart = new ColaIngreso($this->adapter);
                    $items = $cart->loadCart();

                    $this->frameview("ventas/contable/edit",array(
                        "venta"=>$venta,
                        "contabilidad"=>$contabilidad,
                        "sucursal"=>$getsucursal,
                        "idusuario"=>$idusuario,
                        "pos"=>$pos_proceso,
                        "control"=>$control_proceso,
                        "comprobantes" => $comprobantes,
                        "formaspago" => $formaspago,
                        "autocomplete"=>$autocomplete,
                        "items"=>$items,
                        "impuestos"=>$impuestos,
                        "retenciones"=>$retenciones
                    ));

                }
            }
        }else{

        }
    }

    public function updateVenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            if(isset($_POST["idventa"]) && !empty($_POST["idventa"])){
                $idventa = $_POST["idventa"];
                $ventas = new Ventas($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $cartera = new Cartera($this->adapter);
                $detalleVenta = new DetalleVenta($this->adapter);
                /******************************************************************************************/
                //dividir estring
                $array = explode(" - ", $_POST["proveedor"]);
                $cliente = new Persona($this->adapter);
                $i =0;
                foreach ($array as $search) {$getCliente = $cliente->getClienteByDocument($array[$i]);
                    //si se encontro algo en clientes lo retorna
                foreach ($getCliente as $datacliente) {}
                $i++;
                }
                //recuperar id del cliente
                $idcliente = $datacliente->idpersona;
                if($idcliente > 0){
                //obtener carro de articulos
                $carro = new ColaIngreso($this->adapter);
                $getCart = $carro->getCart();
                foreach ($getCart as $getCart){}
                $getArticulos = $carro->getArtByCart($getCart->ci_id);
                $dataformapago = new FormaPago($this->adapter);
                $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
                foreach ($formapago as $formapago) {}
                $formapago = $formapago->fp_nombre;
                //articulos recuperados
                $subtotal = $carro->getSubTotal($getCart->ci_id);
                //calcular el tocal incluyendo impuestos y retenciones
                $totalVenta = $this->calculoVenta2($_POST["comprobante"]);
                foreach ($subtotal as $subtotal) {}
                $calcsubtotal = $subtotal->cdi_debito;
                //start date configuracion
                $start_date = date_format_calendar($_POST["start_date"],"/");
                $end_date = date_format_calendar($_POST["end_date"],"/");
                if($getArticulos != null){
                    //despues de autorizar la actualizacion descontamos los articulos del stock 
                    //se obtiene la venta
                    $actual_venta = $ventas->getVentaById($idventa);
                    foreach($actual_venta as $actual_venta){}
                    //se obtienen los articulos
                     $articles_actual_venta = $detalleVenta->getArticulosByVenta($actual_venta->idventa);
                     //se recorre cada articulo
                     foreach($articles_actual_venta as $actual_articles){
                         //se agregan la cantidad que habia anteriormente para luego almacenarlos de nuevo
                        $articulo = $dataarticulos->addCantStock($actual_articles->idarticulo,$actual_articles->cantidad);
                     }
                    //setear los datos para manipularlos luego
                    $ventas->setIdusuario($_SESSION['usr_uid']);
                    $ventas->setIdsucursal($_SESSION['idsucursal']);
                    $ventas->setIdCliente($idcliente);
                    $ventas->setTipo_pago($formapago);
                    $ventas->setFecha($start_date);
                    $ventas->setFecha_final($end_date);
                    $ventas->setImpuesto("19");
                    $ventas->setSub_total($totalVenta);
                    $ventas->setSubtotal_importe("0");
                    $ventas->setTotal($totalVenta);
                    $ventas->setImporte_pagado($totalVenta);
                    $ventas->setEstado("A");
                    //terminado los datos manipulados se actualiza la venta
                    $updateVenta = $ventas->updateVenta($idventa);
                    //si se actualiza
                    if($updateVenta){
                        //tratamiento de la cartera
                        if($formapago == "Credito"){
                            $cartera->setIdventa($idventa);
                            $cartera->setFecha_pago($end_date);
                            $cartera->setTotal_pago(0);
                            $cartera->setDeuda_total($totalVenta);
                            $cartera->setCp_estado("A");
                            //ver si existe esta cartera
                            $existVenta = $cartera->getCarteraClienteByVenta($idventa);
                            if($existVenta){
                                $generarCartera = $cartera->updateCarteraCliente($idventa);
                            }else{
                                $generarCartera = $cartera->generarCarteraCliente();
                            }
                        }else{
                            $generarCartera = $cartera->deleteCarteraCliente($idventa);
                        }
                        //si se ha manipulado la cartera correctamente
                        
                            //limpiar detalle de articulos antiguo
                            $deleteLastDetalleVenta = $detalleVenta->deleteDetalleVentaById($idventa);
                            $impuesto_venta=0;
                            foreach ($getArticulos as $articulos) {
                                 //guardando en variables
                                $idarticulo = $articulos->cdi_idarticulo;
                                $stock_ingreso =$articulos->cdi_stock_ingreso;
                                $impuesto = $articulos->cdi_importe;
                                $precio_unitario = $articulos->cdi_precio_unitario;
                                $total =$articulos->cdi_precio_total_lote;
                                $cod_costos =$articulos->cdi_cod_costos;
                                $impuesto_venta+=$total -($total /(($impuesto/100)+1));
                                //guardar cada articulo en el ingreso
                                $detalleVenta->setIdventa($idventa);
                                $detalleVenta->setIdarticulo($idarticulo);
                                $detalleVenta->setCantidad($stock_ingreso);
                                $detalleVenta->setPrecio_venta($precio_unitario);
                                $detalleVenta->setIva_compra($total -($total /(($impuesto/100)+1)));
                                $detalleVenta->setImporte_categoria($impuesto);
                                $detalleVenta->setPrecio_total_lote($total);
                                $detalleVenta->setEstado("A");
                                $addArticulos = $detalleVenta->addArticulos();
                                //agregar stock 
                                $articulo = $dataarticulos->removeCantStock($idarticulo,$stock_ingreso);
                                //eliminar carro
                                $carro->deleteCart();
                            }
                            if($articulo){
                                $ventas->setSubtotal_importe($impuesto_venta);
                                $impuestoventa = $ventas->addImpuestoVenta($idventa);
                                echo json_encode(array("success"=>$idventa));
                            }

                    }

                }else{}
            }else{
                echo json_encode(array("error"=>"debe agregar un cliente"));
            }
            }
        }else{
            echo json_encode(array("error"=>"No se puede actualizar esta venta, es probable que no tengas permisos"));
        }
    }

    public function delete_contable(){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4 ){
            if(isset($_GET["data"]) && !empty($_GET["data"]) && $_GET["data"] >0){
                $idventa=$_GET["data"];
                $this->delete_venta_contable($idventa,false);
                $success = "Accion realizada, verifica la lista de facturas contables";
                $this->frameview("alert/success/successSmall",array("success"=>$success));
            }else{
                $error = "Esta factura no se puede eliminar";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error = "No tienes permisos";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        } 
    }

    public function delete_venta_contable($idventa,$alert){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4 && !empty($idventa) && $idventa > 0){
            //models
                $ventacontable = new ComprobanteContable($this->adapter);
                $detalleVentaContable = new DetalleComprobanteContable($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $cartera = new Cartera($this->adapter);
                $tokenization = new Tokenization($this->adapter);
                $user = new User($this->adapter);

                $venta = $ventacontable->getComprobanteById($idventa);
                foreach($venta as $venta){}
                if($venta->cc_id_transa){
                     $detalleventa= $detalleVentaContable->getArticulosByComprobante($venta->cc_id_transa);
                            foreach($detalleventa as $detalleventa){
                                    if($detalleventa->dcc_cod_art){
                                        $dataarticulos->addCantStock($detalleventa->dcc_cod_art,$detalleventa->dcc_cant_item_det);
                                    }
                                }
                            $detalleVentaContable ->deleteDetalleComprobante($venta->cc_id_transa);
                            $ventacontable->delete_comprobante($venta->cc_id_transa);
                            $cartera->deleteCarteraCliente($venta->cc_id_transa);
                        }else{
                            if($alert){
                            echo json_encode(array("error"=>"no tienes todos los permisos necesarios"));
                            }
                        } 
        }else{
            if($alert){
            echo json_encode(array("error"=>"Forbiden gateway"));
            }
        }
    }
    /*                                                           REPORTES                                                    */
    #############################################################################################################################
    public function general()
    {
        
        $control = "ventas";
        $pos = "reporte_general";
        $venta = new Ventas($this->adapter);
        $ventas = $venta->getVentasAll();

        $this->frameview("ventas/reportes/general/general",array(
            "ventas"=>$ventas,
            "pos"=>$pos,
            "control"=>$control
        ));
    }
    public function reporte_general()
    {
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate =$_POST["end_date"];
            $array_end_date = explode("/", $enddate);
            foreach ($array_end_date as $enddate) {}
            $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
            $venta = new Ventas($this->adapter);
            $ventas = $venta->reporte_general($start_date,$end_date);
            $this->frameview("ventas/reportes/general/tableGeneral",array(
                "ventas"=>$ventas
            ));

        }else{
            
        }
        
    }
    #############################################################################################################################
    public function detallado()
    {
        $control = "ventas";
        $pos = "reporte_detallado";
        $venta = new Ventas($this->adapter);
        $ventas = $venta->getVentasDetalladas();

        $this->frameview("ventas/reportes/detallada/detallada",array(
            "ventas"=>$ventas,
            "pos"=>$pos,
            "control"=>$control
        ));
    }

    public function reporte_detallado()
    {
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate =$_POST["end_date"];
            $array_end_date = explode("/", $enddate);
            foreach ($array_end_date as $enddate) {}
            $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
            $venta = new Ventas($this->adapter);
            $ventas = $venta->reporte_detallado($start_date,$end_date);
            $this->frameview("ventas/reportes/detallada/tableDetallada",array(
                "ventas"=>$ventas
            ));

        }else{
            
        }
    }
    #############################################################################################################################
    public function pendiente()
    {
        $control = "ventas";
        $pos = "reporte_pendiente";
        $venta = new Ventas($this->adapter);
        $ventas = $venta->getVentasPendiente();

        $this->frameview("ventas/reportes/pendiente/pendiente",array(
            "ventas"=>$ventas,
            "pos"=>$pos,
            "control"=>$control
        ));
    }

    public function reporte_pendiente()
    {
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate =$_POST["end_date"];
            $array_end_date = explode("/", $enddate);
            foreach ($array_end_date as $enddate) {}
            $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
            $venta = new Ventas($this->adapter);
            $ventas = $venta->reporte_pendiente($start_date,$end_date);
            $this->frameview("ventas/reportes/pendiente/tablePendiente",array(
                "ventas"=>$ventas
            ));

        }else{
            
        }
        
    }
    #############################################################################################################################

    public function contado()
    {
        $control = "ventas";
        $pos = "reporte_contado";
        $venta = new Ventas($this->adapter);
        $ventas = $venta->getVentasContado();

        $this->frameview("ventas/reportes/contado/contado",array(
            "ventas"=>$ventas,
            "pos"=>$pos,
            "control"=>$control
        ));
    }
    public function reporte_contado()
    {
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate =$_POST["end_date"];
            $array_end_date = explode("/", $enddate);
            foreach ($array_end_date as $enddate) {}
            $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
            $venta = new Ventas($this->adapter);
            $ventas = $venta->reporte_contado($start_date,$end_date);
            $this->frameview("ventas/reportes/contado/tableContado",array(
                "ventas"=>$ventas
            ));

        }else{
            
        }
        
    }
    #############################################################################################################################
    public function credito()
    {
        $control = "ventas";
        $pos = "reporte_credito";
        $venta = new Ventas($this->adapter);
        $ventas = $venta->getVentasCredito();

        $this->frameview("ventas/reportes/credito/credito",array(
            "ventas"=>$ventas,
            "pos"=>$pos,
            "control"=>$control
        ));
    }

    public function reporte_credito()
    {
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate =$_POST["end_date"];
            $array_end_date = explode("/", $enddate);
            foreach ($array_end_date as $enddate) {}
            $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
            $venta = new Ventas($this->adapter);
            $ventas = $venta->reporte_credito($start_date,$end_date);
            $this->frameview("ventas/reportes/credito/tableCredito",array(
                "ventas"=>$ventas
            ));

        }else{
            
        }
        
    }
    #############################################################################################################################
    public function cliente()
    {
        $control = "ventas";
        $pos = "reporte_cliente";
        $venta = new Ventas($this->adapter);
        $ventas = $venta->getVentasCredito();

        $this->frameview("ventas/reportes/cliente/cliente",array(
            "ventas"=>$ventas,
            "pos"=>$pos,
            "control"=>$control
        ));
    }

    public function reporte_cliente()
    {
        if(isset($_POST["cliente"]) && !empty($_POST["cliente"])){
            $array = explode(" - ", $_POST["cliente"]);
            $cliente = new Persona($this->adapter);
            $i =0;
            foreach ($array as $search) {$getClientes = $cliente->getClienteByDocument($array[$i]);
                //si se encontro algo en proveedores lo retorna
                foreach ($getClientes as $datacliente) {}
                    $i++;
            }

            $num_documento = $datacliente->num_documento;
            $venta = new Ventas($this->adapter);
            $ventas = $venta->reporte_cliente($num_documento);
            
            $this->frameview("ventas/reportes/cliente/tableCliente",array(
                "ventas"=>$ventas
            ));

        }else{
            
        }
    }
    #############################################################################################################################
    public function historyByClient()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
                if(isset($_POST["tercero"]) && !empty($_POST["tercero"])){
                    //models
                    $cliente = new Persona($this->adapter);
                    //
                    $datacliente =[];
                    $array = explode(" - ", $_POST["tercero"]);
                    $i =0;
                    foreach ($array as $search) {
                        $getCliente = $cliente->getClienteByDocument($array[$i]);
                        //si se encontro algo en proveedores lo retorna
                        foreach ($getCliente as $datacliente) {}
                        $i++;
                    }
                    //id cliente recuperado
                    
                    if($datacliente){
                        $idcliente = $datacliente->idpersona;
                        $detalle = new DetalleVenta($this->adapter);
                        $datadetalle = $detalle->historyByClient($idcliente);
                        $this->frameview("ventas/reportes/historyClient",array("detalle"=>$datadetalle));
                    }else{
                        $error = "Hubo problema con este cliente";
                        $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
                    }
                }else{
                    $error = "Agrega un cliente valido";
                    $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
                }
        }else{
            $error = "No tienes permisos";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

}
?>