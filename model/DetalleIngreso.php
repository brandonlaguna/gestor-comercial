<?php
class DetalleIngreso extends EntidadBase{

    private $iddetalle_ingreso;
    private $idingreso;
    private $idarticulo;
    private $stock_ingreso;
    private $stock_actual;
    private $precio_compra;
    private $iva_compra;
    private $importe_categoria;
    private $precio_total_lote;
    private $precio_ventadistribuidor;
    private $precio_ventapublico;

    public function __construct($adapter) {
        $table ="detalle_ingreso";
        parent:: __construct($table, $adapter);
    }

    public function getIddetalle_ingreso()
    {
        return $this->iddetalle_ingreso;
    }
    public function setIddetalle_ingreso($iddetalle_ingreso)
    {
        $this->iddetalle_ingreso = $iddetalle_ingreso;
    }

    public function getIdingreso()
    {
        return $this->idingreso;
    }
    public function setIdingreso($idingreso)
    {
        $this->idingreso = $idingreso;
    }

    public function getIdarticulo()
    {
        return $this->idarticulo;
    }
    public function setIdarticulo($idarticulo)
    {
        $this->idarticulo = $idarticulo;
    }

    public function getStock_ingreso()
    {
        return $this->stock_ingreso;
    }
    public function setStock_ingreso($stock_ingreso)
    {
        $this->stock_ingreso = $stock_ingreso;
    }

    public function getStock_actual()
    {
        return $this->stock_actual;
    }
    public function setStock_actual($stock_actual)
    {
        $this->stock_actual = $stock_actual;
    }

    public function getPrecio_compra()
    {
        return $this->precio_compra;
    }
    public function setPrecio_compra($precio_compra)
    {
        $this->precio_compra = $precio_compra;
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

    public function getPrecio_ventadistribuidor()
    {
        return $this->precio_ventadistribuidor;
    }
    public function setPrecio_ventadistribuidor($precio_ventadistribuidor)
    {
        $this->precio_ventadistribuidor = $precio_ventadistribuidor;
    }

    public function getPrecio_ventapublico()
    {
        return $this->precio_ventapublico;
    }
    public function setPrecio_ventapublico($precio_ventapublico)
    {
        $this->precio_ventapublico = $precio_ventapublico;
    }

    public function deleteItemDetalle($iditem)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query= $this->db()->query("DELETE FROM detalle_ingreso WHERE iddetalle_ingreso = '$iditem'");
            return $query;
        }
    }

    public function addArticulos()
    {
        $query ="INSERT INTO `detalle_ingreso` (idingreso,idarticulo,stock_ingreso,stock_actual,precio_compra,iva_compra,importe_categoria,precio_total_lote,precio_ventadistribuidor,precio_ventapublico )
            VALUES(
                '".$this->idingreso."',
                '".$this->idarticulo."',
                '".$this->stock_ingreso."',
                '".$this->stock_actual."',
                '".$this->precio_compra."',
                '".$this->iva_compra."',
                '".$this->importe_categoria."',
                '".$this->precio_total_lote."',
                '".$this->precio_ventadistribuidor."',
                '".$this->precio_ventapublico."')";
        $addArticulo=$this->db()->query($query);

       if($addArticulo){
           $status =true;
       }else{
            $status =false;
       }
       return $status;
    }

    public function getArticulosByCompra($idcompra)
    {
        $query=$this->db()->query("SELECT di.*, a.*, c.*, um.*, c.nombre as nombre_categoria, um.nombre as unidad_medida, um.prefijo as prefijo_medida,
        a.nombre as nombre_articulo
        FROM detalle_ingreso di
        INNER JOIN articulo a ON di.idarticulo = a.idarticulo
        INNER JOIN unidad_medida um ON a.idunidad_medida = um.idunidad_medida
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        WHERE di.idingreso = '$idcompra'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }
    public function deleteDetalleIngresoById($idingreso)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query= $this->db()->query("DELETE FROM detalle_ingreso WHERE idingreso = '$idingreso'");
            return $query;
        }
    }

    public function totalIngreso($idingreso)
    {
        $query=$this->db()->query("SELECT sum(precio_total_lote) as total, sum(iva_compra) as iva_compra, sum(precio_compra* stock_ingreso) as subtotal 
        FROM detalle_ingreso 
        WHERE idingreso = '$idingreso'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    } 

    public function getSubTotal($idingreso)
    {
        $query=$this->db()->query("SELECT  sum(precio_total_lote) as cdi_credito, sum(precio_total_lote) as cdi_debito
        FROM detalle_ingreso WHERE idingreso = '$idingreso'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getImpuestos($idingreso)
    {
        $query=$this->db()->query("SELECT *, sum(precio_total_lote) as cdi_credito, sum(precio_total_lote) as cdi_debito,
        importe_categoria as cdi_importe
        FROM detalle_ingreso 
        WHERE idingreso = '$idingreso'  AND importe_categoria > 0 GROUP BY importe_categoria");
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