<?php

class CentroCostos Extends EntidadBase{

    private $cc_id;
    private $cc_nombre;
    private $cc_departamento;
    private $cc_ciudad;
    private $cc_estado;

    public function __construct($adapter) {
        $table ="tb_centro_costos";
        parent:: __construct($table, $adapter);
    }

    public function getCc_id()
    {
        return $this->cc_id;
    }
    public function setCc_id($cc_id)
    {
        $this->cc_id = $cc_id;
    }
    public function getCc_nombre()
    {
        return $this->cc_nombre;
    }
    public function setCc_nombre($cc_nombre)
    {
        $this->cc_nombre = $cc_nombre;
    }
    public function getCc_departamento()
    {
        return $this->cc_departamento;
    }
    public function setCc_departamento($cc_departamento)
    {
        $this->cc_departamento = $cc_departamento;
    }
    public function getCc_ciudad()
    {
        return $this->cc_ciudad;
    }
    public function setCc_ciudad($cc_ciudad)
    {
        $this->cc_ciudad = $cc_ciudad;
    }

    public function getCc_estado()
    {
        return $this->cc_estado;
    }
    public function setCc_estado($cc_estado)
    {
        $this->cc_estado = $cc_estado;
    }


    public function getCentroCostos()
    {
        $query=$this->db()->query("SELECT * FROM tb_centro_costos cc 
        INNER JOIN departamentos dp on cc.cc_departamento = dp.dp_id
        INNER JOIN municipios mn on cc.cc_ciudad = mn.mn_id
        WHERE cc.cc_estado = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getCentroCostosById($idcentro)
    {
        $query=$this->db()->query("SELECT * FROM tb_centro_costos cc 
        INNER JOIN departamentos dp on cc.cc_departamento = dp.dp_id
        INNER JOIN municipios mn on cc.cc_ciudad = mn.mn_id WHERE cc_id = '$idcentro'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function update_centro_costos($idcentro)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
        $query ="UPDATE tb_centro_costos SET
            cc_nombre = '".$this->cc_nombre."',
            cc_departamento = '".$this->cc_departamento."',
            cc_ciudad = '".$this->cc_ciudad."'
            WHERE cc_id = '$idcentro'";
        $update = $this->db()->query($query);
        return $update;
       }else{
           return false;
       }
    }

    public function delete_centro_cotos($idcentro)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
        $query = $this->db()->query("UPDATE tb_centro_costos SET cc_estado = 'D' WHERE cc_id = '$idcentro'");
        if($query){
            return true;
        }else{
            return false;
        }
        }else{
            return false;
        }
        
    }

    public function add_centro_costos()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            
        }
    }

    
    
}
?>