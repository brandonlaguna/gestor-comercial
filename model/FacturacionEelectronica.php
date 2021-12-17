<?php
class FacturacionElectronica extends EntidadBase
{

    //private $im_id;
    private $fe_id;
    private $fe_code;
    private $idventa;
    private $fe_type;
    private $invoiceType;
    private $fe_idsucursal;
    private $fe_pointOfSale;
    private $fe_currency;
    private $fe_active;

    public function __construct($adapter)
    {
        $table = "tb_facturacion_electronica";
        parent::__construct($table, $adapter);
    }

    public function getFe_id()
    {
        return $this->fe_id;
    }
    public function setFe_id($fe_id)
    {
        $this->fe_id = $fe_id;
    }
    public function getFe_idsucursal()
    {
        return $this->fe_idsucursal;
    }
    public function setFe_idsucursal($fe_idsucursal)
    {
        $this->fe_idsucursal = $fe_idsucursal;
    }
    public function getFe_code()
    {
        return $this->fe_code;
    }
    public function setFe_code($fe_code)
    {
        $this->fe_code = $fe_code;
    }
    public function getFe_active()
    {
        return $this->fe_active;
    }
    public function setFe_active($fe_active)
    {
        $this->fe_active = $fe_active;
    }
    public function getIdventa()
    {
        return $this->idventa;
    }
    public function setIdventa($idventa)
    {
        $this->idventa = $idventa;
    }
    public function getFe_type()
    {
        return $this->fe_type;
    }
    public function setFe_type($fe_type)
    {
        $this->fe_type = $fe_type;
    }
    public function getFe_pointOfSale()
    {
        return $this->fe_pointOfSale;
    }
    public function setFe_pointOfSale($fe_pointOfSale)
    {
        $this->fe_pointOfSale = $fe_pointOfSale;
    }
    public function getFe_currency()
    {
        return $this->fe_currency;
    }
    public function setFe_currency($fe_currency)
    {
        $this->fe_currency = $fe_currency;
    }
    public function getInvoiceType()
    {
        return $this->invoiceType;
    }
    public function setInvoiceType($invoiceType)
    {
        $this->invoiceType = $invoiceType;
    }

    public function status()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            $query = $this->db()->query("SELECT * from tb_facturacion_electronica WHERE fe_active = 1 LIMIT 1");
            if ($query->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function generate($adapter)
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"])) {
            if (isset($this->idventa)) {
                //require all model files
                foreach (glob("*.php") as $file) {require_once $file;}

                //models
                $ventas = new Ventas($adapter);
                $detalleventa = new DetalleVenta($adapter);
                $ventacontable = new ComprobanteContable($adapter);
                $detalleventacontable = new DetalleComprobanteContable($adapter);
                $personas = new Persona($adapter);
                $sucursales = new Sucursal($adapter);
                $detalleretencion = new DetalleRetencion($adapter);
                $detalleimpuesto = new DetalleImpuesto($adapter);
                $dataimpuestos = new Impuestos($adapter);
                $dataretencion = new Retenciones($adapter);
                $articulomodel = new Articulo($adapter);
                $ventafacturacionelectronica = new VentaFacturacionElectronica($adapter);

                $params = [];
                $items = [];
                $errors = [];
                $sucursal = $sucursales->getSucursalById($_SESSION["idsucursal"]);
                $proceso = ($this->fe_type == "C") ? 1 : 0;
                $retenciones = $detalleretencion->getRetencionBy($this->idventa, $proceso, $this->fe_type);
                $impuestos = $detalleimpuesto->getImpuestosBy($this->idventa, $proceso, $this->fe_type);
                $subtotal = $detalleventa->getSubTotal($this->idventa);
                $totalimpuestos = $detalleventa->getImpuestos($this->idventa);
                foreach ($sucursal as $sucursal) {}
                $time = date("h:m:s");
                $configuraciones = $this->getConfiguracionByIdSucursal($sucursal->idsucursal);
                foreach ($configuraciones as $configuracion) {}

                //preparing data

                switch ($this->fe_type) {
                    case 'V':

                        # Venta sin contabilidad
                        $venta = $ventas->getVentaById($this->idventa);
                        $articulos = $detalleventa->getArticulosByVenta($this->idventa);
                        foreach ($venta as $venta) {}
                        if ($venta->idventa) {

                            $costomerId = $venta->idCliente;
                            //invoice config
                            $params = array(
                                "invoiceType" => $this->invoiceType,
                                "operationCode" => $this->fe_code,
                                "pointOfSale" => $configuracion->fe_pointOfSale,
                                "currency" => $configuracion->fe_currency,
                            );
                            //items
                            foreach ($articulos as $articulo) {
                                if ($articulo->importe_categoria) {
                                    $itemType = "GRAVADO";
                                } else {
                                    $itemType = "EXENTO";
                                }
                                $array = array(
                                    "itemType" => $itemType,
                                    "quantity" => $articulo->cantidad,
                                    "amount" => intval($articulo->precio_venta),
                                    "name" => $articulo->nombre_articulo,
                                    "description" => $articulo->descripcion,
                                    "taxableAmount" => $articulo->precio_venta * $articulo->cantidad,
                                    "totalTax" => $articulo->iva_compra ,
                                    "total" => $articulo->precio_total_lote,
                                    "clasificationId" => "",
                                    "clasificationName" => "",
                                    "discountPercentage" => 0.00,
                                    "discountAmount" => 0.00,
                                    "itemCode" => $articulo->idarticulo,
                                    "observation" => "",
                                    "isGift" => false,
                                );
                                $arr_taxes = [];
                                foreach ($impuestos as $taxes) {
                                    if ($taxes->im_porcentaje == $articulo->importe_categoria) {
                                        array_push($arr_taxes, array(
                                            "type" => $taxes->im_type,
                                            "amount" => $articulo->iva_compra,
                                            "taxableAmount" => $articulo->precio_venta, //$articulo->precio_venta * $articulo->cantidad,
                                            "percentage" => intval($articulo->importe_categoria),
                                        ));

                                    } else {
                                    }
                                }
                                $array = array_merge($array, array("taxes" => $arr_taxes));

                                array_push($items, $array);

                                //retenciones individuales
                            }

                            $params = array_merge($params, array("items" => $items));

                            //customer
                            $persona = $personas->getPersonaById($costomerId);
                            foreach ($persona as $persona) {}

                            //select zip code

                            $params = array_merge($params, array(
                                "customer" => array(
                                    "type" => $persona->to_type,
                                    "id" => cln_number($persona->num_documento),
                                    "idType" => $persona->tipo_documento,
                                    "name" => $persona->nombre_persona,
                                    "department" => $persona->direccion_departamento,
                                    "postalCode" => "110111",
                                    "address" => $persona->direccion_calle,
                                    "cityName" => $persona->direccion_provincia,
                                    "countryCode" => "CO",
                                    "firstName" => $persona->nombre_persona,
                                    "lastName" => "",
                                    "email" => $persona->email,
                                    "telephone" => $persona->telefono,
                                ))
                            );

                            //taxes
                            $taxes = [];
                            foreach ($subtotal as $subtotal) {}

                            //valores
                            $subtotalimpuesto = 0;
                            $listImpuesto = [];
                            $listRetencion = [];
                            $total_bruto = $subtotal->cdi_debito;
                            $total_neto = $subtotal->cdi_debito;
                            foreach ($totalimpuestos as $imp) {
                                $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
                                foreach ($impuestos as $data2) {}
                                if ($impuestos) {

                                    $total_neto -= $subtotalimpuesto;
                                    $total_bruto -= $subtotalimpuesto;

                                } else {

                                }
                                
                                foreach ($impuestos as $impuesto) {
                                    if ($imp->cdi_importe == $impuesto->im_porcentaje) {
                                        //calculado
                                        $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
                                        //arreglo
                                        $listImpuesto[] = array(
                                            "type" => $impuesto->im_type,
                                            "amount" => $calc,
                                            "taxableAmount" => abs($imp->cdi_debito - $calc),
                                            "percentage" => intval($impuesto->im_porcentaje),
                                        );
                                        /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                                        $total_neto += $calc;
                                    } else {

                                    }
                                }
                            }

                            $params = array_merge($params, array("taxes" => $listImpuesto));
                            $price_retencion = 0;
                            foreach ($retenciones as $retencion) {
                                if ($retencion) {
                                    $status_retencion = true;
                                }
                                if ($retencion->re_im_id <= 0) {
                                    //concatenacion del nombre
                                    $re_nombre = $retencion->re_nombre . " " . $retencion->re_porcentaje . "%";
                                    //calculado $subtotal->cdi_debito*($retencion->re_porcentaje/100)
                                    $calc = $total_bruto * ($retencion->re_porcentaje / 100);
                                    $price_retencion += $calc;
                                    //arreglo
                                    $listRetencion[] = array(
                                        "type" => $retencion->re_type,
                                        "amount" => $calc,
                                        "taxableAmount" => $total_bruto,
                                        "percentage" => intval($retencion->re_porcentaje),
                                    );
                                    /************************RESTANDO RETENCION DEL CALCULO*****************************/
                                    $total_neto -= $calc;
                                } else {
                                    foreach ($totalimpuestos as $imp) {
                                        $impid = $dataimpuestos->getImpuestosById($retencion->re_im_id);
                                        foreach ($impid as $impid) {
                                            if ($imp->cdi_importe == $impid->im_porcentaje) {
                                                $iva = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));

                                                $calc = $iva * ($retencion->re_porcentaje / 100);
                                                $price_retencion += $calc;
                                                $listRetencion[] = array(
                                                    "type" => $retencion->re_type,
                                                    "amount" => $calc,
                                                    "taxableAmount" => $iva,
                                                    "percentage" => intval($retencion->re_porcentaje),
                                                );
                                                /************************RESTANDO RETENCION DEL CALCULO*****************************/
                                                $total_neto -= $calc;
                                            } else {
                                            }
                                        }
                                    }
                                }

                            }

                            $params = array_merge($params, array("retes" => $listRetencion));

                            $params = array_merge($params, array(
                                "otherTaxesTotal" => 0.00,
                                "total" => $venta->total,
                                "paid" => $venta->total,
                                "vatAmount" => $venta->subtotal_importe,
                                "exemptAmount" => $price_retencion,
                                "taxableAmount" => $venta->sub_total,
                                "issuedDate" => $venta->fecha . " " . $time,
                                "saleCondition" => $venta->num_comprobante,
                                "customerInvoiceId" => $venta->num_comprobante,
                                "seller" => $venta->nombre_empleado . " " . $venta->apellido_empleado,
                            ));

                            if ($venta->fecha_final != "0000-00-00") {
                                $params = array_merge($params, array(
                                    "expirationDate" => $venta->fecha_final . " " . $time,
                                ));
                            } else {
                                $threeMont = date("Y-m-d", strtotime($venta->fecha . "+ 1 year"));
                                $params = array_merge($params, array(
                                    "expirationDate" => $venta->fecha . " " . $time,
                                ));
                            }

                            //retes

                        } else {
                            array_push($errors, array("error" => "venta no disponible"));
                        }

                        //to send
                        $tosend = array("invoices" => array($params));

                        break;
                    case 'C':
                        #Venta con contabilidad
                        $venta = $ventacontable->getComprobanteById($this->idventa);
                        $articulos = $detalleventacontable->getArticulosByComprobante($this->idventa);
                        $totales = $detalleventacontable->getTotalByCompra($this->idventa);
                        $totalimpuestos = $detalleventacontable->getImpuestos($this->idventa);

                        foreach ($venta as $venta) {}
                        //configuracion por sucursal
                        $configuraciones = $this->getConfiguracionByIdSucursal($sucursal->idsucursal);
                        foreach ($configuraciones as $configuracion) {}

                        $costomerId = $venta->cc_idproveedor;
                        //invoice config
                        $params = array(
                            "invoiceType" => $this->invoiceType,
                            "operationCode" => $this->fe_code,
                            "pointOfSale" => $configuracion->fe_pointOfSale,
                            "currency" => $configuracion->fe_currency,
                        );

                        //items
                        foreach ($articulos as $articulos) {

                            $getArticulo = $articulomodel->getArticuloById($articulos->dcc_cod_art);
                            if ($getArticulo) {
                                foreach ($getArticulo as $articuloitem) {}
                                if ($articulos->dcc_base_imp_item) {
                                    $itemType = "GRAVADO";
                                } else {
                                    $itemType = "EXENTO";
                                }
                                $array = array(
                                    "itemType" => $itemType,
                                    "quantity" => $articulos->dcc_cant_item_det,
                                    "amount" => intval($articulos->dcc_valor_item),
                                    "name" => $articuloitem->nombre_articulo,
                                    "description" => $articuloitem->descripcion,
                                    "taxableAmount" => $articulos->dcc_valor_item,
                                    "totalTax" => round($articulos->dcc_valor_item * ($articulos->dcc_base_imp_item / 100)),
                                    "total" => round($articulos->dcc_valor_item * (($articulos->dcc_base_imp_item / 100) + 1)),
                                    "clasificationId" => "",
                                    "clasificationName" => "",
                                    "discountPercentage" => 0.00,
                                    "discountAmount" => 0.00,
                                    "itemCode" => $articuloitem->idarticulo,
                                    "observation" => "",
                                    "isGift" => false,
                                );

                                $arr_taxes = [];
                                foreach ($impuestos as $taxes) {
                                    if ($taxes->im_porcentaje == $articulos->dcc_base_imp_item) {
                                        array_push($arr_taxes, array(
                                            "type" => $taxes->im_type,
                                            "amount" => round($articulos->dcc_valor_item * ($articulos->dcc_base_imp_item / 100)),
                                            "taxableAmount" => $articulos->dcc_valor_item, //$articulo->precio_venta * $articulo->cantidad,
                                            "percentage" => intval($articulos->dcc_base_imp_item),
                                        ));

                                    } else {
                                    }
                                }

                                $array = array_merge($array, array("taxes" => $arr_taxes));

                                array_push($items, $array);
                            }

                            //retenciones individuales
                        }

                        $params = array_merge($params, array("items" => $items));

                         //customer
                         $persona = $personas->getPersonaById($costomerId);
                         foreach ($persona as $persona) {}

                        $params = array_merge($params, array(
                            "customer" => array(
                                "type" => $persona->to_type,
                                "id" => cln_number($persona->num_documento),
                                "idType" => $persona->tipo_documento,
                                "name" => $persona->nombre_persona,
                                "department" => $persona->direccion_departamento,
                                "postalCode" => "110111",
                                "address" => $persona->direccion_calle,
                                "cityName" => $persona->direccion_provincia,
                                "countryCode" => "CO",
                                "firstName" => $persona->nombre_persona,
                                "lastName" => "",
                                "email" => $persona->email,
                                "telephone" => $persona->telefono,
                            ))
                        );

                        //impuestos y retenciones
                        foreach ($totales as $subtotal) {}
                        $subtotalimpuesto = 0;
                        $listImpuesto = [];
                        $listRetencion = [];
                        $total_bruto = $subtotal->cdi_debito;
                        $total_neto = $subtotal->cdi_debito;
                        $price_impuesto =0;
                        //obtener impuestos en grupos por porcentaje (19% 10% 5% etc...)

                        foreach ($totalimpuestos as $imp) {
                            $subtotalimpuesto += $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1)); //aqui
                            foreach ($impuestos as $data2) {}
                            if ($impuestos) {
                                if ($data2->im_porcentaje == $imp->cdi_importe) {
                                    $total_neto -= $subtotalimpuesto;
                                    $total_bruto -= $subtotalimpuesto;
                                } else {

                                }
                            } else {

                            }

                            foreach ($impuestos as $impuesto) {
                                if ($imp->cdi_importe == $impuesto->im_porcentaje) {
                                    //calculado
                                    $calc = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));
                                    //concatenacion del nombre
                                    $price_impuesto += $calc;
                                    $im_nombre = $impuesto->im_nombre . " " . $impuesto->im_porcentaje . "%";
                                    //arreglo
                                    $listImpuesto[] = array(
                                        "type" => $impuesto->im_type,
                                        "amount" => $calc,
                                        "taxableAmount" => abs($imp->cdi_debito - $calc),
                                        "percentage" => intval($impuesto->im_porcentaje),
                                    );
                                    /************************SUMANDO IMPUESTOS DEL CALCULO*****************************/
                                    $total_neto += $calc;
                                } else {

                                }
                            }
                        }

                        $params = array_merge($params, array("taxes" => $listImpuesto));
                        $price_retencion = 0;
                        foreach ($retenciones as $retencion) {

                            if ($retencion->re_im_id <= 0) {
                                //concatenacion del nombre
                                $re_nombre = $retencion->re_nombre . " " . $retencion->re_porcentaje . "%";
                                //calculado $subtotal->cdi_debito*($retencion->re_porcentaje/100)
                                $calc = $total_bruto * ($retencion->re_porcentaje / 100);
                                //arreglo
                                $price_retencion += $calc;
                                $listRetencion[] = array(
                                    "type" => $retencion->re_type,
                                    "amount" => $calc,
                                    "taxableAmount" => $total_bruto,
                                    "percentage" => intval($retencion->re_porcentaje),
                                );
                                /************************RESTANDO RETENCION DEL CALCULO*****************************/
                                $total_neto -= $calc;
                            } else {
                                foreach ($totalimpuestos as $imp) {
                                    $impid = $dataimpuestos->getImpuestosById($retencion->re_im_id);
                                    foreach ($impid as $impid) {
                                        if ($imp->cdi_importe == $impid->im_porcentaje) {
                                            $re_nombre = $retencion->re_nombre . " (" . $retencion->re_porcentaje . "%)";
                                            $iva = $imp->cdi_debito - ($imp->cdi_debito / (($imp->cdi_importe / 100) + 1));

                                            $calc = $iva * ($retencion->re_porcentaje / 100);
                                            $price_retencion += $calc;
                                            $listRetencion[] = array(
                                                "type" => $retencion->re_type,
                                                "amount" => $calc,
                                                "taxableAmount" => $iva,
                                                "percentage" => intval($retencion->re_porcentaje),
                                            );
                                            /************************RESTANDO RETENCION DEL CALCULO*****************************/
                                            $total_neto -= $calc;
                                        } else {
                                        }
                                    }
                                }
                            }

                        }
                        $params = array_merge($params, array("retes" => $listRetencion));

                        $params = array_merge($params, array(
                            "otherTaxesTotal" => 0.00,
                            "total" => $total_neto,
                            "paid" => $total_neto,
                            "vatAmount" => $price_impuesto,
                            "exemptAmount" => $price_retencion,
                            "taxableAmount" => $total_bruto,
                            "issuedDate" => $venta->cc_fecha_cpte . " " . $time,
                            "saleCondition" => cln_number($venta->cc_num_cpte).$venta->cc_cons_cpte,
                            "customerInvoiceId" => $venta->cc_id_transa,
                            "seller" => $venta->nombre_empleado . " " . $venta->apellido_empleado,
                        ));

                        if ($venta->cc_fecha_final_cpte != "0000-00-00") {
                            $params = array_merge($params, array(
                                "expirationDate" => $venta->cc_fecha_final_cpte . " " . $time,
                            ));
                        } else {
                            $threeMont = date("Y-m-d", strtotime($venta->cc_fecha_cpte . "+ 1 year"));
                            $params = array_merge($params, array(
                                "expirationDate" => $venta->cc_fecha_cpte . " " . $time,
                            ));
                        }

                        $tosend = array("invoices" => array($params));

                        break;

                }

                $auth = [FACTURACION_ELECTRONICA['user'], FACTURACION_ELECTRONICA['password']];
                $make_call = fetch('POST', FACTURACION_ELECTRONICA['url'], json_encode($tosend,JSON_UNESCAPED_UNICODE), $auth);
                $responseapi = $make_call;

                $anexo = json_decode($responseapi, true);
                //verificar si la factura fue aceptada o anulada
                $errors = $anexo["errors"];
                $info = $anexo["info"];

                if($errors){
                    $statusFE="P";
                }else{
                    $statusFE="A";
                }
                $dateCreated = date("Y-m-d H:i:s");
                $ventafacturacionelectronica->setVfe_idventa($this->idventa);
                $ventafacturacionelectronica->setVfe_detalle_registro($this->fe_type);
                $ventafacturacionelectronica->setVfe_contabilidad($proceso);
                $ventafacturacionelectronica->setVfe_response($responseapi);
                $ventafacturacionelectronica->setVfe_status($statusFE);
                $ventafacturacionelectronica->setVfe_date_created($dateCreated);

                $ventafacturacionelectronica->addFacturaElectronica();

                //anexo identificador
                $anexo = array_merge($anexo, array("id"=>$this->idventa));

                return json_encode($anexo);

                // return json_encode(array("response"=>"status"));
                //return json_encode($params, JSON_UNESCAPED_UNICODE);

            } else {
                return json_encode(array(
                    "errors" => array(
                        "errors" => true,
                        "info" => "Error al recuperar informacion de esta venta.",
                        "id" => "",
                    ),
                ));
            }
        } else {
            return json_encode(array(
                "errors" => array(
                    "errors" => true,
                    "info" => "No tiene permisos para esta accion.",
                    "id" => "",
                ),
            ));
        }
    }

    public function addConfiguracion()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            $query = "INSERT INTO tb_facturacion_electronica ()
            VALUES(
                '" . $this->fe_idsucursal . "',
                '" . $this->fe_pointOfSale . "',
                '" . $this->fe_currency . "',
                '" . $this->fe_active . "',
            )";
            $addArticulo = $this->db()->query($query);

        } else {
            return false;
        }
    }

    public function getConfiguracionByIdSucursal($id)
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            $query = $this->db()->query("SELECT * FROM tb_facturacion_electronica WHERE fe_idsucursal = '$id' LIMIT 1");
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {
                $resultSet = [];
            }
            return $resultSet;
        } else {
            return false;
        }
    }



}
