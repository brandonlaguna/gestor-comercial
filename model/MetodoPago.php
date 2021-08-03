<?php
class MetodoPago Extends EntidadBase{

    private $mp_id;
    private $mp_nombre;
    private $mp_descripcion;
    private $mp_cuenta_contable;
    private $mp_idsucursal;
    private $mp_estado;
    private $mp_image;
    

    public function __construct($adapter) {
        $table ="tb_metodo_pago";
        parent:: __construct($table, $adapter);
    }

    public function getMp_id()
    {
        return $this->mp_id;
    }
    public function setMp_id($mp_id)
    {
        $this->mp_id = $mp_id;
    }
    public function getMp_nombre()
    {
        return $this->mp_nombre;
    }
    public function setMp_nombre($mp_nombre)
    {
        $this->mp_nombre = $mp_nombre;
    }
    public function getMp_descripcion()
    {
        return $this->mp_descripcion;
    }
    public function setMp_descripcion($mp_descripcion)
    {
        $this->mp_descripcion = $mp_descripcion;
    }
    public function getMp_cuenta_contable()
    {
        return $this->mp_cuenta_contable;
    }
    public function setMp_cuenta_contable($mp_cuenta_contable)
    {
        $this->mp_cuenta_contable = $mp_cuenta_contable;
    }
    public function getMp_idsucursal()
    {
        return $this->mp_idsucursal;
    }
    public function setMp_idsucursal($mp_idsucursal)
    {
        $this->mp_idsucursal = $mp_idsucursal;
    }
    public function getMp_estado()
    {
        return $this->mp_estado;
    }
    public function setMp_estado($mp_estado)
    {
        $this->mp_estado = $mp_estado;
    }
    public function getMp_image()
    {
        return $this->mp_image;
    }
    public function setMp_image($mp_image)
    {
        $this->mp_image = $mp_image;
    }

    public function getAllMetodoPago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
        $query=$this->db()->query("SELECT * FROM tb_metodo_pago WHERE mp_estado = 'A' AND mp_idsucursal = '".$_SESSION['idsucursal']."' ORDER BY mp_id ASC");
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
    
    public function getMetodoPago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
        $query=$this->db()->query("SELECT * FROM tb_metodo_pago WHERE mp_estado = 'A' AND mp_idsucursal = '".$_SESSION['idsucursal']."' ORDER BY mp_nombre ASC");
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

    public function getMetodoPagoById($id)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
        $query=$this->db()->query("SELECT * FROM tb_metodo_pago WHERE mp_estado = 'A' AND mp_id = '$id'");
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

    public function getMetodoPagoBy($column, $value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
        $query=$this->db()->query("SELECT * FROM tb_metodo_pago WHERE mp_estado = 'A' AND $column = '$value'");
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

    public function addMetodopago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query="INSERT INTO tb_metodo_pago (mp_nombre,mp_descripcion,mp_idsucursal,mp_cuenta_contable,mp_estado,mp_image)
            VALUES (
            '".$this->mp_nombre."',
            '".$this->mp_descripcion."',
            '".$this->mp_idsucursal."',
            '".$this->mp_cuenta_contable."',
            '".$this->mp_estado."',
            '".$this->mp_image."')";
            $add =$this->db()->query($query);
            $id = $this->db()->insert_id;
            return $id;
        }else{
            return false;
        }
    }

    public function updateMetodopago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

            $placeholder = ($this->mp_image)?",mp_image = '".$this->mp_image."'":"";
            $query = "UPDATE tb_metodo_pago SET
                mp_nombre = '".$this->mp_nombre."',
                mp_descripcion = '".$this->mp_descripcion."',
                mp_idsucursal = '".$this->mp_idsucursal."',
                mp_cuenta_contable = '".$this->mp_cuenta_contable."',
                mp_estado = '".$this->mp_estado."'
                $placeholder
                WHERE mp_id = '".$this->mp_id."'
            ";
            $update = $this->db()->query($query);
            if($update){
                return $this->mp_id;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function state_metodopago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $query = "UPDATE tb_metodo_pago SET
            mp_estado = '".$this->mp_estado."'
            WHERE mp_id = '".$this->mp_id."'
        ";
        $update = $this->db()->query($query);
        return $update;
        }else{
            return false;
        }
    }


    
}
?>