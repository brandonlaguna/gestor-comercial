<?php
class Ventas extends EntidadBase
{

    private $idventa;
    private $idsucursal;
    private $idpedido;
    private $idCliente;
    private $idusuario;
    private $tipo_venta;
    private $tipo_pago;
    private $tipo_comprobante;
    private $serie_comprobante;
    private $num_comprobante;
    private $fecha;
    private $fecha_final;
    private $impuesto;
    private $sub_total;
    private $subtotal_importe;
    private $total;
    private $importe_pagado;
    private $affected;
    private $estado;

    //news rows
    private $observaciones;

    public function __construct($adapter)
    {
        $table = "ventas";
        parent::__construct($table, $adapter);
    }

    public function getIdventa()
    {
        return $this->idventa;
    }
    public function setIdventa($idventa)
    {
        $this->idventa = $idventa;
    }
    public function getIdsucursal()
    {
        return $this->idsucursal;
    }
    public function setIdsucursal($idsucursal)
    {
        $this->idsucursal = $idsucursal;
    }
    public function getIdpedido()
    {
        return $this->idpedido;
    }
    public function setIdpedido($idpedido)
    {
        $this->idpedido = $idpedido;
    }
    public function getIdCliente()
    {
        return $this->idCliente;
    }
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }
    public function getIdusuario()
    {
        return $this->idusuario;
    }
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }
    public function getTipo_venta()
    {
        return $this->tipo_venta;
    }
    public function setTipo_venta($tipo_venta)
    {
        $this->tipo_venta = $tipo_venta;
    }
    public function getTipo_pago()
    {
        return $this->tipo_pago;
    }
    public function setTipo_pago($tipo_pago)
    {
        $this->tipo_pago = $tipo_pago;
    }
    public function getSerie_comprobante()
    {
        return $this->serie_comprobante;
    }
    public function setSerie_comprobante($serie_comprobante)
    {
        $this->serie_comprobante = $serie_comprobante;
    }
    public function getTipo_comprobante()
    {
        return $this->tipo_comprobante;
    }
    public function setTipo_comprobante($tipo_comprobante)
    {
        $this->tipo_comprobante = $tipo_comprobante;
    }
    public function getNum_comprobante()
    {
        return $this->num_comprobante;
    }
    public function setNum_comprobante($num_comprobante)
    {
        $this->num_comprobante = $num_comprobante;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function getFecha_final()
    {
        return $this->fecha_final;
    }
    public function setFecha_final($fecha_final)
    {
        $this->fecha_final = $fecha_final;
    }
    public function getImpuesto()
    {
        return $this->impuesto;
    }
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }
    public function getSub_total()
    {
        return $this->sub_total;
    }
    public function setSub_total($sub_total)
    {
        $this->sub_total = $sub_total;
    }
    public function getSubtotal_importe()
    {
        return $this->subtotal_importe;
    }
    public function setSubtotal_importe($subtotal_importe)
    {
        $this->subtotal_importe = $subtotal_importe;
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function setTotal($total)
    {
        $this->total = $total;
    }
    public function getImporte_pagado()
    {
        return $this->importe_pagado;
    }
    public function setImporte_pagado($importe_pagado)
    {
        $this->importe_pagado = $importe_pagado;
    }
    public function getAffected()
    {
        return $this->affected;
    }
    public function setAffected($affected)
    {
        $this->affected = $affected;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function getObservaciones()
    {
        return $this->observaciones;
    }
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }
    public function getVentas()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        ");

            if ($query->num_rows > 0) {
                while ($row = $query->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {
                $resultSet[] = [];
            }

            $query2 = $this->db()->query("SELECT *, (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as total,
        cc.cc_fecha_cpte as fecha
        FROM comprobante_contable cc
        INNER JOIN detalle_comprobante_contable dcc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN usuario u on u.idusuario = cc.cc_idusuario
        INNER JOIN empleado em on em.idempleado = u.idempleado
        INNER JOIN persona pe on pe.idpersona = cc.cc_idproveedor
        WHERE cc.cc_tipo_comprobante = 'V'");
            if ($query2->num_rows > 0) {
                while ($row = $query2->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {

            }

            return $resultSet;
        } else {
            return false;
        }
    }

    public function getVentasAll()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3) {
            $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.idsucursal = '" . $_SESSION['idsucursal'] . "' ORDER BY idventa DESC");

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

    public function getVentasByRangeId($venta_start, $venta_finish)
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3) {
            $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.idsucursal = '" . $_SESSION['idsucursal'] . "' 
        AND v.idventa >= '$venta_start' AND v.idventa <= '$venta_finish'
        AND v.tipo_pago = 'Contado'
        ORDER BY v.idventa DESC ");

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

    public function getVentasBySucursal($idsurucsal)
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3) {
            $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.idsucursal = '$idsurucsal' ORDER BY idventa DESC");

            if ($query->num_rows > 0) {
                while ($row = $query->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {
                $resultSet[] = [];
            }

            $query2 = $this->db()->query("SELECT *, (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as total,
        cc.cc_fecha_cpte as fecha
        FROM comprobante_contable cc
        INNER JOIN detalle_comprobante_contable dcc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN usuario u on u.idusuario = cc.cc_idusuario
        INNER JOIN empleado em on em.idempleado = u.idempleado
        INNER JOIN persona pe on pe.idpersona = cc.cc_idproveedor
        WHERE cc.cc_ccos_cpte = '$idsurucsal' AND AND cc.cc_tipo_comprobante = 'V' ORDER BY cc.cc_id_transa DESC");
            if ($query2->num_rows > 0) {
                while ($row = $query2->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {

            }

            return $resultSet;

        } else {
            return false;
        }
    }

    public function getVentasByRange($range_start)
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3) {
            $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.idsucursal = '" . $_SESSION['idsucursal'] . "'
        AND v.idusuario = '" . $_SESSION['usr_uid'] . "'
        AND v.idventa >= '$range_start'
        AND v.estado = 'A'
        ORDER BY idventa DESC");

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

    public function getVentaByArticulo($idarticulo)
    {
        $query = $this->db()->query("SELECT *, sum(dv.cantidad) as stock_ventas, (dv.precio_total_lote) as precio_ventas
        FROM detalle_venta dv
            INNER JOIN articulo a on dv.idarticulo = a.idarticulo
            INNER JOIN detalle_stock ds on a.idarticulo = ds.idarticulo
            INNER JOIN venta v on v.idventa = dv.idventa
            INNER JOIN usuario u on v.idusuario = u.idusuario
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN sucursal su on v.idsucursal = su.idsucursal
            WHERE dv.idarticulo = '$idarticulo' AND ds.st_idsucursal = '" . $_SESSION["idsucursal"] . "' AND su.idsucursal = '" . $_SESSION["idsucursal"] . "' AND v.estado='A'");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet[] = [];
        }
        $query2 = $this->db()->query("SELECT *, sum(dcc.dcc_cant_item_det) as stock_ventas, dcc.dcc_valor_item as precio_ventas, cc.cc_fecha_cpte as fecha
        FROM detalle_comprobante_contable dcc
            INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art
            INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
            INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            INNER JOIN usuario u on u.idusuario = cc.cc_idusuario
            INNER JOIN empleado em on em.idempleado = u.idempleado
            INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
            WHERE dcc.dcc_cod_art = '$idarticulo' AND ds.st_idsucursal = '" . $_SESSION["idsucursal"] . "' AND cc.cc_ccos_cpte = '" . $_SESSION["idsucursal"] . "' AND cc.cc_tipo_comprobante = 'V' AND cc.cc_estado ='A'");
        if ($query2->num_rows > 0) {
            while ($row = $query2->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {}
        return $resultSet;
    }

    public function getDetalleVentasByDay($start_date, $end_date, $column, $value)
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            $query = $this->db()->query("SELECT *, v.estado as estado_venta, pe.num_documento as documento_tercero, pe.nombre as nombre_cliente,
            a.nombre as nombre_articulo, (dv.cantidad) as stock_total, dv.importe_categoria as importe_articulo, dv.precio_venta as precio_unidad, (dv.cantidad) as stock_total_ventas, (dv.precio_total_lote) as precio_total_ventas
            FROM detalle_venta dv
            INNER JOIN articulo a on dv.idarticulo = a.idarticulo
            INNER JOIN venta v on v.idventa = dv.idventa
            INNER JOIN persona pe on v.idCliente = pe.idpersona
            WHERE v.idsucursal = '" . $_SESSION["idsucursal"] . "' and v.estado='A' AND v.fecha >= '$start_date' AND v.fecha <= '$end_date' AND $column = '$value' ORDER BY v.fecha DESC");

            if ($query->num_rows > 0) {
                while ($row = $query->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {
                $resultSet=[];
            }
            $query2 = $this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.num_documento as documento_tercero, pe.nombre as nombre_cliente,
            a.nombre as nombre_articulo, dcc.dcc_cant_item_det as stock_ingreso, dcc.dcc_base_imp_item as importe_articulo, (dcc.dcc_cant_item_det) as stock_total_ventas, (dcc.dcc_cant_item_det) as stock_total,
            (dcc.dcc_valor_item+(dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100))) as precio_total_ventas, cc.cc_fecha_cpte as fecha,
            cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante, cc.cc_nit_cpte as documento_tercero
            FROM detalle_comprobante_contable dcc
            INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art
            INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
            INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
            WHERE cc.cc_ccos_cpte = '" . $_SESSION["idsucursal"] . "' AND cc.cc_fecha_cpte >= '$start_date' AND cc.cc_fecha_cpte <= '$end_date' AND dcc.dcc_cod_art = '$value' AND cc.cc_tipo_comprobante = 'V' ORDER BY cc.cc_fecha_cpte DESC");

            if ($query2->num_rows > 0) {
                while ($row = $query2->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {
                $resultSet=[];
            }
            return $resultSet;
        }
    }
    public function getVentasDetalladas()
    {
        $query = $this->db()->query("SELECT dv.*,a.*,v.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, dv.cantidad as stock_venta, dv.importe_categoria as importe_articulo, dv.precio_venta as precio_unidad
        FROM detalle_venta dv
        INNER JOIN articulo a on dv.idarticulo = a.idarticulo
        INNER JOIN venta v on v.idventa = dv.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        INNER JOIN detalle_documento_sucursal dds on v.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
		ORDER BY v.idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;

    }

    public function getVentasPendiente()
    {
        $query = $this->db()->query("SELECT c.*,v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM credito c
        INNER JOIN venta v on c.idventa = v.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE c.total_pago <> c.deuda_total and su.idsucursal= '" . $_SESSION["idsucursal"] . "'
        ORDER BY v.idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;

    }

    public function getVentasContado()
    {
        $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.tipo_pago = 'Contado' and su.idsucursal = '" . $_SESSION["idsucursal"] . "'
        ORDER BY idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;

    }

    public function getVentasCredito()
    {
        $query = $this->db()->query("SELECT c.*,v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM credito c
        INNER JOIN venta v on c.idventa = v.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado = 'A'
        ORDER BY v.idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;

    }

    public function saveVenta()
    {
        if (!empty($_SESSION["idsucursal"])) {
            $query = "INSERT INTO `venta` (idsucursal,idCliente,idusuario,tipo_venta,tipo_pago,tipo_comprobante,serie_comprobante,num_comprobante,fecha,fecha_final,impuesto,sub_total,subtotal_importe,total,importe_pagado,estado,observaciones)
            VALUES(
                '" . $this->idsucursal . "',
                '" . $this->idCliente . "',
                '" . $this->idusuario . "',
                '" . $this->tipo_venta . "',
                '" . $this->tipo_pago . "',
                '" . $this->tipo_comprobante . "',
                '" . $this->serie_comprobante . "',
                '" . $this->num_comprobante . "',
                '" . $this->fecha . "',
                '" . $this->fecha_final . "',
                '" . $this->impuesto . "',
                '" . $this->sub_total . "',
                '" . $this->subtotal_importe . "',
                '" . $this->total . "',
                '" . $this->importe_pagado . "',
                '" . $this->estado . "',
                '" . $this->observaciones ."' )";
            $addVenta = $this->db()->query($query);

            $returnId = $this->db()->query("SELECT idventa FROM venta ORDER BY idventa DESC LIMIT 1");
            if ($returnId->num_rows > 0) {
                while ($row = $returnId->fetch_assoc()) {
                    $idventa = $row["idventa"];
                }
            }

            if ($addVenta) {
                $status = $idventa;
            } else {
                $status = false;
            }
            return $status;

        } else {
            return false;
        }
    }

    public function updateVenta($idventa)
    {
        if (!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4) {
            $query = "UPDATE venta
            SET
                idsucursal = '" . $this->idsucursal . "',
                idCliente = '" . $this->idCliente . "',
                idusuario = '" . $this->idusuario . "',
                tipo_venta = '" . $this->tipo_venta . "',
                tipo_pago = '" . $this->tipo_pago . "',
                fecha = '" . $this->fecha . "',
                fecha_final = '" . $this->fecha_final . "',
                impuesto = '" . $this->impuesto . "',
                sub_total = '" . $this->sub_total . "',
                subtotal_importe = '" . $this->subtotal_importe . "',
                total = '" . $this->total . "',
                importe_pagado = '" . $this->importe_pagado . "',
                affected = '" . $this->affected . "',
                estado = '" . $this->estado . "',
                observaciones = '" . $this->observaciones ."'
                WHERE idventa = '$idventa'";
            $updateVenta = $this->db()->query($query);
            return $updateVenta;

        }
    }

    public function addImpuestoVenta($idventa)
    {
        if (!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1) {
            $query = "UPDATE venta
            SET
            subtotal_importe = '" . $this->subtotal_importe . "'
            WHERE idventa = '$idventa'";

            $addImpuesto = $this->db()->query($query);
            return $addImpuesto;
        }
    }

    public function getVentaById($id)
    {
        $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*,fp.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        em.apellidos as apellido_empleado, pe.telefono as telefono_cliente
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        INNER JOIN detalle_documento_sucursal dds on v.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        INNER JOIN tb_forma_pago fp on v.tipo_pago = fp.fp_nombre and fp.fp_proceso = 'Venta'
        WHERE idventa = '$id'");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;
    }

    public function reporte_general($start_date, $end_date)
    {
        //agregar opcion de retencion retencion
        $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        v.subtotal_importe as impuesto, v.affected as retencion
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN detalle_documento_sucursal dds on v.serie_comprobante = dds.ultima_serie
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
        ORDER BY idventa DESC");
        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }

        $query2 = $this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, cc.cc_fecha_cpte as fecha,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa and dcc.dcc_cod_art > 0) as sub_total,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.impuesto = 1 WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as impuesto,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.retencion = 1 WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as retencion,
        dds.ultima_serie as tipo_comprobante, cc.cc_id_transa as idventa
        FROM comprobante_contable cc
        INNER JOIN detalle_comprobante_contable dcc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art
        INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
        INNER JOIN usuario u on u.idusuario = cc.cc_idusuario
        INNER JOIN empleado em on em.idempleado = u.idempleado
        INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
        INNER JOIN persona pe on pe.idpersona = cc.cc_idproveedor
        INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
        WHERE su.idsucursal = '" . $_SESSION["idsucursal"] . "' AND cc.cc_fecha_cpte >= '$start_date' AND cc.cc_fecha_cpte <= '$end_date' AND cc.cc_tipo_comprobante = 'V' AND cc.cc_estado = 'A' GROUP BY cc.cc_id_transa ORDER BY cc.cc_id_transa DESC");

        if ($query2->num_rows > 0) {
            while ($row = $query2->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            
        }

        return $resultSet;
    }

    public function reporte_general_comprobante($start_date, $end_date, $comprobante)
    {

        $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_tercero, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.serie_comprobante = '$comprobante'  and v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
        ORDER BY idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;
    }

    public function reporte_detallado($start_date, $end_date)
    {
        $query = $this->db()->query("SELECT dv.*,a.*,v.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, dv.cantidad as stock_venta, dv.importe_categoria as importe_articulo, dv.precio_venta as precio_unidad
        FROM detalle_venta dv
        INNER JOIN articulo a on dv.idarticulo = a.idarticulo
        INNER JOIN venta v on v.idventa = dv.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        INNER JOIN detalle_documento_sucursal dds on v.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
		ORDER BY v.idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        
        return $resultSet;
    }

    public function reporte_detallado_comprobante($start_date, $end_date, $comprobante)
    {
        $query = $this->db()->query("SELECT dv.*,a.*,v.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, v.estado as estado_venta, pe.nombre as nombre_tercero, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, dv.cantidad as stock_cantidad, dv.importe_categoria as importe_articulo, dv.precio_venta as precio_unidad
        FROM detalle_venta dv
        INNER JOIN articulo a on dv.idarticulo = a.idarticulo
        INNER JOIN venta v on v.idventa = dv.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        INNER JOIN detalle_documento_sucursal dds on v.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "'
        and v.serie_comprobante = '$comprobante' and v.estado='A'
		ORDER BY v.fecha DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;
    }

    public function reporte_pendiente($start_date, $end_date)
    {
        $query = $this->db()->query("SELECT c.*,v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        v.subtotal_importe as impuesto
        FROM credito c
        INNER JOIN venta v on c.idventa = v.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
        and c.total_pago <> c.deuda_total
        ORDER BY v.idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet=[];
        }

        $query2 = $this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, cc.cc_fecha_cpte as fecha,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa and dcc.dcc_cod_art > 0) as sub_total,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.impuesto = 1 WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as impuesto,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.retencion = 1 WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as retencion,
        dds.ultima_serie as tipo_comprobante, cc.cc_id_transa as idventa
        FROM comprobante_contable cc
        INNER JOIN detalle_comprobante_contable dcc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art
        INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
        INNER JOIN usuario u on u.idusuario = cc.cc_idusuario
        INNER JOIN empleado em on em.idempleado = u.idempleado
        INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
        INNER JOIN persona pe on pe.idpersona = cc.cc_idproveedor
        INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
        INNER JOIN tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id
        INNER JOIN credito c on c.idventa = cc.cc_id_transa and c.contabilidad = 1
        WHERE su.idsucursal = '" . $_SESSION["idsucursal"] . "' AND cc.cc_fecha_cpte >= '$start_date' 
        AND cc.cc_fecha_cpte <= '$end_date' AND cc.cc_tipo_comprobante = 'V' AND cc.cc_estado = 'A' 
        AND fp.fp_nombre = 'Credito'
        AND c.total_pago <> c.deuda_total
        GROUP BY cc.cc_id_transa ORDER BY cc.cc_id_transa DESC");


        if ($query2->num_rows > 0) {
            while ($row = $query2->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
           
        }
        return $resultSet;

    }

    public function reporte_contado($start_date, $end_date)
    {
        $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.tipo_pago = 'Contado' and v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
        ORDER BY idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }

        $query2 = $this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, cc.cc_fecha_cpte as fecha,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa and dcc.dcc_cod_art > 0) as sub_total,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.impuesto = 1 WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as impuesto,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.retencion = 1 WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as retencion,
        dds.ultima_serie as tipo_comprobante, cc.cc_id_transa as idventa
        FROM comprobante_contable cc
        INNER JOIN detalle_comprobante_contable dcc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art
        INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
        INNER JOIN usuario u on u.idusuario = cc.cc_idusuario
        INNER JOIN empleado em on em.idempleado = u.idempleado
        INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
        INNER JOIN persona pe on pe.idpersona = cc.cc_idproveedor
        INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
        INNER JOIN tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id
        WHERE su.idsucursal = '" . $_SESSION["idsucursal"] . "' AND cc.cc_fecha_cpte >= '$start_date' 
        AND cc.cc_fecha_cpte <= '$end_date' AND cc.cc_tipo_comprobante = 'V' AND cc.cc_estado = 'A' 
        AND fp.fp_nombre = 'Contado'
        GROUP BY cc.cc_id_transa ORDER BY cc.cc_id_transa DESC");

        if ($query2->num_rows > 0) {
            while ($row = $query2->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {

        }

        return $resultSet;

    }

    public function reporte_credito($start_date, $end_date)
    {
        $query = $this->db()->query("SELECT c.*,v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado
        FROM credito c
        INNER JOIN venta v on c.idventa = v.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
        ORDER BY v.fecha ASC");
        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
        }

        $query2 = $this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, cc.cc_fecha_cpte as fecha,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa and dcc.dcc_cod_art > 0) as sub_total,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.impuesto = 1  and puc.movimiento = 1  WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as impuesto,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.retencion = 1 and puc.movimiento = 1 WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as retencion
        FROM comprobante_contable cc
        INNER JOIN detalle_comprobante_contable dcc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art
        INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
        INNER JOIN usuario u on u.idusuario = cc.cc_idusuario
        INNER JOIN empleado em on em.idempleado = u.idempleado
        INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
        INNER JOIN persona pe on pe.idpersona = cc.cc_idproveedor
        INNER JOIN tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id
        INNER JOIN credito c on c.idventa = cc.cc_id_transa and c.contabilidad = 1
        WHERE su.idsucursal = '" . $_SESSION["idsucursal"] . "' AND cc.cc_fecha_cpte >= '$start_date' 
        AND cc.cc_fecha_cpte <= '$end_date' AND cc.cc_tipo_comprobante = 'V' AND cc.cc_estado = 'A' 
        AND fp.fp_nombre = 'Credito'
        GROUP BY cc.cc_id_transa ORDER BY cc.cc_fecha_cpte ASC");

        if ($query2->num_rows > 0) {
            while ($row = $query2->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {

        }
        return $resultSet;

    }

    public function reporte_cliente($data)
    {
        $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, v.estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        v.subtotal_importe as impuesto
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        INNER JOIN detalle_documento_sucursal dds on v.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE pe.num_documento = '$data' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A' 
		ORDER BY v.fecha DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }

        $query2 = $this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.nombre as nombre_cliente, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, cc.cc_fecha_cpte as fecha,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa and dcc.dcc_cod_art > 0) as sub_total,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.impuesto = 1 WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as impuesto,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.retencion = 1 WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as retencion,
        dds.ultima_serie as tipo_comprobante, cc.cc_id_transa as idventa, fp.fp_nombre as tipo_pago 
        FROM comprobante_contable cc
        INNER JOIN detalle_comprobante_contable dcc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art
        INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
        INNER JOIN usuario u on u.idusuario = cc.cc_idusuario
        INNER JOIN empleado em on em.idempleado = u.idempleado
        INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
        INNER JOIN persona pe on pe.idpersona = cc.cc_idproveedor
        INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
        INNER JOIN tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id
        WHERE su.idsucursal = '" . $_SESSION["idsucursal"] . "' AND cc.cc_tipo_comprobante = 'V' AND cc.cc_estado = 'A' 
        AND pe.num_documento = '$data'
        GROUP BY cc.cc_id_transa ORDER BY cc.cc_id_transa DESC");

        if ($query2->num_rows > 0) {
            while ($row = $query2->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            
        }
        return $resultSet;
    }

    public function reporte_detallado_categoria($start_date, $end_date)
    {
        $query = $this->db()->query("SELECT dv.*,a.*,v.*,u.*,su.*,c.*, v.estado as estado_venta,
        a.nombre as nombre_articulo, dv.cantidad as stock_venta, dv.importe_categoria as importe_articulo, dv.precio_venta as precio_unidad, c.nombre as nombre_categoria,
        sum(dv.precio_venta*dv.cantidad) as precio_categoria, sum(dv.iva_compra) as precio_importe_categoria
        FROM detalle_venta dv
        INNER JOIN articulo a on dv.idarticulo = a.idarticulo
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        INNER JOIN venta v on v.idventa = dv.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        WHERE v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
        AND v.tipo_pago = 'Contado'
        GROUP BY c.idcategoria
		ORDER BY v.fecha DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;
    }

    public function reporte_detallado_categoria_by_user($start_venta, $end_venta)
    {
        $query = $this->db()->query("SELECT dv.*,a.*,v.*,u.*,su.*,c.*, v.estado as estado_venta,
        a.nombre as nombre_articulo, dv.cantidad as stock_venta, dv.importe_categoria as importe_articulo, dv.precio_venta as precio_unidad, c.nombre as nombre_categoria,
        sum(dv.precio_venta*dv.cantidad) as precio_categoria, sum(dv.iva_compra) as precio_importe_categoria
        FROM detalle_venta dv
        INNER JOIN articulo a on dv.idarticulo = a.idarticulo
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        INNER JOIN venta v on v.idventa = dv.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        WHERE v.idventa >= '$start_venta' and v.idventa <='$end_venta' and su.idsucursal= '" .$this->idsucursal. "' and
        v.idusuario = '".$this->idusuario."' and v.estado='A'
        AND v.tipo_pago = 'Contado'
        GROUP BY c.idcategoria
		ORDER BY v.fecha DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;
    }

    public function reporte_detallado_articulo($start_date, $end_date)
    {
        $query = $this->db()->query("SELECT dv.*,a.*,v.*,u.*,su.*,c.*, v.estado as estado_venta,
        a.nombre as nombre_articulo, dv.cantidad as stock_venta, dv.importe_categoria as importe_articulo, dv.precio_venta as precio_unidad, c.nombre as nombre_categoria,
        sum(dv.precio_venta*dv.cantidad) as precio_categoria, sum(dv.iva_compra) as precio_importe_categoria
        FROM detalle_venta dv
        INNER JOIN articulo a on dv.idarticulo = a.idarticulo
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        INNER JOIN venta v on v.idventa = dv.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        WHERE v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado='A'
        GROUP BY a.idarticulo
		ORDER BY v.fecha DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;
    }

    public function reporte_detallado_articulo_by_user($start_venta, $end_venta)
    {
        $query = $this->db()->query("SELECT dv.*,a.*,v.*,u.*,su.*,c.*, v.estado as estado_venta,
        a.nombre as nombre_articulo, dv.cantidad as stock_venta, dv.importe_categoria as importe_articulo, dv.precio_venta as precio_unidad, c.nombre as nombre_categoria,
        sum(dv.precio_venta*dv.cantidad) as precio_categoria, sum(dv.iva_compra) as precio_importe_categoria
        FROM detalle_venta dv
        INNER JOIN articulo a on dv.idarticulo = a.idarticulo
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        INNER JOIN venta v on v.idventa = dv.idventa
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        WHERE v.idventa >= '$start_venta' and v.idventa <='$end_venta' and su.idsucursal= '" . $this->idsucursal . "' 
        and v.idusuario = '".$this->idusuario."' and v.estado='A'
        GROUP BY a.idarticulo");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;
    }

    public function getDetalleMetodoPagoByVentas($start_venta,$end_venta)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $placeholder ='';
            if(isset($this->idusuario) && $this->idusuario !=null){
                $placeholder .="AND v.idusuario = '".$this->idusuario."' ";
            }
            $query=$this->db()->query("SELECT *, SUM(dmpg_monto) as total_metodo_pago 
            FROM tb_detalle_metodo_pago_general dmpg
            INNER JOIN tb_metodo_pago mp ON dmpg.dmpg_mp_id = mp.mp_id 
            INNER JOIN venta v ON v.idventa = dmpg.dmpg_registro_comprobante 
            WHERE dmpg.dmpg_detalle_registro = 'V' 
            AND v.idventa >= '$start_venta' and v.idventa <='$end_venta' and v.idsucursal= '" . $this->idsucursal . "' 
            and v.estado='A' $placeholder
            GROUP BY mp.mp_id
        ");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{return false;}
    }

    public function reporte_detallado_metodo_pago($start_date,$end_date)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $placeholder ='';
            if(isset($this->idusuario) && $this->idusuario !=null){
                $placeholder .="AND v.idusuario = '".$this->idusuario."' ";
            }
            $query=$this->db()->query("SELECT *, SUM(dmpg_monto) as total_metodo_pago 
            FROM tb_detalle_metodo_pago_general dmpg
            INNER JOIN tb_metodo_pago mp ON dmpg.dmpg_mp_id = mp.mp_id 
            INNER JOIN venta v ON v.idventa = dmpg.dmpg_registro_comprobante 
            WHERE dmpg.dmpg_detalle_registro = 'V' AND v.tipo_pago = 'Contado'
            AND v.fecha >= '$start_date' AND v.fecha <='$end_date' AND v.idsucursal= '" . $this->idsucursal . "' 
            AND v.estado='A' $placeholder
            GROUP BY mp.mp_id
        ");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{return false;}
    }

    public function getLastVentaByUser()
    {
        if (!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            $query = $this->db()->query("SELECT * FROM venta
            WHERE fecha = '" . $this->fecha . "'
            AND idusuario = '" . $this->idusuario . "'
            AND idsucursal ='" . $this->idsucursal . "'
            AND estado = '" . $this->estado . "'
            ORDER BY idventa DESC LIMIT 1");
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

    public function anularVentaById()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $placeholder = (isset($this->observaciones) && $this->observaciones != null)?", observaciones = '" . $this->observaciones ."'":"";
            $query = "UPDATE venta
                SET
                estado = '" . $this->estado . "'
                WHERE idventa = '$this->idventa'";
            $updateVenta = $this->db()->query($query);
            return $updateVenta;
        }else{
            return false;
        }
    }

    public function getVentasAnuladasByDay($start_date, $end_date)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
        $query = $this->db()->query("SELECT v.*,u.*,em.*,su.*,pe.*,td.*, v.estado as estado_venta, pe.nombre as nombre_tercero, em.nombre as nombre_empleado
        FROM venta v
        INNER JOIN usuario u on v.idusuario = u.idusuario
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on v.idsucursal = su.idsucursal
        INNER JOIN persona pe on v.idCliente = pe.idpersona
        INNER JOIN tipo_documento td on v.tipo_comprobante = td.nombre
        WHERE v.fecha >= '$start_date' and v.fecha <='$end_date' and su.idsucursal= '" . $_SESSION["idsucursal"] . "' and v.estado != 'A'
        ORDER BY idventa DESC");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;
    }
    }

}
