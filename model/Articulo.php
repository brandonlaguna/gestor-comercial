<?php

class Articulo Extends EntidadBase{

    private $idarticulo;
    private $idcategoria;
    private $a_cod_contable;
    private $idunidad_medida;
    private $nombre;
    private $descripcion;
    private $imagen;
    private $estado;
    private $costo_producto;
    private $ar_iva_compra;
    private $ar_iva_venta;
    private $precio_venta;
    private $stock;
    
    public function __construct($adapter) {
        $table ="articulo";
        parent:: __construct($table, $adapter);
    }
    public function getIdarticulo()
    {
        return $this->idarticulo;
    }
    public function setIdarticulo($idarticulo)
    {
        $this->idarticulo = $idarticulo;
    }
    public function getIdcategoria()
    {
        return $this->idcategoria;
    }
    public function setIdcategoria($idcategoria)
    {
        $this->idcategoria = $idcategoria;
    }
    public function getA_cod_contable()
    {
        return $this->a_cod_contable;
    }
    public function setA_cod_contable($a_cod_contable)
    {
        $this->a_cod_contable = $a_cod_contable;
    }
    public function getIdunidad_medida()
    {
        return $this->idunidad_medida;
    }
    public function setIdunidad_medida($idunidad_medida)
    {
        $this->idunidad_medida = $idunidad_medida;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function getImagen()
    {
        return $this->imagen;
    }
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function getCosto_producto()
    {
        return $this->costo_producto;
    }
    public function setCosto_producto($costo_producto)
    {
        $this->costo_producto = $costo_producto;
    }
    public function getAr_iva_compra()
    {
        return $this->ar_iva_compra;
    }
    public function setAr_iva_compra($ar_iva_compra)
    {
        $this->ar_iva_compra = $ar_iva_compra;
    }
    public function getAr_iva_venta()
    {
        return $this->ar_iva_venta;
    }
    public function setAr_iva_venta($ar_iva_venta)
    {
        $this->ar_iva_venta = $ar_iva_venta;
    }
    public function getPrecio_venta()
    {
        return $this->precio_venta;
    }
    public function setPrecio_venta($precio_venta)
    {
        $this->precio_venta = $precio_venta;
    }
    public function getStock()
    {
        return $this->stock;
    }
    public function setStock($stock)
    {
        $this->stock = $stock;
    } 
    public function deleteArticulo($idarticulo)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $query = $this->db()->query("UPDATE detalle_stock SET  
            detalle_stock.stock = 0,
            detalle_stock.st_estado = 'D'
            WHERE idarticulo = '$idarticulo'
            AND st_idsucursal = '".$_SESSION["idsucursal"]."'");
            return $query;
        }else{
            return false;
        }
    }

    public function getArticuloAll()
    {
        $query=$this->db()->query("SELECT a.*,c.*,um.*,ds.*, a.nombre as nombre_articulo, a.descripcion as descripcion_articulo, c.nombre as nombre_categoria,
        um.nombre as nombre_medida,a.estado as estado_articulo
        FROM articulo a
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        INNER JOIN unidad_medida um on a.idunidad_medida = um.idunidad_medida
        INNER JOIN detalle_stock ds on ds.idarticulo = a.idarticulo
        WHERE ds.st_idsucursal = '".$_SESSION["idsucursal"]."' ORDER BY a.idarticulo ASC ");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }
    
    public function getArticulo($value)
    {
        $query=$this->db()->query("SELECT * FROM articulo WHERE estado = 'A' ");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getArticuloBy($value)
    {
        $query=$this->db()->query("SELECT ar.*, c.nombre as nombre_categoria, c.imp_compra, c.imp_venta, c.cod_costos as cod_costos,
        ar.nombre as nombre_articulo, ar.descripcion as descripcion_articulo, sum(ar.costo_producto *((c.imp_compra/100)+1)) as total_compra, sum(ar.costo_producto * 1) as sub_total_compra,
        sum(ar.precio_venta *((c.imp_venta/100)+1)) as total_venta, sum(ar.precio_venta * 1) as sub_total_venta,
        ar.idarticulo as iditem
        FROM articulo ar
        INNER JOIN categoria c on ar.idcategoria = c.idcategoria
        INNER JOIN detalle_stock st on ar.idarticulo = st.idarticulo and st.st_idsucursal = '".$_SESSION["idsucursal"]."'
        WHERE st.st_idsucursal = '".$_SESSION["idsucursal"]."' AND ar.estado = 'A' AND ar.idarticulo = '$value' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getArticuloByBarcode($value)
    {
        $query=$this->db()->query("SELECT ar.*, c.nombre as nombre_categoria, c.imp_compra, c.imp_venta, c.cod_costos as cod_costos,
        ar.nombre as nombre_articulo, ar.descripcion as descripcion_articulo, sum(ar.costo_producto *((c.imp_compra/100)+1)) as total_compra,sum(ar.costo_producto * 1) as sub_total_compra,
        sum(ar.precio_venta *((c.imp_venta/100)+1)) as total_venta,sum(ar.precio_venta * 1) as sub_total_venta,
        ar.idarticulo as iditem
        FROM articulo ar
        INNER JOIN categoria c on ar.idcategoria = c.idcategoria
        INNER JOIN detalle_stock st on ar.idarticulo = st.idarticulo and st.st_idsucursal = '".$_SESSION["idsucursal"]."'
        INNER JOIN tb_codigo_barras cb on ar.idarticulo = cb.cb_idarticulo 
        WHERE st.st_idsucursal = '".$_SESSION["idsucursal"]."' AND ar.estado = 'A' AND cb.cb_barcode = '$value' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getArticuloById($id)
    {
        $query=$this->db()->query("SELECT a.*, c.*, um.*,st.*, c.nombre as nombre_categoria, a.nombre as nombre_articulo, um.nombre as nombre_unidad_medida 
        FROM articulo a
        INNER JOIN detalle_stock st on a.idarticulo = st.idarticulo
        INNER JOIN categoria c on a.idcategoria = c.idcategoria
        INNER JOIN unidad_medida um on a.idunidad_medida = um.idunidad_medida
        WHERE st.st_idsucursal = '".$_SESSION["idsucursal"]."' AND a.idarticulo = '$id'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }
    public function addCantStock($idarticulo,$cantidad)
    {
        $query ="UPDATE detalle_stock SET 
        `stock` = stock + $cantidad
        WHERE idarticulo = '$idarticulo' AND st_idsucursal = '".$_SESSION["idsucursal"]."'";
        $update_cuenta = $this->db()->query($query);
        return $update_cuenta;
    }

    public function removeCantStock($idarticulo,$cantidad)
    {
        $query ="UPDATE detalle_stock SET 
        stock = stock - $cantidad
        WHERE idarticulo = '$idarticulo' AND st_idsucursal = '".$_SESSION["idsucursal"]."'";
        $update_cuenta = $this->db()->query($query);
        return $update_cuenta;
    }

    public function addArticulo()
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query ="INSERT INTO articulo (idcategoria,idunidad_medida,nombre,descripcion,imagen,estado,costo_producto,ar_iva_compra,ar_iva_venta,precio_venta)
            VALUES(
                '".$this->idcategoria."',
                '".$this->idunidad_medida."',
                '".$this->nombre."',
                '".$this->descripcion."',
                '".$this->imagen."',
                '".$this->estado."',
                '".$this->costo_producto."',
                '".$this->ar_iva_compra."',
                '".$this->ar_iva_venta."',
                '".$this->precio_venta."'
            )";
        $addArticulo=$this->db()->query($query);

        $returnId=$this->db()->query("SELECT idarticulo FROM articulo ORDER BY idarticulo DESC LIMIT 1");
        if($returnId->num_rows > 0){
            while($row = $returnId->fetch_assoc()) {
                $idarticulo= $row["idarticulo"];
            }
        }

        $stock = "INSERT INTO detalle_stock (idarticulo,stock,st_idsucursal)VALUES('$idarticulo','0','".$_SESSION["idsucursal"]."')";
        $addStock=$this->db()->query($stock);

        if($addArticulo){
            $status = "Agregado";
        }else{
             $status ="Error al agregar";
        }
        return $status;

        }
    }

    public function updateArticulo($idarticulo)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query = "UPDATE articulo SET 
                idcategoria  = '".$this->idcategoria."',
                idunidad_medida  = '".$this->idunidad_medida."',
                nombre  = '".$this->nombre."',
                descripcion  = '".$this->descripcion."',
                imagen  = '".$this->imagen."',
                estado  = '".$this->estado."',
                costo_producto  = '".$this->costo_producto."',
                ar_iva_compra  = '".$this->ar_iva_compra."',
                ar_iva_venta  = '".$this->ar_iva_venta."',
                precio_venta  = '".$this->precio_venta."'
                WHERE idarticulo = '$idarticulo'
                ";
            $updateArticulo=$this->db()->query($query);

            if($updateArticulo){
                if($_SESSION["permission"] > 4){
                $stock = "UPDATE detalle_stock SET stock = '".$this->stock."' WHERE idarticulo = '$idarticulo' AND st_idsucursal = '".$_SESSION["idsucursal"]."'";
                $this->db()->query($stock);
                }else{}
            }else{
                
            }
            return $updateArticulo;

        }else{
            echo "No tienes permisos";
        }
    }

    

    
    
}