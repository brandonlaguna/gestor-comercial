<?php
class DetalleMetodoPago extends EntidadBase{

    private $dmpg_id;
    private $dmpg_registro_comprobante;
    private $dmpg_detalle_registro;
    private $dmpg_contabilidad;
    private $dmpg_mp_id;
    private $dmpg_monto;
    private $dmpg_date;

    private $dmpg_diferencia;
    
    public function __construct($adapter) {
        $table ="tb_detalle_metodo_pago_general";
        parent:: __construct($table, $adapter);
    }

    public function getDmpg_id()
    {
        return $this->dmpg_id;
    }
    public function setDmpg_id($dmpg_id)
    {
        $this->dmpg_id = $dmpg_id;
    }
    public function getDmpg_registro_comprobante()
    {
        return $this->dmpg_registro_comprobante;
    }
    public function setDmpg_registro_comprobante($dmpg_registro_comprobante)
    {
        $this->dmpg_registro_comprobante = $dmpg_registro_comprobante;
    }
    public function getDmpg_detalle_registro()
    {
        return $this->dmpg_detalle_registro;
    }
    public function setDmpg_detalle_registro($dmpg_detalle_registro)
    {
        $this->dmpg_detalle_registro = $dmpg_detalle_registro;
    }
    public function getDmpg_contabilidad()
    {
        return $this->dmpg_contabilidad;
    }
    public function setDmpg_contabilidad($dmpg_contabilidad)
    {
        $this->dmpg_contabilidad = $dmpg_contabilidad;
    }
    public function getDmpg_mp_id()
    {
        return $this->dmpg_mp_id;
    }
    public function setDmpg_mp_id($dmpg_mp_id)
    {
        $this->dmpg_mp_id = $dmpg_mp_id;
    }
    public function getDmpg_monto()
    {
        return $this->dmpg_monto;
    }
    public function setDmpg_monto($dmpg_monto)
    {
        $this->dmpg_monto = $dmpg_monto;
    }
    public function getDmpg_date()
    {
        return $this->dmpg_date;
    }
    public function setDmpg_date($dmpg_date)
    {
        $this->dmpg_date = $dmpg_date;
    }
    public function getDmpg_diferencia()
    {
        return $this->dmpg_diferencia;
    }
    public function setDmpg_diferencia($dmpg_diferencia)
    {
        $this->dmpg_diferencia = $dmpg_diferencia;
    }

    public function addDetalleMetodoPago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){

            $filter=$this->db()->query("SELECT * FROM tb_detalle_metodo_pago_general WHERE 
            dmpg_registro_comprobante = '".$this->dmpg_registro_comprobante."' AND
            dmpg_detalle_registro = '".$this->dmpg_detalle_registro."',
            dmpg_mp_id = '".$this->dmpg_mp_id."' AND 
            dmpg_contabilidad = '".$this->dmpg_contabilidad."' ");
            if($filter){
                //actualizar monto 
                $sql = "UPDATE `tb_detalle_metodo_pago_general` SET
                dmpg_monto = dmpg_monto+'".$this->dmpg_monto."'
                WHERE 
                dmpg_registro_comprobante = '".$this->dmpg_registro_comprobante."' AND
                dmpg_detalle_registro = '".$this->dmpg_detalle_registro."',
                dmpg_mp_id = '".$this->dmpg_mp_id."' AND 
                dmpg_contabilidad = '".$this->dmpg_contabilidad."' 
                ";
                $update = $this->db()->query($sql);
                return $update;
            }else{
                $sql = "INSERT INTO `tb_detalle_metodo_pago_general` (dmpg_registro_comprobante, dmpg_detalle_registro, dmpg_mp_id, dmpg_contabilidad, dmpg_monto)
                VALUES ( 
                    '".$this->dmpg_registro_comprobante."',
                    '".$this->dmpg_detalle_registro."', 
                    '".$this->dmpg_mp_id."',
                    '".$this->dmpg_contabilidad."',
                    '".$this->dmpg_monto."'
                    )";

            $add = $this->db()->query($sql);
            return $add;
            }
        }else{
            return false;
        }
    }

    public function getDetalleMetodoPagoByComprobante($value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_detalle_metodo_pago_general dmpg
        INNER JOIN tb_metodo_pago mp ON dmpg.dmpg_mp_id = mp.mp_id WHERE dmpg.dmpg_registro_comprobante = '$value'");
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

    public function getDetalleMetodoPagoBy($value,$contabilidad,$detalle_registro)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_detalle_metodo_pago_general dmpg
        INNER JOIN tb_metodo_pago mp ON dmpg.dmpg_mp_id = mp.mp_id 
        WHERE dmpg.dmpg_registro_comprobante = '$value' 
        AND dmpg.dmpg_detalle_registro = '$detalle_registro'
        AND dmpg.dmpg_contabilidad = '$contabilidad'");
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

    public function getDetalleMetodoPagoById($value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_detalle_metodo_pago_general dmpg
        INNER JOIN tb_metodo_pago mp ON ON dmpg.dmpg_mp_id = mp.mp_id WHERE mp.mp_id = '$value'");
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
                $query ="UPDATE tb_detalle_metodo_pago_general SET 
                dmpg_monto = '".$this->dmpg_monto."'
                WHERE dmpg_mp_id = '".$this->dmpg_mp_id."' AND
                dmpg_ci_id = '".$this->dmpg_ci_id."'";
                $addMonto=$this->db()->query($query);
                return $addMonto;

        }else{return false;}
    }

    public function deleteDetalleMetodoPago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query = $this->db()->query("DELETE FROM tb_detalle_metodo_pago_general WHERE 
            dmpg_registro_comprobante = '".$this->dmpg_registro_comprobante."' AND
            dmpg_detalle_registro = '".$this->dmpg_detalle_registro."' AND 
            dmpg_contabilidad = '".$this->dmpg_contabilidad."' AND 
            dmpg_mp_id = '".$this->dmpg_mp_id."' ");

            return $query;
            
        }else{
            return false;
        }
    }
    public function deleteDetalleMetodoPagoByComprobante()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query = $this->db()->query("DELETE FROM tb_detalle_metodo_pago_general WHERE 
            dmpg_registro_comprobante = '".$this->dmpg_registro_comprobante."' AND
            dmpg_detalle_registro = '".$this->dmpg_detalle_registro."' AND 
            dmpg_contabilidad = '".$this->dmpg_contabilidad."'");
            return $query;
            
        }else{
            return false;
        }
    }
    
}
?>