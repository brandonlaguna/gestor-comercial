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

    public function getCompraAll()
    {
        $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,td.*, i.estado as estado_ingreso, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado
        FROM ingreso i 
        INNER JOIN usuario u on i.idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.idsucursal = su.idsucursal
        INNER JOIN persona pe on i.idproveedor = pe.idpersona
        INNER JOIN tipo_documento td on i.tipo_comprobante = td.nombre 
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
        WHERE idingreso = '$id' "); 

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
        $query=$this->db()->query("SELECT di.*,a.*,ds.*, sum(stock_ingreso) as stock_compras, sum(precio_compra) as precio_compras
        FROM detalle_ingreso di
        INNER JOIN articulo a on di.idarticulo = a.idarticulo
        INNER JOIN detalle_stock ds on a.idarticulo = ds.idarticulo
        WHERE di.idarticulo = '$idarticulo'");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
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
        WHERE a.estado='A'
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
        $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,td.*, i.estado as estado_ingreso, pe.nombre as nombre_proveedor, em.nombre as nombre_empleado
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
        return $resultSet;

    }

    public function reporte_detallada_proveedor($proveedor,$start_date,$end_date)
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

