<?php
class DetalleImpuesto extends EntidadBase{

    private $dig_id;
    private $dig_registro_comprobante;
    private $dig_detalle_registro;
    private $dig_contabilidad;
    private $dig_im_id;
    private $dig_date_created;
    
    public function __construct($adapter) {
        $table ="tb_detalle_impuestos_general";
        parent:: __construct($table, $adapter);
    }

    public function getDig_id()
    {
        return $this->dig_id;
    }
    public function setDig_id($dig_id)
    {
        $this->dig_id = $dig_id;
    }
    public function getDig_registro_comprobante()
    {
        return $this->dig_registro_comprobante;
    }
    public function setDig_registro_comprobante($dig_registro_comprobante)
    {
        $this->dig_registro_comprobante = $dig_registro_comprobante;
    }
    public function getDig_contabilidad()
    {
        return $this->dig_contabilidad;
    }
    public function setDig_contabilidad($dig_contabilidad)
    {
        $this->dig_contabilidad = $dig_contabilidad;
    }
    public function getDig_im_id()
    {
        return $this->dig_im_id;
    }
    public function setDig_im_id($dig_im_id)
    {
        $this->dig_im_id = $dig_im_id;
    }
    public function getDig_date_created()
    {
        return $this->dig_date_created;
    }
    public function setDig_date_created($dig_date_created)
    {
        $this->dig_date_created = $dig_date_created;
    }
    public function getDig_detalle_registro()
    {
        return $this->dig_detalle_registro;
    }
    public function setDig_detalle_registro($dig_detalle_registro)
    {
        $this->dig_detalle_registro = $dig_detalle_registro;
    }

    public function addImpuesto()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $sql = "INSERT INTO `tb_detalle_impuestos_general` (dig_registro_comprobante,dig_detalle_registro, dig_contabilidad, dig_im_id)
            VALUES(
                '".$this->dig_registro_comprobante."',
                '".$this->dig_detalle_registro."',
                '".$this->dig_contabilidad."',
                '".$this->dig_im_id."')";

            $add = $this->db()->query($sql);
            return $add;
        }else{
            return false;
        }
    }

    public function getImpuestosBy($value,$contabilidad,$proceso = 'I')
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $query=$this->db()->query("SELECT * from tb_detalle_impuestos_general dig
        INNER JOIN tb_impuestos i ON dig.dig_im_id = i.im_id WHERE dig.dig_registro_comprobante = '$value' AND dig.dig_contabilidad = '$contabilidad' AND dig.dig_detalle_registro ='$proceso'");
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