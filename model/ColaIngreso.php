<?php

class ColaIngreso Extends EntidadBase{

    private $cdi_id;
    private $cdi_idsucursal;
    private $cdi_tercero;
    private $cdi_idarticulo;
    private $cdi_stock_ingreso;
    private $cdi_precio_unitario;
    private $cdi_importe;
    private $cdi_im_id;
    private $cdi_precio_total_lote;
    private $cdi_credito;
    private $cdi_debito;
    private $cdi_cod_costos;
    private $cdi_type;

    public function __construct($adapter) {
        $table ="tb_cola_detalle_ingreso";
        parent:: __construct($table, $adapter);
    }

    public function getCdi_id()
    {
        return $this->cdi_id;
    }
    public function setCdi_id($cdi_id)
    {
        $this->cdi_id = $cdi_id;
    }
    public function getCdi_idsucursal()
    {
        return $this->cdi_idsucursal;
    }
    public function setCdi_idsucursal($cdi_idsucursal)
    {
        $this->cdi_idsucursal = $cdi_idsucursal;
    }
    public function getCdi_tercero()
    {
        return $this->cdi_tercero;
    }
    public function setCdi_tercero($cdi_tercero)
    {
        $this->cdi_tercero = $cdi_tercero;
    }
    public function getCdi_idarticulo()
    {
        return $this->cdi_idarticulo;
    }
    public function setCdi_idarticulo($cdi_idarticulo)
    {
        $this->cdi_idarticulo = $cdi_idarticulo;
    }
    public function getCdi_stock_ingreso()
    {
        return $this->cdi_stock_ingreso;
    }
    public function setCdi_stock_ingreso($cdi_stock_ingreso)
    {
        $this->cdi_stock_ingreso = $cdi_stock_ingreso;
    }
    public function getCdi_precio_unitario()
    {
        return $this->cdi_precio_unitario;
    }
    public function setCdi_precio_unitario($cdi_precio_unitario)
    {
        $this->cdi_precio_unitario = $cdi_precio_unitario;
    }
    public function getCdi_importe()
    {
        return $this->cdi_importe;
    }
    public function setCdi_importe($cdi_importe)
    {
        $this->cdi_importe = $cdi_importe;
    }
    public function getCdi_im_id()
    {
        return $this->cdi_im_id;
    } 
    public function setCdi_im_id($cdi_im_id)
    {
        $this->cdi_im_id = $cdi_im_id;
    }
    public function getCdi_precio_total_lote()
    {
        return $this->cdi_precio_total_lote;
    }
    public function setCdi_precio_total_lote($cdi_precio_total_lote)
    {
        $this->cdi_precio_total_lote = $cdi_precio_total_lote;
    }
    public function getCdi_credito()
    {
        return $this->cdi_credito;
    }
    public function setCdi_credito($cdi_credito)
    {
        $this->cdi_credito = $cdi_credito;
    } 
    public function getCdi_debito()
    {
        return $this->cdi_debito;
    }
    public function setCdi_debito($cdi_debito)
    {
        $this->cdi_debito = $cdi_debito;
    } 
    public function getCdi_cod_costos()
    {
        return $this->cdi_cod_costos;
    }
    public function setCdi_cod_costos($cdi_cod_costos)
    {
        $this->cdi_cod_costos = $cdi_cod_costos;
    }
    public function getcdi_type()
    {
        return $this->cdi_type;
    } 
    public function setcdi_type($cdi_type)
    {
        $this->cdi_type = $cdi_type;
    }

    public function addItemToCart()
    {
        $query ="INSERT INTO `tb_cola_detalle_ingreso` (cdi_idsucursal, cdi_tercero, cdi_idarticulo, cdi_stock_ingreso, cdi_precio_unitario, cdi_importe, cdi_im_id, cdi_precio_total_lote, cdi_credito, cdi_debito, cdi_cod_costos, cdi_type)
            VALUES(
                '".$this->cdi_idsucursal."',
                '".$this->cdi_tercero."',
                '".$this->cdi_idarticulo."',
                '".$this->cdi_stock_ingreso."',
                '".$this->cdi_precio_unitario."',
                '".$this->cdi_importe."',
                '".$this->cdi_im_id."',
                '".$this->cdi_precio_total_lote."',
                '".$this->cdi_credito."',
                '".$this->cdi_debito."',
                '".$this->cdi_cod_costos."',
                '".$this->cdi_type."')";
        $addItem=$this->db()->query($query);
        //
       if($addItem){
           $status =true;
       }else{
            $status =false;
       }
       return $status;
    }

    public function loadCart()
    {
        $resultSet=[];
        $queryarticulo=$this->db()->query("SELECT cdi.*, a.a_cod_contable as cod_contable, a.descripcion as descripcion 
        FROM tb_cola_detalle_ingreso cdi
        INNER JOIN articulo a ON cdi.cdi_idarticulo = a.idarticulo
        WHERE cdi.cdi_idsucursal = '".$_SESSION['idsucursal']."'");
        if($queryarticulo->num_rows > 0){
            while ($row = $queryarticulo->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{}
        $query2 =$this->db()->query("SELECT cdi.*, cc.idcodigo as cod_contable, cc.tipo_codigo as descripcion FROM tb_cola_detalle_ingreso cdi
        INNER JOIN codigo_contable cc on cdi.cdi_idarticulo = cc.idcodigo 
        WHERE cdi.cdi_idsucursal = '".$_SESSION['idsucursal']."'");
        if($query2->num_rows >0){
            while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
            }
        }else{

        }

        return $resultSet;
    }
    public function deleteCart()
    {
        $query = "DELETE FROM tb_cola_detalle_ingreso WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."'";
        $delete = $this->db()->query($query);
        if($delete){
          $status = array("status" => 1);
           
        }else{
            $status =  array("status" => 0);
        }
    }

    public function deleteItem($id)
    {
        $query = "DELETE FROM tb_cola_detalle_ingreso WHERE cdi_id = '$id' AND cdi_idsucursal = '".$_SESSION['idsucursal']."'";
        $delete = $this->db()->query($query);
        if($delete){
          $status = array("status" => 1);
           
        }else{
            $status =  array("status" => 0);
        }
    }

    public function getTotal()
    {
        $query=$this->db()->query("SELECT sum(cdi_credito) as cdi_credito, sum(cdi_debito) as cdi_debito 
        FROM tb_cola_detalle_ingreso WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getSubTotal()
    {//($imp->cdi_debito / (($imp->cdi_importe/100)+1))
        $query=$this->db()->query("SELECT sum(cdi_credito) as cdi_credito, sum(cdi_debito) as cdi_debito
        FROM tb_cola_detalle_ingreso WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi_type = 'AR'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getImpuestos()
    {
        $query=$this->db()->query("SELECT *, sum(cdi_credito) as cdi_credito, sum(cdi_debito) as cdi_debito 
        FROM tb_cola_detalle_ingreso 
        WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi_importe > 0 AND cdi_type = 'AR' GROUP BY cdi_importe");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }
    public function getCart()
    {
        $query=$this->db()->query("SELECT * FROM tb_cola_detalle_ingreso 
        WHERE cdi_idsucursal = '".$_SESSION["idsucursal"]."' AND cdi_type = 'AR'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }
    public function getAllCart()
    {
        $query=$this->db()->query("SELECT * FROM tb_cola_detalle_ingreso 
        WHERE cdi_idsucursal = '".$_SESSION["idsucursal"]."'");
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