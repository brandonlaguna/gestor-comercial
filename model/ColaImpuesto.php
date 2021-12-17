<?php
class ColaImpuesto extends EntidadBase{

    private $cdim_id;
    private $cdim_ci_id;
    private $cdim_idcomprobante;
    private $cdim_im_id;
    private $cdim_contabilidad;
    private $cdim_cdim_date;

    public function __construct($adapter) {
        $table ="tb_cola_detalle_impuesto";
        parent:: __construct($table, $adapter);
    }

    public function getCdim_id()
    {
        return $this->cdim_id;
    }
    public function setCdim_id($cdim_id)
    {
        $this->cdim_id = $cdim_id;
    }
    public function getCdim_ci_id()
    {
        return $this->cdim_ci_id;
    }
    public function setCdim_ci_id($cdim_ci_id)
    {
        $this->cdim_ci_id = $cdim_ci_id;
    }
    public function getCdim_idcomprobante()
    {
        return $this->cdim_idcomprobante;
    }
    public function setCdim_idcomprobante($cdim_idcomprobante)
    {
        $this->cdim_idcomprobante = $cdim_idcomprobante;
    }
    public function getCdim_im_id()
    {
        return $this->cdim_im_id;
    }
    public function setCdim_im_id($cdim_im_id)
    {
        $this->cdim_im_id = $cdim_im_id;
    }
    public function getCdim_cdim_date()
    {
        return $this->cdim_cdim_date;
    }
    public function setCdim_cdim_date($cdim_cdim_date)
    {
        $this->cdim_cdim_date = $cdim_cdim_date;
    }
    public function getCdim_contabilidad()
    {
        return $this->cdim_contabilidad;
    }
    public function setCdim_contabilidad($cdim_contabilidad)
    {
        $this->cdim_contabilidad = $cdim_contabilidad;
    }

    public function addImpuesto()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $sql = "INSERT INTO `tb_cola_detalle_impuesto` (cdim_ci_id, cdim_idcomprobante, cdim_im_id, cdim_contabilidad)
            VALUES ( 
                '".$this->cdim_ci_id."', 
                '".$this->cdim_idcomprobante."', 
                '".$this->cdim_im_id."',
                '".$this->cdim_contabilidad."'
                )";

            $add = $this->db()->query($sql);
            return $add;
        }else{
            return false;
        }
    }

    public function getImpuestosBy($value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_cola_detalle_impuesto cdim
        INNER JOIN tb_impuestos i ON cdim.cdim_im_id = i.im_id WHERE cdim.cdim_ci_id = '$value'");
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

    public function deleteColaImpuesto()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("DELETE FROM tb_cola_detalle_impuesto WHERE cdim_id = '".$this->cdim_id."'");
            if($query){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}

?>