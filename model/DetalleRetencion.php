<?php
class DetalleRetencion extends EntidadBase{

    private $drg_id;
    private $drg_registro_comprobante;
    private $drg_detalle_registro;
    private $drg_contabilidad;
    private $drg_re_id;
    private $drg_date_created;
    
    public function __construct($adapter) {
        $table ="tb_detalle_retenciones_general";
        parent:: __construct($table, $adapter);
    }

    
    public function getDrg_id()
    {
        return $this->drg_id;
    }
    public function setDrg_id($drg_id)
    {
        $this->drg_id = $drg_id;
    }
    public function getDrg_registro_comprobante()
    {
        return $this->drg_registro_comprobante;
    }
    public function setDrg_registro_comprobante($drg_registro_comprobante)
    {
        $this->drg_registro_comprobante = $drg_registro_comprobante;
    }
    public function getDrg_contabilidad()
    {
        return $this->drg_contabilidad;
    }
    public function setDrg_contabilidad($drg_contabilidad)
    {
        $this->drg_contabilidad = $drg_contabilidad;
    }
    public function getDrg_re_id()
    {
        return $this->drg_re_id;
    }
    public function setDrg_re_id($drg_re_id)
    {
        $this->drg_re_id = $drg_re_id;
    }
    public function getDrg_date_created()
    {
        return $this->drg_date_created;
    }
    public function setDrg_date_created($drg_date_created)
    {
        $this->drg_date_created = $drg_date_created;
    }
    public function getDrg_detalle_registro()
    {
        return $this->drg_detalle_registro;
    }
    public function setDrg_detalle_registro($drg_detalle_registro)
    {
        $this->drg_detalle_registro = $drg_detalle_registro;
    }

    public function addRetencion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $sql = "INSERT INTO `tb_detalle_retenciones_general` (drg_registro_comprobante, drg_detalle_registro, drg_contabilidad, drg_re_id)
           VALUES(
                '".$this->drg_registro_comprobante."',
                '".$this->drg_detalle_registro."',
                '".$this->drg_contabilidad."',
                '".$this->drg_re_id."')";
            $add = $this->db()->query($sql);
            return $add;
        }else{
            return false;
        }
    }

    public function getRetencionBy($value,$contabilidad, $proceso = 'I')
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_detalle_retenciones_general drg
            INNER JOIN tb_retenciones r ON drg.drg_re_id = r.re_id WHERE drg.drg_registro_comprobante = '$value' AND drg.drg_contabilidad = '$contabilidad' AND drg.drg_detalle_registro = '$proceso'");
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
}