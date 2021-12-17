<?php

class FormaPago Extends EntidadBase{

    private $fp_id;
    private $fp_nombre;
    private $fp_descripcion;
    private $fp_idsucursal;
    private $fp_cuenta_contable;
    private $fp_estado;
    private $fp_proceso;
    
    public function __construct($adapter) {
        $table ="tb_forma_pago";
        parent:: __construct($table, $adapter);
    }
    public function getFp_id()
    {
        return $this->fp_id;
    }
    public function setFp_id($fp_id)
    {
        $this->fp_id = $fp_id;
    }
    public function getFp_nombre()
    {
        return $this->fp_nombre;
    }
    public function setFp_nombre($fp_nombre)
    {
        $this->fp_nombre = $fp_nombre;
    }
    public function getFp_descripcion()
    {
        return $this->fp_descripcion;
    }
    public function setFp_descripcion($fp_descripcion)
    {
        $this->fp_descripcion = $fp_descripcion;
    }
    public function getFp_idsucursal()
    {
        return $this->fp_idsucursal;
    }
    public function setFp_idsucursal($fp_idsucursal)
    {
        $this->fp_idsucursal = $fp_idsucursal;
    }
    public function getFp_cuenta_contable()
    {
        return $this->fp_cuenta_contable;
    }
    public function setFp_cuenta_contable($fp_cuenta_contable)
    {
        $this->fp_cuenta_contable = $fp_cuenta_contable;
    }
    public function getFp_estado()
    {
        return $this->fp_estado;
    }
    public function setFp_estado($fp_estado)
    {
        $this->fp_estado = $fp_estado;
    }
    public function getFp_proceso()
    {
        return $this->fp_proceso;
    }
    public function setFp_proceso($fp_proceso)
    {
        $this->fp_proceso = $fp_proceso;
    }

    public function getAllFormaPago()
    {
        $query=$this->db()->query("SELECT * FROM tb_forma_pago WHERE fp_estado = 'A' AND fp_idsucursal = '".$_SESSION['idsucursal']."' ORDER BY fp_id ASC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }
    public function getFormaPago($pos_proceso)
    {
        $query=$this->db()->query("SELECT * FROM tb_forma_pago WHERE fp_proceso = '$pos_proceso' AND fp_estado = 'A' AND fp_idsucursal = '".$_SESSION['idsucursal']."' ORDER BY fp_nombre ASC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }

    public function getFormaPagoById($id)
    {
        $query=$this->db()->query("SELECT * FROM tb_forma_pago WHERE fp_estado = 'A' AND fp_id = '$id'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function add_formapago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query="INSERT INTO tb_forma_pago (fp_nombre,fp_descripcion,fp_idsucursal,fp_cuenta_contable,fp_estado,fp_proceso)
            VALUES (
            '".$this->fp_nombre."',
            '".$this->fp_descripcion."',
            '".$this->fp_idsucursal."',
            '".$this->fp_cuenta_contable."',
            '".$this->fp_estado."',
            '".$this->fp_proceso."')";
            
            $add =$this->db()->query($query);
            $id = $this->db()->insert_id;
            return $add;
        }else{
            return false;
        }
    }
    public function update_formapago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query = "UPDATE tb_forma_pago SET
                fp_nombre = '".$this->fp_nombre."',
                fp_descripcion = '".$this->fp_descripcion."',
                fp_idsucursal = '".$this->fp_idsucursal."',
                fp_cuenta_contable = '".$this->fp_cuenta_contable."',
                fp_estado = '".$this->fp_estado."',
                fp_proceso = '".$this->fp_proceso."'
                WHERE fp_id = '".$this->fp_id."'
            ";
            $update = $this->db()->query($query);
            return $update;
        }else{
            return false;
        }
    }

    public function state_formapago()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $query = "UPDATE tb_forma_pago SET
            fp_estado = '".$this->fp_estado."'
            WHERE fp_id = '".$this->fp_id."'
        ";
        $update = $this->db()->query($query);
        return $update;
        }else{
            return false;
        }
    }

    
}
    