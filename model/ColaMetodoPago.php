<?php
class ColaMetodoPago extends EntidadBase{

    private $cdmp_id;
    private $cdmp_ci_id;
    private $cdmp_idcomprobante;
    private $cdmp_mp_id;
    private $cdmp_contabilidad;
    private $cdmp_monto;
    private $cdmp_date;

    public function __construct($adapter) {
        $table ="tb_cola_detalle_metodo_pago";
        parent:: __construct($table, $adapter);
    }
    public function getCdmp_id()
    {
        return $this->cdmp_id;
    }
    public function setCdmp_id($cdmp_id)
    {
        $this->cdmp_id = $cdmp_id;
    }
    public function getCdmp_ci_id()
    {
        return $this->cdmp_ci_id;
    }
    public function setCdmp_ci_id($cdmp_ci_id)
    {
        $this->cdmp_ci_id = $cdmp_ci_id;
    }
    public function getCdmp_idcomprobante()
    {
        return $this->cdmp_idcomprobante;
    }
    public function setCdmp_idcomprobante($cdmp_idcomprobante)
    {
        $this->cdmp_idcomprobante = $cdmp_idcomprobante;
    }
    public function getCdmp_mp_id()
    {
        return $this->cdmp_mp_id;
    }
    public function setCdmp_mp_id($cdmp_mp_id)
    {
        $this->cdmp_mp_id = $cdmp_mp_id;
    }
    public function getCdmp_contabilidad()
    {
        return $this->cdmp_contabilidad;
    }
    public function setCdmp_contabilidad($cdmp_contabilidad)
    {
        $this->cdmp_contabilidad = $cdmp_contabilidad;
    }
    public function getCdmp_monto()
    {
        return $this->cdmp_monto;
    }
    public function setCdmp_monto($cdmp_monto)
    {
        $this->cdmp_monto = $cdmp_monto;
    }
    public function getCdmp_date()
    {
        return $this->cdmp_date;
    }
    public function setCdmp_date($cdmp_date)
    {
        $this->cdmp_date = $cdmp_date;
    }

    public function addMetodoPago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){

            $filter=$this->db()->query("SELECT * FROM tb_cola_detalle_metodo_pago WHERE 
            cdmp_ci_id = '".$this->cdmp_ci_id."' AND
            cdmp_idcomprobante = '".$this->cdmp_idcomprobante."' AND
            cdmp_mp_id = '".$this->cdmp_mp_id."' AND 
            cdmp_contabilidad = '".$this->cdmp_contabilidad."' ");
            if($filter->num_rows > 0){
                $sql = "UPDATE `tb_cola_detalle_metodo_pago` SET
                cdmp_monto = cdmp_monto+'".$this->cdmp_monto."'
                WHERE 
                cdmp_ci_id = '".$this->cdmp_ci_id."' AND
                cdmp_idcomprobante = '".$this->cdmp_idcomprobante."' AND
                cdmp_mp_id = '".$this->cdmp_mp_id."' AND 
                cdmp_contabilidad = '".$this->cdmp_contabilidad."' ";
                $update = $this->db()->query($sql);
                return $update;
                
            }else{
                $monto = (isset($this->cdmp_monto) && !empty($this->cdmp_monto) && is_numeric($this->cdmp_monto))?$this->cdmp_monto:0;
                $sql = "INSERT INTO `tb_cola_detalle_metodo_pago` (cdmp_ci_id, cdmp_idcomprobante, cdmp_mp_id, cdmp_contabilidad, cdmp_monto)
                VALUES ( 
                    '".$this->cdmp_ci_id."', 
                    '".$this->cdmp_idcomprobante."', 
                    '".$this->cdmp_mp_id."',
                    '".$this->cdmp_contabilidad."',
                    '".$monto."'
                    )";

            $add = $this->db()->query($sql);
            return $add;
            }
        }else{
            return false;
        }
    }

    public function getMetodoPagoBy($value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_cola_detalle_metodo_pago cdmp
        INNER JOIN tb_metodo_pago mp ON cdmp.cdmp_mp_id = mp.mp_id WHERE cdmp.cdmp_ci_id = '$value'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{return false;}
       
    }

    public function getMetodoPagoById($value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_cola_detalle_metodo_pago cdmp
        INNER JOIN tb_metodo_pago mp ON cdmp.cdmp_im_id = mp.im_id WHERE mp.mp_id = '$value'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{return false;}
    }

    public function addMontoMetodoPago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
                $query ="UPDATE tb_cola_detalle_metodo_pago SET 
                cdmp_monto = '".$this->cdmp_monto."'
                WHERE cdmp_mp_id = '".$this->cdmp_mp_id."' AND
                cdmp_ci_id = '".$this->cdmp_ci_id."'";
                $addMonto=$this->db()->query($query);
                return $addMonto;

        }else{return false;}
    }

    public function deleteMeotodoPago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("DELETE FROM tb_cola_detalle_metodo_pago WHERE 
            AND cdmp_ci_id = '".$this->cdmp_ci_id."'
            AND cdmp_idcomprobante = '".$this->cdmp_idcomprobante."'
            AND cdmp_mp_id = '".$this->cdmp_mp_id."'
            AND cdmp_contabilidad = '".$this->cdmp_contabilidad."'
            ");
            return $query;
        }else{return false;}
    }

}