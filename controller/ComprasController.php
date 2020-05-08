<?php
class ComprasController extends ControladorBase{
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

        $compras = new Compras($this->adapter);
        $allcompras = $compras->getCompraAll();

        $this->frameview("compras/index",array(
            "compras"=>$allcompras
        ));
    }

    public function reg_compras()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){

        //limpiamos el registro del carro donde se almacenan los articulos
        $carro = new ColaIngreso($this->adapter);
        $carro->deleteCart();
        
        //models
        $compras = new Compras($this->adapter);
        $allcompras = $compras->getCompraAll();
        
        $this->frameview("compras/index",array(
            "compras"=>$allcompras
        ));
        }else{
            echo "Forbidden gateway";
        }
    }

    public function reg_contable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            //models
            $carro = new ColaIngreso($this->adapter);
            $compra = new IngresoContable($this->adapter);
            $detalle = new DetalleIngresoContable($this->adapter);
            //limpiamos el registro del carro donde se almacenan los articulos
            $carro->deleteCart();
            //recuperar compras contables
            $compras = $compra->getCompraAll();
            foreach ($compras as $data) {}

            //recuperar artiuclos de esta compra
            $detalleingreso = $detalle->getArticulosByCompra($data->ic_id_transa);
            foreach ($detalleingreso as $articulos) {
                
            }
            $this->frameview("compras/contable/index",array(
                "compras"=>$compras
            ));
        }else{
            echo "Forbidden gateway";
        }
    }
    public function delete_contable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idcompra = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
            $compras = new IngresoContable($this->adapter);
            $delete = $compras->delete_compra($idcompra);
            if($delete){
                $success = "Compra anulada";
                $this->frameview("alert/success/successSmall",array("success"=>$success));
            }else{
                $error = "No se pudo anular esta compra";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error = "No tienes permisos";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

    public function delete()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idcompra = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
            if($idcompra){
                $compras = new Compras($this->adapter);
                $delete = $compras->delete_compra($idcompra);
                if($delete){
                    echo "Esta compra se ha anulado";
                }else{
                    echo "No se ha podido eliminar esta compra";
                }
            }
        }else{
            echo "No puedes anular esta compra";
        }
    }

    public function detail()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1){
            if(!empty($_GET["data"])){
                $idcompra = $_GET["data"];
                //obtener compra por id 
                $datacompras = new Compras($this->adapter);
                $compra = $datacompras->getCompraById($idcompra);

                //obtener articulos de la compra
                $dataarticulos = new DetalleIngreso($this->adapter);
                $articulos = $dataarticulos->getArticulosByCompra($idcompra);
                $subtotal = $dataarticulos->totalIngreso($idcompra);
                $dataretenciones = new Retenciones($this->adapter);

                foreach ($compra as $data) {}
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
                    foreach($impuestos as $impuesto2){}
                    if($impuestos){
                       if($impuesto2->im_porcentaje == $imp->cdi_importe){
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
                
                $this->frameview("compras/Detail/index",array(
                    "compra"=>$compra,
                    "articulos"=>$articulos,
                    "retenciones"=>$listRetencion,
                    "impuestos"=>$listImpuesto,
                    "total_bruto"=>$total_bruto,
                    "total_neto"=>$total_neto,
                ));
            }
        }else{
            echo "Forbidden Gateway";
        }
    }

    public function nuevo_ingreso()
    {
        //obtener sucursal
        $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
        if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] >= 3){
        $sucursal = new Sucursal($this->adapter);
        $getsucursal= $sucursal->getSucursalById($idsucursal);
        //obtener datos de usuario
        $idusuario = $_SESSION["usr_uid"];
        //ubicacion
        $pos_proceso ="Ingreso";
        $autocomplete ="autocomplete_articulo";
        $contabilidad = ""; 
        //comprobante
        $comprobante = new Comprobante($this->adapter);
        $comprobantes = $comprobante->getComprobante($pos_proceso);
        //formas de pago
        $formapago= new FormaPago($this->adapter);
        $formaspago = $formapago->getFormaPago($pos_proceso);
        //si hay un articulos agregados a el carrito de la sucursal 
        $cart = new ColaIngreso($this->adapter);
        $items = $cart->loadCart();

        $this->frameview("compras/New/new",array(
            "contabilidad"=>$contabilidad,
            "sucursal"=>$getsucursal,
            "idusuario"=>$idusuario,
            "pos"=>$pos_proceso,
            "comprobantes" => $comprobantes,
            "formaspago" => $formaspago,
            "items"=>$items,
            "autocomplete"=>$autocomplete
        ));  
    }else{
        echo "Forbidden Gateway";
    }    
    }

    public function nuevo()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
        //obtener sucursal
        $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
        $sucursal = new Sucursal($this->adapter);
        $getsucursal= $sucursal->getSucursalById($idsucursal);
        //obtener datos de usuario
        $idusuario = $_SESSION["usr_uid"];
        //ubicacion
        $pos_proceso ="Ingreso";
        $contabilidad = "Contable";
        $autocomplete= "codigo_contable";
        //comprobante
        $comprobante = new Comprobante($this->adapter);
        $comprobantes = $comprobante->getComprobanteContable($pos_proceso);
        //formas de pago
        $formapago= new FormaPago($this->adapter);
        $formaspago = $formapago->getFormaPago($pos_proceso);
        //si hay un articulos agregados a el carrito de la sucursal 
        $cart = new ColaIngreso($this->adapter);
        $items = $cart->loadCart();

        $this->frameview("compras/New/new",array(
            "contabilidad"=>$contabilidad,
            "sucursal"=>$getsucursal,
            "idusuario"=>$idusuario,
            "pos"=>$pos_proceso,
            "comprobantes" => $comprobantes,
            "formaspago" => $formaspago,
            "items"=>$items,
            "autocomplete"=>$autocomplete
        ));       
     }else{
         echo "Forbidden gateway";
     }

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
            $ivaarticulo = $item["imp_compra"];
            $costo_producto= $item["costo_producto"];
            $cod_costos =$item["cod_costos"];
            //calcular 
            
            $total_lote = ($costo_producto * (($ivaarticulo/100)+1))*$cantidad;

            $total_sin_iva = $total_lote / (($ivaarticulo/100)+1);


            //posicion de pagina
            if($pos =="Ingreso"){$debito=$total_lote;}
            elseif($pos=="Venta"){$credito =$total_lote;}
            else{}
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
            $ivacodigo = $item["imp_compra"];
            $costo_producto = $item["total_compra"];
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

    public function calculoCompra()
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
                   if($data->im_porcentaje == $imp->cdi_importe){
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
            echo $this->frameview("compras/New/calculoCompra",array(
                "retenciones"=>$listRetencion,
                "impuestos"=>$listImpuesto,
                "total_bruto"=>$total_bruto,
                "total_neto"=>$total_neto,
            ));
        
        }else{
            
        }
    }

    public function crearCompra()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
        //ver que los datos se enviaron
        if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 0){
            $ingreso = new Ingreso($this->adapter);
            $dataarticulos = new Articulo($this->adapter);
            /******************************************************************************************/
            $factura_proveedor = $_POST["factura_proveedor"];
            //dividir estring
            $array = explode(" - ", $_POST["proveedor"]);
            $proveedor = new Persona($this->adapter);
            $i =0;
            foreach ($array as $search) {$getProveedor = $proveedor->getProveedorByDocument($array[$i]);
                //si se encontro algo en proveedores lo retorna
            foreach ($getProveedor as $dataproveedor) {}
            $i++;
            }
            //idproveedor recuperado

            if($dataproveedor){
                $idproveedor = $dataproveedor->idpersona;
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
            $comprobanteContable = $comprobante->contabilidad;
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
            $total = $this->calculoCompra2($_POST["comprobante"]);
            foreach ($subtotal as $subtotal) {}
            $calcsubtotal = $subtotal->cdi_debito;
            $start_date = date_format_calendar($_POST["start_date"],"/");
            $end_date = date_format_calendar($_POST["end_date"],"/");
            
            //si hay articulos en el carro de lo contrario cancela el proceso
            if($getArticulos != null){

                $ingreso->setIdusuario($_SESSION['usr_uid']);
                $ingreso->setIdsucursal($_SESSION['idsucursal']);
                $ingreso->setIdproveedor($idproveedor);
                $ingreso->setTipo_pago($formapago);
                $ingreso->setTipo_comprobante($tipoComprobante);
                $ingreso->setSerie_comprobante($serieComprobante);
                $ingreso->setNum_comprobante($ultimoNComprobante);
                $ingreso->setFactura_proveedor($factura_proveedor);
                $ingreso->setFecha($start_date);
                $ingreso->setFecha_final($end_date);
                $ingreso->setImpuesto("0");
                $ingreso->setSub_total($total);
                $ingreso->setSubtotal_importe("0");
                $ingreso->setTotal($total);
                $ingreso->setImporte_pagado($total);
                $ingreso->setEstado("A");
                $saveIngreso = $ingreso->saveIngreso();

            if($saveIngreso){
                //si la forma de pago es cartera o credito
                if($formapago == "Credito"){
                    //generar cartera
                    $cartera = new Cartera($this->adapter);
                    $cartera->setIdingreso($saveIngreso);
                    $cartera->setFecha_pago($end_date);
                    $cartera->setTotal_pago(0);
                    $cartera->setDeuda_total($total);
                    $cartera->setContabilidad(0);
                    $cartera->setCp_estado("A");
                    $generarCartera = $cartera->generarCarteraProveedor();
                   

                }else{}

                $detalleIngreso = new DetalleIngreso($this->adapter);
                 $impuesto_compra = 0;
                foreach ($getArticulos as $articulos) {
                    //guardando en variables
                    $idarticulo = $articulos->cdi_idarticulo;
                    $stock_ingreso =$articulos->cdi_stock_ingreso;
                    $impuesto = $articulos->cdi_importe;
                    $precio_unitario = $articulos->cdi_precio_unitario;
                    $total =$articulos->cdi_precio_total_lote;
                    $cod_costos =$articulos->cdi_cod_costos;
                    $idingreso = $saveIngreso;
                    $impuesto_compra += $total -($total /(($impuesto/100)+1));
                    //guardar cada articulo en el ingreso
                    $detalleIngreso->setIdingreso($idingreso);
                    $detalleIngreso->setIdarticulo($idarticulo);
                    $detalleIngreso->setStock_ingreso($stock_ingreso);
                    $detalleIngreso->setStock_actual(0);
                    $detalleIngreso->setPrecio_compra($precio_unitario);
                    $detalleIngreso->setIva_compra($total -($total /(($impuesto/100)+1)));
                    $detalleIngreso->setImporte_categoria($impuesto);
                    $detalleIngreso->setPrecio_total_lote($total);
                    $detalleIngreso->setPrecio_ventadistribuidor(0);
                    $detalleIngreso->setPrecio_ventapublico(0);
                    $addArticulos = $detalleIngreso->addArticulos();

                    //agregar stock 
                    $articulo = $dataarticulos->addCantStock($idarticulo,$stock_ingreso);
                    
                }

                if($addArticulos){
                    //eliminar carro
                    $carro->deleteCart();
                    $ingreso->setSubtotal_importe($impuesto_compra);
                    $impuestocompra = $ingreso->addImpuestoIngreso($idingreso);
                    echo json_encode(array("success"=>$saveIngreso));
                }

            }else{
                echo json_encode(array("error"=>"Error al ingresar la compra. refresca la pagina e intentalo de nuevo"));
            }

            } else{
                echo json_encode(array("error"=>"Debe agregar articulos"));
            }          

        }else{
            echo json_encode(array("error"=>"Proveedor no existe"));
        }

        }else{
            echo json_encode(array("error"=>"el Proveedor y el comprobante son importantes"));
        }

        }else{

        }
    }

    public function crearCompraContable()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            //ver que los datos se enviaron
            if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 0){
                $ingresocontable = new IngresoContable($this->adapter);
                $detalleIngresoContable = new DetalleIngresoContable($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $puc = new PUC($this->adapter);
                $impuestos = new Impuestos($this->adapter);
                $retenciones = new Retenciones($this->adapter);
                $categorias = new Categoria($this->adapter);
                $mymediafiles= new MyMediaFiles($this->adapter);
                $cartera = new Cartera($this->adapter);
                $comprobantes = new Comprobante($this->adapter);
                $carro = new ColaIngreso($this->adapter);
                $dataformapago = new FormaPago($this->adapter);

                /******************************************************************************************/
                $factura_proveedor = $_POST["factura_proveedor"];
                $forma_pago =$_POST["formaPago"];
                //dividir estring
                $array = explode(" - ", $_POST["proveedor"]);
                $proveedor = new Persona($this->adapter);
                $i =0;
                foreach ($array as $search) {$getProveedor = $proveedor->getProveedorByDocument($array[$i]);
                //si se encontro algo en proveedores lo retorna
                foreach ($getProveedor as $dataproveedor) {}
                    $i++;
                }
                //idproveedor recuperado
                if($dataproveedor){
                    $idproveedor = $dataproveedor->idpersona;
                /******************************************************************************************/
                /***********************************configuracion del comprobante***************************************/
                $idcomprobante = $_POST["comprobante"];
                
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
                
                $getArticulos = $carro->getAllCart();
                //obtener tipo de pago
                
                $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
                foreach ($formapago as $formapago) {}
                $formadepago = $formapago->fp_nombre;
                

                //articulos recuperados
                $subtotal = $carro->getSubTotal();
                $total = $this->calculoCompra2($_POST["comprobante"]);
                foreach ($subtotal as $subtotal) {}
                $calcsubtotal = $subtotal->cdi_debito;
                $start_date = date_format_calendar($_POST["start_date"],"/");
                $end_date = date_format_calendar($_POST["end_date"],"/");

                //si hay articulos en el carro de lo contrario cancela el proceso
                $state =0;
                if($getArticulos != null){
                    foreach ($getArticulos as $filter) {}

                    $ingresocontable->setIc_idusuario($_SESSION["usr_uid"]);
                    $ingresocontable->setIc_idproveedor($dataproveedor->idpersona);
                    $ingresocontable->setIc_id_forma_pago($forma_pago);
                    $ingresocontable->setIc_id_tipo_cpte($tipoComprobante);
                    $ingresocontable->setIc_num_cpte($serieComprobante);
                    $ingresocontable->setIc_cons_cpte(zero_fill($ultimoNComprobante,8));
                    $ingresocontable->setIc_fecha_cpte($start_date);
                    $ingresocontable->setIc_nit_cpte($dataproveedor->num_documento);
                    $ingresocontable->setIc_dig_verifi(0);
                    $ingresocontable->setIc_ccos_cpte($_SESSION["idsucursal"]);
                    $ingresocontable->setIc_fp_cpte($formadepago);
                    $ingresocontable->setIc_estado("A");
                    $ingresocontable->setIc_log_reg($_SESSION['usr_uid']."_".$start_date."_".date("h:i:s"));
                    $addIngreso = $ingresocontable->saveIngresoContable();

                    $total_retenido =0;
                    foreach ($getArticulos as $item) {

                        if($item->cdi_type == "AR"){
                        $articulo = $dataarticulos->getArticuloById($item->cdi_idarticulo);
                        foreach ($articulo as $articulo) {}
                        //almacenando articulos
                        $addStock = $dataarticulos->addCantStock($item->cdi_idarticulo,$item->cdi_stock_ingreso);
                        //obtener la categoria de este articulo
                        $categoria = $categorias->getCategoriaById($articulo->idcategoria);
                        foreach ($categoria as $categoria) {}
                        //obtener codigo contable de la categoria -> codigo de inventario
                        $cuenta_inventario = $puc->getPucById($categoria->cod_inventario);
                        foreach ($cuenta_inventario as $cuenta_inventario) {}
                        $inventario = ($cuenta_inventario !=null)? $cuenta_inventario->idcodigo:$categoria->cod_inventario;

                        $detalleIngresoContable->setDic_id_trans($addIngreso);
                        $detalleIngresoContable->setDic_seq_detalle(0);
                        $detalleIngresoContable->setDic_cta_item_det($inventario);
                        $detalleIngresoContable->setDic_det_item_det($articulo->nombre_articulo);
                        $detalleIngresoContable->setDic_cant_item_det($item->cdi_stock_ingreso);
                        $detalleIngresoContable->setDic_ter_item_det($dataproveedor->num_documento);
                        $detalleIngresoContable->setDic_ccos_item_det($_SESSION["idsucursal"]);
                        $detalleIngresoContable->setDic_d_c_item_det("D");
                        $detalleIngresoContable->setDic_valor_item($item->cdi_precio_unitario*$item->cdi_stock_ingreso);
                        $detalleIngresoContable->setDic_base_ret_item(0);
                        $detalleIngresoContable->setDic_fecha_vcto_item(0);
                        $detalleIngresoContable->setDic_dato_fact_prove("");
                        $addItem=$detalleIngresoContable->addArticulos();
                        
                        
                        //

                        // $retencion = $retenciones->getRetencionesByComprobanteId($idcomprobante);
                        // foreach ($retencion as $retencion) {
                        //     if($retencion != null){
                        //         $cuenta_retencion = $puc->getPucById($retencion->re_cta_contable);
                        //         foreach ($cuenta_retencion as $cuenta_retencion) {}
                        //         if($cuenta_retencion != null){
                        //             if($retencion->re_im_id == $impuesto->im_id){

                        //                 $impuesto_articulo = ($item->cdi_importe>0)?$item->cdi_importe:1;
                        //                 $total = $item->cdi_precio_total_lote / (($impuesto_articulo/100)+1);
                        //                 $total_impuesto = $total * ($impuesto_articulo/100);

                        //                 $calculo_retencion = $total_impuesto * ($retencion->re_porcentaje/100);
                                        
                        //                 $total_retenido += $calculo_retencion;
                        //                 $detalleIngresoContable->setDic_id_trans($addIngreso);
                        //                 $detalleIngresoContable->setDic_seq_detalle(0);
                        //                 $detalleIngresoContable->setDic_cta_item_det($cuenta_retencion->idcodigo);
                        //                 $detalleIngresoContable->setDic_ter_item_det($dataproveedor->num_documento);
                        //                 $detalleIngresoContable->setDic_ccos_item_det($_SESSION["idsucursal"]);
                        //                 $detalleIngresoContable->setDic_d_c_item_det("D");
                        //                 $detalleIngresoContable->setDic_valor_item($calculo_retencion);
                        //                 $detalleIngresoContable->setDic_base_ret_item(0);
                        //                 $detalleIngresoContable->setDic_fecha_vcto_item(0);
                        //                 $detalleIngresoContable->setDic_dato_fact_prove($factura_proveedor);
                        //                 $addCodCostos=$detalleIngresoContable->addArticulos();
                        //             }else{
                        //                 $impuesto_articulo = ($item->cdi_importe>0)?$item->cdi_importe:1;
                        //                 $total = $item->cdi_precio_total_lote / (($impuesto_articulo/100)+1);
                        //                 $retencion_total = $total * ($retencion->re_porcentaje/100);

                        //                 $total_retenido +=$retencion_total;
                        //                 $detalleIngresoContable->setDic_id_trans($addIngreso);
                        //                 $detalleIngresoContable->setDic_seq_detalle(0);
                        //                 $detalleIngresoContable->setDic_cta_item_det($cuenta_retencion->idcodigo);
                        //                 $detalleIngresoContable->setDic_ter_item_det($dataproveedor->num_documento);
                        //                 $detalleIngresoContable->setDic_ccos_item_det($_SESSION["idsucursal"]);
                        //                 $detalleIngresoContable->setDic_d_c_item_det("D");
                        //                 $detalleIngresoContable->setDic_valor_item($retencion_total);
                        //                 $detalleIngresoContable->setDic_base_ret_item(0);
                        //                 $detalleIngresoContable->setDic_fecha_vcto_item(0);
                        //                 $detalleIngresoContable->setDic_dato_fact_prove($factura_proveedor);
                        //                 $addCodCostos=$detalleIngresoContable->addArticulos();
                        //             }
                        //         }else{}
                        //     }else{}    
                        // }
                    }
                    else{}
                }

                //recorrer cada articulo y buscar sus impuestos
                foreach ($getArticulos as $dataimpuestos) {
                    if($dataimpuestos->cdi_type == "AR"){
                    $dataimpuesto = $impuestos->getBy('im_porcentaje',$dataimpuestos->cdi_importe);
                        foreach ($dataimpuesto as $impuesto) {}
                        $cuenta_impuesto = ($impuesto->im_cta_contable > 0)?$impuesto->im_cta_contable:0;

                        if($cuenta_impuesto){
                        $cuenta_codigo = $puc->getPucById($impuesto->im_cta_contable);
                        foreach ($cuenta_codigo as $cuenta) {}
                        $precio_total_lote_sin_iva = $dataimpuestos->cdi_precio_total_lote / (($impuesto->im_porcentaje /100)+1);

                        $detalleIngresoContable->setDic_id_trans($addIngreso);
                        $detalleIngresoContable->setDic_seq_detalle(0);
                        $detalleIngresoContable->setDic_cta_item_det($cuenta_impuesto);
                        $detalleIngresoContable->setDic_det_item_det($cuenta->tipo_codigo);
                        $detalleIngresoContable->setDic_cant_item_det($dataimpuestos->cdi_stock_ingreso);
                        $detalleIngresoContable->setDic_ter_item_det($dataproveedor->num_documento);
                        $detalleIngresoContable->setDic_ccos_item_det($_SESSION["idsucursal"]);
                        $detalleIngresoContable->setDic_d_c_item_det("D");
                        $detalleIngresoContable->setDic_valor_item($precio_total_lote_sin_iva * ($impuesto->im_porcentaje/100));
                        $detalleIngresoContable->setDic_base_ret_item(0);
                        $detalleIngresoContable->setDic_fecha_vcto_item(0);
                        $detalleIngresoContable->setDic_dato_fact_prove("");

                        $addImpuesto=$detalleIngresoContable->addArticulos();
                        }
                    }
                }

                //recorrer cada articulo y buscar sus retenciones
                foreach ($getArticulos as $dataretenciones) {
                    if($dataretenciones->cdi_type == "AR"){
                        $retencion = $retenciones->getRetencionesByComprobanteId($idcomprobante);
                        foreach ($retencion as $retencion) {
                            if($retencion != null){
                                $cuenta_retencion = $puc->getPucById($retencion->re_cta_contable);
                                foreach ($cuenta_retencion as $cuenta_retencion) {}
                                if($cuenta_retencion != null){
                                    if($retencion->re_im_id == $impuesto->im_id){

                                        $impuesto_articulo = ($dataretenciones->cdi_importe>0)?$dataretenciones->cdi_importe:1;
                                        $total = $dataretenciones->cdi_precio_total_lote / (($impuesto_articulo/100)+1);
                                        $total_impuesto = $total * ($impuesto_articulo/100);

                                        $calculo_retencion = $total_impuesto * ($retencion->re_porcentaje/100);
                                        
                                        $total_retenido += $calculo_retencion;
                                        $detalleIngresoContable->setDic_id_trans($addIngreso);
                                        $detalleIngresoContable->setDic_seq_detalle(0);
                                        $detalleIngresoContable->setDic_cta_item_det($cuenta_retencion->idcodigo);
                                        $detalleIngresoContable->setDic_det_item_det($cuenta_retencion->tipo_codigo);
                                        $detalleIngresoContable->setDic_cant_item_det($dataretenciones->cdi_stock_ingreso);
                                        $detalleIngresoContable->setDic_ter_item_det($dataproveedor->num_documento);
                                        $detalleIngresoContable->setDic_ccos_item_det($_SESSION["idsucursal"]);
                                        $detalleIngresoContable->setDic_d_c_item_det("C");
                                        $detalleIngresoContable->setDic_valor_item($calculo_retencion);
                                        $detalleIngresoContable->setDic_base_ret_item($total_impuesto);
                                        $detalleIngresoContable->setDic_fecha_vcto_item(0);
                                        $detalleIngresoContable->setDic_dato_fact_prove("");
                                        $addCodCostos=$detalleIngresoContable->addArticulos();
                                    }else{
                                        $impuesto_articulo = ($dataretenciones->cdi_importe>0)?$dataretenciones->cdi_importe:1;
                                        $total = $dataretenciones->cdi_precio_total_lote / (($impuesto_articulo/100)+1);
                                        $retencion_total = $total * ($retencion->re_porcentaje/100);

                                        $total_retenido +=$retencion_total;
                                        $detalleIngresoContable->setDic_id_trans($addIngreso);
                                        $detalleIngresoContable->setDic_seq_detalle(0);
                                        $detalleIngresoContable->setDic_cta_item_det($cuenta_retencion->idcodigo);
                                        $detalleIngresoContable->setDic_det_item_det($cuenta_retencion->tipo_codigo);
                                        $detalleIngresoContable->setDic_cant_item_det($dataretenciones->cdi_stock_ingreso);
                                        $detalleIngresoContable->setDic_ter_item_det($dataproveedor->num_documento);
                                        $detalleIngresoContable->setDic_ccos_item_det($_SESSION["idsucursal"]);
                                        $detalleIngresoContable->setDic_d_c_item_det("C");
                                        $detalleIngresoContable->setDic_valor_item($retencion_total);
                                        $detalleIngresoContable->setDic_base_ret_item($total);
                                        $detalleIngresoContable->setDic_fecha_vcto_item(0);
                                        $detalleIngresoContable->setDic_dato_fact_prove("");
                                        $addCodCostos=$detalleIngresoContable->addArticulos();
                                    }
                                }else{}
                            }else{}    
                        }
                    }
                }

                 //en este paso del codigo el item ingresado es un codigo contable y no un articulo
                //ingresar la cuenta contable de pago
                foreach ($getArticulos as $datapago) {
                    if($datapago->cdi_type == "CO"){
                       
                        //cargaremos ahora de donde va a salir el dinero
                        if($formadepago == "Credito"){
                            
                            $cuenta = $puc->getPucById($formapago->fp_cuenta_contable);
                            foreach ($cuenta as $cuenta) {}

                            $detalleIngresoContable->setDic_id_trans($addIngreso);
                            $detalleIngresoContable->setDic_seq_detalle(0);
                            $detalleIngresoContable->setDic_cta_item_det($cuenta->idcodigo);
                            $detalleIngresoContable->setDic_det_item_det($cuenta->tipo_codigo);
                            $detalleIngresoContable->setDic_cant_item_det($datapago->cdi_stock_ingreso);
                            $detalleIngresoContable->setDic_ter_item_det($dataproveedor->num_documento);
                            $detalleIngresoContable->setDic_ccos_item_det($_SESSION["idsucursal"]);
                            $detalleIngresoContable->setDic_d_c_item_det("C");
                            $detalleIngresoContable->setDic_valor_item($datapago->cdi_precio_total_lote - $total_retenido);
                            $detalleIngresoContable->setDic_base_ret_item(0);
                            $detalleIngresoContable->setDic_fecha_vcto_item($end_date);
                            $detalleIngresoContable->setDic_dato_fact_prove($factura_proveedor);
                            
                            $addCodigo=$detalleIngresoContable->addArticulos();

                            //generar cartera de proveedores
                            
                            $cartera->setIdingreso($addIngreso);
                            $cartera->setFecha_pago($end_date);
                            $cartera->setTotal_pago(0);
                            $cartera->setDeuda_total($datapago->cdi_precio_total_lote - $total_retenido);
                            $cartera->setContabilidad(1);
                            $cartera->setCp_estado("A");
                            $generarCartera = $cartera->generarCarteraProveedor();

                        }else{

                            $cuenta = $puc->getPucById($datapago->cdi_idarticulo);
                            foreach ($cuenta as $cuenta) {}
    
                            //se agrega el el codigo contable de la forma de pago ya predefinida en la configuracion
                            $detalleIngresoContable->setDic_id_trans($addIngreso);
                            $detalleIngresoContable->setDic_seq_detalle(0);
                            $detalleIngresoContable->setDic_cta_item_det($datapago->cdi_idarticulo);
                            $detalleIngresoContable->setDic_det_item_det($cuenta->tipo_codigo);
                            $detalleIngresoContable->setDic_cant_item_det($datapago->cdi_stock_ingreso);
                            $detalleIngresoContable->setDic_ter_item_det($dataproveedor->num_documento);
                            $detalleIngresoContable->setDic_ccos_item_det($_SESSION["idsucursal"]);
                            $detalleIngresoContable->setDic_d_c_item_det("C");
                            $detalleIngresoContable->setDic_valor_item($datapago->cdi_precio_total_lote - $total_retenido);
                            $detalleIngresoContable->setDic_base_ret_item(0);
                            $detalleIngresoContable->setDic_fecha_vcto_item(0);
                            $detalleIngresoContable->setDic_dato_fact_prove(0);
                            $addCodigo=$detalleIngresoContable->addArticulos();
                        }
                    }
                }

                    if($addIngreso){
                        $mymediafiles->setMmf_name("REG CONTABLE ");
                        $mymediafiles->setMmf_url("file/ingresoContable/window_view/print_off");
                        $mymediafiles->setMmf_user($_SESSION["usr_uid"]);
                        $mymediafiles->setMmf_idsucursal($_SESSION["idsucursal"]);
                        $mymediafiles->setMmf_ext("pdf");
                        $mymediafiles->setMmf_status("1");
                        $mymediafiles->setMmf_reg("");
                        $mymediafiles->setMmf_window(1);
                        $mymediafiles->setMmf_viewed(0);
                        $addFile = $mymediafiles->createFile();
                        //eliminar carro
                        $carro->deleteCart();

                        echo json_encode(array("success"=>$addIngreso));
                    }else{
                    echo json_encode(array("error"=>"No se pudo agregar"));
                    }
                }else{
                    echo json_encode(array("error"=>"Debe agregar articulos a esta compra"));
                }
                    
            }else{
                echo json_encode(array("error"=>"Proveedor no existe"));
            }
               
            }else{
                echo json_encode(array("error"=>"el Proveedor y el comprobante son importantes"));
            }
        }else{

        }
    }
    
    public function calculoCompra2($idcomprobante)
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
            //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)
            foreach ($totalimpuestos as $imp) {
                $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe/100)+1));
                foreach($impuestos as $data){}
                if($impuestos){
                   if($data->im_porcentaje == $imp->cdi_importe){
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
            return $total_neto;
    }

    public function forma_pago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){

            if(isset($_POST["comprobante"]) && $_POST["comprobante"] > 0){

                $total = $this->calculoCompra2($_POST["comprobante"]);

                if(isset($_POST["idPago"]) && $_POST["idPago"] > 0){
                    
                    
                    $listPrice = [];
                    $caracteres = strlen($total);
                    $max = substr($total,0,1) +1;
                    $aprox = substr($total,0,2) +1;
                    for($i=0;$i<$caracteres-1;$i++){
                        $max .="0";
                    }

                    for($i=0;$i<$caracteres-2;$i++){
                        $aprox .="0";
                    }

                    $listPrice[] = $total;
                    $listPrice[] = $aprox;
                    $listPrice[] = $max;
                    switch ($_POST["idPago"]) {
                        case '1':
                            
                            $this->frameview("formaPago/efectivo",array(
                                "total" =>$total,
                                "listPrice"=>$listPrice
                            ));
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }
                  elseif(isset($_POST["pago"]) && $_POST["pago"] > 0){
                    $pago = $_POST["pago"];

                    $msg="Por pagar";
                    $attr = true;
                    $color="text-success";
                    $calc = round($total - $pago);

                    if($calc <= $pago){
                        $msg="Cambio";
                        $color="text-danger";
                    }
    
                    if($pago > 0){
                        $attr =false;
                    }
                    echo json_encode(array("total"=>abs($calc),"msg"=>$msg,"color"=>$color,"attr"=>$attr));

                        
                }else{
                    $this->frameview("formaPago/list",array());
                }


            }else{
                echo json_encode(array("error"=>"Selecciona un comprobante"));
            }

        }else{
            echo json_encode(array("error"=>"Forbidden Gateway"));
        }
    }

    public function edit_compra()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
            if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] > 4){
            $sucursal = new Sucursal($this->adapter);
            $getsucursal= $sucursal->getSucursalById($idsucursal);
            //obtener datos de usuario
            $idusuario = $_SESSION["usr_uid"];
            //ubicacion
            $pos_proceso ="Ingreso";
            $contabilidad = 0;
            //comprobante
            $comprobante = new Comprobante($this->adapter);
            $comprobantes = $comprobante->getComprobante($pos_proceso);
            //formas de pago
            $formapago= new FormaPago($this->adapter);
            $formaspago = $formapago->getFormaPago($pos_proceso);
            $idcompra = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
            if($idcompra){
                //preparando datos
            $compras = new Compras($this->adapter);
            $compra = $compras->getCompraById($idcompra);
            foreach ($compra as $datacompra) {}
                //recuperar items de la compra
            $detalleIngreso = new DetalleIngreso($this->adapter);
            $detalle = $detalleIngreso->getArticulosByCompra($idcompra);
            //llamar el modelo del cart
            $cart = new ColaIngreso($this->adapter);
            foreach ($detalle as $dataitems) {
            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
            $cart->setCdi_tercero($datacompra->nombre_proveedor);
            $cart->setCdi_idarticulo($dataitems->idarticulo);
            $cart->setCdi_stock_ingreso($dataitems->stock_ingreso);
            $cart->setCdi_precio_unitario($dataitems->precio_compra);
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

                $this->frameview("compras/Edit/index",array(
                    "compra"=>$compra,
                    "items"=>$items,
                    "contabilidad"=>$contabilidad,
                    "sucursal"=>$getsucursal,
                    "idusuario"=>$idusuario,
                    "pos"=>$pos_proceso,
                    "comprobantes" => $comprobantes,
                    "formaspago" => $formaspago,
                ));

            }
        }else{
            echo "Forbidden Gateway";
        }
    }else{
        
    }

    }
    public function updateCompra()
    {
        if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 4){
            if(isset($_POST["idingreso"]) && !empty($_POST["idingreso"])){
            $idingreso = $_POST["idingreso"];
            $factura_proveedor = $_POST["factura_proveedor"];
            $ingreso = new Ingreso($this->adapter);
            $dataarticulos = new Articulo($this->adapter);
            $cartera = new Cartera($this->adapter);
            /******************************************************************************************/
            //dividir estring
            $array = explode(" - ", $_POST["proveedor"]);
            $proveedor = new Persona($this->adapter);
            $i =0;
            foreach ($array as $search) {$getProveedor = $proveedor->getProveedorByDocument($array[$i]);
                //si se encontro algo en proveedores lo retorna
            foreach ($getProveedor as $dataproveedor) {}
            $i++;
            }
            //idproveedor recuperado
            $idproveedor = $dataproveedor->idpersona;
            if($idproveedor >0){
            /////////
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
            $total = $this->calculoCompra2($_POST["comprobante"]);
            foreach ($subtotal as $subtotal) {}
            $calcsubtotal = $subtotal->cdi_debito;
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
                    $ingreso->setIdusuario($_SESSION['usr_uid']);
                    $ingreso->setIdsucursal($_SESSION['idsucursal']);
                    $ingreso->setIdproveedor($idproveedor);
                    $ingreso->setTipo_pago($formapago);
                    $ingreso->setFactura_proveedor($factura_proveedor);
                    $ingreso->setFecha($start_date);
                    $ingreso->setFecha_final($end_date);
                    $ingreso->setImpuesto("19");
                    $ingreso->setSub_total($total);
                    $ingreso->setSubtotal_importe("0");
                    $ingreso->setTotal($total);
                    $ingreso->setImporte_pagado($total);
                    $ingreso->setEstado("A");
                    $updateIngreso = $ingreso->updateIngreso($idingreso);
                    
                    if($updateIngreso){
                        //si la forma de pago es cartera o credito
                        if($formapago == "Credito"){
                            //generar cartera
                            
                            $cartera->setIdingreso($idingreso);
                            $cartera->setFecha_pago($end_date);
                            $cartera->setTotal_pago(0);
                            $cartera->setDeuda_total($total);
                            $cartera->setCp_estado("A");
                            $existCredito = $cartera->getCarteraProveedorByIngreso($idingreso);
                            if($existCredito){
                                $generarCartera = $cartera->updateCarteraProveedor($idingreso);
                            }else{
                                $generarCartera = $cartera->generarCarteraProveedor();
                            }

                        }else{
                            $deleteCartera = $cartera->deleteCarteraProveedor($idingreso);
                        }
                        //limpiar detalle de articulos antiguo
                        $detalleIngreso = new DetalleIngreso($this->adapter);
                        $deleteLastDetalleIngreso = $detalleIngreso->deleteDetalleIngresoById($idingreso);
                        $impuesto_compra =0;
                        foreach ($getArticulos as $articulos) {
                            //guardando en variables
                            $idarticulo = $articulos->cdi_idarticulo;
                            $stock_ingreso =$articulos->cdi_stock_ingreso;
                            $impuesto = $articulos->cdi_importe;
                            $precio_unitario = $articulos->cdi_precio_unitario;
                            $total =$articulos->cdi_precio_total_lote;
                            $cod_costos =$articulos->cdi_cod_costos;
                            $idingreso = $idingreso;
                            $impuesto_compra += $total -($total /(($impuesto/100)+1));
                            //guardar cada articulo en el ingreso
                            $detalleIngreso->setIdingreso($idingreso);
                            $detalleIngreso->setIdarticulo($idarticulo);
                            $detalleIngreso->setStock_ingreso($stock_ingreso);
                            $detalleIngreso->setStock_actual(0);
                            $detalleIngreso->setPrecio_compra($precio_unitario);
                            $detalleIngreso->setIva_compra($total -($total /(($impuesto/100)+1)));
                            $detalleIngreso->setImporte_categoria($impuesto);
                            $detalleIngreso->setPrecio_total_lote($total);
                            $detalleIngreso->setPrecio_ventadistribuidor(0);
                            $detalleIngreso->setPrecio_ventapublico(0);
                            $addArticulos = $detalleIngreso->addArticulos();
        
                            //agregar stock 
                            $articulo = $dataarticulos->addCantStock($idarticulo,$stock_ingreso);
                            //eliminar carro
                            $carro->deleteCart();
                            
                            }
                            if($articulo){
                                $ingreso->setSubtotal_importe($impuesto_compra);
                                $impuestocompra = $ingreso->addImpuestoIngreso($idingreso);
                                echo json_encode(array("success"=>$idingreso)); 
                            }
            }
        }
    }else{
        echo json_encode(array("error"=>"el Proveedor y el comprobante son importantes"));
    }
    }
    }
    }

    public function deleteItemDetalle()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            if(isset($_POST["data"]) && !empty($_POST["data"])){
                $iditem=$_POST["data"];
                $detalleIngreso = new DetalleIngreso($this->adapter);
                $deleteItem = $detalleIngreso->deleteItemDetalle($iditem);
            }else{}
        }else{}
    }
    /*                                                           REPORTES                                                    */
    #############################################################################################################################
    public function general()
    {  
        $control = "compras";
        $pos = "reporte_general";
        $compra = new Compras($this->adapter);
        $compras = $compra->getCompraAll();

        $this->frameview("compras/reportes/general/general",array(
            "compras"=>$compras,
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
            $compra = new Compras($this->adapter);
            $compras = $compra->reporte_general($start_date,$end_date);
            $this->frameview("compras/reportes/general/tableGeneral",array(
                "compras"=>$compras
            ));

        }else{
            
        }
        
    }
    #############################################################################################################################
    public function detallada()
    {  
        $control = "compras";
        $pos = "reporte_detallada";
        $compra = new Compras($this->adapter);
        $compras = $compra->getCompraDetallada();

        $this->frameview("compras/reportes/detallada/detallada",array(
            "compras"=>$compras,
            "pos"=>$pos,
            "control"=>$control
        ));
    }
    public function reporte_detallada()
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
            $compra = new Compras($this->adapter);
            $compras = $compra->reporte_detallada($start_date,$end_date);
            $this->frameview("compras/reportes/detallada/tableDetallada",array(
                "compras"=>$compras
            ));

        }else{
            
        }
        
    }
    #############################################################################################################################

    public function general_proveedor()
    {
        $control = "compras";
        $pos = "reporte_general_proveedor";
        $compra = new Compras($this->adapter);
        $compras = $compra->getCompraAll();

        $this->frameview("compras/reportes/general/proveedor",array(
            "compras"=>$compras,
            "pos"=>$pos,
            "control"=>$control
        ));
    }
    public function reporte_general_proveedor()
    {
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            $proveedor = cln_str($_POST["proveedor"]);
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate =$_POST["end_date"];
            $array_end_date = explode("/", $enddate);
            foreach ($array_end_date as $enddate) {}
            $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
            $compra = new Compras($this->adapter);
            $compras = $compra->reporte_general_proveedor($proveedor,$start_date,$end_date);
            $this->frameview("compras/reportes/general/tableProveedor",array(
                "compras"=>$compras
            ));

        }else{
            
        }
        
    }
    #############################################################################################################################

    public function detallada_proveedor()
    {
        $control = "compras";
        $pos = "reporte_detallada_proveedor";
        $compra = new Compras($this->adapter);
        $compras = $compra->getCompraDetallada();

        $this->frameview("compras/reportes/detallada/proveedor",array(
            "compras"=>$compras,
            "pos"=>$pos,
            "control"=>$control
        ));
    }
    public function reporte_detallada_proveedor()
    {
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            $proveedor = cln_str($_POST["proveedor"]);
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate =$_POST["end_date"];
            $array_end_date = explode("/", $enddate);
            foreach ($array_end_date as $enddate) {}
            $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
            $compra = new Compras($this->adapter);
            $compras = $compra->reporte_detallada_proveedor($proveedor,$start_date,$end_date);
            $this->frameview("compras/reportes/detallada/tableProveedor",array(
                "compras"=>$compras
            ));

        }else{
            
        }
        
    }

    #############################################################################################################################

    public function kardex()
    {
        $control = "compras";
        $pos = "reporte_detallada_proveedor";
        $compra = new Compras($this->adapter);
        $articulo = new Articulo($this->adapter);
        $venta = new Ventas($this->adapter);
        //todas las compras
        
        //todos los articulos
        $articulos = $articulo->getArticuloAll();
        $array =[];
        foreach ($articulos as $articulo) {
            $arrart =[];
            $compras = $compra->getCompraByArticulo($articulo->idarticulo);
            foreach ($compras as $compras) {
            $arrart[]= $articulo->nombre_articulo;
            $arrart[] += $compras->stock_compras;
            $stock_compras = ($compras->stock_compras >0)?$compras->stock_compras:1;
            $cost_prom =($compras->precio_compras /$stock_compras );
            $arrart[] = number_format(round($cost_prom));
            $arrart[] = number_format(round($cost_prom * $stock_compras));
            }

            $ventas = $venta->getVentaByArticulo($articulo->idarticulo);
            foreach ($ventas as $ventas) {
                $arrart[]=$ventas->stock_ventas;
                $stock_venta = ($ventas->stock_ventas>0)?$ventas->stock_ventas:1;
                $venta_prom = $ventas->precio_ventas / $stock_venta;
                $arrart[]=number_format(round($venta_prom));
                $venta_t = ($venta_prom*$stock_venta);
                $arrart[] = number_format(round($venta_t));
                $costo_v = ($venta_prom*$stock_venta)/1.3;
                $arrart[] = number_format(round($costo_v));
                $arrart[] = number_format(round($venta_t-$costo_v));
                $costo_v2 = ($venta_prom >0)?($venta_prom*$stock_venta)/1.3:1;
                $utilidad_p = (($venta_t-$costo_v) *100) / $costo_v2;
                $arrart[] =$utilidad_p;
            }
    

        $array[] = $arrart;
        }
        

        $this->frameview("compras/reportes/kardex/kardex",array(
            "compras"=>$compras,
            "pos"=>$pos,
            "control"=>$control,
            "array"=>$array,
        ));
    }

    #############################################################################################################################
    public function utilidad()
    {
        $control = "compras";
        $pos = "reporte_detallada_proveedor";
        $compra = new Compras($this->adapter);
        $compras = $compra->getCompraUtilidad();

        $this->frameview("compras/reportes/utilidad/utilidad",array(
            "compras"=>$compras,
            "pos"=>$pos,
            "control"=>$control
        ));
    }

    public function reporte_utilidad()
    {
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            $proveedor = cln_str($_POST["proveedor"]);
            $date = $_POST["start_date"];
            $array_date = explode("/", $date);
            foreach ($array_date as $date) {}
            $start_date = $array_date[2]."-".$array_date[0]."-".$array_date[1];
            //end date configuracion
            $enddate =$_POST["end_date"];
            $array_end_date = explode("/", $enddate);
            foreach ($array_end_date as $enddate) {}
            $end_date = $array_end_date[2]."-".$array_end_date[0]."-".$array_end_date[1];
            $compra = new Compras($this->adapter);
            $compras = $compra->reporte_detallada_proveedor($proveedor,$start_date,$end_date);
            $this->frameview("compras/reportes/detallada/tableProveedor",array(
                "compras"=>$compras
            ));

        }else{
            
        }
    }

    
}
?>