<?php

class Compras Extends EntidadBase{

    private $idingreso;
    private $idusuario;
    private $idsucursal;
    private $idproveedor;
    private $tipo_pago;
    private $tipo_comprobante;
    private $serie;
    private $num_comprobante;
    private $fecha;
    private $fecha_final;
    private $impuesto;
    private $sub_total;
    private $subtotal_importe;
    private $total;
    private $importe_pagado;
    private $estado;
    
    public function __construct($adapter) {
        $table ="ingreso";
        parent:: __construct($table, $adapter);
    }

    public function getIdingreso()
    {
        return $this->idingreso;
    }
    public function setIdingreso($idingreso)
    {
        $this->idingreso = $idingreso;
    }
    public function getIdusuario()
    {
        return $this->idusuario;
    }
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }
    public function getIdsucursal()
    {
        return $this->idsucursal;
    }
    public function setIdsucursal($idsucursal)
    {
        $this->idsucursal = $idsucursal;
    }
    public function getIdproveedor()
    {
        return $this->idproveedor;
    }
    public function setIdproveedor($idproveedor)
    {
        $this->idproveedor = $idproveedor;
    }
    public function getTipo_pago()
    {
        return $this->tipo_pago;
    }
    public function setTipo_pago($tipo_pago)
    {
        $this->tipo_pago = $tipo_pago;
    }
    public function getTipo_comprobante()
    {
        return $this->tipo_comprobante;
    }
    public function setTipo_comprobante($tipo_comprobante)
    {
        $this->tipo_comprobante = $tipo_comprobante;
    }
    public function getSerie()
    {
        return $this->serie;
    }
    public function setSerie($serie)
    {
        $this->serie = $serie;
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
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function delete_compra($idcompra)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $query = $this->db()->query("UPDATE ingreso SET estado = 'C' WHERE idingreso = '$idcompra'");
            return $query;
        }else{
            return false;
        }
    }

    public function getComprasByDay($start_date,$end_date,$column,$value)
    {
        if(!empty($_SESSION["idsucursal"]) &&!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
            $ingreso =$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,td.*, i.estado as estado_ingreso, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado
            FROM ingreso i 
            INNER JOIN usuario u on i.idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN sucursal su on i.idsucursal = su.idsucursal
            INNER JOIN persona pe on i.idproveedor = pe.idpersona
            INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
            WHERE su.idsucursal = '".$_SESSION["idsucursal"]."' AND i.fecha >= '$start_date' AND i.fecha <= '$end_date' AND $column = '$value' GROUP BY i.fecha ORDER BY i.fecha DESC");
            if($ingreso->num_rows > 0){
                while ($row = $ingreso->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{$resultSet=[];}

            return $resultSet;
        }else{}
    }

    public function getDetalleComprasByDay($start_date,$end_date,$column,$value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
        $query=$this->db()->query("SELECT *, i.estado as estado_venta, pe.nombre as nombre_proveedor, pe.num_documento as documento_tercero,
        a.nombre as nombre_articulo, di.stock_ingreso as stock_ingreso, di.importe_categoria as importe_articulo, (di.stock_ingreso) as stock_total_compras, (di.stock_ingreso) as stock_total, (di.precio_total_lote) as precio_total_compras
        FROM detalle_ingreso di
        INNER JOIN articulo a on di.idarticulo = a.idarticulo
        INNER JOIN ingreso i on i.idingreso = di.idingreso
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        WHERE i.idsucursal = '".$_SESSION["idsucursal"]."' AND i.estado='A' AND i.fecha >= '$start_date' AND i.fecha <= '$end_date' AND $column = '$value' ORDER BY i.fecha DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        $query2 =$this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.nombre as nombre_proveedor,
        a.nombre as nombre_articulo, dcc.dcc_cant_item_det as stock_ingreso, dcc.dcc_base_imp_item as importe_articulo, (dcc.dcc_cant_item_det) as stock_total_compras, (dcc.dcc_cant_item_det) as stock_total, 
        (dcc.dcc_valor_item+(dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100))) as precio_total_compras, cc.cc_fecha_cpte as fecha,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante, cc.cc_nit_cpte as documento_tercero
        FROM detalle_comprobante_contable dcc
        INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art 
        INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
        WHERE cc.cc_ccos_cpte = '".$_SESSION["idsucursal"]."' AND cc.cc_fecha_cpte >= '$start_date' AND cc.cc_fecha_cpte <= '$end_date' AND dcc.dcc_cod_art = '$value' AND cc.cc_tipo_comprobante = 'I' ORDER BY cc.cc_fecha_cpte DESC");
        
        if($query2->num_rows > 0){
            while ($row = $query2->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }

        return $resultSet;
        }
    }



    public function getCompraAll()
    {
        $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,td.*, i.estado as estado_ingreso, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado
        FROM ingreso i 
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        WHERE su.idsucursal = '".$_SESSION["idsucursal"]."' ORDER BY idingreso DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }

    public function getCompraById($id) 
    {
        $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*,fp.*, i.estado as estado_ingreso, pe.nombre as nombre_proveedor,pe.nombre as nombre_proveedor,
        pe.telefono as telefono_proveedor
        FROM ingreso i 
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        INNER JOIN detalle_documento_sucursal dds on i.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        INNER JOIN tb_forma_pago fp on i.tipo_pago = fp.fp_nombre and fp_proceso = 'Ingreso'
        WHERE idingreso = '$id' AND su.idsucursal = '".$_SESSION["idsucursal"]."'"); 

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getCompraDetallada()
    {
        $query=$this->db()->query("SELECT di.*,a.*,ds.*,i.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, i.estado as estado_venta, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, di.stock_ingreso as stock_ingreso, di.importe_categoria as importe_articulo
        FROM detalle_ingreso di
        INNER JOIN articulo a on di.idarticulo = a.idarticulo
        INNER JOIN detalle_stock ds on a.idarticulo = ds.idarticulo
        INNER JOIN ingreso i on i.idingreso = di.idingreso
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        INNER JOIN detalle_documento_sucursal dds on i.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE su.idsucursal= '".$_SESSION["idsucursal"]."' and i.estado='A'
		ORDER BY i.idingreso DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getCompraByArticulo($idarticulo)
    {
        $query=$this->db()->query("SELECT di.*,a.*,i.*, di.stock_ingreso as stock_compras, di.precio_total_lote as precio_compras
        FROM detalle_ingreso di
        INNER JOIN articulo a on di.idarticulo = a.idarticulo
        INNER JOIN ingreso i on i.idingreso = di.idingreso
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        WHERE di.idarticulo = '$idarticulo' AND i.idsucursal = '".$_SESSION["idsucursal"]."'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet[] = [];
        }
        $query2=$this->db()->query("SELECT dcc.*,a.*,cc.*, (dcc.dcc_cant_item_det) as stock_compras, dcc.dcc_valor_item as precio_compras, cc.cc_fecha_cpte as fecha
        FROM detalle_comprobante_contable dcc
        INNER JOIN articulo a on a.idarticulo = dcc.dcc_cod_art 
        INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
        INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
        INNER JOIN persona pe on pe.idpersona = cc.cc_idproveedor
        WHERE dcc.dcc_cod_art = '$idarticulo' AND cc.cc_ccos_cpte = '".$_SESSION["idsucursal"]."' AND cc.cc_tipo_comprobante = 'I'");
        //
        if($query2->num_rows > 0){
            while ($row = $query2->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet[] = [];
        }
        return $resultSet;
    }

    public function getCompraUtilidad()
    {
        $query=$this->db()->query("SELECT a.*,c.*,ds.*, (ds.stock* a.costo_producto) as subtotal, 
        (ds.stock* a.costo_producto * (c.imp_venta/100)+1) as iva,
        a.nombre as nombre_articulo
        FROM articulo a 
        INNER JOIN detalle_stock ds on a.idarticulo = ds.idarticulo
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        WHERE a.estado='A' and ds.st_idsucursal ='".$_SESSION["idsucursal"]."'
		ORDER BY a.idarticulo DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function reporte_general($start_date,$end_date)
    {
        $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,td.*, i.estado as estado_ingreso, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado,
        i.subtotal_importe as impuesto
        FROM ingreso i 
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        WHERE i.fecha >= '$start_date' and i.fecha <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."' and i.estado='A'
        ORDER BY idingreso DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }

        $query2 = $this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.nombre as nombre_proveedor , em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, cc.cc_fecha_cpte as fecha,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa and dcc.dcc_cod_art > 0) as sub_total,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.impuesto = 1 WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as impuesto,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.retencion = 1 WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as retencion,
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
        WHERE su.idsucursal = '" . $_SESSION["idsucursal"] . "' AND cc.cc_fecha_cpte >= '$start_date' AND cc.cc_fecha_cpte <= '$end_date' AND cc.cc_tipo_comprobante = 'I' AND cc.cc_estado = 'A' GROUP BY cc.cc_id_transa ORDER BY cc.cc_id_transa DESC");

        if ($query2->num_rows > 0) {
            while ($row = $query2->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            
        }

        return $resultSet;

    }
    public function reporte_general_comprobante($start_date,$end_date,$comprobante)
    {
        $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,td.*, i.estado as estado_ingreso, pe.nombre as nombre_tercero, em.nombre as nombre_empleado
        FROM ingreso i 
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        WHERE i.fecha >= '$start_date' and i.fecha <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."' 
        and i.serie_comprobante = '$comprobante' and i.estado='A'
        ORDER BY idingreso DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }

    public function reporte_detallada($start_date,$end_date)
    {
        $query=$this->db()->query("SELECT *, i.estado as estado_venta, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, di.stock_ingreso as stock_ingreso, di.importe_categoria as impuesto
        FROM detalle_ingreso di
        INNER JOIN articulo a on di.idarticulo = a.idarticulo
        INNER JOIN detalle_stock ds on a.idarticulo = ds.idarticulo
        INNER JOIN ingreso i on i.idingreso = di.idingreso
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        INNER JOIN detalle_documento_sucursal dds on i.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE i.fecha >= '$start_date' and i.fecha <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."' and i.estado='A'
		ORDER BY i.idingreso DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function reporte_detallada_comprobante($start_date,$end_date,$comprobante)
    {
        $query=$this->db()->query("SELECT di.*,a.*,ds.*,i.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, i.estado as estado_venta, pe.nombre as nombre_tercero, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, di.stock_ingreso as stock_cantidad, di.importe_categoria as importe_articulo, di.precio_compra as precio_unidad
        FROM detalle_ingreso di
        INNER JOIN articulo a on di.idarticulo = a.idarticulo
        INNER JOIN detalle_stock ds on a.idarticulo = ds.idarticulo
        INNER JOIN ingreso i on i.idingreso = di.idingreso
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        INNER JOIN detalle_documento_sucursal dds on i.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE i.fecha >= '$start_date' and i.fecha <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."'
        and i.serie_comprobante = '$comprobante' and i.estado='A'
		ORDER BY i.idingreso DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function reporte_general_proveedor($proveedor,$start_date,$end_date)
    {
        $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,td.*, i.estado as estado_ingreso, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado
        FROM ingreso i 
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        WHERE i.fecha >= '$start_date' and i.fecha <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."' and i.estado='A'
        and pe.num_documento = '$proveedor'
        ORDER BY idingreso DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }

        $query2 = $this->db()->query("SELECT *, cc.cc_estado as estado_venta, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, cc.cc_fecha_cpte as fecha,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa and dcc.dcc_cod_art > 0) as sub_total,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.impuesto = 1 WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as impuesto,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo and puc.retencion = 1 WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as retencion,
        dds.ultima_serie as tipo_comprobante, cc.cc_id_transa as idingreso, fp.fp_nombre as tipo_pago 
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
        WHERE su.idsucursal = '" . $_SESSION["idsucursal"] . "' AND cc.cc_tipo_comprobante = 'I' AND cc.cc_estado = 'A' 
        AND pe.num_documento = '$proveedor'
        GROUP BY cc.cc_id_transa ORDER BY cc.cc_id_transa DESC");

        if ($query2->num_rows > 0) {
            while ($row = $query2->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = [];
        }
        return $resultSet;

    }

    public function reporte_detallada_proveedor($proveedor,$start_date,$end_date)
    {
        $query=$this->db()->query("SELECT *, i.estado as estado_venta, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado,
        a.nombre as nombre_articulo, di.stock_ingreso as stock_ingreso, di.importe_categoria as impuesto
        FROM detalle_ingreso di
        INNER JOIN articulo a on di.idarticulo = a.idarticulo
        INNER JOIN detalle_stock ds on a.idarticulo = ds.idarticulo
        INNER JOIN ingreso i on i.idingreso = di.idingreso
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
        INNER JOIN detalle_documento_sucursal dds on i.serie_comprobante = dds.ultima_serie
        INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
        WHERE i.fecha >= '$start_date' and i.fecha <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."' and i.estado='A'
        and pe.num_documento = '$proveedor'
		ORDER BY i.idingreso DESC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    
}

