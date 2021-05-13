<?php
class DetalleIngresoContable extends EntidadBase{

    private $dic_id;
    private $dic_id_trans;
    private $dic_seq_detalle;
    private $dic_cta_item_det;
    private $dic_det_item_det;
    private $dic_cod_art; ## <--nuevo
    private $dic_cant_item_det;
    private $dic_ter_item_det;
    private $dic_ccos_item_det;
    private $dic_d_c_item_det;
    private $dic_valor_item;
    private $dic_base_imp_item;
    private $dic_base_ret_item;
    private $dic_fecha_vcto_item;
    private $dic_dato_fact_prove;

    public function __construct($adapter) {
        $table ="detalle_ingreso_contable";
        parent:: __construct($table, $adapter);
    }
    
    public function getDic_id()
    {
        return $this->dic_id;
    }
    public function setDic_id($dic_id)
    {
        $this->dic_id = $dic_id;
    }
    public function getDic_id_trans()
    {
        return $this->dic_id_trans;
    }
    public function setDic_id_trans($dic_id_trans)
    {
        $this->dic_id_trans = $dic_id_trans;
    }
    public function getDic_seq_detalle()
    {
        return $this->dic_seq_detalle;
    }
    public function setDic_seq_detalle($dic_seq_detalle)
    {
        $this->dic_seq_detalle = $dic_seq_detalle;
    }
    public function getDic_cta_item_det()
    {
        return $this->dic_cta_item_det;
    }
    public function setDic_cta_item_det($dic_cta_item_det)
    {
        $this->dic_cta_item_det = $dic_cta_item_det;
    }
    public function getDic_det_item_det()
    {
        return $this->dic_det_item_det;
    }
    public function setDic_det_item_det($dic_det_item_det)
    {
        $this->dic_det_item_det = $dic_det_item_det;
    }
    public function getDic_cant_item_det()
    {
        return $this->dic_cant_item_det;
    }
    public function setDic_cant_item_det($dic_cant_item_det)
    {
        $this->dic_cant_item_det = $dic_cant_item_det;
    }
    public function getDic_ter_item_det()
    {
        return $this->dic_ter_item_det;
    }
    public function setDic_ter_item_det($dic_ter_item_det)
    {
        $this->dic_ter_item_det = $dic_ter_item_det;
    }
    public function getDic_ccos_item_det()
    {
        return $this->dic_ccos_item_det;
    }
    public function setDic_ccos_item_det($dic_ccos_item_det)
    {
        $this->dic_ccos_item_det = $dic_ccos_item_det;
    }
    public function getDic_d_c_item_det()
    {
        return $this->dic_d_c_item_det;
    }
    public function setDic_d_c_item_det($dic_d_c_item_det)
    {
        $this->dic_d_c_item_det = $dic_d_c_item_det;
    }
    public function getDic_valor_item()
    {
        return $this->dic_valor_item;
    }
    public function setDic_valor_item($dic_valor_item)
    {
        $this->dic_valor_item = $dic_valor_item;
    }
    public function getDic_base_ret_item()
    {
        return $this->dic_base_ret_item;
    }
    public function setDic_base_ret_item($dic_base_ret_item)
    {
        $this->dic_base_ret_item = $dic_base_ret_item;
    }
    public function getDic_fecha_vcto_item()
    {
        return $this->dic_fecha_vcto_item;
    }
    public function setDic_fecha_vcto_item($dic_fecha_vcto_item)
    {
        $this->dic_fecha_vcto_item = $dic_fecha_vcto_item;
    }
    public function getDic_dato_fact_prove()
    {
        return $this->dic_dato_fact_prove;
    }
    public function setDic_dato_fact_prove($dic_dato_fact_prove)
    {
        $this->dic_dato_fact_prove = $dic_dato_fact_prove;
    }

    ##nuevo 

    public function getDic_cod_art()
    {
        return $this->dic_cod_art;
    }
    public function setDic_cod_art($dic_cod_art)
    {
        $this->dic_cod_art = $dic_cod_art;
    }

    public function getDic_base_imp_item()
    {
        return $this->dic_base_imp_item;
    } 
    public function setDic_base_imp_item($dic_base_imp_item)
    {
        $this->dic_base_imp_item = $dic_base_imp_item;
    }

    public function getArticulosByCompra($idcompra)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT * FROM detalle_ingreso_contable WHERE dic_cta_item_det > 0 AND dic_id_trans = '$idcompra'");
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

    public function getTotalByCompra($idcompra)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query= $this->db()->query("SELECT
                SUM(dic_valor_item*((dic_base_imp_item/100)+1)) as total, SUM(dic_valor_item) as subtotal, SUM(dic_valor_item*((dic_base_imp_item/100)+1)) as cdi_debito,SUM(dic_valor_item*((dic_base_imp_item/100)+1)) as cdi_credito 
                FROM detalle_ingreso_contable
                WHERE dic_id_trans = '$idcompra' AND dic_cod_art <> 0");
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
    public function getImpuestos($idcompra)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query=$this->db()->query("SELECT *, sum(dic_valor_item*((dic_base_imp_item/100)+1)) as cdi_credito, sum(dic_valor_item*((dic_base_imp_item/100)+1)) as cdi_debito,
            dic_base_imp_item as cdi_importe
            FROM detalle_ingreso_contable
            WHERE dic_id_trans = '$idcompra' AND dic_cod_art <> 0 GROUP BY dic_base_imp_item");
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

    public function addArticulos()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            //filtro para detectar si existe un articulo con las mismas caracteristicas (codigo contable, nombre y factura)
            $filter= $this->db()->query("SELECT * FROM detalle_ingreso_contable WHERE 
            dic_id_trans = '".$this->dic_id_trans."' AND 
            dic_cta_item_det = '".$this->dic_cta_item_det."' AND
            dic_det_item_det = '".$this->dic_det_item_det."'");

        if($filter->num_rows > 0){
            $query = "UPDATE detalle_ingreso_contable SET
            dic_valor_item = dic_valor_item +'".$this->dic_valor_item."',
            dic_cant_item_det = dic_cant_item_det + '".$this->dic_cant_item_det."',
            dic_base_ret_item = dic_base_ret_item +'".$this->dic_base_ret_item."'
            WHERE dic_id_trans = '".$this->dic_id_trans."' AND 
            dic_cta_item_det = '".$this->dic_cta_item_det."' AND
            dic_det_item_det = '".$this->dic_det_item_det."'";

            $addArticulo=$this->db()->query($query);
            return $addArticulo;
        }else{
            $query="INSERT INTO detalle_ingreso_contable (dic_id_trans, dic_seq_detalle, dic_cta_item_det,dic_det_item_det, dic_cod_art, dic_cant_item_det, dic_ter_item_det, dic_ccos_item_det, dic_d_c_item_det, dic_valor_item,dic_base_imp_item, dic_base_ret_item, dic_fecha_vcto_item, dic_dato_fact_prove)
            VALUES(
    	        '".$this->dic_id_trans."',
    	        '".$this->dic_seq_detalle."',
    	        '".$this->dic_cta_item_det."',
                '".$this->dic_det_item_det."',
                '".$this->dic_cod_art."',
                '".$this->dic_cant_item_det."',
    	        '".$this->dic_ter_item_det."',
    	        '".$this->dic_ccos_item_det."',
    	        '".$this->dic_d_c_item_det."',
    	        '".$this->dic_valor_item."',
                '".$this->dic_base_imp_item."',
    	        '".$this->dic_base_ret_item."',
    	        '".$this->dic_fecha_vcto_item."',
                '".$this->dic_dato_fact_prove."')";
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

    