<?php
class Sucursal extends EntidadBase{

    private $idsucursal;
    private $razon_social;
    private $tipo_documento;
    private $num_documento;
    private $direccion;
    private $telefono;
    private $email;
    private $representante;
    private $logo;
    private $estado;
    private $base_diaria;
    private $fecha_diaria;


    public function __construct($adapter) {
        $table ="sucursal";
        parent:: __construct($table, $adapter);
    }
    public function getIdsucursal()
    {
        return $this->idsucursal;
    }
    public function setIdsucursal($idsucursal)
    {
        $this->idsucursal = $idsucursal;
    }
    public function getRazon_social()
    {
        return $this->razon_social;
    }
    public function setRazon_social($razon_social)
    {
        $this->razon_social = $razon_social;
    }
    public function getTipo_documento()
    {
        return $this->tipo_documento;
    }
    public function setTipo_documento($tipo_documento)
    {
        $this->tipo_documento = $tipo_documento;
    }
    public function getNum_documento()
    {
        return $this->num_documento;
    }
    public function setNum_documento($num_documento)
    {
        $this->num_documento = $num_documento;
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
    public function getRepresentante()
    {
        return $this->representante;
    }
    public function setRepresentante($representante)
    {
        $this->representante = $representante;
    }
    public function getLogo()
    {
        return $this->logo;
    }
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado; 
    }
    public function getBase_diaria()
    {
        return $this->base_diaria;
    }
    public function setBase_diaria($base_diaria)
    {
        $this->base_diaria = $base_diaria;
    }
    public function getFecha_diaria()
    {
        return $this->fecha_diaria;
    }
    public function setFecha_diaria($fecha_diaria)
    {
        $this->fecha_diaria = $fecha_diaria;
    }

    public function getSucursalAll()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            $query = $this->db()->query("SELECT s.*, td.*, td.nombre as tipo_documento, td.prefijo as prefijo_documento, s.logo as logo_img
            FROM sucursal s
            INNER JOIN tipo_documento td on td.idtipo_documento = s.tipo_documento
            WHERE s.estado = 'A'");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }

            return $resultSet;
        }else{
            return false;
        }
    }

    public function getSucursalById($id)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            $query = $this->db()->query("SELECT s.*, td.*,g.*, td.nombre as tipo_documento, td.prefijo as prefijo_documento, s.logo as logo_img
            FROM sucursal s
            INNER JOIN tipo_documento td on td.idtipo_documento = s.tipo_documento
            INNER JOIN global g
            WHERE idsucursal = '$id' limit 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         return $resultSet;
        }
        }else{
            return false;
        }
    }

    public function updateBaseDiaria()
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

            $query = "UPDATE sucursal SET 
                base_diaria  = '".$this->base_diaria."',
                fecha_diaria  = '".$this->fecha_diaria."'
                WHERE idsucursal = '$this->idsucursal'
            ";

            $updateArticulo=$this->db()->query($query);
            return $updateArticulo;

        }else{
            echo "No tienes permisos";
        }
    }
}