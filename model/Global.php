<?php
class sGlobal extends EntidadBase{

    private $idglobal;
    private $empresa;
    private $direccion;
    private $telefono;
    private $email;
    private $pais;
    private $ciudad;
    private $nombre_impuesto;
    private $porcentaje_impuesto;
    private $simbolo_moneda;
    private $logo;
    

    public function __construct($adapter) {
        $table ="global";
        parent:: __construct($table, $adapter);
    }

    public function getIdglobal()
    {
        return $this->idglobal;
    }
    public function setIdglobal($idglobal)
    {
        $this->idglobal = $idglobal;
    }
    public function getEmpresa()
    {
        return $this->empresa;
    }
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getPais()
    {
        return $this->pais;
    }
    public function setPais($pais)
    {
        $this->pais = $pais;
    }
    public function getCiudad()
    {
        return $this->ciudad;
    }
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }
    public function getNombre_impuesto()
    {
        return $this->nombre_impuesto;
    }

    public function setNombre_impuesto($nombre_impuesto)
    {
        $this->nombre_impuesto = $nombre_impuesto;
    }
    
    public function getPorcentaje_impuesto()
    {
        return $this->porcentaje_impuesto;
    }
    public function setPorcentaje_impuesto($porcentaje_impuesto)
    {
        $this->porcentaje_impuesto = $porcentaje_impuesto;
    }
    public function getSimbolo_moneda()
    {
        return $this->simbolo_moneda;
    }
    public function setSimbolo_moneda($simbolo_moneda)
    {
        $this->simbolo_moneda = $simbolo_moneda;
    }
    public function getLogo()
    {
        return $this->logo;
    }
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }


    public function getGlobal()
    {
        $query = $this->db()->query("SELECT * FROM `global` limit 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         return $resultSet;
        }
    }

    public function updateSucursal()
    {
        $query ="UPDATE global SET
                `empresa`  =  '".$this->empresa."',
                `direccion`= '".$this->direccion."',
                `telefono`= '".$this->telefono."',
                `email`= '".$this->email."',
                `pais`= '".$this->pais."',
                `ciudad`= '".$this->ciudad."',
                `nombre_impuesto`='".$this->nombre_impuesto."',
                `porcentaje_impuesto`='".$this->porcentaje_impuesto."',
                `simbolo_moneda` = '$this->simbolo_moneda',
                `logo`= '".$this->logo."'";
                $update =$this->db()->query($query);
                return $update;
    }

    public function getMunicipios()
    {
        $query = $this->db()->query("SELECT * FROM municipios ORDER BY mn_nombre ASC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         return $resultSet;
        }
    }

    public function getDepartamentos()
    {
        $query = $this->db()->query("SELECT * FROM departamentos ORDER BY dp_nombre ASC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         return $resultSet;
        }
    }

    public function getUnidadMedidaAll()
    {
        $query = $this->db()->query("SELECT * FROM `unidad_medida`");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         return $resultSet;
        }
    }
    
}