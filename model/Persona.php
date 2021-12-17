<?php
class Persona extends EntidadBase{

    private $idpersona;
    private $tipo_persona;
    private $nombre;
    private $tipo_documento;
    private $tipo_organizacion;
    private $tipo_regimen;
    private $num_documento;
    private $direccion_departamento;
    private $direccion_provincia;
    private $direccion_distrito;
    private $direccion_calle;
    private $telefono;
    private $email;
    private $numero_cuenta;
    private $estado;
     

    public function __construct($adapter) {
        $table ="persona";
        parent:: __construct($table, $adapter);
    }
    public function getIdpersona()
    {
        return $this->idpersona;
    }
    public function setIdpersona($idpersona)
    {
        $this->idpersona = $idpersona;
    }
    public function getTipo_persona()
    {
        return $this->tipo_persona;
    }
    public function setTipo_persona($tipo_persona)
    {
        $this->tipo_persona = $tipo_persona;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getTipo_documento()
    {
        return $this->tipo_documento;
    }
    public function setTipo_documento($tipo_documento)
    {
        $this->tipo_documento = $tipo_documento;
    }
    public function getTipo_organizacion()
    {
        return $this->tipo_organizacion;
    }
    public function setTipo_organizacion($tipo_organizacion)
    {
        $this->tipo_organizacion = $tipo_organizacion;
    }
    public function getTipo_regimen()
    {
        return $this->tipo_regimen;
    }
    public function setTipo_regimen($tipo_regimen)
    {
        $this->tipo_regimen = $tipo_regimen;
    }
    public function getNum_documento()
    {
        return $this->num_documento;
    }
    public function setNum_documento($num_documento)
    {
        $this->num_documento = $num_documento;
    }
    public function getDireccion_departamento()
    {
        return $this->direccion_departamento;
    }
    public function setDireccion_departamento($direccion_departamento)
    {
        $this->direccion_departamento = $direccion_departamento;
    }
    public function getDireccion_provincia()
    {
        return $this->direccion_provincia;
    }
    public function setDireccion_provincia($direccion_provincia)
    {
        $this->direccion_provincia = $direccion_provincia;
    }
    public function getDireccion_distrito()
    {
        return $this->direccion_distrito;
    }
    public function setDireccion_distrito($direccion_distrito)
    {
        $this->direccion_distrito = $direccion_distrito;
    }
    public function getDireccion_calle()
    {
        return $this->direccion_calle;
    }
    public function setDireccion_calle($direccion_calle)
    {
        $this->direccion_calle = $direccion_calle;
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
    public function getNumero_cuenta()
    {
        return $this->numero_cuenta;
    }
    public function setNumero_cuenta($numero_cuenta)
    {
        $this->numero_cuenta = $numero_cuenta;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getPersonaAll()
    {
        $query = $this->db()->query("SELECT p.*, td.*, td.nombre as nombre_documento, p.estado as estado_persona, p.nombre as nombre_persona
        FROM persona p
        INNER JOIN tipo_documento td on p.tipo_documento = td.idtipo_documento");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         
        }else{
            $resultSet = [];
        }

        return $resultSet;
    }

    public function getPersonaById($idpersona)
    {
        $query = $this->db()->query("SELECT p.*, td.*,tr.*,tor.*, td.nombre as nombre_documento, p.estado as estado_persona, p.nombre as nombre_persona, td.type as tipo_documento
        FROM persona p
        INNER JOIN tipo_documento td on p.tipo_documento = td.idtipo_documento
        INNER JOIN tipo_regimen tr on p.tipo_regimen = tr.idtipo_regimen
        INNER JOIN tipo_organizacion tor on p.tipo_organizacion = tor.idtipo_organizacion
        WHERE idpersona = '$idpersona' ");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet = [];
        }
        return $resultSet;
    }

    public function autoComplete($data)
    {
        if($data){
            $query = $this->db()->query("SELECT * FROM persona WHERE estado = 'A' AND tipo_persona = '$data'");
        }else{
            $query = $this->db()->query("SELECT * FROM persona WHERE estado = 'A'");
        }
        
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet = [];
        }

        return $resultSet;
    }

    public function getProveedorByDocument($data)
    {
        $query = $this->db()->query("SELECT * FROM persona
        WHERE estado = 'A' AND tipo_persona = 'Proveedor' AND num_documento = '$data' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getPersonaByDocument($data)
    {
        $query = $this->db()->query("SELECT * FROM persona
        WHERE estado = 'A' AND num_documento = '$data' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getClienteByDocument($data)
    {
        $query = $this->db()->query("SELECT * FROM persona
        WHERE estado = 'A' AND tipo_persona = 'Cliente' AND num_documento = '$data' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function deletePersona($idpersona)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $query=$this->db()->query("UPDATE persona SET estado = 'C' WHERE idpersona = '$idpersona'");
            return $query;
        }else{
            return false;
        }
    }

    public function addPersona()
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1){

            $filter = $this->db()->query("SELECT * FROM persona WHERE num_documento = '".$this->num_documento."' AND tipo_persona = '".$this->tipo_persona."'");
            if($filter->num_rows > 0){
                return 2;
            }else{

                $query="INSERT INTO persona(tipo_persona, nombre, tipo_documento, tipo_organizacion, tipo_regimen, num_documento, direccion_departamento, direccion_provincia, direccion_distrito, direccion_calle, telefono, email, numero_cuenta, estado)
             VALUES(
            '".$this->tipo_persona."',
            '".$this->nombre."',
            '".$this->tipo_documento."',
            '".$this->tipo_organizacion."',
            '".$this->tipo_regimen."',
            '".$this->num_documento."',
            '".$this->direccion_departamento."',
            '".$this->direccion_provincia."',
            '".$this->direccion_distrito."',
            '".$this->direccion_calle."',
            '".$this->telefono."',
            '".$this->email."',
            '".$this->numero_cuenta."',
            '".$this->estado."' 
            ) ";
        $addPersona=$this->db()->query($query);
        return $addPersona;
                
            }
        
        }else{
            return false;
        }
    }

    public function updatePersona($idpersona)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
        $query ="UPDATE persona SET 
        tipo_persona = '".$this->tipo_persona."',
        nombre = '".$this->nombre."',
        tipo_documento = '".$this->tipo_documento."',
        tipo_organizacion = '".$this->tipo_organizacion."',
        tipo_regimen = '".$this->tipo_regimen."',
        num_documento = '".$this->num_documento."',
        direccion_departamento = '".$this->direccion_departamento."',
        direccion_provincia = '".$this->direccion_provincia."',
        direccion_distrito= '".$this->direccion_distrito."',
        direccion_calle= '".$this->direccion_calle."',
        telefono= '".$this->telefono."',
        email= '".$this->email."',
        numero_cuenta= '".$this->numero_cuenta."',
        estado= '".$this->estado."' 
        WHERE idpersona = '$idpersona'";

        $updatePersona=$this->db()->query($query);
        return $updatePersona;

        }else{
            return false;
        }
    }

}
