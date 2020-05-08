<?php
class DetalleVentaContable extends EntidadBase{
 
    private $dvc_id;
    private $dvc_id_trans;
    private $dvc_seq_detalle;
    private $dvc_cta_item_det;
    private $dvc_det_item_det;
    private $dvc_cant_item_det;
    private $dvc_ter_item_det;
    private $dvc_ccos_item_det;
    private $dvc_d_c_item_det;
    private $dvc_valor_item;
    private $dvc_base_ret_item;
    private $dvc_fecha_vcto_item;
    private $dvc_dato_fact_prove;

    public function __construct($adapter) {
        $table ="detalle_venta_contable";
        parent:: __construct($table, $adapter);
    }

    public function getDvc_id()
    {
        return $this->dvc_id;
    }
    public function setDvc_id($dvc_id)
    {
        $this->dvc_id = $dvc_id;
    }
    public function getDvc_id_trans()
    {
        return $this->dvc_id_trans;
    }
    public function setDvc_id_trans($dvc_id_trans)
    {
        $this->dvc_id_trans = $dvc_id_trans;
    }
    public function getDvc_seq_detalle()
    {
        return $this->dvc_seq_detalle;
    }
    public function setDvc_seq_detalle($dvc_seq_detalle)
    {
        $this->dvc_seq_detalle = $dvc_seq_detalle;
    }
    public function getDvc_cta_item_det()
    {
        return $this->dvc_cta_item_det;
    }
    public function setDvc_cta_item_det($dvc_cta_item_det)
    {
        $this->dvc_cta_item_det = $dvc_cta_item_det;
    }
    public function getDvc_det_item_det()
    {
        return $this->dvc_det_item_det;
    }
    public function setDvc_det_item_det($dvc_det_item_det)
    {
        $this->dvc_det_item_det = $dvc_det_item_det;
    }
    public function getDvc_cant_item_det()
    {
        return $this->dvc_cant_item_det;
    }
    public function setDvc_cant_item_det($dvc_cant_item_det)
    {
        $this->dvc_cant_item_det = $dvc_cant_item_det;
    }
    public function getDvc_ter_item_det()
    {
        return $this->dvc_ter_item_det;
    }
    public function setDvc_ter_item_det($dvc_ter_item_det)
    {
        $this->dvc_ter_item_det = $dvc_ter_item_det;
    }
    public function getDvc_ccos_item_det()
    {
        return $this->dvc_ccos_item_det;
    }
    public function setDvc_ccos_item_det($dvc_ccos_item_det)
    {
        $this->dvc_ccos_item_det = $dvc_ccos_item_det;
    }
    public function getDvc_d_c_item_det()
    {
        return $this->dvc_d_c_item_det;
    }
    public function setDvc_d_c_item_det($dvc_d_c_item_det)
    {
        $this->dvc_d_c_item_det = $dvc_d_c_item_det;
    }
    public function getDvc_valor_item()
    {
        return $this->dvc_valor_item;
    }
    public function setDvc_valor_item($dvc_valor_item)
    {
        $this->dvc_valor_item = $dvc_valor_item;
    }
    public function getDvc_base_ret_item()
    {
        return $this->dvc_base_ret_item;
    }
    public function setDvc_base_ret_item($dvc_base_ret_item)
    {
        $this->dvc_base_ret_item = $dvc_base_ret_item;
    }
    public function getDvc_fecha_vcto_item()
    {
        return $this->dvc_fecha_vcto_item;
    }
    public function setDvc_fecha_vcto_item($dvc_fecha_vcto_item)
    {
        $this->dvc_fecha_vcto_item = $dvc_fecha_vcto_item;
    }
    public function getDvc_dato_fact_prove()
    {
        return $this->dvc_dato_fact_prove;
    }
    public function setDvc_dato_fact_prove($dvc_dato_fact_prove)
    {
        $this->dvc_dato_fact_prove = $dvc_dato_fact_prove;
    }

    public function getArticulosByCompra($idcompra)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT * FROM detalle_venta_contable WHERE dvc_cta_item_det > 0 AND dvc_id_trans = '$idcompra'");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

    public function addArticulos()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            //filtro para detectar si existe un articulo con las mismas caracteristicas (codigo contable, nombre y factura)
            $filter= $this->db()->query("SELECT * FROM detalle_venta_contable WHERE 
            dvc_id_trans = '".$this->dvc_id_trans."' AND 
            dvc_cta_item_det = '".$this->dvc_cta_item_det."' AND
            dvc_det_item_det = '".$this->dvc_det_item_det."'");

        if($filter->num_rows > 0){
            $query = "UPDATE detalle_venta_contable SET
            dvc_valor_item = dvc_valor_item +'".$this->dvc_valor_item."',
            dvc_cant_item_det = dvc_cant_item_det + '".$this->dvc_cant_item_det."',
            dvc_base_ret_item = dvc_base_ret_item +'".$this->dvc_base_ret_item."'
            WHERE dvc_id_trans = '".$this->dvc_id_trans."' AND 
            dvc_cta_item_det = '".$this->dvc_cta_item_det."' AND
            dvc_det_item_det = '".$this->dvc_det_item_det."'";

            $addArticulo=$this->db()->query($query);
            return $addArticulo;
        }else{
            $query="INSERT INTO detalle_venta_contable (dvc_id_trans, dvc_seq_detalle, dvc_cta_item_det,dvc_det_item_det,dvc_cant_item_det, dvc_ter_item_det, dvc_ccos_item_det, dvc_d_c_item_det, dvc_valor_item, dvc_base_ret_item, dvc_fecha_vcto_item, dvc_dato_fact_prove)
            VALUES(
    	        '".$this->dvc_id_trans."',
    	        '".$this->dvc_seq_detalle."',
    	        '".$this->dvc_cta_item_det."',
                '".$this->dvc_det_item_det."',
                '".$this->dvc_cant_item_det."',
    	        '".$this->dvc_ter_item_det."',
    	        '".$this->dvc_ccos_item_det."',
    	        '".$this->dvc_d_c_item_det."',
    	        '".$this->dvc_valor_item."',
    	        '".$this->dvc_base_ret_item."',
    	        '".$this->dvc_fecha_vcto_item."',
                '".$this->dvc_dato_fact_prove."')";
                $addArticulo=$this->db()->query($query);

                if($addArticulo){
                    $status =true;
                }else{
                     $status =false;
                }
                return $status;
        }
        
        }else{
            return false;
        }
    }
}