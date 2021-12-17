<?php
class Empleado extends EntidadBase{

    private $idempleado;
    private $apellidos;
    private $nombre; 
    private $tipo_documento;
    private $num_documento;
    private $direccion;
    private $telefono;
    private $email;
    private $fecha_nacimiento;
    private $foto;
    private $login;
    private $clave;
    private $estado;

    public function __construct($adapter) {
        $table ="empleado";
        parent:: __construct($table, $adapter);
    }

    public function getIdempleado()
    {
        return $this->idempleado;
    }
    public function setIdempleado($idempleado)
    {
        $this->idempleado = $idempleado;
    }
    public function getApellidos()
    {
        return $this->apellidos;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
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
    public function getFecha_nacimiento()
    {
        return $this->fecha_nacimiento;
    }
    public function setFecha_nacimiento($fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }
    public function getFoto()
    {
        return $this->foto;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
    public function getLogin()
    {
        return $this->login;
    }
    public function setLogin($login)
    {
        $this->login = $login;
    }
    public function getClave()
    {
        return $this->clave;
    }
    public function setClave($clave)
    {
        $this->clave = $clave;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function create_empleado() 
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) )
        $query="INSERT INTO empleado (apellidos,nombre,num_documento,direccion,telefono,email,foto,fecha_nacimiento,estado)
        VALUES (
            '".$this->apellidos."',
            '".$this->nombre."',
            '".$this->num_documento."',
            '".$this->direccion."',
            '".$this->telefono."',
            '".$this->email."',
            '".$this->foto."',
            '".$this->fecha_nacimiento."',
            '".$this->estado."')";
        $add =$this->db()->query($query);

        $filter = $this->db()->query("SELECT * FROM empleado ORDER BY idempleado DESC LIMIT 1");
        if ($filter->num_rows > 0) {
            while($row = $filter->fetch_assoc()) {
                $idempleado = $row["idempleado"];
            }
        }else{
                $idempleado = 8;
        }

        return $idempleado;

    }

    public function delete_empleado($idempleado)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"])){
            $query ="UPDATE empleado SET `estado`= 'D' WHERE `idempleado` = '$idempleado'";
            $delete = $this->db()->query($query);
            return $delete;
        }else{
            return false;
        }
    }
    public function update_empleado($idempleado)
    {
        $query ="UPDATE empleado SET
                `apellidos`  =  '".$this->apellidos."',
                `nombre` = '".$this->nombre."',
                `num_documento` = '".$this->num_documento."',
                `direccion` = '".$this->direccion."',
                `telefono` = '".$this->telefono."',
                `email` = '".$this->email."',
                `fecha_nacimiento` = '".$this->fecha_nacimiento."',
                `estado` = '".$this->estado."'
                 WHERE `idempleado` = '$idempleado'";
            $update =$this->db()->query($query);
    
        return $update;
    }

    public function getEmpleadoById($idempleado)
    {
        $query = $this->db()->query("SELECT * FROM empleado WHERE idempleado = '$idempleado'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getEmpleadoByUserId($idusuario)
    {
        $query = $this->db()->query("SELECT * FROM usuario u
        INNER JOIN empleado e on u.idempleado = e.idempleado
        WHERE idusuario = '$idusuario'");
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
    