<?php
class DetalleComprobanteContable extends EntidadBase{

    private $dcc_id;
    private $dcc_id_trans;
    private $dcc_seq_detalle;
    private $dcc_cta_item_det;
    private $dcc_det_item_det;
    private $dcc_cod_art;
    private $dcc_cant_item_det;
    private $dcc_ter_item_det;
    private $dcc_ccos_item_det;
    private $dcc_d_c_item_det;
    private $dcc_valor_item;
    private $dcc_base_imp_item;
    private $dcc_base_ret_item;
    private $dcc_fecha_vcto_item;
    private $dcc_dato_fact_prove;

    public function __construct($adapter) {
        $table ="detalle_comprobante_contable";
        parent:: __construct($table, $adapter);
    }
    public function getDcc_id()
    {
        return $this->dcc_id;
    }
    public function setDcc_id($dcc_id)
    {
        $this->dcc_id = $dcc_id;
    }
    public function getDcc_id_trans()
    {
        return $this->dcc_id_trans;
    }
    public function setDcc_id_trans($dcc_id_trans)
    {
        $this->dcc_id_trans = $dcc_id_trans;
    }
    public function getDcc_seq_detalle()
    {
        return $this->dcc_seq_detalle;
    }
    public function setDcc_seq_detalle($dcc_seq_detalle)
    {
        $this->dcc_seq_detalle = $dcc_seq_detalle;
    }
    public function getDcc_cta_item_det()
    {
        return $this->dcc_cta_item_det;
    }
    public function setDcc_cta_item_det($dcc_cta_item_det)
    {
        $this->dcc_cta_item_det = $dcc_cta_item_det;
    }
    public function getDcc_det_item_det()
    {
        return $this->dcc_det_item_det;
    }
    public function setDcc_det_item_det($dcc_det_item_det)
    {
        $this->dcc_det_item_det = $dcc_det_item_det;
    }
    public function getDcc_cod_art()
    {
        return $this->dcc_cod_art;
    }
    public function setDcc_cod_art($dcc_cod_art)
    {
        $this->dcc_cod_art = $dcc_cod_art;
    }
    public function getDcc_cant_item_det()
    {
        return $this->dcc_cant_item_det;
    }
    public function setDcc_cant_item_det($dcc_cant_item_det)
    {
        $this->dcc_cant_item_det = $dcc_cant_item_det;
    }
    public function getDcc_ter_item_det()
    {
        return $this->dcc_ter_item_det;
    }
    public function setDcc_ter_item_det($dcc_ter_item_det)
    {
        $this->dcc_ter_item_det = $dcc_ter_item_det;
    }
    public function getDcc_ccos_item_det()
    {
        return $this->dcc_ccos_item_det;
    }
    public function setDcc_ccos_item_det($dcc_ccos_item_det)
    {
        $this->dcc_ccos_item_det = $dcc_ccos_item_det;
    }
    public function getDcc_d_c_item_det()
    {
        return $this->dcc_d_c_item_det;
    }
    public function setDcc_d_c_item_det($dcc_d_c_item_det)
    {
        $this->dcc_d_c_item_det = $dcc_d_c_item_det;
    }
    public function getDcc_valor_item()
    {
        return $this->dcc_valor_item;
    }
    public function setDcc_valor_item($dcc_valor_item)
    {
        $this->dcc_valor_item = $dcc_valor_item;
    }
    public function getDcc_base_imp_item()
    {
        return $this->dcc_base_imp_item;
    }
    public function setDcc_base_imp_item($dcc_base_imp_item)
    {
        $this->dcc_base_imp_item = $dcc_base_imp_item;
    }
    public function getDcc_base_ret_item()
    {
        return $this->dcc_base_ret_item;
    }
    public function setDcc_base_ret_item($dcc_base_ret_item)
    {
        $this->dcc_base_ret_item = $dcc_base_ret_item;
    }
    public function getDcc_fecha_vcto_item()
    {
        return $this->dcc_fecha_vcto_item;
    }
    public function setDcc_fecha_vcto_item($dcc_fecha_vcto_item)
    {
        $this->dcc_fecha_vcto_item = $dcc_fecha_vcto_item;
    }
    public function getDcc_dato_fact_prove()
    {
        return $this->dcc_dato_fact_prove;
    }
    public function setDcc_dato_fact_prove($dcc_dato_fact_prove)
    {
        $this->dcc_dato_fact_prove = $dcc_dato_fact_prove;
    }

    public function getArticulosByComprobante($idcomprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT * FROM detalle_comprobante_contable WHERE dcc_cta_item_det > 0 AND dcc_id_trans = '$idcomprobante'");
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

    public function getTotalByComprobante($idcomprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query= $this->db()->query("SELECT
                SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) as total, SUM(dcc_valor_item) as subtotal, SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) as cdi_debito,SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) as cdi_credito 
                FROM detalle_comprobante_contable
                WHERE dcc_id_trans = '$idcomprobante' AND dcc_cod_art <> 0");
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
    public function getImpuestos($idcomprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query=$this->db()->query("SELECT *, sum(dcc_valor_item*((dcc_base_imp_item/100)+1)) as cdi_credito, sum(dcc_valor_item*((dcc_base_imp_item/100)+1)) as cdi_debito,
            dcc_base_imp_item as cdi_importe
            FROM detalle_comprobante_contable
            WHERE dcc_id_trans = '$idcomprobante' AND dcc_cod_art <> 0 GROUP BY dcc_base_imp_item");
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

    public function getTotalByCompra($idcomprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query= $this->db()->query("SELECT
                SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) as total, SUM(dcc_valor_item) as subtotal, SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) as cdi_debito,SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) as cdi_credito 
                FROM detalle_comprobante_contable
                WHERE dcc_id_trans = '$idcomprobante' AND dcc_cod_art <> 0 ");
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
    public function getTotalByCuenta($idcodigo)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query= $this->db()->query("SELECT cc.*, dcc.*
                FROM detalle_comprobante_contable dcc
                INNER JOIN comprobante_contable cc on dcc.dcc_id_trans = cc.cc_id_transa
                WHERE dcc_cta_item_det = '$idcodigo' AND cc.cc_ccos_cpte = '".$_SESSION["idsucursal"]."'");
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

    public function addArticulos($replacement = true)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            //filtro para detectar si existe un articulo con las mismas caracteristicas (codigo contable, nombre y factura)
            $filter= $this->db()->query("SELECT * FROM detalle_comprobante_contable WHERE 
            dcc_id_trans = '".$this->dcc_id_trans."' AND 
            dcc_cta_item_det = '".$this->dcc_cta_item_det."' AND
            dcc_det_item_det = '".$this->dcc_det_item_det."' AND
            dcc_d_c_item_det = '".$this->dcc_d_c_item_det."'");

        if($filter->num_rows > 0 && $replacement){
            $query = "UPDATE detalle_comprobante_contable SET
            dcc_valor_item = dcc_valor_item +'".$this->dcc_valor_item."',
            dcc_cant_item_det = dcc_cant_item_det + '".$this->dcc_cant_item_det."',
            dcc_base_ret_item = dcc_base_ret_item +'".$this->dcc_base_ret_item."'
            WHERE dcc_id_trans = '".$this->dcc_id_trans."' AND 
            dcc_cta_item_det = '".$this->dcc_cta_item_det."' AND
            dcc_det_item_det = '".$this->dcc_det_item_det."'";

            $addArticulo=$this->db()->query($query);
            return $addArticulo;
        }else{
            $query="INSERT INTO detalle_comprobante_contable (dcc_id_trans, dcc_seq_detalle, dcc_cta_item_det,dcc_det_item_det, dcc_cod_art, dcc_cant_item_det, dcc_ter_item_det, dcc_ccos_item_det, dcc_d_c_item_det, dcc_valor_item,dcc_base_imp_item, dcc_base_ret_item, dcc_fecha_vcto_item, dcc_dato_fact_prove)
            VALUES(
    	        '".$this->dcc_id_trans."',
    	        '".$this->dcc_seq_detalle."',
    	        '".$this->dcc_cta_item_det."',
                '".$this->dcc_det_item_det."',
                '".$this->dcc_cod_art."',
                '".$this->dcc_cant_item_det."',
    	        '".$this->dcc_ter_item_det."',
    	        '".$this->dcc_ccos_item_det."',
    	        '".$this->dcc_d_c_item_det."',
    	        '".$this->dcc_valor_item."',
                '".$this->dcc_base_imp_item."',
    	        '".$this->dcc_base_ret_item."',
    	        '".$this->dcc_fecha_vcto_item."',
                '".$this->dcc_dato_fact_prove."')";
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

    public function deleteDetalleComprobante($idComprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("DELETE FROM detalle_comprobante_contable WHERE dcc_id_trans = '$idComprobante'");
            return $query;
        }else{
            return false;
        }
    }
    
}