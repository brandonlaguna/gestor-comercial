<?php

class Cuentas Extends EntidadBase{

    private $cu_id;
    private $cu_codigo;
    private $cu_nombre;
    private $cu_caracteristicas;
    private $cu_terceros;
    private $cu_impuestos;
    private $cu_retenciones;
    private $cu_centro_costos;
    
    public function __construct($adapter) {
        $table ="tb_cuentas";
        parent:: __construct($table, $adapter);
    }
    public function getCu_id()
    {
        return $this->cu_id;
    }

    public function setCu_id($cu_id)
    {
        $this->cu_id = $cu_id;
    }
    public function getCu_codigo()
    {
        return $this->cu_codigo;
    }

    public function setCu_codigo($cu_codigo)
    {
        $this->cu_codigo = $cu_codigo;
    }
    public function getCu_terceros()
    {
        return $this->cu_terceros;
    }
    public function getCu_nombre()
    {
        return $this->cu_nombre;
    }
    public function setCu_nombre($cu_nombre)
    {
        $this->cu_nombre = $cu_nombre;
    }

    public function setCu_terceros($cu_terceros)
    {
        $this->cu_terceros = $cu_terceros;
    }
    public function getCu_caracteristicas()
    {
        return $this->cu_caracteristicas;
    }

    public function setCu_caracteristicas($cu_caracteristicas)
    {
        $this->cu_caracteristicas = $cu_caracteristicas;
    }
    public function getCu_impuestos()
    {
        return $this->cu_impuestos;
    }

    public function setCu_impuestos($cu_impuestos)
    {
        $this->cu_impuestos = $cu_impuestos;
    }
    public function getCu_retenciones()
    {
        return $this->cu_retenciones;
    }

    public function setCu_retenciones($cu_retenciones)
    {
        $this->cu_retenciones = $cu_retenciones;
    }
    public function getCu_centro_costos()
    {
        return $this->cu_centro_costos;
    }

    public function setCu_centro_costos($cu_centro_costos)
    {
        $this->cu_centro_costos = $cu_centro_costos;
    }

    public function get_cuentas()
    {
        $query=$this->db()->query("SELECT * FROM tb_cuentas cu 
        INNER JOIN tb_impuestos im on cu.cu_impuestos = im.im_id
        INNER JOIN tb_retenciones re on cu.cu_retenciones = re.re_id
        INNER JOIN tb_centro_costos cc on cu.cu_centro_costos = cc.cc_id");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function get_cuentas_byId($id)
    {
        $query=$this->db()->query("SELECT * FROM tb_cuentas cu 
        INNER JOIN tb_impuestos im on cu.cu_impuestos = im.im_id
        INNER JOIN tb_retenciones re on cu.cu_retenciones = re.re_id
        INNER JOIN tb_centro_costos cc on cu.cu_centro_costos = cc.cc_id WHERE cu_id = '$id'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function add_cuenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>2){
            $sql = "INSERT INTO `tb_cuentas` ( cu_codigo, cu_nombre,cu_caracteristicas, cu_terceros, cu_impuestos, cu_retenciones, cu_centro_costsos)
            VALUES(
                '".$this->cu_codigo."',
                '".$this->cu_nombre."',
                '".$this->cu_caracteristicas."',
                '".$this->cu_terceros."',
                '".$this->cu_impuestos."',
                '".$this->cu_retenciones."',
                '".$this->cu_centro_costos."')";

            $add = $this-db()->query($sql);
            return $add;
        }else{
            return false;
        }
    }

    public function update_cuenta($id)
    {
        $query ="UPDATE tb_cuentas SET 
        cu_codigo = '".$this->cu_codigo."',
        cu_nombre = '".$this->cu_nombre."',
        cu_caracteristicas = '".$this->cu_caracteristicas."',
        cu_terceros = '".$this->cu_terceros."',
        cu_impuestos ='".$this->cu_impuestos."',
        cu_retenciones = '".$this->cu_retenciones."',
        cu_centro_costos = '".$this->cu_centro_costos."'
        WHERE cu_id = '$id'";
        $update_cuenta = $this->db()->query($query);
        return $update_cuenta;
    }



    

    
}
    
?>