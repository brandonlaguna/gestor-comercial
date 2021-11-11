<?php
class ComprasController extends ControladorBase{
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
            'DocumentoSucursal/M_DocumentoSucursal',
            'Compras/M_GuardarCompra'
        ],$this->adapter);
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
            $compra = new ComprobanteContable($this->adapter);
            $detalle = new DetalleComprobanteContable($this->adapter);
            //limpiamos el registro del carro donde se almacenan los articulos
            $carro->deleteCart();
            //recuperar compras contables
            $compras = $compra->getComprobanteAllBy("cc_tipo_comprobante","I");
            foreach ($compras as $data) {}

            //recuperar artiuclos de esta compra
            $detalleingreso = $detalle->getArticulosByComprobante($data->cc_id_transa);
            foreach ($detalleingreso as $articulos) {}
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
            //$delete = $compras->delete_ingreso($idcompra);
            $delete = $this->delete_compra_contable($idcompra,true);
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
                $articulo = new Articulo($this->adapter);
                $detalleingreso = new DetalleIngreso($this->adapter);
                $items = $detalleingreso->getArticulosByCompra($idcompra);
                foreach($items as $items){
                    $articulo->removeCantStock($items->idarticulo,$items->stock_ingreso);
                }
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
        $this->Verificar->verificarPermisoAccion($this->M_Permisos->estado(2,$_SESSION["usr_uid"]));
        //obtener sucursal
        $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
        if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] >= 3){
        //models
        $sucursal = new Sucursal($this->adapter);
        $comprobante = new Comprobante($this->adapter);
        $formapago= new FormaPago($this->adapter);
        $cart = new ColaIngreso($this->adapter);
        $impuesto = new Impuestos($this->adapter);
        $retencion = new Retenciones($this->adapter);
        //functions
        $idusuario = $_SESSION["usr_uid"];
        //ubicacion
        $pos_proceso ="Ingreso";
        $autocomplete ="nombre_articulo";
        $contabilidad = ""; 
        $getsucursal= $sucursal->getSucursalById($idsucursal);
        //obtener datos de usuario
        //comprobante
        $comprobantes = $comprobante->getComprobante($pos_proceso);
        //formas de pago
        $formaspago = $formapago->getFormaPago($pos_proceso);
        //cargar lista de impuestos y retenciones
        $impuestos = $impuesto->getImpuestosAll();
        $retenciones = $retencion->getRetencionesAll();
        //si hay un articulos agregados a el carrito de la sucursal 
        
        //crear carro padre
        $cart->setCi_usuario($_SESSION["usr_uid"]);
        $cart->setCi_idsucursal($_SESSION["idsucursal"]);
        $cart->setCi_idproveedor(0);
        $cart->setCi_tipo_pago(0);
        $cart->setCi_comprobante(0);
        $cart->setCi_fecha(date("Y-m-d"));
        $cart->setCi_fecha_final(date("Y-m-d"));
        $addCart = $cart->createCart();

        $items = $cart->loadArtByCart($addCart);

        $this->frameview("compras/New/new",array(
            "contabilidad"=>$contabilidad,
            "sucursal"=>$getsucursal,
            "idusuario"=>$idusuario,
            "pos"=>$pos_proceso,
            "comprobantes" => $comprobantes,
            "formaspago" => $formaspago,
            "items"=>$items,
            "autocomplete"=>$autocomplete,
            "impuestos"=>$impuestos,
            "retenciones"=>$retenciones
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
        //models
        $sucursal = new Sucursal($this->adapter);
        $comprobante = new Comprobante($this->adapter);
        $formapago= new FormaPago($this->adapter);
        $cart = new ColaIngreso($this->adapter);
        $impuesto = new Impuestos($this->adapter);
        $retencion = new Retenciones($this->adapter);
        //functions
        $getsucursal= $sucursal->getSucursalById($idsucursal);
        //obtener datos de usuario
        $idusuario = $_SESSION["usr_uid"];
        //ubicacion
        $pos_proceso ="Ingreso";
        $contabilidad = "Contable";
        $autocomplete= "codigo_contable";
        //comprobante
        $comprobantes = $comprobante->getComprobanteContable($pos_proceso);
        //formas de pago
        
        $formaspago = $formapago->getFormaPago($pos_proceso);
        //si hay un articulos agregados a el carrito de la sucursal 
        //cargar lista de impuestos y retenciones
        $impuestos = $impuesto->getImpuestosAll();
        $retenciones = $retencion->getRetencionesAll();
        
        $cart->setCi_usuario($_SESSION["usr_uid"]);
        $cart->setCi_idsucursal($_SESSION["idsucursal"]);
        $cart->setCi_idproveedor(0);
        $cart->setCi_tipo_pago(0);
        $cart->setCi_comprobante(0);
        $cart->setCi_fecha(date("Y-m-d"));
        $cart->setCi_fecha_final(date("Y-m-d"));

        $addCart = $cart->createCart();
        $items = $cart->loadArtByCart($addCart);

        

        $this->frameview("compras/New/new",array(
            "contabilidad"=>$contabilidad,
            "sucursal"=>$getsucursal,
            "idusuario"=>$idusuario,
            "pos"=>$pos_proceso,
            "comprobantes" => $comprobantes,
            "formaspago" => $formaspago,
            "items"=>$items,
            "autocomplete"=>$autocomplete,
            "impuestos"=>$impuestos,
            "retenciones"=>$retenciones
        ));       
     }else{
         echo "Forbidden gateway";
     }

    }

    public function sendItem()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"])){
        if($_POST["data"] && !empty($_POST["data"]) && !empty($_POST["tercero"]) && !empty($_POST["pos"])){
            //models
            $cart = new ColaIngreso($this->adapter);
            $articulos= new Articulo($this->adapter);

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
                $getCart = $cart->getCart();
                foreach($getCart as $getCart);

                $articulo = $articulos->getArticuloById($idarticulo);
                foreach($articulo as $articulo){}
                if($articulo->idarticulo){
                    //calcular 
                    $total_lote = ($costo_producto * (($ivaarticulo/100)+1))*$cantidad;

                    $total_sin_iva = $total_lote / (($ivaarticulo/100)+1);
                    //obtener la informacion del articulo de este articulo
            
            
                    //posicion de pagina
                    if($pos =="Ingreso"){$debito=$total_lote;}
                    elseif($pos=="Venta"){$credito =$total_lote;}
                    else{}
                    //obtener carro
                    //agregar articulo al carro
                    if($cantidad){
                    $cart->setCdi_ci_id($getCart->ci_id);
                    $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                    $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                    $cart->setCdi_tercero($tercero);
                    $cart->setCdi_idarticulo($idarticulo);
                    $cart->setCdi_stock_ingreso($cantidad);
                    $cart->setCdi_precio_unitario($costo_producto);
                    $cart->setCdi_importe($ivaarticulo);
                    $cart->setCdi_im_id(0);
                    $cart->setCdi_precio_total_lote($total_lote);
                    $cart->setCdi_credito($credito);
                    $cart->setCdi_debito($debito);
                    $cart->setCdi_cod_costos($cod_costos);
                    $cart->setCdi_type("AR");
                    $result = $cart->addItemToCart();
                    }else{
                        $result = json_encode(array("error"=>"no hay la cantidad suficiente para este articulo"));
                    }
                }
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
            $result = $cart->addItemToCart();
            }
            echo json_encode($result);
        }
        }
        
    }

    public function calculoCompra()
    {
        if(isset($_POST["data"]) && !empty($_POST["data"])){

            $contabilidad = $_POST["contabilidad"];
            $colaingreso = new ColaIngreso($this->adapter);
            $getCart = $colaingreso->getCart();
            foreach($getCart as $getCart){}
            $colaretencion = new ColaRetencion($this->adapter);
            $retenciones = $colaretencion->getRetencionBy($getCart->ci_id);

            $colaimpuestos= new ColaImpuesto($this->adapter);
            $impuestos = $colaimpuestos->getImpuestosBy($getCart->ci_id);

            $dataretenciones = new Retenciones($this->adapter);
            $dataimpuestos = new Impuestos($this->adapter);



            $totalcart = new ColaIngreso($this->adapter);
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
                    $listRetencion[] = array($re_nombre,$calc,$retencion->cdr_id);
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

                        $listRetencion[] = array($re_nombre,$calc,$retencion->cdr_id);
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
                "total_bruto"=>number_format($total_bruto,0,'.',','),
                "total_neto"=>$total_neto,
            ));
        
        }else{
            
        }
    }

    public function crearCompra()
    {
        $estadoPermiso = $this->Verificar->verificarPermisoAccion($this->M_Permisos->estado(2,$_SESSION["usr_uid"]));
        if($estadoPermiso){
        //ver que los datos se enviaron
        if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 0){
            //models
            $ingreso = new Ingreso($this->adapter);
            $dataarticulos = new Articulo($this->adapter);
            $proveedor = new Persona($this->adapter);
            $colaimpuesto = new Colaimpuesto($this->adapter);
            $colaretencion = new Colaretencion($this->adapter);
            $detalleimpuesto = new DetalleImpuesto($this->adapter);
            $detalleretencion = new DetalleRetencion($this->adapter);
            $carro = new ColaIngreso($this->adapter);
            $dataformapago = new FormaPago($this->adapter);
            /******************************************************************************************/
            $factura_proveedor = isset($_POST["factura_proveedor"])&& !empty($_POST["factura_proveedor"])?$_POST["factura_proveedor"]:'N/A';
            //dividir estring
            $array = explode(" - ", $_POST["proveedor"]);
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
            $getCart = $carro->getCart();
            foreach ($getCart as $getCart){}

            //obtener articulos
            $getArticulos = $carro->getArtByCart($getCart->ci_id);
            //obtener tipo de pago

            $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
            foreach ($formapago as $formapago) {}
            $formapago = $formapago->fp_nombre;
            //articulos recuperados
            $subtotal = $carro->getSubTotal($getCart->ci_id);
            $total = $this->calculoCompra2($_POST["comprobante"]);
            foreach ($subtotal as $subtotal) {}
            $calcsubtotal = $subtotal->subtotal;
            $start_date = date_format_calendar($_POST["start_date"],"/");
            $end_date = date_format_calendar($_POST["end_date"],"/");

            //si hay articulos en el carro de lo contrario cancela el proceso
            if($getArticulos != null){
                $saveIngreso = $this->M_GuardarCompra->guardarActualizarCompra([
                        'idusuario'             => $_SESSION['usr_uid'],
                        'idsucursal'            => $_SESSION['idsucursal'],
                        'idproveedor'           => $idproveedor,
                        'tipo_pago'             => $formapago,
                        'tipo_comprobante'      => $tipoComprobante,
                        'serie_comprobante'     => $serieComprobante,
                        'num_comprobante'       => $ultimoNComprobante,
                        'factura_proveedor'     => $factura_proveedor,
                        'fecha'                 => $start_date,
                        'fecha_final'           => $end_date,
                        'impuesto'              => 0,
                        'sub_total'             => $calcsubtotal,
                        'subtotal_importe'      => 0,
                        'total'                 => $total,
                        'importe_pagado'        => $total,
                        'estado'                => "A"
            ]);
            exit($saveIngreso);
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
                /***********congifuracion de impuestos y retenciones ************/
                
                $impuestos = $colaimpuesto->getImpuestosBy($getCart->ci_id);
                $retenciones = $colaretencion->getRetencionBy($getCart->ci_id);
                /***********fin congifuracion de impuestos y retenciones ************/

                //registrar impuestos
                foreach($impuestos as $impuestos){
                    $detalleimpuesto->setDig_registro_comprobante($saveIngreso);
                    $detalleimpuesto->setDig_detalle_registro("I");
                    $detalleimpuesto->setDig_contabilidad(0);
                    $detalleimpuesto->setDig_im_id($impuestos->cdim_im_id);
                    $detalleimpuesto->addImpuesto();
                }
                //registrar retenciones
                foreach($retenciones as $retenciones){
                    $detalleretencion->setDrg_registro_comprobante($saveIngreso);
                    $detalleretencion->setDrg_detalle_registro("I");
                    $detalleretencion->setDrg_contabilidad(0);
                    $detalleretencion->setDrg_re_id($retenciones->cdr_re_id);
                    $detalleretencion->addRetencion();
                }

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
                    echo json_encode(array("success"=>"file/ingreso/$saveIngreso"));
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
                $ingresocontable = new ComprobanteContable($this->adapter);
                $detalleIngresoContable = new DetalleComprobanteContable($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $puc = new PUC($this->adapter);
                $impuestos = new Impuestos($this->adapter);
                $retenciones = new Retenciones($this->adapter);
                $categorias = new Categoria($this->adapter);
                $mymediafiles= new MyMediaFiles($this->adapter);
                $cartera = new Cartera($this->adapter);
                $comprobantes = new Comprobante($this->adapter);
                $colaimpuesto = new Colaimpuesto($this->adapter);
                $colaretencion = new Colaretencion($this->adapter);
                $detalleimpuesto = new DetalleImpuesto($this->adapter);
                $detalleretencion = new DetalleRetencion($this->adapter);
                $carro = new ColaIngreso($this->adapter);
                $dataformapago = new FormaPago($this->adapter);

                /******************************************************************************************/
                $pos =$_POST["pos"];
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
                 foreach ($getCart as $getCart){}
     
                 //obtener articulos
                 $getArticulos = $carro->getArtByCart($getCart->ci_id);
                //obtener tipo de pago
                
                $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
                foreach ($formapago as $formapago) {}
                $formadepago = $formapago->fp_nombre;
                
                //articulos recuperados
                $subtotal = $carro->getSubTotal($getCart->ci_id);
                $total = $this->calculoCompra2($_POST["comprobante"]);
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

                        if(isset($_POST["idingreso"]) && !empty($_POST["idingreso"]) && $_POST["idingreso"] >0){
                            $idingreso = $_POST["idingreso"];
                            //ella verificara si tiene todos los permisos necesarios para anularla, sino. solo creara otra factura y no hara afectacion
                            //a la anterior 
                            $actual_articles = $detalleIngresoContable->getArticulosByComprobante($idingreso);
                            $lastingreso = $ingresocontable->getComprobanteById($idingreso);
                            foreach($lastingreso as $lastingreso){}
                            $tipoComprobante = $lastingreso->cc_id_tipo_cpte;
                            $serieComprobante = $lastingreso->cc_num_cpte;
                            $ultimoNComprobante = $lastingreso->cc_cons_cpte;
                            //obtener lista de articulos
                            
                            $this->delete_compra_contable($idingreso,false);
                        }else{
                             //usar comprobante
                             $usarcomprobante = $comprobantes->usarComprobante($idcomprobante);
                        }
                    $ingresocontable->setCc_idusuario($_SESSION["usr_uid"]);
                    $ingresocontable->setCc_idproveedor($dataproveedor->idpersona);
                    $ingresocontable->setCc_tipo_comprobante("I");
                    $ingresocontable->setCc_id_forma_pago($forma_pago);
                    $ingresocontable->setCc_id_tipo_cpte($tipoComprobante);
                    $ingresocontable->setCc_num_cpte($serieComprobante);
                    $ingresocontable->setCc_cons_cpte(zero_fill($ultimoNComprobante,8));
                    $ingresocontable->setCc_det_fact_prov($factura_proveedor);
                    $ingresocontable->setCc_fecha_cpte($start_date);
                    $ingresocontable->setCc_fecha_final_cpte($end_date);
                    $ingresocontable->setCc_nit_cpte($dataproveedor->num_documento);
                    $ingresocontable->setCc_dig_verifi(0);
                    $ingresocontable->setCc_ccos_cpte($_SESSION["idsucursal"]);
                    $ingresocontable->setCc_fp_cpte($formadepago);
                    $ingresocontable->setCc_estado("A");
                    $ingresocontable->setCc_log_reg($_SESSION['usr_uid']."_".$start_date."_".date("h:i:s"));
                    $addIngreso = $ingresocontable->saveComprobanteContable();

                    $listImpuestos = $colaimpuesto->getImpuestosBy($getCart->ci_id);
                    $listRetenciones = $colaretencion->getRetencionBy($getCart->ci_id);
                    /***********fin congifuracion de impuestos y retenciones ************/

                    //registrar impuestos
                    foreach($listImpuestos as $listImpuestos){
                        $detalleimpuesto->setDig_registro_comprobante($addIngreso);
                        $detalleimpuesto->setDig_detalle_registro("I");
                        $detalleimpuesto->setDig_contabilidad(1);
                        $detalleimpuesto->setDig_im_id($listImpuestos->cdim_im_id);
                        $detalleimpuesto->addImpuesto();
                    }
                    //registrar retenciones
                    foreach($listRetenciones as $listRetenciones){
                        $detalleretencion->setDrg_registro_comprobante($addIngreso);
                        $detalleretencion->setDrg_detalle_registro("I");
                        $detalleretencion->setDrg_contabilidad(1);
                        $detalleretencion->setDrg_re_id($listRetenciones->cdr_re_id);
                        $detalleretencion->addRetencion();
                    }

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

                        $detalleIngresoContable->setDcc_id_trans($addIngreso);
                        $detalleIngresoContable->setDcc_seq_detalle(0);
                        $detalleIngresoContable->setDcc_cta_item_det($inventario);
                        $detalleIngresoContable->setDcc_det_item_det($articulo->nombre_articulo);
                        $detalleIngresoContable->setDcc_cod_art($articulo->idarticulo);
                        $detalleIngresoContable->setDcc_cant_item_det($item->cdi_stock_ingreso);
                        $detalleIngresoContable->setDcc_ter_item_det($dataproveedor->num_documento);
                        $detalleIngresoContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                        $detalleIngresoContable->setDcc_d_c_item_det("D");
                        $detalleIngresoContable->setDcc_valor_item($item->cdi_precio_unitario*$item->cdi_stock_ingreso);
                        $detalleIngresoContable->setDcc_base_imp_item($item->cdi_importe);
                        $detalleIngresoContable->setDcc_base_ret_item(0);
                        $detalleIngresoContable->setDcc_fecha_vcto_item(0);
                        $detalleIngresoContable->setDcc_dato_fact_prove("");
                        $addItem=$detalleIngresoContable->addArticulos(false);
                        
                    
                    }
                    else{}
                }

                //recorrer cada articulo y buscar sus impuestos
                foreach ($getArticulos as $dataimpuestos) {
                    if($dataimpuestos->cdi_type == "AR"){
                        //nuevo
                        if($dataimpuestos->cdi_importe){
                        $dataimpuesto = $impuestos->getImpuestoBy('im_porcentaje',$dataimpuestos->cdi_importe,"");
                        foreach ($dataimpuesto as $impuesto) {}
                        $cuenta_impuesto = $impuesto->im_cta_contable;

                        if($cuenta_impuesto){
                        $cuenta_codigo = $puc->getPucById($cuenta_impuesto);
                        foreach ($cuenta_codigo as $datapuc) {}
                        $precio_total_lote_sin_iva = $dataimpuestos->cdi_precio_total_lote / (($impuesto->im_porcentaje /100)+1);

                        $detalleIngresoContable->setDcc_id_trans($addIngreso);
                        $detalleIngresoContable->setDcc_seq_detalle(0);
                        $detalleIngresoContable->setDcc_cta_item_det($cuenta_impuesto);
                        $detalleIngresoContable->setDcc_det_item_det($datapuc->tipo_codigo);
                        $detalleIngresoContable->setDcc_cod_art(0);
                        $detalleIngresoContable->setDcc_cant_item_det($dataimpuestos->cdi_stock_ingreso);
                        $detalleIngresoContable->setDcc_ter_item_det($dataproveedor->num_documento);
                        $detalleIngresoContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                        $detalleIngresoContable->setDcc_d_c_item_det("D");
                        $detalleIngresoContable->setDcc_valor_item($precio_total_lote_sin_iva * ($impuesto->im_porcentaje/100));
                        $detalleIngresoContable->setDcc_base_imp_item(0);
                        $detalleIngresoContable->setDcc_base_ret_item(0);
                        $detalleIngresoContable->setDcc_fecha_vcto_item(0);
                        $detalleIngresoContable->setDcc_dato_fact_prove("");

                        $addImpuesto=$detalleIngresoContable->addArticulos();
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
                                    $detalleIngresoContable->setDcc_id_trans($addIngreso);
                                    $detalleIngresoContable->setDcc_seq_detalle(0);
                                    $detalleIngresoContable->setDcc_cta_item_det($cuenta_retencion->idcodigo);
                                    $detalleIngresoContable->setDcc_det_item_det($cuenta_retencion->tipo_codigo);
                                    $detalleIngresoContable->setDcc_cod_art(0);
                                    $detalleIngresoContable->setDcc_cant_item_det($dataretenciones->cdi_stock_ingreso);
                                    $detalleIngresoContable->setDcc_ter_item_det($dataproveedor->num_documento);
                                    $detalleIngresoContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                    $detalleIngresoContable->setDcc_d_c_item_det("C");
                                    $detalleIngresoContable->setDcc_valor_item($calculo_retencion);
                                    $detalleIngresoContable->setDcc_base_imp_item(0);
                                    $detalleIngresoContable->setDcc_base_ret_item($total_impuesto);
                                    $detalleIngresoContable->setDcc_fecha_vcto_item(0);
                                    $detalleIngresoContable->setDcc_dato_fact_prove("");
                                    $addCodCostos=$detalleIngresoContable->addArticulos();
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
                                $detalleIngresoContable->setDcc_id_trans($addIngreso);
                                $detalleIngresoContable->setDcc_seq_detalle(0);
                                $detalleIngresoContable->setDcc_cta_item_det($cuenta_retencion->idcodigo);
                                $detalleIngresoContable->setDcc_det_item_det($cuenta_retencion->tipo_codigo);
                                $detalleIngresoContable->setDcc_cod_art(0);
                                $detalleIngresoContable->setDcc_cant_item_det($dataretenciones->cdi_stock_ingreso);
                                $detalleIngresoContable->setDcc_ter_item_det($dataproveedor->num_documento);
                                $detalleIngresoContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                                $detalleIngresoContable->setDcc_d_c_item_det("C");
                                $detalleIngresoContable->setDcc_valor_item($retencion_this);
                                $detalleIngresoContable->setDcc_base_imp_item(0);
                                $detalleIngresoContable->setDcc_base_ret_item($total);
                                $detalleIngresoContable->setDcc_fecha_vcto_item(0);
                                $detalleIngresoContable->setDcc_dato_fact_prove("");
                                $addCodCostos=$detalleIngresoContable->addArticulos();
                            }
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
                            $detalleIngresoContable->setDcc_id_trans($addIngreso);
                            $detalleIngresoContable->setDcc_seq_detalle(0);
                            $detalleIngresoContable->setDcc_cta_item_det($cuenta->idcodigo);
                            $detalleIngresoContable->setDcc_det_item_det($cuenta->tipo_codigo);
                            $detalleIngresoContable->setDcc_cod_art(0);
                            $detalleIngresoContable->setDcc_cant_item_det($datapago->cdi_stock_ingreso);
                            $detalleIngresoContable->setDcc_ter_item_det($dataproveedor->num_documento);
                            $detalleIngresoContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                            $detalleIngresoContable->setDcc_d_c_item_det("C");
                            $detalleIngresoContable->setDcc_valor_item($datapago->cdi_precio_total_lote - $total_retenido);
                            $detalleIngresoContable->setDcc_base_imp_item(0);
                            $detalleIngresoContable->setDcc_base_ret_item(0);
                            $detalleIngresoContable->setDcc_fecha_vcto_item($end_date);
                            $detalleIngresoContable->setDcc_dato_fact_prove($factura_proveedor);
                            
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
                            $detalleIngresoContable->setDcc_id_trans($addIngreso);
                            $detalleIngresoContable->setDcc_seq_detalle(0);
                            $detalleIngresoContable->setDcc_cta_item_det($datapago->cdi_idarticulo);
                            $detalleIngresoContable->setDcc_det_item_det($cuenta->tipo_codigo);
                            $detalleIngresoContable->setDcc_cod_art(0);
                            $detalleIngresoContable->setDcc_cant_item_det($datapago->cdi_stock_ingreso);
                            $detalleIngresoContable->setDcc_ter_item_det($dataproveedor->num_documento);
                            $detalleIngresoContable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                            $detalleIngresoContable->setDcc_d_c_item_det("C");
                            $detalleIngresoContable->setDcc_valor_item($datapago->cdi_precio_total_lote - $total_retenido);
                            $detalleIngresoContable->setDcc_base_imp_item(0);
                            $detalleIngresoContable->setDcc_base_ret_item(0);
                            $detalleIngresoContable->setDcc_fecha_vcto_item(0);
                            $detalleIngresoContable->setDcc_dato_fact_prove(0);
                            $addCodigo=$detalleIngresoContable->addArticulos();
                        }
                    }
                }

                    if($addIngreso){
                        
                        
                        $mymediafiles->setMmf_name("Ingreso ".$serieComprobante.zero_fill($ultimoNComprobante,8));
                        $mymediafiles->setMmf_url("file/comprobantes/".$addIngreso."/window_view/print_off");
                        $mymediafiles->setMmf_user($_SESSION["usr_uid"]);
                        $mymediafiles->setMmf_idsucursal($_SESSION["idsucursal"]);
                        $mymediafiles->setMmf_ext("pdf");
                        $mymediafiles->setMmf_status("1");
                        $mymediafiles->setMmf_reg("");
                        $mymediafiles->setMmf_window(1);
                        $mymediafiles->setMmf_viewed(0);

                        $addFile = $mymediafiles->createFile();
                        if($addFile){
                            $ext = "/true";
                        }else{
                            $ext = "/false";
                        }
                        //eliminar carro
                        $carro->deleteCart();

                        echo json_encode(array("success"=>"file/comprobantes/$addIngreso"));
                    }else{
                        echo json_encode(array("error"=>"No se pudo agregar"));
                        }
                }else{
                    echo json_encode(array("error"=>"Debe indicar una cartera o cuenta de pago"));
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
            $dataimpuestos= new Impuestos($this->adapter);
            $totalcart = new ColaIngreso($this->adapter);
            $colaretencion = new Colaretencion($this->adapter);
            $colaimpuestos= new ColaImpuesto($this->adapter);
            $colaingreso = new ColaIngreso($this->adapter);
            $getCart = $colaingreso->getCart();
            foreach($getCart as $getCart){}

            $retenciones = $colaretencion->getRetencionBy($getCart->ci_id);
            $impuestos = $colaimpuestos->getImpuestosBy($getCart->ci_id);

            $subtotal = $colaingreso->getSubTotal($getCart->ci_id);
            $totalimpuestos = $colaingreso->getImpuestos($getCart->ci_id);
            
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
            $impuesto = new Impuestos($this->adapter);
            $retencion = new Retenciones($this->adapter);
            $comprobante = new Comprobante($this->adapter);
            $formapago= new FormaPago($this->adapter);
            $compras = new Compras($this->adapter);
            $detalleIngreso = new DetalleIngreso($this->adapter);
            $cart = new ColaIngreso($this->adapter);
            $detalleimpuesto = new DetalleImpuesto($this->adapter);
            $detalleretencion = new DetalleRetencion($this->adapter);
            $colaimpuesto = new ColaImpuesto($this->adapter);
            $colaretencion = new ColaRetencion($this->adapter);

            $impuestos = $impuesto->getImpuestosAll();
            $retenciones = $retencion->getRetencionesAll();
            $getsucursal= $sucursal->getSucursalById($idsucursal);
            //obtener datos de usuario
            $idusuario = $_SESSION["usr_uid"];
            //ubicacion
            $pos_proceso ="Ingreso";
            $contabilidad = 0;
            //comprobante
            $comprobantes = $comprobante->getComprobante($pos_proceso);
            //formas de pago
            
            $formaspago = $formapago->getFormaPago($pos_proceso);
            $idcompra = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
            if($idcompra){
                //preparando datos
            
            $compra = $compras->getCompraById($idcompra);
            foreach ($compra as $datacompra) {}
                //recuperar items de la compra
            
            $detalle = $detalleIngreso->getArticulosByCompra($idcompra);
            //llamar el modelo del cart

            $detalleimpuestos = $detalleimpuesto->getImpuestosBy($idcompra,'0','I');
            $detalleretenciones = $detalleretencion->getRetencionBy($idcompra,'0','I');
            
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

                $this->frameview("compras/Edit/index",array(
                    "compra"=>$compra,
                    "items"=>$items,
                    "contabilidad"=>$contabilidad,
                    "sucursal"=>$getsucursal,
                    "idusuario"=>$idusuario,
                    "pos"=>$pos_proceso,
                    "comprobantes" => $comprobantes,
                    "formaspago" => $formaspago,
                    "impuestos"=>$impuestos,
                    "retenciones"=>$retenciones
                ));

            }
        }else{
            echo "Forbidden Gateway";
        }
    }else{
        
    }

    }

    public function edit_compra_contable(){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
            if(!empty($_SESSION["usr_uid"])  && $_SESSION["permission"] > 4){
                //models
                $sucursal = new Sucursal($this->adapter);
                $ingresos = new ComprobanteContable($this->adapter);
                $detalleIngresoContable = new DetalleComprobanteContable($this->adapter);
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

                $getsucursal= $sucursal->getSucursalById($idsucursal);
                $impuestos = $impuesto->getImpuestosAll();
                $retenciones = $retencion->getRetencionesAll();
                //obtener datos de usuario
                $idusuario = $_SESSION["usr_uid"];
                //ubicacion
                $pos_proceso ="Ingreso";
                $contabilidad = "Contable";
                $autocomplete= "codigo_contable";
                $control_proceso="";

                $comprobantes = $comprobante->getComprobante($pos_proceso);
                //cargar formas de patgo
                $formaspago = $formapago->getFormaPago($pos_proceso);
                $idcompra = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
                if($idcompra){
                    
                    $ingreso = $ingresos->getComprobanteById($idcompra);
                    foreach($ingreso as $dataingreso){}
                    $detalle = $detalleIngresoContable->getArticulosByComprobante($idcompra);
                    //recuperar impuestos y retenciones
                    $detalleimpuestos = $detalleimpuesto->getImpuestosBy($idcompra,'1','I');
                    $detalleretenciones = $detalleretencion->getRetencionBy($idcompra,'1','I');

                    $cart = new ColaIngreso($this->adapter);
                    $cart->setCi_usuario($_SESSION["usr_uid"]);
                    $cart->setCi_idsucursal($_SESSION["idsucursal"]);
                    $cart->setCi_idproveedor(0);
                    $cart->setCi_tipo_pago(0);
                    $cart->setCi_comprobante(0);
                    $cart->setCi_fecha(date("Y-m-d"));
                    $cart->setCi_fecha_final(date("Y-m-d"));
                    $addCart = $cart->createCart();
                    $costo_total_articulos = 0;
                    foreach ($detalle as $dataitems) {
                        if($dataitems->dcc_cod_art){
                            $articulo = $articulos->getArticuloById($dataitems->dcc_cod_art);
                            foreach($articulo as $articulo){}
                            $cart->setCdi_ci_id($addCart);
                            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                            $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                            $cart->setCdi_tercero($dataingreso->nombre_tercero);
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
                                    $cart->setCdi_tercero($dataingreso->nombre_tercero);
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

                    $this->frameview("compras/contable/edit",array(
                        "compra"=>$ingreso,
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
        }
    }
    public function updateCompra()
    {
        
        if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_SESSION["permission"] > 4){
            if(isset($_POST["idingreso"]) && !empty($_POST["idingreso"])){
            
            $idingreso = $_POST["idingreso"];
            $factura_proveedor = $_POST["factura_proveedor"];
            $ingreso = new Ingreso($this->adapter);
            $compras = new Compras($this->adapter);
            $dataarticulos = new Articulo($this->adapter);
            $cartera = new Cartera($this->adapter);
            $carro = new ColaIngreso($this->adapter);
            $dataformapago = new FormaPago($this->adapter);
            $detalleIngreso = new DetalleIngreso($this->adapter);
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
            
            $getCart = $carro->getCart();
            foreach ($getCart as $getCart){}
            $getArticulos = $carro->getArtByCart($getCart->ci_id);
            //obtener tipo de pago
            $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
            foreach ($formapago as $formapago) {}
            $formapago = $formapago->fp_nombre;
            //articulos recuperados
            $subtotal = $carro->getSubTotal($getCart->ci_id);
            $total = $this->calculoCompra2($_POST["comprobante"]);
            foreach ($subtotal as $subtotal) {}
            $calcsubtotal = $subtotal->cdi_debito;

            $start_date = date_format_calendar($_POST["start_date"],"/");
            $end_date = date_format_calendar($_POST["end_date"],"/");

                if($getArticulos != null){
                    //despues de autorizar la actualizacion descontamos los articulos del stock 
                    //se obtiene la venta
                    $actual_compra = $compras->getCompraById($idingreso);
                    foreach($actual_compra as $actual_compra){}
                    //se obtienen los articulos
                     $articles_actual_compra = $detalleIngreso->getArticulosByCompra($actual_compra->idingreso);
                     //se recorre cada articulo
                     foreach($articles_actual_compra as $actual_articles){
                         //se agregan la cantidad que habia anteriormente para luego almacenarlos de nuevo
                        $articulo = $dataarticulos->removeCantStock($actual_articles->idarticulo,$actual_articles->stock_ingreso);
                     }
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
                            
                                $ingreso->setSubtotal_importe($impuesto_compra);
                                $impuestocompra = $ingreso->addImpuestoIngreso($idingreso);
                            
                    echo json_encode(array("success"=>$idingreso)); 
            }
        }
    }else{
        echo json_encode(array("error"=>"el Proveedor y el comprobante son importantes"));
    }
    }else{
        echo json_encode(array("error"=>"compra no disponible para actualizar"));
    }
    }else{
        echo json_encode(array("error"=>"no se puede actualizar esta compra, es probable que no tengas permisos"));
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

    public function delete_compra_contable($idingreso,$alert){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4 && !empty($idingreso) && $idingreso > 0){
            //models
                $ingresocontable = new ComprobanteContable($this->adapter);
                $detalleIngresoContable = new DetalleComprobanteContable($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $cartera = new Cartera($this->adapter);
                $tokenization = new Tokenization($this->adapter);
                $user = new User($this->adapter);

                $ingreso = $ingresocontable->getComprobanteById($idingreso);
                foreach($ingreso as $ingreso){}
                if($ingreso->cc_id_transa){
                        
                        
                                $detalleingreso= $detalleIngresoContable->getArticulosByComprobante($ingreso->cc_id_transa);
                                foreach($detalleingreso as $detalleingreso){
                                    if($detalleingreso->dcc_cod_art){
                                        $dataarticulos->removeCantStock($detalleingreso->dcc_cod_art,$detalleingreso->dcc_cant_item_det);
                                    }
                                }
                            $ingresocontable->delete_comprobante($ingreso->cc_id_transa);
                            if($ingresocontable && $alert){
                                return true;
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
            $start_date = date_format_calendar($_POST["start_date"],"/");
            $end_date = date_format_calendar($_POST["end_date"],"/");
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
            $array = explode(" - ", $_POST["proveedor"]);
            $proveedores = new Persona($this->adapter);
            $i =0;
            foreach ($array as $search) {$getProveedor = $proveedores->getProveedorByDocument($array[$i]);
                //si se encontro algo en proveedores lo retorna
                foreach ($getProveedor as $dataProveedor) {}
                    $i++;
            }

            $proveedor = $dataProveedor->num_documento;

            $start_date = date_format_calendar($_POST["start_date"],"/");
            $end_date = date_format_calendar($_POST["end_date"],"/");
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
            $array = explode(" - ", $_POST["proveedor"]);
            $proveedores = new Persona($this->adapter);
            $i =0;
            foreach ($array as $search) {$getProveedor = $proveedores->getProveedorByDocument($array[$i]);
                //si se encontro algo en proveedores lo retorna
                foreach ($getProveedor as $dataProveedor) {}
                    $i++;
            }

            $proveedor = $dataProveedor->num_documento;
            $start_date = date_format_calendar($_POST["start_date"],"/");
            $end_date = date_format_calendar($_POST["end_date"],"/");
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
        $pos = "reporte_utilidad";
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
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
        if(isset($_POST["start_date"]) && isset($_POST["end_date"]) && !empty($_POST["start_date"]) && !empty($_POST["end_date"])){
            //models
            $articulo = new Articulo($this->adapter);
            $compra = new Compras($this->adapter);
            $venta = new Ventas($this->adapter);
            $detallecompra = new DetalleIngreso($this->adapter);
            $detalleventa = new DetalleVenta($this->adapter);
            $comprasAll = $detallecompra->getDetalleAllByDay();
            $ventasAll = $detalleventa->getDetalleAll();
            $start_date = date_format_calendar($_POST["start_date"],"/");
            $end_date = date_format_calendar($_POST["end_date"],"/");

            $itemResult =[];
            $dataarticulos = $articulo->getArticuloAll();
            foreach ($dataarticulos as $articulos) {
                /*-----------------*/
                    $stock_compra = 0;
                    $precio_total_stock_compra =0;
                    $stock_venta = 0;
                    $precio_total_stock_venta =0;
                    $saldo=0;
                    $promedio_compra=0;
                    $costo_total=0;
                /*-----------------*/
                $datacompras = $compra->getCompraByArticulo($articulos->idarticulo);
                
                    foreach ($datacompras as $compras) {
                        if($compras){
                        if($compras->fecha >= $start_date && $compras->fecha <=$end_date){
                            $stock_compra += (isset($compras->stock_compras) && !empty($compras->stock_compras))?$compras->stock_compras:0;
                            $precio_total_stock_compra += round($compras->precio_compras);
                            //$saldo +=$compras->stock_ingreso;
                            
                        }
                    }
                }

                $dataventas = $venta->getVentaByArticulo($articulos->idarticulo);
                
                    foreach ($dataventas as $ventas) {
                        if($ventas){
                        if($ventas->fecha >= $start_date && $ventas->fecha <=$end_date){
                            $stock_venta += $ventas->stock_ventas;
                            $precio_total_stock_venta += $ventas->precio_ventas;
                            //$saldo -=$stock_venta;
                        }
                    }
                }

                ################## saldo anterior
                $mes_anterior = date("m",strtotime($start_date)) - 1;
                $stock_anterior = 0;
                $precio_compra_anterior = 0;
                $promedio_precio_compra_anterior =0;
                $precio_venta_anterior =0;
                
                if($comprasAll){
                    foreach ($comprasAll as $compra_anterior){
                        $mes_compra = date("m",strtotime($compra_anterior->fecha));
                        $ano_compra = date("Y",strtotime($compra_anterior->fecha));
                        if($compra_anterior->fecha < $start_date && $compra_anterior->idarticulo == $articulos->idarticulo){
                            $stock_anterior += $compra_anterior->stock_total_compras;
                            $precio_compra_anterior  += $compra_anterior->precio_total_lote; ##junto con impuesto
                        }
                    }
                    $promedio_precio_compra_anterior = ($stock_anterior)?$precio_compra_anterior / $stock_anterior:0;
                }
                if($ventasAll){
                    foreach ($ventasAll as $venta_anterior){
                        $mes_venta = date("m",strtotime($venta_anterior->fecha));
                        $ano_venta = date("Y",strtotime($venta_anterior->fecha));
                        if($venta_anterior->fecha == $start_date && $venta_anterior->idarticulo == $articulos->idarticulo){
                            $stock_anterior -= $venta_anterior->cantidad;
                            $precio_venta_anterior += $venta_anterior->precio_total_lote;
                            $precio_compra_anterior -= $promedio_precio_compra_anterior;
                            
                        }
                    }
                }
                
                
                //[promedios]
                if($stock_compra || $stock_venta){
                    $saldo = ($stock_compra - $stock_venta) + $stock_anterior ;
                    $promedio_compra = $precio_total_stock_compra ;
                    $costo_total =($saldo)?$saldo*$promedio_compra:0;
                }
                    $itemResult[]=array(
                        "stock_compra"=>$stock_compra,
                        "precio_total_stock_compra"=>$precio_total_stock_compra,
                        "stock_venta"=>$stock_venta,
                        "precio_total_stock_venta"=>$precio_total_stock_venta,
                        "saldo"=>$saldo,
                        "costo_total"=>$costo_total,
                        "promedio_compra"=>$promedio_compra,
                        "idarticulo"=>$articulos->idarticulo,
                        "nombre_articulo"=>$articulos->nombre_articulo,
                    );
                
                

            }
            $this->frameview("compras/reportes/detallada/tableStock",array(
                "itemResult"=>$itemResult
            ));
        }else{
            
        }
    }
    
    }
    
}
?>
