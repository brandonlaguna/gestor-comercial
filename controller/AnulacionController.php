<?php
class AnulacionController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        echo "Forbidden Gateway";
    }

    public function prepare_ventas()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

                if(isset($_POST['data'])){
                
                $idventa = $_POST['data'];
                $type = "Mensaje del sistema ";
                $function= [];
                $legend = "Espera ahí!";
                $function[] = array(
                    "id"=>1,
                    "reaction" => "actionToReaction('reaction1','modalSystem','$idventa'); return false;",
                    "inyectHmtl" => "finish='anulacion/ventas&s=false'",
                    "functionMessage" => "Solo anular.",
                );

                $function[] = array(
                    "id"=>2,
                    "reaction" => "actionToReaction('reaction2','modalSystem','$idventa'); return false;",
                    "inyectHmtl" => "finish='anulacion/ventas&s=true'",
                    "functionMessage" => "Volver a realizarla.",
                );

                $message = "Deseas anular esta venta y volver a realizarla? o solo anular?";
                $this->frameview("modal/index", array(
                    "type" => $type,
                    "legend" => $legend,
                    "message" => $message,
                    "function" => $function,
                ));
            }else{
                $type = "Mensaje del sistema";
                $function= [];
                $legend = "Espera ahí!";
                $message = "No se puedo acceder al identificador de esta venta";
                $this->frameview("modal/index", array(
                    "type" => $type,
                    "legend" => $legend,
                    "message" => $message,
                    "function" => $function,
                ));
            }

        }else{
            echo "Forbidden Gateway";
        }
    }

    public function ventas()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($_POST['data'])){
                $idventa = $_POST['data'];
                $sucursal = new Sucursal($this->adapter);
                $comprobante = new Comprobante($this->adapter);
                $formapago= new FormaPago($this->adapter);
                $ventas = new Ventas($this->adapter);
                $detalleventa = new DetalleVenta($this->adapter);
                $cartera = new Cartera($this->adapter);
                $cart = new ColaIngreso($this->adapter);
                $impuesto = new Impuestos($this->adapter);
                $retencion = new Retenciones($this->adapter);
                $detalleimpuesto = new DetalleImpuesto($this->adapter);
                $detalleretencion = new DetalleRetencion($this->adapter);
                $colaimpuesto = new ColaImpuesto($this->adapter);
                $colaretencion = new ColaRetencion($this->adapter);
                $empleados = new Empleado($this->adapter);
                $metodopago =new MetodoPago($this->adapter);
                $detallemetodopago = new DetalleMetodoPago($this->adapter);
                $colametodopago = new ColaMetodoPago($this->adapter);
                $articulos = new Articulo($this->adapter);
                $personas = new Persona($this->adapter);
                //primer paso recuperar la venta y ver si existe

                $venta = $ventas->getVentaById($idventa);
                foreach ($venta as $venta) {}

                if($venta->idventa){
                    //anular venta
                    $ventas->setEstado('D');
                    $ventas->setIdventa($venta->idventa);
                    $anularventa = $ventas->anularVentaById();
                    if($anularventa){
                        //devolver cantidad vendida al stock
                        $detallearticulos = $detalleventa->getArticulosByVenta($venta->idventa);
                        foreach ($detallearticulos as $detallearticulo) {
                            
                        }
                        //anular articulos relacionados
                        $detalleventa->setEstado('d');
                        $detalleventa->setIdventa($venta->idventa);
                        $anulardetalleventa = $detalleventa->anularDetalleVenta();

                        //anular cartera si esta venta es a credito
                        if($venta->tipo_pago === 'Credito'){
                            //buscar cartera
                            $carteraventa = $cartera->getCarteraClienteByVenta($venta->idventa);
                            foreach ($carteraventa as $carteraventa) {}
                            //desactivar cartera
                            //desactivar detalle de cartera
                            $cartera->setEstado('D');
                            $cartera->setIdcredito($carteraventa->idcredito);
                            $cartera->setIdsucursal($venta->idsucursal);
                            $anularcartera = $cartera->anularCarteraCliente();

                        }

                        if(isset($_GET['s']) && $_GET['s'] != 'false'){
                            //recuperar articulos de esta venta y almacenarlos en el carrito
                            $cart->setCi_usuario($_SESSION["usr_uid"]);
                            $cart->setCi_idsucursal($_SESSION["idsucursal"]);
                            $cart->setCi_idproveedor(0);
                            $cart->setCi_tipo_pago(0);
                            $cart->setCi_comprobante(0);
                            $cart->setCi_fecha(date("Y-m-d"));
                            $cart->setCi_fecha_final(date("Y-m-d"));
                            $addCart = $cart->createCart();
                            //almacenando cada articulo al carrito
                            foreach ($detallearticulos as $dataitems) {
                                $cart->setCdi_ci_id($addCart);
                                $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
                                $cart->setCdi_idusuraio($_SESSION["usr_uid"]);
                                $cart->setCdi_tercero($venta->nombre_cliente);
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

                            //recuperar impuestos, retenciones y metodos depago usados
                            $detalleimpuestos = $detalleimpuesto->getImpuestosBy($venta->idventa,'0','V');
                            $detalleretenciones = $detalleretencion->getRetencionBy($venta->idventa,'0','V');
                            $detallemetodopagos = $detallemetodopago->getDetalleMetodoPagoBy($venta->idventa,'0','V');

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
        
                            //almacenar metodos de pago utilizados en la venta
        
                            foreach ($detallemetodopagos as $metodotoinsert) {
                                $colametodopago->setCdmp_ci_id($addCart);
                                $colametodopago->setCdmp_idcomprobante(0);
                                $colametodopago->setCdmp_mp_id($metodotoinsert->dmpg_mp_id);
                                $colametodopago->setCdmp_contabilidad($metodotoinsert->dmpg_contabilidad);
                                $colametodopago->setCdmp_monto($metodotoinsert->dmpg_monto);
                                $colametodopago->addMetodoPago();
                            }

                            //redireccionar y recuperar datos de la venta
                            echo json_encode(array(
                                "type"=>"redirect",
                                "success"=>"#ventas/restore/$venta->idventa"
                            ));
                        }else{
                            //redireccionar a la factura de venta en este caso anulada
                            echo json_encode(array(
                                "type"=>"redirect",
                                "success"=>"#file/venta/$venta->idventa"
                            ));
                        }
                }else{

                }
            }else{
                    echo json_encode(array(
                        "type"=>"message",
                        "alertType"=>"error",
                        "response"=>"venta no encontrada",
                        "success"=>"error"
                        ));
            }
        }
        }else{
            echo "Forbidden gateway";
        }
    }
}
