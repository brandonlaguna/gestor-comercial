<?php
class ColaRetencion extends EntidadBase{

    private $cdr_id;
    private $cdr_ci_id;
    private $cdr_re_id;
    private $cdr_contabilidad;
    private $cdr_date;
    
    public function __construct($adapter) {
        $table ="tb_detalle_cola_impuesto";
        parent:: __construct($table, $adapter);
    }
    public function getCdr_id()
    {
        return $this->cdr_id;
    }
    public function setCdr_id($cdr_id)
    {
        $this->cdr_id = $cdr_id;
    }
    public function getCdr_ci_id()
    {
        return $this->cdr_ci_id;
    }
    public function setCdr_ci_id($cdr_ci_id)
    {
        $this->cdr_ci_id = $cdr_ci_id;
    }
    public function getCdr_re_id()
    {
        return $this->cdr_re_id;
    }
    public function setCdr_re_id($cdr_re_id)
    {
        $this->cdr_re_id = $cdr_re_id;
    }
    public function getCdr_contabilidad()
    {
        return $this->cdr_contabilidad;
    }
    public function setCdr_contabilidad($cdr_contabilidad)
    {
        $this->cdr_contabilidad = $cdr_contabilidad;
    }
    public function getCdr_date()
    {
        return $this->cdr_date;
    }
    public function setCdr_date($cdr_date)
    {
        $this->cdr_date = $cdr_date;
    }

    public function addRetencion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $sql = "INSERT INTO `tb_cola_detalle_retencion` (cdr_ci_id, cdr_re_id, cdr_contabilidad)
            VALUES (
                '".$this->cdr_ci_id."' ,
                '".$this->cdr_re_id."',
                '".$this->cdr_contabilidad."'
                ) ";
            $add = $this->db()->query($sql);
            return $add;
        }else{
            return false;
        }
    }

    public function getRetencionBy($value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_cola_detalle_retencion cdr
            INNER JOIN tb_retenciones r ON cdr.cdr_re_id = r.re_id WHERE cdr.cdr_ci_id = '$value'");
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

    public function deleteColaRetencion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("DELETE FROM tb_cola_detalle_retencion WHERE cdr_id = '".$this->cdr_id."'");
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
    