<?php
class ClienteController extends ControladorBase{
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

    public function cartera()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $cartera = new Cartera($this->adapter);
            $carteras = $cartera->getCreditoClienteAll();
            $pago = 0;
            $por_pagar = 0; 
            $vencidas = 0;
            $total=0;
            $color = 'danger';
        foreach ($carteras as $calculo) {
           $pago += $calculo->total_pago;
           $por_pagar += ($calculo->deuda_total - $calculo->total_pago);
           $total += $calculo->deuda_total;
            if($calculo->fecha_pago < date("Y-m-d")){
                $vencidas +=($calculo->deuda_total - $calculo->total_pago);
            }
        }
        $porcentaje_vencido = bcdiv(($vencidas / $total)*100,'1',1);
        $porcentaje_pago = bcdiv(($pago / $total)*100,'1',1);

        if($porcentaje_pago < 30.0){
            $color = "danger";
        }elseif($porcentaje_pago > 30.1 && $porcentaje_pago < 80.0){
            $color = "primary";
        }elseif($porcentaje_pago > 80.1){ 
            $color = "success";
        }

        $this->frameview("cliente/deudas/deudas",array(
            "cartera"=>$carteras,
            "deuda_total"=>$total,
            "deuda_pagada"=>$pago,
            "vencidas"=> $vencidas,
            "prcentaje_pago"=>$porcentaje_pago,
            "porcentaje_vencido"=>$porcentaje_vencido,
            "color"=>$color

        ));
        }

    }

    public function pagar_deuda()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($_GET["data"]) && !empty($_GET["data"]) && $_GET["data"] >0){
                $idcredito = $_GET["data"];

                $cartera = new Cartera($this->adapter);
                $metodoPago = new MetodoPago($this->adapter);
                $credito= $cartera->getCreditoClienteById($idcredito);

                $pagos = $cartera->getPagoCarteraCliente($idcredito);
                $metodosPago = $metodoPago->getAllMetodoPago();
                $this->frameview("cliente/deudas/pagarDeuda",array(
                    "credito"=>$credito,
                    "pagos"=>$pagos,
                    "metodosPago"=>$metodosPago
                ));


            }else{
                echo "Forbidden Gateway";
            }
        }else{
            echo "Forbidden Gateway";
        }
    }

    public function generar_pago_cliente()
    { 
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"]> 1){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                //forma_pago en una variable
                $forma_pago = $_GET["data"];
                //idcredito en una variable
                $idcredito = $_POST["id_credito"];
                $pos ="cliente";
                
                //models
                $cartera = new Cartera($this->adapter);
                $retencion = new Retenciones($this->adapter);
                $comprobante = new Comprobante($this->adapter);
                $metodosPago = new MetodoPago($this->adapter);
                //functions
                $attr= "c_cobrar";
                $param="1";
                //obtener informacion de este credito
                $credito = $cartera->getCreditoClienteById($idcredito);
                //traer lista de retenciones
                $retenciones = $retencion->getAll();
                //lista traer comprobantes
                $comprobantes = $comprobante->getComprobanteAll();

                $metodopago = $metodosPago->getMetodoPagoById($forma_pago);

                //funcion de opciones de prcio rapidas
                    foreach ($credito as $data) {}
                    $total = $data->deuda_total-$data->total_pago;
                    $idcartera = $data->idcredito;
                    //cantidad de caracteres
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


                
                    $this->frameview("cartera/formaPago",array(
                        "credito"=>$credito,
                        "listPrice"=>$listPrice,
                        "comprobantes"=>$comprobantes,
                        "retenciones"=>$retenciones,
                        "idcredito"=>$idcartera,
                        "pos"=>$pos,
                        "attr"=>$attr,
                        "param"=>$param,
                        "metodopago"=>$metodopago
                    ));

            }
        }else{
            echo "No tienes permisos";
        }
    }

    public function calcularCartera()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"]> 1){
            if(isset($_POST["pago"])) {
                $msg="Por pagar";
                $attr = true;
                $color="text-success";
                $credito = $_POST["credito"];
                $idretencion = (isset($_POST["retencion"]) && !empty($_POST["retencion"]))?$_POST["retencion"]:0;
                $status = false;
                $pago = (isset($_POST["pago"]) && $_POST["pago"]>0 || $_POST["pago"] !="")?$_POST["pago"]:0;
                $cod_cont_afect =0;
                //llamar clases para calcular el total
                $retenciones = new Retenciones($this->adapter);
                $cartera = new Cartera($this->adapter);
                $comprobantecontable = new ComprobanteContable($this->adapter);
                $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
                $cartera = new Cartera($this->adapter);
                $puc = new PUC($this->adapter);
                $impuestos = new Impuestos($this->adapter);
                $retenciones = new Retenciones($this->adapter);
                //llamar funciones para calcular total
                $credito = $cartera->getCreditoClienteById($credito);
                $retencion = $retenciones->getRetencionesById($idretencion);
                //obteniendo resultados en variables
                
                foreach ($retencion as $retencion) {}
                foreach ($credito as $credito) {}
                //
                $comprobante = $comprobantecontable->getComprobanteById($credito->idventa);
                foreach($comprobante as $comprobante){}
                $deuda = $credito->deuda_total - $credito->total_pago;
                $status=false;
                $status2 =true;
                if($credito->contabilidad ==1){
                if($idretencion > 0){
                    $detalle = $detallecomprobantecontable->getArticulosByComprobante($comprobante->cc_id_transa);
                    if($retencion->re_im_id){
                        $deuda_imp=0;
                        $cod_imp = 0;
                        //obtener iva
                        foreach ($detalle as $search_imp){
                            $getpuc = $puc->getPucById($search_imp->dcc_cta_item_det);
                            foreach ($getpuc as $imp){}
                            if($imp->impuesto == 1){
                                $cod_imp = $imp->idcodigo;
                                $deuda = $search_imp->dcc_valor_item;
                                
                            }
                        }
                        //obtener detalle credito
                        $detalle_credito = $cartera->getPagoCarteraClienteBy($credito->idcredito,'cuenta_contable',$cod_imp);
                        if($detalle_credito){
                            foreach($detalle_credito as $detalle_credito){
                                $deuda -= $detalle_credito->pago_parcial;
                            }
                            $status2 =true;
                            $msg2 = "no se pueve volver a hacer retencion a este impuesto";
                        }else{
                                    //obtener total del precio de este impuesto
                                    $total_impuesto = $deuda;
                                    $precio_retencion = $total_impuesto*($retencion->re_porcentaje/100);
                                    $total = $total_impuesto - $precio_retencion;
                                    $deuda = $total_impuesto;
                        }
                        $cod_cont_afect =$cod_imp;
                        
                    }else{
                        $cod_sub=0;
                        $detalle = $detallecomprobantecontable->getArticulosByComprobante($comprobante->cc_id_transa);
                            foreach ($detalle as $search_subtotal){
                                $getpuc = $puc->getPucById($search_subtotal->dcc_cta_item_det);
                                foreach ($getpuc as $sub){}
                                if($sub->centro_costos == 1){
                                    $deuda = $search_subtotal->dcc_valor_item;
                                    $cod_sub=$search_subtotal->dcc_cta_item_det;
                                }
                            }
                            foreach ($detalle as $search_imp){
                                $getpuc = $puc->getPucById($search_imp->dcc_cta_item_det);
                                foreach ($getpuc as $imp){}
                                if($imp->impuesto == 1){
                                    $deuda -= $search_imp->dcc_valor_item;

                                }
                            }

                            $precio_retencion = $deuda*($retencion->re_porcentaje/100);
                            $total = $deuda - $precio_retencion;
                            $detalle_credito = $cartera->getPagoCarteraCliente($credito->idcredito);
                            if($detalle_credito){
                                foreach ($detalle_credito as $detalle) {
                                    $getpuc = $puc->getPucById($detalle->cuenta_contable);
                                    foreach ($getpuc as $getpuc) {}
                                    if($getpuc->impuesto==1){
                                        //$deuda -= $detalle->pago_parcial;
                                    }else{
                                        $deuda -= $detalle->pago_parcial;
                                    }
                                }
                            }

                    $cod_cont_afect = $cod_sub;
                    }
                    $deuda2 = ($retencion->re_porcentaje <100)?$deuda / ($retencion->re_porcentaje/100+1):0;
                    $total = intval($deuda2 - $pago);
                    $reteinfo = $retencion->re_porcentaje;
                    

                }else{
                    $detalle2 = $detallecomprobantecontable->getArticulosByComprobante($comprobante->cc_id_transa);
                    $cod_fact = 0;
                    foreach ($detalle2 as $detalle) {
                        $getpuc = $puc->getPucById($detalle->dcc_cta_item_det);
                        foreach ($getpuc as $puc_det) {}
                        if($puc_det->centro_costos==1){
                            $cod_fact = $puc_det->idcodigo;
                        }
                    }
                    $reteinfo = false;
                    $total =$deuda - $pago;
                    $cod_cont_afect=$cod_fact;
                }
            }else{
                $total =$deuda - $pago;
                $cod_cont_afect=0;
            }

                if($deuda <= $pago){
                    $msg="Cambio";
                    $color="text-danger";
                }

                if($pago > 0){
                    $attr =false;
                }

                if($pago <= 0 || $pago > $deuda && $credito->contabilidad == 1){
                    $status = false;
                }else{
                    $status = true;
                }
                if($status2 == false){
                    $status = false;
                }
                

                echo json_encode(array("total"=>abs($total),"msg"=>$msg,"color"=>$color,"attr"=>$attr,"status"=>$status,"deuda"=>$deuda,"cod_cont_afect"=>$cod_cont_afect));
            }
        }
    }

    public function pago_autorizado()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"]> 1 && $_POST["pago"] > 0){
            //almacenando variables
            $pago = $_POST["pago"];
            $idcredito = $_POST["idcredito"];
            $idretencion = (isset($_POST["retenciones"]) && !empty($_POST["retenciones"]))?$_POST["retenciones"]:0;
            $tipo_pago =(isset($_POST["tipo_pago"]) && !empty($_POST["tipo_pago"]))?$_POST["tipo_pago"]:1;
            $cod_cont_afect = (isset($_POST["cod_cont_afect"]) && !empty($_POST["cod_cont_afect"]))?$_POST["cod_cont_afect"]:false;
            $comprobanteid = (isset($_POST["comprobante"]) && !empty($_POST["comprobante"]))?$_POST["comprobante"]:false;
            $cuenta_pago = (isset($_POST["cuenta_pago"]) && !empty($_POST["cuenta_pago"]))?$_POST["cuenta_pago"]:"";
			$start_date = (isset($_POST["start_date"]) && !empty($_POST["start_date"]))?date_format_calendar($_POST["start_date"],"/"):date('Y-m-d');
            //llamar clases
            $retenciones = new Retenciones($this->adapter);
            $cartera = new Cartera($this->adapter);
            $comprobantecontable= new ComprobanteContable($this->adapter);
            $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
            $comprobantes = new Comprobante($this->adapter);
            $puc = new PUC($this->adapter);
            $detallemetodopago = new DetalleMetodoPago($this->adapter);
            $ventas = new Ventas($this->adapter);

            //llamar funciones para calcular total
            $credito = $cartera->getCreditoClienteById($idcredito);
            $retencion = $retenciones->getRetencionesById($idretencion);
            //obteniendo resultados en variables
            foreach ($retencion as $retencion) {}
            foreach ($credito as $credito) {}
            $cuenta_proveedor = "";
            $cuenta_nombre = "";
            $cuenta_usada=0;
            $nombre_cuenta_usada=0;
            $nombre_cuenta_pago_credito ="";
            $setcomprobante=0;
            $deuda_actual = $credito->deuda_total - $credito->total_pago;

             if($credito->contabilidad == 1){

                //recuperar comprobante contable donde se almacenara
                $comprobante = $comprobantes->getComprobanteById($comprobanteid);
                foreach($comprobante as $datacomprobante){}
                $ingreso = $comprobantecontable->getComprobanteById($credito->idventa);
                if($ingreso){
                    foreach ($ingreso as $data) {}
                    //ahora obtener detalle de ese ingreso contable
                    $detalleingreso = $detallecomprobantecontable->getArticulosByComprobante($credito->idventa);
                    if($detalleingreso){
                        foreach ($detalleingreso as $datadetalle){
                            $getpuc = $puc->getPucById($datadetalle->dcc_cta_item_det);
                            if($getpuc){
                                foreach ($getpuc as $datapuc) {
                                    if($datapuc->centro_costos){
                                        $cuenta_proveedor = $datapuc->idcodigo;
                                        $cuenta_nombre = $datapuc->tipo_codigo;
                                    }else{}

                                    ////obtener cuenta de pago en enviado
                                    if($cod_cont_afect){
                                        $getpuc2 = $puc->getPucById($cod_cont_afect);
                                        foreach ($getpuc2 as $getpuc2) {}
                                        $cuenta_usada = $getpuc2->idcodigo;
                                        $nombre_cuenta_usada =$getpuc2->tipo_codigo;
                                    }

                                }

                            }
                        }
                    }

                    $tercero = $data->documento_proveedor;
                    //ingreso de cuenta con la que se paga al proveedor
                    $pucpago = $puc->getPucById($cuenta_pago);
                    if($pucpago){
                        foreach ($pucpago as $pucpago) {}
                        if($pucpago->idcodigo && $pucpago->movimiento){
                            $cuenta_pago_credito = $pucpago->idcodigo;
                            $nombre_cuenta_pago_credito = $pucpago->tipo_codigo;
                        }
                    }

                }
            }

            if($idretencion >0){
                if($retencion){
                   //obtener precio de la deuda
                    //aplicar retencion
                    $deuda_retenida =($retencion->re_porcentaje <100)?$pago*($retencion->re_porcentaje/100):0;

                    $precio_retenido = $pago - $deuda_retenida;

                    $pago_parcial = $pago;

                    $deuda = $deuda_actual - $pago_parcial;
                }else{
                    
                }
            }else{
                if($pago <= $deuda_actual){
                    $pago_parcial = $pago;
                    $deuda = $deuda_actual - $pago_parcial;
                    $precio_retenido =$pago_parcial;
                    $deuda_retenida=0;
                }else{
                    $pago_parcial = $deuda_actual;
                    $deuda = 0;
                    $precio_retenido =$pago_parcial;
                    $deuda_retenida=0;
                }
            }
            

            if($cuenta_usada){
                $comprobantecontable->setCc_idusuario($_SESSION["usr_uid"]);
                $comprobantecontable->setCc_idproveedor($data->cc_idproveedor);
                $comprobantecontable->setCc_tipo_comprobante("C");
                $comprobantecontable->setCc_id_forma_pago($data->cc_id_forma_pago);
                $comprobantecontable->setCc_id_tipo_cpte($datacomprobante->iddetalle_documento_sucursal);
                $comprobantecontable->setCc_num_cpte($datacomprobante->ultima_serie);
                $comprobantecontable->setCc_cons_cpte(zero_fill($datacomprobante->ultimo_numero+1,8));
                $comprobantecontable->setCc_det_fact_prov($data->cc_num_cpte."".$data->cc_cons_cpte);
                $comprobantecontable->setCc_fecha_cpte($start_date);
                $comprobantecontable->setCc_fecha_final_cpte("0000-00-00");
                $comprobantecontable->setCc_nit_cpte($data->cc_nit_cpte);
                $comprobantecontable->setCc_dig_verifi(0);
                $comprobantecontable->setCc_ccos_cpte($_SESSION["idsucursal"]);
                $comprobantecontable->setCc_fp_cpte(0);
                $comprobantecontable->setCc_estado("A");
                $comprobantecontable->setCc_log_reg($_SESSION['usr_uid']."_".date("Y-m-d")."_".date("h:i:s"));
                $addComprobante = $comprobantecontable->saveComprobanteContable();

                //usar comprobante
                $usarcomprobante = $comprobantes->usarComprobante($comprobanteid);

                //crear comprobante contable
                $detallecomprobantecontable->setDcc_id_trans($addComprobante);
                $detallecomprobantecontable->setDcc_seq_detalle(0);
                $detallecomprobantecontable->setDcc_cta_item_det($cuenta_usada);
                $detallecomprobantecontable->setDcc_det_item_det($nombre_cuenta_usada);
                $detallecomprobantecontable->setDcc_cod_art(0);
                $detallecomprobantecontable->setDcc_cant_item_det(1);
                $detallecomprobantecontable->setDcc_ter_item_det($tercero);
                $detallecomprobantecontable->setDcc_ccos_item_det($data->cc_ccos_cpte);
                $detallecomprobantecontable->setDcc_d_c_item_det("C");
                $detallecomprobantecontable->setDcc_valor_item($pago_parcial);
                $detallecomprobantecontable->setDcc_base_imp_item(0);
                $detallecomprobantecontable->setDcc_base_ret_item($precio_retenido);
                $detallecomprobantecontable->setDcc_fecha_vcto_item($data->cc_fecha_final_cpte);
                $detallecomprobantecontable->setDcc_dato_fact_prove($data->cc_num_cpte."".$data->cc_cons_cpte);
                $addItem=$detallecomprobantecontable->addArticulos();

                //imprimir retencion si existe
                if($retencion){
                    $pucretencion = $puc->getPucById($retencion->re_cta_contable);
                    foreach($pucretencion as $pucretencion);
                    if($pucretencion){

                        $detallecomprobantecontable->setDcc_id_trans($addComprobante);
                        $detallecomprobantecontable->setDcc_seq_detalle(0);
                        $detallecomprobantecontable->setDcc_cta_item_det($pucretencion->idcodigo);
                        $detallecomprobantecontable->setDcc_det_item_det($pucretencion->tipo_codigo);
                        $detallecomprobantecontable->setDcc_cod_art(0);
                        $detallecomprobantecontable->setDcc_cant_item_det(1);
                        $detallecomprobantecontable->setDcc_ter_item_det($tercero);
                        $detallecomprobantecontable->setDcc_ccos_item_det($data->cc_ccos_cpte);
                        $detallecomprobantecontable->setDcc_d_c_item_det("D");
                        $detallecomprobantecontable->setDcc_valor_item($pago_parcial - $precio_retenido);
                        $detallecomprobantecontable->setDcc_base_imp_item(0);
                        $detallecomprobantecontable->setDcc_base_ret_item($pago_parcial);
                        $detallecomprobantecontable->setDcc_fecha_vcto_item($data->cc_fecha_final_cpte);
                        $detallecomprobantecontable->setDcc_dato_fact_prove($data->cc_num_cpte."".$data->cc_cons_cpte);
                        $addItem=$detallecomprobantecontable->addArticulos();
                    }
                }
                
                //imprimir segunda cuenta
                if($cuenta_pago_credito){
                $detallecomprobantecontable->setDcc_id_trans($addComprobante);
                $detallecomprobantecontable->setDcc_seq_detalle(0);
                $detallecomprobantecontable->setDcc_cta_item_det($cuenta_pago_credito);
                $detallecomprobantecontable->setDcc_det_item_det($nombre_cuenta_pago_credito);
                $detallecomprobantecontable->setDcc_cod_art(0);
                $detallecomprobantecontable->setDcc_cant_item_det(1);
                $detallecomprobantecontable->setDcc_ter_item_det($tercero);
                $detallecomprobantecontable->setDcc_ccos_item_det($data->cc_ccos_cpte);
                $detallecomprobantecontable->setDcc_d_c_item_det("D");
                $detallecomprobantecontable->setDcc_valor_item($precio_retenido);
                $detallecomprobantecontable->setDcc_base_imp_item(0);
                $detallecomprobantecontable->setDcc_base_ret_item(0);
                $detallecomprobantecontable->setDcc_fecha_vcto_item("");
                $detallecomprobantecontable->setDcc_dato_fact_prove("");
                $addItem=$detallecomprobantecontable->addArticulos();

                $creditoid = $addComprobante;
                $setcomprobante = $addComprobante;
                }else{
                $creditoid= $idcredito;
                $setcomprobante=0;
                }  
            }

                $cartera->setIdcredito($idcredito);
                $cartera->setIdcomprobante($setcomprobante);
                $cartera->setCuenta_contable($cuenta_usada);
                $cartera->setCuenta_contable_pago($cuenta_pago);
                $cartera->setPago_parcial($pago_parcial);
                $cartera->setDeuda_parcial($deuda);
                $cartera->setRetencion($precio_retenido);
                $cartera->setMonto($pago);
                $cartera->setTipo_pago($tipo_pago);
                $cartera->setEstado(1);
                
                $pago = $cartera->generar_pago_cliente();
                if($pago_parcial >= $deuda){
                    //cambiar estado de la venta de pendiente a aceptado
                    $ventas->setIdventa($credito->idventa);
                    $ventas->setEstado("A");
                    $cambiarestado = $ventas->cambiarEstado();
                }
                
                if($pago){
                    //si se hizo el pago agregar esto a la tabla tb_detalle_metodo_pago_general para mantener el registro de dinero en esa factura

                    
                    $detallemetodopago->setDmpg_registro_comprobante($credito->idventa);
                    $detallemetodopago->setDmpg_detalle_registro("V");
                    $detallemetodopago->setDmpg_contabilidad(0);
                    $detallemetodopago->setDmpg_mp_id($tipo_pago);
                    $detallemetodopago->setDmpg_monto($pago_parcial);
                    $detallemetodopago->addDetalleMetodoPago();
                }

            if($cuenta_usada){
                if($addComprobante){
                    echo json_encode(array("success"=>"file/comprobantes/$addComprobante"));
                }else{
                    echo json_encode(array("error"=>"Existe un error en la creacion del comprobante"));
                }
                
            }else{
                echo json_encode(array("success"=>"file/cartera/cliente/$idcredito"));
            }

        }else{
            echo json_encode(array("error"=>"No se puede realizar este pago"));
        }
    }

}
