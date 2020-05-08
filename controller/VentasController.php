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

    public function nueva_venta()
    {
        $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
        if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] >= 3){
        
        $sucursal = new Sucursal($this->adapter);
        $getsucursal= $sucursal->getSucursalById($idsucursal);
        //obtener datos de usuario
        $idusuario = $_SESSION["usr_uid"];
        //ubicacion
        $pos_proceso ="Venta";
        $contabilidad = "";
        $control_proceso="";
        $autocomplete= "autocomplete_articulo";
        //comprobante
        $comprobante = new Comprobante($this->adapter);
        $comprobantes = $comprobante->getComprobante($pos_proceso);
        //formas de pago
        $formapago= new FormaPago($this->adapter);
        $formaspago = $formapago->getFormaPago($pos_proceso);

        $cart = new ColaIngreso($this->adapter);
        $items = $cart->loadCart();

        $this->frameview("ventas/New/new",array(
            "contabilidad"=>$contabilidad,
            "sucursal"=>$getsucursal,
            "idusuario"=>$idusuario,
            "autocomplete"=>$autocomplete,
            "pos"=>$pos_proceso,
            "control"=>$control_proceso,
            "comprobantes" => $comprobantes,
            "formaspago" => $formaspago,
            "items"=>$items
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
            //functions
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
            $items = $cart->loadCart();

            $this->frameview("ventas/New/new",array(
                "contabilidad"=>$contabilidad,
                "sucursal"=>$getsucursal,
                "idusuario"=>$idusuario,
                "pos"=>$pos_proceso,
                "autocomplete"=>$autocomplete,
                "control"=>$control_proceso,
                "comprobantes" => $comprobantes,
                "formaspago" => $formaspago,
                "items"=>$items
            ));

        }else{echo "Forbidden gateway";}
    }

    public function sendItem()
    {
        if($_POST["data"] && !empty($_POST["data"]) && !empty($_POST["tercero"]) && !empty($_POST["pos"])){
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
            //calcular 
            $total_iva = ($costo_producto * $cantidad) *(($ivaarticulo/100));
            $total_lote = ($costo_producto * $cantidad) + $total_iva;
            //posicion de pagina
            $debito=$total_lote;
            $credito =$total_lote;
            
            //agregar articulo al carro
            $cart = new ColaIngreso($this->adapter);
            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
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
            $result = $cart->addItemToCart();

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
            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
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
            
            $result = $cart->addItemToCart();
            }
            echo json_encode($result);
        }
        
    }

    public function calculoVenta()
    {
        if(isset($_POST["data"]) && !empty($_POST["data"])){

            $contabilidad = $_POST["contabilidad"];
            $dataretenciones = new Retenciones($this->adapter);
            $retenciones = $dataretenciones->getRetencionesByComprobanteId($_POST["data"]);

            $dataimpuestos= new Impuestos($this->adapter);
            $impuestos = $dataimpuestos->getImpuestosByComprobanteId($_POST["data"]);

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
            echo $this->frameview("compras/New/calculoCompra",array(
                "retenciones"=>$listRetencion,
                "impuestos"=>$listImpuesto,
                "total_bruto"=>$total_bruto,
                "total_neto"=>$total_neto,
            ));
        
        }else{
            
        }
    }
    public function calculoVenta2($idcomprobante)
    {

            $contabilidad = $_POST["contabilidad"];
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

            return $total_neto;
            
    }

    public function crearVenta()
    {
        //ver que los datos se enviaron
        if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 0){
            $venta = new Ventas($this->adapter);
            $dataarticulos = new Articulo($this->adapter);
            /******************************************************************************************/
            //dividir estring
            $array = explode(" - ", $_POST["proveedor"]);
            $cliente = new Persona($this->adapter);
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
            $comprobantes = new Comprobante($this->adapter);
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
            $carro = new ColaIngreso($this->adapter);
            $getArticulos = $carro->getCart();
            //obtener tipo de pago
            $dataformapago = new FormaPago($this->adapter);
            $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
            foreach ($formapago as $formapago) {}
            $formapago = $formapago->fp_nombre;
            //articulos recuperados
            $subtotal = $carro->getSubTotal();

            foreach ($subtotal as $subtotal) {}
            $calcsubtotal = $subtotal->cdi_debito;
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
            
                $venta->setIdsucursal($_SESSION['idsucursal']);
                $venta->setIdCliente($idproveedor);
                $venta->setIdusuario($_SESSION['usr_uid']);
                $venta->setTipo_pago($formapago);
                $venta->setTipo_comprobante($tipoComprobante);
                $venta->setSerie_comprobante($serieComprobante);
                $venta->setNum_comprobante($ultimoNComprobante);
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

                    echo json_encode(array("success"=>$idventa));
                }
            } else{
                echo json_encode(array("error"=>"Error al ingresar la compra. refresca la pagina e intentalo de nuevo"));
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
                $ventacontable = new VentaContable($this->adapter);
                $detalleVentaContable = new DetalleVentaContable($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $puc = new PUC($this->adapter);
                $impuestos = new Impuestos($this->adapter);
                $retenciones = new Retenciones($this->adapter);
                $categorias = new Categoria($this->adapter);
                $mymediafiles= new MyMediaFiles($this->adapter);
                $clientes = new Persona($this->adapter);
                $comprobantes = new Comprobante($this->adapter);
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
                    //usar comprobante
                    $usarcomprobante = $comprobantes->usarComprobante($idcomprobante);
                    //recuperar comprobante
                    $getComprobanteByid = $comprobantes->getComprobanteById($idcomprobante);
                    foreach ($getComprobanteByid as $comprobante) {}
                    /*************info comprobante***********/
                    $tipoComprobante =$comprobante->iddetalle_documento_sucursal;
                    $serieComprobante = $comprobante->ultima_serie;
                    $ultimoNComprobante = $comprobante->ultimo_numero;
                    $comprobanteContable = $comprobante->contabilidad;
                    /***********fin info comprobante*********/

                     //obtener carro de articulos
                    $carro = new ColaIngreso($this->adapter);
                    $getArticulos = $carro->getAllCart();
                    //obtener tipo de pago
                    $dataformapago = new FormaPago($this->adapter);
                    $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
                    foreach ($formapago as $formapago) {}
                    $formadepago = $formapago->fp_nombre;

                    //articulos recuperados
                    $subtotal = $carro->getSubTotal();
                    $total = $this->calculoVenta2($_POST["comprobante"]);
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
                                //indicamos que el estado es activo
                                $state =1;
                            }
                        }
                        if($state == 1){
                        $ventacontable->setVc_idusuario($_SESSION["usr_uid"]);
                        $ventacontable->setVc_idproveedor($datacliente->idpersona);
                        $ventacontable->setVc_id_forma_pago($forma_pago);
                        $ventacontable->setVc_id_tipo_cpte($tipoComprobante);
                        $ventacontable->setVc_num_cpte($serieComprobante);
                        $ventacontable->setVc_cons_cpte(zero_fill($ultimoNComprobante,8));
                        $ventacontable->setVc_det_fact_cli($tipoComprobante." ".$serieComprobante.zero_fill($ultimoNComprobante,8));
                        $ventacontable->setVc_fecha_cpte($start_date);
                        $ventacontable->setVc_fecha_final_cpte($end_date);
                        $ventacontable->setVc_nit_cpte($datacliente->num_documento);
                        $ventacontable->setVc_dig_verifi(0);
                        $ventacontable->setVc_ccos_cpte($_SESSION["idsucursal"]);
                        $ventacontable->setVc_fp_cpte($formadepago);
                        $ventacontable->setVc_estado("A");
                        $ventacontable->setVc_log_reg($_SESSION['usr_uid']."_".$start_date."_".date("h:i:s"));
                        $addVenta = $ventacontable->saveIngresoContable();

                        $total_retenido =0;
                        foreach ($getArticulos as $item) {
                            if($item->cdi_type == "AR"){
                                $articulo = $dataarticulos->getArticuloById($item->cdi_idarticulo);
                                foreach ($articulo as $articulo) {}
                                //almacenando articulos
                                $addStock = $dataarticulos->removeCantStock($item->cdi_idarticulo,$item->cdi_stock_ingreso);
                                //obtener la categoria de este articulo
                                $categoria = $categorias->getCategoriaById($articulo->idcategoria);
                                foreach ($categoria as $categoria) {}
                                //obtener codigo contable de la categoria -> codigo de inventario
                                $cuenta_inventario = $puc->getPucById($categoria->cod_venta);
                                foreach ($cuenta_inventario as $cuenta_inventario) {}
                                $inventario = ($cuenta_inventario !=null)? $cuenta_inventario->idcodigo:$categoria->cod_venta;
        
                                $detalleVentaContable->setDvc_id_trans($addVenta);
                                $detalleVentaContable->setDvc_seq_detalle(0);
                                $detalleVentaContable->setDvc_cta_item_det($inventario);
                                $detalleVentaContable->setDvc_det_item_det($articulo->nombre_articulo);
                                $detalleVentaContable->setDvc_cant_item_det($item->cdi_stock_ingreso);
                                $detalleVentaContable->setDvc_ter_item_det($datacliente->num_documento);
                                $detalleVentaContable->setDvc_ccos_item_det($_SESSION["idsucursal"]);
                                $detalleVentaContable->setDvc_d_c_item_det("C");
                                $detalleVentaContable->setDvc_valor_item($item->cdi_precio_unitario*$item->cdi_stock_ingreso);
                                $detalleVentaContable->setDvc_base_ret_item(0);
                                $detalleVentaContable->setDvc_fecha_vcto_item(0);
                                $detalleVentaContable->setDvc_dato_fact_prove("");
                                $addItem=$detalleVentaContable->addArticulos();

                            }else{}
                            //recorrer cada articulo y buscar sus impuestos
                        foreach ($getArticulos as $dataimpuestos) {
                        if($dataimpuestos->cdi_type == "AR"){

                        $listImpuestos = $impuestos->getImpuestosByComprobanteId($tipoComprobante);

                        foreach ($listImpuestos as $listImpuesto) {
                            $dataimpuesto = $impuestos->getBy('im_porcentaje',$dataimpuestos->cdi_importe);
                            foreach ($dataimpuesto as $impuesto) {}
                        }

                        $cuenta_impuesto = ($impuesto->im_cta_contable > 0)?$impuesto->im_cta_contable:0;

                        if($cuenta_impuesto){
                        $cuenta_codigo = $puc->getPucById($impuesto->im_cta_contable);
                        foreach ($cuenta_codigo as $cuenta) {}
                        $precio_total_lote_sin_iva = $dataimpuestos->cdi_precio_total_lote / (($impuesto->im_porcentaje /100)+1);

                        $detalleVentaContable->setDvc_id_trans($addVenta);
                        $detalleVentaContable->setDvc_seq_detalle(0);
                        $detalleVentaContable->setDvc_cta_item_det($cuenta_impuesto);
                        $detalleVentaContable->setDvc_det_item_det($cuenta->tipo_codigo);
                        $detalleVentaContable->setDvc_cant_item_det($dataimpuestos->cdi_stock_ingreso);
                        $detalleVentaContable->setDvc_ter_item_det($datacliente->num_documento);
                        $detalleVentaContable->setDvc_ccos_item_det($_SESSION["idsucursal"]);
                        $detalleVentaContable->setDvc_d_c_item_det("C");
                        $detalleVentaContable->setDvc_valor_item($precio_total_lote_sin_iva * ($impuesto->im_porcentaje/100));
                        $detalleVentaContable->setDvc_base_ret_item(0);
                        $detalleVentaContable->setDvc_fecha_vcto_item(0);
                        $detalleVentaContable->setDvc_dato_fact_prove("");

                        $addImpuesto=$detalleVentaContable->addArticulos();

                        
                        }
                    }
                }
            }
                echo json_encode(array("success"=>$addVenta));
                        }else{
                            echo json_encode("Debe indicar una cartera o cuenta de pago");
                        }

                    }

                }else{
                    echo json_encode("Cliente inexistente");
                }

            }else{
                echo json_encode("debe ingresar algun cliente");
            }
        }else{

        }
    }

    public function edit_venta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
            if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] > 4){
                $sucursal = new Sucursal($this->adapter);
                $getsucursal= $sucursal->getSucursalById($idsucursal);
                //obtener datos de usuario
                $idusuario = $_SESSION["usr_uid"];
                //ubicacion
                $pos_proceso ="Venta";
                $contabilidad = 0;
                $control_proceso="";
                //comprobante
                $comprobante = new Comprobante($this->adapter);
                $comprobantes = $comprobante->getComprobante($pos_proceso);
                //formas de pago
                $formapago= new FormaPago($this->adapter);
                $formaspago = $formapago->getFormaPago($pos_proceso);
                $idventa = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
                if($idventa){
                    $ventas = new Ventas($this->adapter);
                    $venta = $ventas->getVentaById($idventa);
                    foreach ($venta as $dataventa) {}
                    $detalleventa = new DetalleVenta($this->adapter);
                    $detalle = $detalleventa->getArticulosByVenta($idventa);
                    $cart = new ColaIngreso($this->adapter);
                    foreach ($detalle as $dataitems) {
                        $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
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

                    //recuperar el carrito previamente creado

                    $cart = new ColaIngreso($this->adapter);
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
                        "items"=>$items
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
                $getArticulos = $carro->getCart();
                $dataformapago = new FormaPago($this->adapter);
                $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
                foreach ($formapago as $formapago) {}
                $formapago = $formapago->fp_nombre;
                //articulos recuperados
                $subtotal = $carro->getSubTotal();
                //calcular el tocal incluyendo impuestos y retenciones
                $totalVenta = $this->calculoVenta2($_POST["comprobante"]);
                foreach ($subtotal as $subtotal) {}
                $calcsubtotal = $subtotal->cdi_debito;
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
                if($getArticulos != null){
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
                            $detalleVenta = new DetalleVenta($this->adapter);
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
                                $articulo = $dataarticulos->addCantStock($idarticulo,$stock_ingreso);
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
            foreach ($array as $search) {$getCliente = $cliente->getClienteByDocument($array[$i]);
                //si se encontro algo en clientes lo retorna
            foreach ($getCliente as $datacliente) {}
            $i++;
            }
            $idcliente = $datacliente->idpersona;

            $venta = new Ventas($this->adapter);
            $ventas = $venta->reporte_cliente($idcliente);
            
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