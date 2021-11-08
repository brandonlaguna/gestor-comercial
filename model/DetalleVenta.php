<?php
class DetalleVenta extends EntidadBase{

    private $iddetalle_venta;
    private $idventa;
    private $idarticulo;
    private $cantidad;
    private $precio_venta;
    private $iva_compra;
    private $importe_categoria;
    private $precio_total_lote;
    private $estado;

    public function __construct($adapter) {
        $table ="detalle_venta";
        parent:: __construct($table, $adapter);
    }
    public function getIddetalle_venta()
    {
        return $this->iddetalle_venta;
    }
    public function setIddetalle_venta($iddetalle_venta)
    {
        $this->iddetalle_venta = $iddetalle_venta;
    }
    public function getIdventa()
    {
        return $this->idventa;
    }
    public function setIdventa($idventa)
    {
        $this->idventa = $idventa;
    }
    public function getIdarticulo()
    {
        return $this->idarticulo;
    }
    public function setIdarticulo($idarticulo)
    {
        $this->idarticulo = $idarticulo;
    }
    public function getCantidad()
    {
        return $this->cantidad;
    }
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }
    public function getPrecio_venta()
    {
        return $this->precio_venta;
    }
    public function setPrecio_venta($precio_venta)
    {
        $this->precio_venta = $precio_venta;
    }
    public function getIva_compra()
    {
        return $this->iva_compra;
    }
    public function setIva_compra($iva_compra)
    {
        $this->iva_compra = $iva_compra;
    }
    public function getImporte_categoria()
    {
        return $this->importe_categoria;
    }
    public function setImporte_categoria($importe_categoria)
    {
        $this->importe_categoria = $importe_categoria;
    }
    public function getPrecio_total_lote()
    {
        return $this->precio_total_lote;
    }
    public function setPrecio_total_lote($precio_total_lote)
    {
        $this->precio_total_lote = $precio_total_lote;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getDetalleAll()
    {
        $resultSet =[];
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("SELECT * FROM detalle_venta dv
            INNER JOIN venta v ON dv.idventa = v.idventa
            WHERE v.estestadoado = 'A' and v.idsucursal = '".$_SESSION['idsucursal']."'");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
            }

            $query2 = $this->db()->query("SELECT *, (dcc.dcc_cant_item_det) as cantidad, (dcc.dcc_valor_item+(dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100))) as precio_venta,
            cc.cc_fecha_cpte as fecha, dcc.dcc_cod_art as idarticulo, (dcc.dcc_valor_item+(dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100))) as precio_total_lote, (dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100)) as iva_compra
            FROM detalle_comprobante_contable dcc
            INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
            WHERE cc.cc_estado = 'A' and su.idsucursal = '".$_SESSION["idsucursal"]."' AND cc.cc_tipo_comprobante = 'V' GROUP BY dcc.dcc_id_trans ");
            if($query2->num_rows > 0){
                while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
            }
            return $resultSet;
        }else{
            return false;
        }
    }

    public function deleteDetalleVentaById($idventa)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $select_venta = $this->db()->query("SELECT idventa, idsucursal FROM venta WHERE idventa = '$idventa'");
            
            while ($venta = $select_venta->fetch_object()) {
                $idsucursal = $venta->idsucursal;
            }
            if(isset($idsucursal)){
                $select_items = $this->db()->query("SELECT * FROM detalle_venta WHERE idventa = '$idventa'");
                while ($item = $select_items->fetch_object()) {
                    $update_inventory = "UPDATE detalle_stock SET stock=stock+cantidad WHERE idarticulo = '$item->idarticulo' AND st_idsucursal = '$idsucursal'";
                }
                $query= $this->db()->query("DELETE FROM detalle_venta WHERE idventa = '$idventa'");
                return $query;
            }else{
                return false;
            }
        }
    }

    public function addArticulos()
    {
        $query ="INSERT INTO `detalle_venta` (idventa,idarticulo,cantidad,precio_venta,iva_compra,importe_categoria,precio_total_lote,estado)
            VALUES(
                '".$this->idventa."',
                '".$this->idarticulo."',
                '".$this->cantidad."',
                '".$this->precio_venta."',
                '".$this->iva_compra."',
                '".$this->importe_categoria."',
                '".$this->precio_total_lote."',
                '".$this->estado."')";
        $addArticulo=$this->db()->query($query);

       if($addArticulo){
           $status =true;
       }else{
            $status =false;
       }
       return $status;
    }

    public function updateArticulos($idventa,$idarticulo)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query ="UPDATE detalle_venta 
            SET
                cantidad= '".$this->cantidad."',
                precio_venta= '".$this->precio_venta."',
                iva_compra= '".$this->iva_compra."',
                importe_categoria = '".$this->importe_categoria."',
                precio_total_lote = '".$this->precio_total_lote."',
                estado = '".$this->estado."'
                WHERE idventa = '$idventa' AND idarticulo= '$idarticulo'";

            $updateArticulos=$this->db()->query($query);
            return $updateArticulos;
        }else{
            return false;
        }
    }

    public function getArticulosByVenta($idventa) 
    {
        $query=$this->db()->query("SELECT dv.*, a.*, c.*, um.*, c.nombre as nombre_categoria, um.nombre as unidad_medida, um.prefijo as prefijo_medida,
        a.nombre as nombre_articulo, dv.precio_venta as precio_unitario, dv.iva_compra as importe
        FROM detalle_venta dv
        INNER JOIN articulo a ON dv.idarticulo = a.idarticulo
        INNER JOIN unidad_medida um ON a.idunidad_medida = um.idunidad_medida
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        WHERE dv.idventa = '$idventa'"); 
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function totalVenta($idventa)
    {
        $query=$this->db()->query("SELECT sum(precio_total_lote) as total, sum(iva_compra) as iva_compra, sum(precio_venta* cantidad) as subtotal 
        FROM detalle_venta 
        WHERE idventa = '$idventa'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getSubTotal($idventa)
    {
        $query=$this->db()->query("SELECT  sum(precio_total_lote) as cdi_credito, sum(precio_total_lote) as cdi_debito
        FROM detalle_venta WHERE idventa = '$idventa'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getImpuestos($idventa)
    {
        $query=$this->db()->query("SELECT *, sum(precio_total_lote) as cdi_credito, sum(precio_total_lote) as cdi_debito,
        importe_categoria as cdi_importe
        FROM detalle_venta
        WHERE idventa = '$idventa'  GROUP BY importe_categoria");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function historyByClient($cliente)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT *, sum(dv.precio_total_lote) as total_venta, sum(dv.cantidad) as stock_venta,
            dv.precio_venta as venta_unitario, a.nombre as nombre_articulo
            FROM detalle_venta dv
            INNER JOIN venta v ON dv.idventa = v.idventa
            INNER JOIN articulo a ON dv.idarticulo = a.idarticulo
            INNER JOIN unidad_medida um ON a.idunidad_medida = um.idunidad_medida
            WHERE v.idCliente = '$cliente' GROUP BY dv.idarticulo"); 
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }

            return $resultSet;
        }else{
            return false;
        }
    }

    public function getBestSellArticulo($limit)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query = $this->db()->query("SELECT *, sum(dv.cantidad) as cantidad_venta
            FROM detalle_venta dv
            INNER JOIN articulo a on dv.idarticulo = a.idarticulo
            GROUP BY a.idarticulo ORDER BY dv.cantidad DESC LIMIT $limit");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
            }
            $query2 = $this->db()->query("SELECT *, sum(dcc.dcc_cant_item_det) as cantidad_venta, (dcc.dcc_valor_item+(dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100))) as precio_venta,
            cc.cc_fecha_cpte as fecha, dcc.dcc_cod_art as idarticulo, (dcc.dcc_valor_item+(dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100))) as precio_total_lote, (dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100)) as iva_compra
            FROM detalle_comprobante_contable dcc
            INNER JOIN articulo a on dcc.dcc_cod_art = a.idarticulo
            INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
            WHERE cc.cc_estado = 'A' and su.idsucursal = '".$_SESSION["idsucursal"]."' AND cc.cc_tipo_comprobante = 'V' GROUP BY dcc.dcc_cod_art ORDER BY dcc.dcc_cant_item_det DESC LIMIT $limit ");
            if($query2->num_rows > 0){
                while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
            }
            return $resultSet;
        }else{
            return false;
        }
    }

    public function getGraphicByCategiryAndTimeToday($day)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){

            $query = $this->db()->query("SELECT *, sum(dv.cantidad) as cantidad_venta, hour(v.date_created) as hora, c.nombre as nombre_categoria
            FROM detalle_venta dv
            INNER JOIN articulo a on dv.idarticulo = a.idarticulo
            INNER JOIN categoria c on a.idcategoria = c.idcategoria
            INNER JOIN venta v on dv.idventa = v.idventa
            WHERE v.fecha = '$day'
            GROUP BY hour(v.date_created), c.idcategoria ");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
            }
            // $query2 = $this->db()->query("SELECT *, sum(dcc.dcc_cant_item_det) as cantidad_venta, (dcc.dcc_valor_item+(dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100))) as precio_venta,
            // cc.cc_fecha_cpte as fecha, dcc.dcc_cod_art as idarticulo, (dcc.dcc_valor_item+(dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100))) as precio_total_lote, (dcc.dcc_valor_item*(dcc.dcc_base_imp_item/100)) as iva_compra
            // FROM detalle_comprobante_contable dcc
            // INNER JOIN articulo a on dcc.dcc_cod_art = a.idarticulo
            // INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            // INNER JOIN sucursal su on su.idsucursal = cc.cc_ccos_cpte
            // WHERE cc.cc_estado = 'A' and su.idsucursal = '".$_SESSION["idsucursal"]."' AND cc.cc_tipo_comprobante = 'V' GROUP BY dcc.dcc_cod_art ORDER BY dcc.dcc_cant_item_det DESC LIMIT $limit ");
            // if($query2->num_rows > 0){
            //     while ($row = $query2->fetch_object()) {
            //     $resultSet[]=$row;
            //     }
            // }else{
            // }
            return $resultSet;
        }else{
            return false;
        }
    }
    
    public function anularDetalleVenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query = "UPDATE detalle_venta 
            SET estado = '".$this->estado."'
            WHERE idventa = '".$this->idventa."'";
            $updateDetalleVenta = $this->db()->query($query);
            return $updateDetalleVenta;
        }else{
            return false;
        }
    }
}