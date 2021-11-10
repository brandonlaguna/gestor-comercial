<?php
class ComprobantesController extends ControladorBase{
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

    public function registro()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            //obtener sucursal
            $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:1;
            //models
            $sucursal = new Sucursal($this->adapter);
            $impuesto = new Impuestos($this->adapter);
            $retencion = new Retenciones($this->adapter);
            $comprobante = new Comprobante($this->adapter);
            $formapago= new FormaPago($this->adapter);
            $cart = new ColaIngreso($this->adapter);
            //functions

            $getsucursal= $sucursal->getSucursalById($idsucursal);
            $idusuario = $_SESSION["usr_uid"];
            //ubicacion
            $pos_proceso ="Contabilidad";

            $contabilidad = "Contable";
            $autocomplete= "codigo_contable";
            //comprobante
            
            $comprobantes = $comprobante->getComprobanteContable($pos_proceso);
            //formas de pago
            
            $formaspago = $formapago->getFormaPago($pos_proceso);
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
            
            $items = $cart->loadCart();


            $impuestos = $impuesto->getImpuestosAll();
            $retenciones = $retencion->getRetencionesAll();
            
            $this->frameview("comprobantes/New/index",array(
                "contabilidad"=>$contabilidad,
                "sucursal"=>$getsucursal,
                "impuestos"=>$impuestos,
                "retenciones"=>$retenciones,
                "idusuario"=>$idusuario,
                "pos"=>$pos_proceso,
                "comprobantes" => $comprobantes,
                "formaspago" => $formaspago,
                "items"=>$items,
                "autocomplete"=>$autocomplete
            ));
        }
    }

    public function sendItem()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"])){
        if($_POST["data"] && !empty($_POST["data"]) && !empty($_POST["tercero"]) && !empty($_POST["pos"])){
            //models
            $cart = new ColaIngreso($this->adapter);
            $articulos= new Articulo($this->adapter);
            $impuestos = new Impuestos($this->adapter); 
            $retenciones = new Retenciones($this->adapter);
            $puc = new PUC($this->adapter);

            $pos = $_POST["pos"];
            $tercero = $_POST["tercero"];
            $item = $_POST["data"];
            //obtener item o servicio
            $credito =0;
            $debito=0;
            $result=[];
            /**********************************Si el item es un producto**************************************/
            //setear datos en variables 
            if($item["iditem"] > 0){
                $idarticulo = $item["iditem"];
                $cantidad = 1; //cantidad por default $_POST["cantidad"]
                $imp_compra = $item["imp_compra"]; 
                $idretencion = $item["retencion"];
                $descripcion = $item["descripcion"];
                $costo_producto= $item["costo_producto"];
                $cod_costos =$item["cod_costos"];
                $method = $item["method"];

                $articulo = $articulos->getArticuloById($idarticulo);
                foreach($articulo as $articulo){}
                if($articulo->idarticulo){
                    //recuperar impuesto enviado {id}
                    $impuesto = $impuestos->getImpuestosById($imp_compra);
                    $retencion = $retenciones->getRetencionesById($idretencion);
                    foreach($impuesto as $impuesto) {}
                    foreach($retencion as $retencion){}
                    $ivaarticulo = ($impuesto->im_porcentaje>0)?$impuesto->im_porcentaje:1;
                    //calcular 
                    $total_lote = ($costo_producto * (($ivaarticulo/100)+1))*$cantidad;

                    $total_sin_iva = $total_lote / (($ivaarticulo/100)+1);
                    //obtener la informacion del articulo de este articulo
                    $debito_imp =0;
                    $credito_imp=0;
                    if(!empty($method)){
                    //debito o credito
                    if($method =="D"){
                        $debito=$total_sin_iva;
                        $debito_imp = $total_lote - $total_sin_iva;
                    }
                    elseif($method=="C"){$credito =$total_sin_iva;
                        $credito_imp = $total_lote - $total_sin_iva;
                    }
                    else{}
                    //agregar articulo al carro
                    if($cantidad){
                    $getCart = $cart->getCart();
                    $retenido =0;
                    $credito_re = 0;
                    $debito_re = 0;
                    foreach($getCart as $getCart);
                    $cart->setCdi_ci_id($getCart->ci_id);
                    $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                    $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                    $cart->setCdi_tercero($tercero);
                    $cart->setCdi_idarticulo($idarticulo);
                    $cart->setCdi_detalle($articulo->nombre_articulo);
                    $cart->setCdi_stock_ingreso($cantidad);
                    $cart->setCdi_precio_unitario($costo_producto);
                    $cart->setCdi_importe($ivaarticulo);
                    $cart->setCdi_im_id($impuesto->im_id);
                    $cart->setCdi_precio_total_lote($total_lote);
                    $cart->setCdi_credito($credito);
                    $cart->setCdi_debito($debito);
                    $cart->setCdi_cod_costos($cod_costos);
                    $cart->setCdi_type("AR");
                    $res = $cart->addItemToCart();
                    //

                    if($retencion->re_cta_contable){
                        $getCart = $cart->getCart();
                        foreach($getCart as $getCart);
                        $porcentaje = $retencion->re_porcentaje;
                        if($retencion->re_im_id >0){
                            //obtener impuesto al que se retiene
                            $reteimpuestos = $impuestos->getImpuestosById($retencion->re_im_id);
                            foreach($reteimpuestos as $reteimpuesto){}
                            //info del impuesto
                            //total del impuesto 
                            $total_impuesto = $total_lote -$total_sin_iva;
                            $retenido = $total_impuesto * ($porcentaje /100);

                        }else{
                            $sub_total_articulo = $total_lote -$total_sin_iva;
                            $retenido = $sub_total_articulo * ($porcentaje /100);
                        }
                        
                        if($method == 'D'){
                            $credito_re = $retenido;
                        }elseif($method == 'C'){
                            $debito_re = $retenido;
                        }
                        $cart->setCdi_ci_id($getCart->ci_id);
                        $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                        $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                        $cart->setCdi_tercero($tercero);
                        $cart->setCdi_idarticulo($retencion->re_cta_contable);
                        $cart->setCdi_detalle($retencion->re_nombre);
                        $cart->setCdi_stock_ingreso(1);
                        $cart->setCdi_precio_unitario($retenido);
                        $cart->setCdi_importe(0);
                        $cart->setCdi_im_id(0);
                        $cart->setCdi_precio_total_lote($retenido);
                        $cart->setCdi_credito($credito_re);
                        $cart->setCdi_debito($debito_re);
                        $cart->setCdi_cod_costos(0);
                        $cart->setCdi_type("CO");
                        $res = $cart->addItemToCart();

                    }

                    if($impuesto->im_cta_contable){
                        $getCart = $cart->getCart();
                        foreach($getCart as $getCart);
                        $cart->setCdi_ci_id($getCart->ci_id);
                        $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                        $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                        $cart->setCdi_tercero($tercero);
                        $cart->setCdi_idarticulo($impuesto->im_cta_contable);
                        $cart->setCdi_detalle($impuesto->im_nombre);
                        $cart->setCdi_stock_ingreso(1);
                        $cart->setCdi_precio_unitario($total_lote -$total_sin_iva);
                        $cart->setCdi_importe($impuesto->im_porcentaje);
                        $cart->setCdi_im_id($impuesto->im_id);
                        $cart->setCdi_precio_total_lote($total_lote -$total_sin_iva);
                        $cart->setCdi_credito($credito_imp);
                        $cart->setCdi_debito($debito_imp);
                        $cart->setCdi_cod_costos(0);
                        $cart->setCdi_type("CO");
                        $res = $cart->addItemToCart();
                    }

                    //obtener codigo de inventarios
                    }else{
                        $result[] = array("errors"=>"no hay la cantidad suficiente para este articulo");
                    }
                }else{
                    $result[] = array("errors"=>"Indica hacia donde va este articulo Debito o Credito");
                }
                }
            }elseif($item["idservicio"] >0){
            /**********************************Si el item es un servicio**************************************/

            }elseif($item["idcodigo"] >0){

             /**********************************Si el item es un codigo contable**************************************/
            $idcodigo = $item["idcodigo"];
            $cantidad = 1;//cantidad por default
            $method = $item["method"];
            $imp_compra = $item["imp_compra"];
            $idretencion = $item["retencion"];
            $descripcion = $item["descripcion"];
            $costo_producto = $item["costo_producto"] * $cantidad;

            $impuesto = $impuestos->getImpuestosById($imp_compra);
            $retencion = $retenciones->getRetencionesById($idretencion);

            foreach ($impuesto as $dataimpuesto) {}
            foreach($retencion as $dataretencion){}

            $ivaarticulo = ($dataimpuesto->im_porcentaje>0)?$dataimpuesto->im_porcentaje:1;
            
            $total = $costo_producto;
            $subtotal = ($dataimpuesto->im_porcentaje>0)?$total * (($dataimpuesto->im_porcentaje/100)+1):0;
            $totalimpuesto = ($dataimpuesto->im_porcentaje>0)?$total*($dataimpuesto->im_porcentaje/100):0;
            $cod_costos=0;
            $debito_imp =0;
            $credito_imp=0;
            $credito_re =0;
            $debito_re =0;
            if($costo_producto){

                if($method =="C"){
                    $credito= $costo_producto - $totalimpuesto;
                    $credito_imp = $totalimpuesto;
                    $costo_producto -= $totalimpuesto;
                }
                elseif(
                    $method=="D"){
                    $debito = $costo_producto - $totalimpuesto;
                    $debito_imp = $totalimpuesto;
                    $costo_producto -= $totalimpuesto;
                }
                else{}

            //retencion arriba para poder setear si se retiene al subtotal o a algun impuesto
            //iniciar variables de retencion
            //retencion a este iva ingresado
            $retencion_iva = 0;
            //retencion al subtotal
            $retencion_subtotal = 0;
            //base de retencion
            $retencion_base=0;
            if($dataretencion->re_cta_contable){
                //obtener informacion de la cuenta contable
                $cuentacontable = $puc->getPucById($dataretencion->re_cta_contable);
                foreach($cuentacontable as $datacuenta){}
                //verificar si esta retencion se le puede aplicar a un impuesto definido
                $total_retencion=0;
                if($dataretencion->re_im_id == $dataimpuesto->im_id){
                    $total_retencion = $totalimpuesto * ($dataretencion->re_porcentaje/100);
                    if($method=="C"){$credito_imp -= $total_retencion;}
                    if($method=="D"){$debito_imp  -= $total_retencion;}
                    $retencion_base=$totalimpuesto;
                    $totalimpuesto -= $total_retencion;
                    //$costo_producto -= $total_retencion;
                }else{}

                if(!$dataretencion->re_im_id){
                    $total_retencion = $costo_producto * ($dataretencion->re_porcentaje/100);
                    if($method=="C"){$credito -= $total_retencion;}
                    if($method=="D"){$debito  -= $total_retencion;}
                    $costo_producto -= $total_retencion;
                    $retencion_base=$costo_producto;
                }else{}

                if($method=="D"){$debito_re = $total_retencion;}
                if($method=="C"){$credito_re = $total_retencion;}

                //registrar comprobante contable de la retencion con el valor y la base de retencion
                if(isset($datacuenta->retencion) && $datacuenta->retencion &&$datacuenta->movimiento){
                    $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                    $getCart = $cart->getCart();
                    foreach($getCart as $getCart);
                        $cart->setCdi_ci_id($getCart->ci_id);
                        $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                        $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                        $cart->setCdi_tercero("");
                        $cart->setCdi_idarticulo($dataretencion->re_cta_contable);
                        $cart->setCdi_detalle($datacuenta->tipo_codigo);
                        $cart->setCdi_stock_ingreso(1);
                        $cart->setCdi_precio_unitario($total_retencion);
                        $cart->setCdi_importe($dataretencion->re_porcentaje);
                        $cart->setCdi_im_id($dataretencion->re_im_id);
                        $cart->setCdi_base_ret($retencion_base);
                        $cart->setCdi_precio_total_lote($total_retencion);
                        $cart->setCdi_credito($credito_re);
                        $cart->setCdi_debito($debito_re);
                        $cart->setCdi_cod_costos(0);
                        $cart->setCdi_type("CO");
                        $res = $cart->addItemToCart();

                }else{
                    $result[] = array("errors"=>"uno o mas retenciones no se han podido agregar, verifica la configuracion de las cuentas de retenciones");
                }


            }


            $getCart = $cart->getCart();
            foreach($getCart as $getCart);

            $cart->setCdi_ci_id($getCart->ci_id);
            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
            $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
            $cart->setCdi_tercero($tercero);
            $cart->setCdi_idarticulo($idcodigo);
            $cart->setCdi_detalle($descripcion);
            $cart->setCdi_stock_ingreso($cantidad);
            $cart->setCdi_precio_unitario($costo_producto / $cantidad);
            $cart->setCdi_importe($ivaarticulo);
            $cart->setCdi_im_id("");
            $cart->setCdi_base_ret(0);
            $cart->setCdi_precio_total_lote($costo_producto);
            $cart->setCdi_credito($credito);
            $cart->setCdi_debito($debito);
            $cart->setCdi_cod_costos($cod_costos);
            $cart->setCdi_type("CO");
            $res = $cart->addItemToCart();

            if($dataimpuesto->im_cta_contable){
                //obtener informacion de la cuenta contable
                $cuentacontable = $puc->getPucById($dataimpuesto->im_cta_contable);
                foreach($cuentacontable as $datacuenta){}
                    if($datacuenta->impuesto && $datacuenta->movimiento){
                        $getCart = $cart->getCart();
                        foreach($getCart as $getCart);
                        $cart->setCdi_ci_id($getCart->ci_id);
                        $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                        $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                        $cart->setCdi_tercero("");
                        $cart->setCdi_idarticulo($dataimpuesto->im_cta_contable);
                        $cart->setCdi_detalle($datacuenta->tipo_codigo);
                        $cart->setCdi_stock_ingreso(1);
                        $cart->setCdi_precio_unitario($totalimpuesto);
                        $cart->setCdi_importe($dataimpuesto->im_porcentaje);
                        $cart->setCdi_im_id($dataimpuesto->im_id);
                        $cart->setCdi_base_ret(0);
                        $cart->setCdi_precio_total_lote(0);
                        $cart->setCdi_credito($credito_imp);
                        $cart->setCdi_debito($debito_imp);
                        $cart->setCdi_cod_costos(0);
                        $cart->setCdi_type("CO");
                        $res = $cart->addItemToCart();
                    }else{
                        $result[] = array("errors"=>"uno o mas impuestos no se han podido agregar, verifica la configuracion de las cuentas de impuesto");
                    }
            }

        
            
            }else{
                $result[] = array("errors"=>"Esta cuenta no debe tener un saldo de 0");
            }
        }
            echo json_encode(array("errors"=>$result));
        }else{
            $result[] = array("errors"=>"Agrega la informacion del tercero y el comprobante");
            echo json_encode(array("errors"=>$result));
        }
        }
        
    }

    public function crearComprobante()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 0){
                //models
                
                $proveedor = new Persona($this->adapter);
                $comprobantes = new Comprobante($this->adapter);
                $dataformapago = new FormaPago($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $carro = new ColaIngreso($this->adapter);
                $comprobantecontable= new ComprobanteContable($this->adapter);
                $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
                $puc = new PUC($this->adapter);
                $impuestos = new Impuestos($this->adapter);
                $retenciones = new Retenciones($this->adapter);
                $categorias = new Categoria($this->adapter);
                $mymediafiles= new MyMediaFiles($this->adapter);
                $cartera = new Cartera($this->adapter);
                $detalleimpuesto = new DetalleImpuesto($this->adapter);
                $detalleretencion = new DetalleRetencion($this->adapter);

                //obtener informacion de el detalle de  la factura

                $tercero = $_POST["proveedor"];
                $idcomprobante = $_POST["comprobante"];
                $idpago = $_POST["formaPago"];
                $start_date = date_format_calendar($_POST["start_date"],"/");
                $end_date = date_format_calendar($_POST["end_date"],"/");
                $factura = $_POST["factura_proveedor"];

                //setear estado de los datos
                $state = false;
                $alerts=[];

                //funciones
                $getArticulos = $carro->getAllCart();

                if($tercero !=null){
                    $array = explode(" - ", $tercero);
                    $i =0;
                    foreach ($array as $search) {$getProveedor = $proveedor->getPersonaByDocument($array[$i]);
                    //si se encontro algo en proveedores lo retorna
                    foreach ($getProveedor as $dataproveedor) {}
                    $i++;
                }
                //setear primer estado en verdader
                    $state = (isset($dataproveedor))?true:false;
                    if(!$state){$alerts[]=array("errors"=>"Este tercero no existe");}
                
                }
                //verificar comprobante
                if($idcomprobante !=null){
                    $getComprobanteByid = $comprobantes->getComprobanteById($idcomprobante);

                    foreach ($getComprobanteByid as $comprobante) {}
                    if($comprobante){
                        $tipoComprobante =$comprobante->iddetalle_documento_sucursal;
                        $serieComprobante = $comprobante->ultima_serie;
                        $ultimoNComprobante = $comprobante->ultimo_numero+1;
                    }
                    //esto se ejecuta cuando se vaya a editar el comprobante creado, se pasa el idcomprobante por post <- una factura ya creada
                    //es diferente a la variable $idcomprobante, este trae el POST de comprobante 
                    if(isset($_POST["idingreso"]) && !empty($_POST["idingreso"]) && $_POST["idingreso"] >0){
                            $comprobanteid = $_POST["idingreso"];
                            
                            $lastcomprobante = $comprobantecontable->getComprobanteById($comprobanteid);

                            foreach($lastcomprobante as $lastcomprobante){}
                            $tipoComprobante = $lastcomprobante->cc_id_tipo_cpte;
                            $serieComprobante = $lastcomprobante->serie_comprobante;
                            $ultimoNComprobante = $lastcomprobante->num_comprobante;
                            //eliminar anterior reporte
                            $del = $this->delete_comprobante_contable($comprobanteid,false);
                            //eliminar detalles del reporte
                            $detallecomprobantecontable->deleteDetalleComprobante($comprobanteid);

                    }else{
                        $usarcomprobante = $comprobantes->usarComprobante($idcomprobante);
                    }

                    //setear estado si ya ha sido modificado como verdadero y si el comprobante existe
                    $state = ($state==true&&$tipoComprobante>0)?true:false;
                    if(!$start_date){$alerts[] = array("errors"=>"Comprobante invalido");}
                    
                }

                //verificar pago
                if($idpago){
                    $formapago = $dataformapago->getFormaPagoById($_POST["formaPago"]);
                    foreach ($formapago as $formapago) {}
                    $formadepago = $formapago->fp_nombre;
                    $state = ($state==true&& $formapago->fp_id>0)?true:false;
                    if(!$formapago->fp_id>0){$alerts[] = array("errors"=>"Forma de pago invalida");}
                }

                if($start_date && $end_date){
                    if($end_date != "0000-00-00"){
                        $state = ($state==true&&$start_date != $end_date && strtotime($end_date) > strtotime($start_date))?true:false;
                    }else{
                        $state = ($state==true && $start_date != $end_date)?true:false;
                    }
                    
                    if($start_date == $end_date){
                        $alerts[] =array("errors"=>"Hay un error en las fechas (no pueden ser iguales si el pago es a credito y debe ser mayor a la fecha inicial)");
                    } 
                }

                //verificar si las cuentas estan parejas
                
                if($getArticulos != null || $getArticulos != [] ){
                    $debito =0;
                    $credito=0;
                    foreach ($getArticulos as $filter) {
                        $debito += $filter->cdi_debito;
                        $credito += $filter->cdi_credito;
                        }
                        //primer state
                    $state =($state==true&& $debito == $credito)?true:false;
                    if($debito !=$credito){$alerts[] = array("errors"=>"Los valores de las cuentas no cuadran");}
                        //segundo state
                    
                  
                }else{
                    $state = false;
                    $alerts[] = array("errors"=>"No se encontraron articulos");
                }


                if($state){
                    $comprobantecontable->setCc_idusuario($_SESSION["usr_uid"]);
                    $comprobantecontable->setCc_idproveedor($dataproveedor->idpersona);
                    $comprobantecontable->setCc_tipo_comprobante("C");
                    $comprobantecontable->setCc_id_forma_pago($idpago);
                    $comprobantecontable->setCc_id_tipo_cpte($tipoComprobante);
                    $comprobantecontable->setCc_num_cpte($serieComprobante);
                    $comprobantecontable->setCc_cons_cpte(zero_fill($ultimoNComprobante,8));
                    $comprobantecontable->setCc_det_fact_prov($factura);
                    $comprobantecontable->setCc_fecha_cpte($start_date);
                    $comprobantecontable->setCc_fecha_final_cpte($end_date);
                    $comprobantecontable->setCc_nit_cpte($dataproveedor->num_documento);
                    $comprobantecontable->setCc_dig_verifi(0);
                    $comprobantecontable->setCc_ccos_cpte($_SESSION["idsucursal"]);
                    $comprobantecontable->setCc_fp_cpte($formadepago);
                    $comprobantecontable->setCc_estado("A");
                    $comprobantecontable->setCc_log_reg($_SESSION['usr_uid']."_".$start_date."_".date("h:i:s"));
                    $addComprobante = $comprobantecontable->saveComprobanteContable();

                    foreach ($getArticulos as $item) {
                        if($item->cdi_type == "AR"){
                            $articulo = $dataarticulos->getArticuloById($item->cdi_idarticulo);
                            foreach ($articulo as $articulo) {}

                            if($item->cdi_credito > 0){
                                $d_c = "C";
                            }elseif($item->cdi_debito > 0){
                                $d_c = "D";
                            }
                            //almacenando articulos
                            //////////verificar si es credito o debito y luego sumar o restar del stock
                            if($d_c == "D"){
                                $dataarticulos->addCantStock($item->cdi_idarticulo,$item->cdi_stock_ingreso);
                            }elseif($d_c == "C"){
                                $dataarticulos->removeCantStock($item->cdi_idarticulo,$item->cdi_stock_ingreso);
                            }
                            //obtener la categoria de este articulo
                            $categoria = $categorias->getCategoriaById($articulo->idcategoria);
                            foreach ($categoria as $categoria) {}
                            //obtener codigo contable de la categoria -> codigo de inventario
                            $cuenta_inventario = $puc->getPucById($categoria->cod_inventario);
                            foreach ($cuenta_inventario as $cuenta_inventario) {}
                            $inventario = ($cuenta_inventario !=null)? $cuenta_inventario->idcodigo:$categoria->cod_inventario;
                            
                            $detallecomprobantecontable->setDcc_id_trans($addComprobante);
                            $detallecomprobantecontable->setDcc_seq_detalle(0);
                            $detallecomprobantecontable->setDcc_cta_item_det($inventario);
                            $detallecomprobantecontable->setDcc_det_item_det($articulo->nombre_articulo);
                            $detallecomprobantecontable->setDcc_cod_art($articulo->idarticulo);
                            $detallecomprobantecontable->setDcc_cant_item_det($item->cdi_stock_ingreso);
                            $detallecomprobantecontable->setDcc_ter_item_det($dataproveedor->num_documento);
                            $detallecomprobantecontable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                            $detallecomprobantecontable->setDcc_d_c_item_det($d_c);
                            $detallecomprobantecontable->setDcc_valor_item($item->cdi_precio_unitario*$item->cdi_stock_ingreso);
                            $detallecomprobantecontable->setDcc_base_imp_item($item->cdi_importe);
                            $detallecomprobantecontable->setDcc_base_ret_item(0);
                            $detallecomprobantecontable->setDcc_fecha_vcto_item(0);
                            $detallecomprobantecontable->setDcc_dato_fact_prove("");
                            $addItem=$detallecomprobantecontable->addArticulos();

                            if($item->cdi_im_id){
                                $detalleimpuesto->setDig_registro_comprobante($addComprobante);
                                $detalleimpuesto->setDig_detalle_registro("C");
                                $detalleimpuesto->setDig_contabilidad(1);
                                $detalleimpuesto->setDig_im_id($item->cdi_im_id);
                                $detalleimpuesto->addImpuesto();
                            }
                            
                        }elseif($item->cdi_type == "CO"){
                            
                            $cuenta = $puc->getPucById($item->cdi_idarticulo);
                            foreach ($cuenta as $cuenta) {}

                            if($item->cdi_credito > 0){
                                $d_c = "C";
                            }elseif($item->cdi_debito > 0){
                                $d_c = "D";
                            }

                            $detallecomprobantecontable->setDcc_id_trans($addComprobante);
                            $detallecomprobantecontable->setDcc_seq_detalle(0);
                            $detallecomprobantecontable->setDcc_cta_item_det($cuenta->idcodigo);
                            $detallecomprobantecontable->setDcc_det_item_det($item->cdi_detalle);
                            $detallecomprobantecontable->setDcc_cod_art(0);
                            $detallecomprobantecontable->setDcc_cant_item_det($item->cdi_stock_ingreso);
                            $detallecomprobantecontable->setDcc_ter_item_det($dataproveedor->num_documento);
                            $detallecomprobantecontable->setDcc_ccos_item_det($_SESSION["idsucursal"]);
                            $detallecomprobantecontable->setDcc_d_c_item_det($d_c);
                            $detallecomprobantecontable->setDcc_valor_item($item->cdi_precio_unitario*$item->cdi_stock_ingreso);
                            $detallecomprobantecontable->setDcc_base_imp_item($item->cdi_importe);
                            $detallecomprobantecontable->setDcc_base_ret_item($item->cdi_base_ret);
                            $detallecomprobantecontable->setDcc_fecha_vcto_item(0);
                            $detallecomprobantecontable->setDcc_dato_fact_prove("");
                            $addItem=$detallecomprobantecontable->addArticulos();
                        }
                    }

                }

                if($state){
                    $carro->deleteCart();
                    echo json_encode(array("success"=>"file/comprobantes/$addComprobante"));
                }else{
                    echo json_encode(array("errors"=>$alerts));
                }
                
            }else{
                $alerts[]= array("errors"=>"Tercero y comprobante son obligatorios");
                echo json_encode(array("errors"=>$alerts));
            }
            
        }else{}
        

    }

    public function edit()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idsucursal = (!empty($_SESSION["idsucursal"]))? $_SESSION["idsucursal"]:false;
            $idcomprobante = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
            if(!empty($_SESSION["usr_uid"])){
                if($idsucursal){
                    if($idcomprobante){
                    //models
                    $sucursal = new Sucursal($this->adapter);
                    $comprobante = new Comprobante($this->adapter);
                    $formapago= new FormaPago($this->adapter);
                    $comprobantescontables = new ComprobanteContable($this->adapter);
                    $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
                    $cart = new ColaIngreso($this->adapter);
                    $impuesto = new Impuestos($this->adapter);
                    $retencion = new Retenciones($this->adapter);
                    //functions
                    $getsucursal= $sucursal->getSucursalById($idsucursal);
                    $impuestos = $impuesto->getImpuestosAll();
                    $retenciones = $retencion->getRetencionesAll();
                    //obtener datos de usuario
                    $idusuario = $_SESSION["usr_uid"];
                    $pos_proceso ="Contabilidad";
                    $contabilidad = 0;
                    $autocomplete="codigo_contable";
                    //comprobante
                    $comprobantes = $comprobante->getComprobante($pos_proceso);
                    //formas de pago
                    $formaspago = $formapago->getFormaPago($pos_proceso);
                    //recuperar compra
                    $comprobantecontable = $comprobantescontables->getComprobanteById($idcomprobante);
                    foreach ($comprobantecontable as $datacomprobantecontable) {}
                    //recuperar items
                    $detalle = $detallecomprobantecontable->getArticulosByComprobante($idcomprobante);
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
                        //verificar si es un articulo o una cuenta


                        $debito = ($dataitems->dcc_d_c_item_det == "D")?$dataitems->dcc_valor_item:0;
                        $credito = ($dataitems->dcc_d_c_item_det == "C")?$dataitems->dcc_valor_item:0;
                        
                        
                        if($dataitems->dcc_cod_art >0){
                            $itemid = $dataitems->dcc_cod_art;
                            $type = "AR";
                        }elseif($dataitems->dcc_cta_item_det > 0){
                            $itemid = $dataitems->dcc_cta_item_det;
                            $type = "CO";
                        }
                        $cart->setCdi_ci_id($addCart);
                        $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                        $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                        $cart->setCdi_tercero($datacomprobantecontable->nombre_tercero);
                        $cart->setCdi_idarticulo($itemid);
                        $cart->setCdi_detalle($dataitems->dcc_det_item_det);
                        $cart->setCdi_stock_ingreso($dataitems->dcc_cant_item_det);
                        $cart->setCdi_precio_unitario($dataitems->dcc_valor_item / $dataitems->dcc_cant_item_det);
                        $cart->setCdi_importe($dataitems->dcc_base_imp_item);
                        $cart->setCdi_precio_total_lote($dataitems->dcc_valor_item);
                        $cart->setCdi_credito($credito);
                        $cart->setCdi_debito($debito);
                        $cart->setCdi_cod_costos("0");
                        $cart->setCdi_type($type);
                        $result = $cart->addItemToCart();
                        } 

                    //recuperar el carrito previamente creado
                    $items = $cart->loadCart();

                    $this->frameview("comprobantes/Edit/index",array(
                        "compra"=>$comprobantecontable,
                        "items"=>$items,
                        "impuestos"=>$impuestos,
                        "retenciones"=>$retenciones,
                        "autocomplete"=>$autocomplete,
                        "contabilidad"=>$contabilidad,
                        "sucursal"=>$getsucursal,
                        "idusuario"=>$idusuario,
                        "pos"=>$pos_proceso,
                        "comprobantes" => $comprobantes,
                        "formaspago" => $formaspago,
                    ));
                    

                    }else{/*comprobante validacion*/}
                }else{/*sucursal validacion*/}
            }else{/*usuario validacion*/}
        }else{/*permisos validacion*/}
    }

    public function delete()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcomprobante = $_GET["data"];
                if($this->delete_comprobante_contable($idcomprobante,false)){
                    $success = "Registro eliminado";
                    $this->frameview("alert/success/successSmall",array("success"=>$success));
                }else{
                    $error= "Este registro no se puede eliminar";
                    $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
                }

            }else{
                $error= "Este registro no se puede eliminar";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error= "No tienes permisos para esta acción";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }


    public function informes()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $data = $_GET["data"];
                switch ($data) {
                    case 'general':
                            $comprobante = new Comprobante($this->adapter);
                            $comprobantes = $comprobante->getAllComprobanteContable();
                            $pos="reporte_general";
                            $control ="comprobantes";
                            $this->frameview("admin/comprobantes/general/index",array(
                                "comprobantes"=>$comprobantes,
                                "control"=>$control,
                                "pos"=>$pos
                            ));

                        break;
                    case 'detallado':
                        $comprobante = new Comprobante($this->adapter);
                        $comprobantes = $comprobante->getAllComprobanteContable();
                        $pos="reporte_detallado";
                        $control ="comprobantes";
                        $this->frameview("admin/comprobantes/detallado/index",array(
                            "comprobantes"=>$comprobantes,
                            "control"=>$control,
                            "pos"=>$pos
                        ));

                        break;
                    case 'terceros':
                            $persona = new Persona($this->adapter);
                            $personas = $persona->getPersonaAll();
                            $pos="reporte_general_tercero";
                            $control ="comprobantes";
                            $this->frameview("admin/comprobantes/general/terceros",array(
                                "personas"=>$personas,
                                "control"=>$control,
                                "pos"=>$pos
                            ));
    
                            break;
                    
                    default:
                        # code...
                        break;
                }
                
            }else{}
        }else{
            $error = "No tienes permisos";
            $this->frameview("alert/error/forbidden",array(
                "error"=>$error
            ));
        }
    }



    public function reporte_general()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST) && !empty($_POST)){
                //set variables
                $pos = (isset($_POST["pos"]) && !empty($_POST["pos"]))?$_POST["pos"]:false;
                $control = (isset($_POST["control"]) && !empty($_POST["control"]))?$_POST["control"]:false;
                $idcomprobante = (isset($_POST["idcomprobante"]) && !empty($_POST["idcomprobante"]))?$_POST["idcomprobante"]:0;
                $start_date = (isset($_POST["start_date"]) && !empty($_POST["start_date"]))?$_POST["start_date"]:false;
                $end_date = (isset($_POST["end_date"]) && !empty($_POST["end_date"]))?$_POST["end_date"]:false;
                //models
                $comprobantes = new Comprobante($this->adapter);
                
                //calling functions
                
                $datacomprobante = $comprobantes->getOnlyComprobanteById($idcomprobante);
                foreach ($datacomprobante as $comprobante) {}
                
                if($comprobante->iddetalle_documento_sucursal){
                $idcomprobante= $comprobante->iddetalle_documento_sucursal;
                $tipo_proceso = $comprobante->proceso;
                }else{
                    $idcomprobante= 0;
                    $tipo_proceso = 'Contabilidad';
                }

                $serie_comprobante = $comprobante->ultima_serie;
                $start_date = date_format_calendar($_POST["start_date"],"/");
                $end_date = date_format_calendar($_POST["end_date"],"/");
                
                switch ($tipo_proceso) {
                    case 'Venta':

                        if($start_date && $end_date){
                            $venta = new ComprobanteContable($this->adapter);
                            $dataventas = $venta->reporte_general_comprobante($start_date,$end_date,$idcomprobante);
                        
                            $this->frameview("admin/comprobantes/general/tableContable",array(
                                "detalle"=>$dataventas
                            ));
                        }else{
                            echo "Agrega fechas";
                        }

                        break;
                    case 'Ingreso':
                        if($start_date && $end_date){
                            $compras = new ComprobanteContable($this->adapter); 
                            $datacompras = $compras->reporte_general_comprobante($start_date,$end_date,$idcomprobante);
                            $this->frameview("admin/comprobantes/general/tableContable",array(
                                "detalle"=>$datacompras
                            ));
                        }else{
                            echo "Agrega fechas";
                        }
                        break;

                    case 'Contabilidad':
                        if($start_date && $end_date){
                            $comprobante = new ComprobanteContable($this->adapter);
                            $datacomprobante = $comprobante->reporte_general_comprobante($start_date,$end_date,$idcomprobante);
                            $this->frameview("admin/comprobantes/general/tableContable",array(
                                "detalle"=>$datacomprobante
                            ));
                        }else{
                            echo "Agrega fechas";
                        }
                        break;
                    default:
                    
                        break;
                }
            }else{}
        }else{

        }
    }

    public function reporte_detallado()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST) && !empty($_POST)){

                $pos = (isset($_POST["pos"]) && !empty($_POST["pos"]))?$_POST["pos"]:false;
                $control = (isset($_POST["control"]) && !empty($_POST["control"]))?$_POST["control"]:false;
                $idcomprobante = (isset($_POST["idcomprobante"]) && !empty($_POST["idcomprobante"]))?$_POST["idcomprobante"]:false;
                $start_date = (isset($_POST["start_date"]) && !empty($_POST["start_date"]))?$_POST["start_date"]:false;
                $end_date = (isset($_POST["end_date"]) && !empty($_POST["end_date"]))?$_POST["end_date"]:false;
                //models
                $comprobantes = new Comprobante($this->adapter);
                
                //calling functions
                $datacomprobante = $comprobantes->getOnlyComprobanteById($idcomprobante);
                foreach ($datacomprobante as $comprobante) {}

                $tipo_proceso = $comprobante->proceso;
                $serie_comprobante = $comprobante->ultima_serie;
                $comprobante = $comprobante->iddetalle_documento_sucursal;
                $start_date = date_format_calendar($_POST["start_date"],"/");
                $end_date = date_format_calendar($_POST["end_date"],"/");

                switch ($tipo_proceso) {
                    case 'Venta':
            
                        if($start_date && $end_date){
                            $venta = new ComprobanteContable($this->adapter);
                            $dataventas = $venta->reporte_detallado_comprobante($start_date,$end_date,$idcomprobante);
                            $this->frameview("admin/comprobantes/detallado/tableContable",array(
                                "detalle"=>$dataventas
                            ));
                        }else{
                            echo "Agrega fechas";
                        }

                        break;
                    case 'Ingreso':

                        if($start_date && $end_date){
                            $compra = new ComprobanteContable($this->adapter);
                            $datacompras = $compra->reporte_detallado_comprobante($start_date,$end_date,$idcomprobante);
                            $this->frameview("admin/comprobantes/detallado/tableContable",array(
                                "detalle"=>$datacompras
                            ));
                        }else{
                            echo "Agrega fechas";
                        }
                        break;
                    case 'Contabilidad':
                        if($start_date && $end_date){
                            $comprobante = new ComprobanteContable($this->adapter);
                            $datacomprobante = $comprobante->reporte_detallado_comprobante($start_date,$end_date,$idcomprobante);
                            $this->frameview("admin/comprobantes/detallado/tableContable",array(
                                "detalle"=>$datacomprobante
                            ));
                        }else{
                            echo "Agrega fechas";
                        }
                        break;
                    
                    default:
                        
                        break;
                    }
            }else{}
            }else{}
    }

    public function reporte_general_tercero(){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST) && !empty($_POST)){
                $idpersona = (isset($_POST["idpersona"]) && !empty($_POST["idpersona"]))?$_POST["idpersona"]:false;
                $start_date = (isset($_POST["start_date"]) && !empty($_POST["start_date"]))?$_POST["start_date"]:false;
                $end_date = (isset($_POST["end_date"]) && !empty($_POST["end_date"]))?$_POST["end_date"]:false;

                $persona = new Persona($this->adapter);
                $personas = $persona->getPersonaById($idpersona);

                foreach ($personas as $persona) {}
                $idpersona= $persona->idpersona;
                $tipo_persona = $persona->tipo_persona;
                $start_date = date_format_calendar($_POST["start_date"],"/");
                $end_date = date_format_calendar($_POST["end_date"],"/");

                switch ($tipo_persona) {
                    case 'Cliente':
                        if($start_date && $end_date){
                            $venta = new ComprobanteContable($this->adapter);
                            $dataventas = $venta->reporte_general_tercero($start_date,$end_date,$idpersona,'V');
                            $this->frameview("admin/comprobantes/general/tableContable",array(
                                "detalle"=>$dataventas
                            ));
                        }else{
                            echo "Agrega fechas";
                        }
                        break;

                    case 'Proveedor':
                            if($start_date && $end_date){
                                $compras = new ComprobanteContable($this->adapter);
                                $datacompras = $compras->reporte_general_tercero($start_date,$end_date,$idpersona,'I');
                                $this->frameview("admin/comprobantes/general/tableContable",array(
                                    "detalle"=>$datacompras
                                ));
                            }else{
                                echo "Agrega fechas";
                            }
                            break;
                    default:
                        break;

                }

            }else{}
        }else{}
    }

    public function delete_comprobante_contable($comprobanteid,$alert){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4 && !empty($comprobanteid) && $comprobanteid > 0){
            //models
                $comprobantecontable = new ComprobanteContable($this->adapter);
                $detallecomprobantecontable = new detalleComprobanteContable($this->adapter);
                $dataarticulos = new Articulo($this->adapter);
                $cartera = new Cartera($this->adapter);
                $tokenization = new Tokenization($this->adapter);
                $user = new User($this->adapter);

                $ingreso = $comprobantecontable->getComprobanteById($comprobanteid);
                foreach($ingreso as $ingreso){}
                if($ingreso->cc_id_transa){
            
                    $datatokenizaation = $tokenization->getTokenization();
                    foreach ($datatokenizaation as $token) {}
                        if(isset($token)){
                        $sequence= $token->tz_datacenter.$token->tz_uid.$token->tz_token.$token->tz_reg_code;
                        if(isset($_COOKIE["refacturacion"]) && !empty($_COOKIE["refacturacion"])){
                            if($sequence == $_COOKIE["refacturacion"]){

                                $detalleingreso= $detallecomprobantecontable->getArticulosByComprobante($ingreso->cc_id_transa);
                                foreach($detalleingreso as $detalleingreso){
                                    if($detalleingreso->dcc_cod_art){
                                        $dataarticulos->addCantStock($detalleingreso->dcc_cod_art,$detalleingreso->dcc_cant_item_det);
                                    }
                                }
                            if($comprobantecontable->delete_comprobante($ingreso->cc_id_transa)){
                                return true;
                            }else{
                                return false;
                            }
                            
                            }else{
                                if($alert){
                                echo json_encode(array("error"=>"Token invalida, vuelve a iniciar sesion"));
                                }
                            }
                        }else{
                            if($alert){
                            echo json_encode(array("error"=>"no tienes todos los permisos necesarios"));
                            }
                        }
                    }else{
                        if($alert){
                            echo json_encode(array("error"=>"no tienes todos los permisos necesarios"));
                            }
                    }
                }
        }else{
            if($alert){
            echo json_encode(array("error"=>"Forbiden gateway"));
            }
        }
    }


    ###############################################][Informes contables][##########################################################

    public function menu()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $comprobantecontable = new ComprobanteContable($this->adapter);
            $listmenu = $comprobantecontable->listmenu();
           
            $this->frameview("comprobantes/Menu/index",array(
                "listmenu"=>$listmenu
            ));

        }else{
            echo "Forbidden gateway";
        }
    }
}
