<?php
class Usuario extends EntidadBase{

    private $idusuario;
    private $idsucursal;
    private $idempleado; 
    private $tipo_usuario;
    private $fecha_registro;
    private $mnu_almacen;
    private $mnu_compras;
    private $mnu_ventas;
    private $mnu_mantenimiento;
    private $mnu_seguridad;
    private $mnu_consulta_compras;
    private $mnu_consulta_ventas;
    private $mnu_admin;
    private $estado;

    public function __construct($adapter) {
        $table ="usuario";
        parent:: __construct($table, $adapter);
    }

    public function getIdusuario()
    {
        return $this->idusuario;
    }
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }
    public function getIdsucursal()
    {
        return $this->idsucursal;
    }
    public function setIdsucursal($idsucursal)
    {
        $this->idsucursal = $idsucursal;
    }
    public function getIdempleado()
    {
        return $this->idempleado;
    }
    public function setIdempleado($idempleado)
    {
        $this->idempleado = $idempleado;
    }
    public function getTipo_usuario()
    {
        return $this->tipo_usuario;
    }
    public function setTipo_usuario($tipo_usuario)
    {
        $this->tipo_usuario = $tipo_usuario;
    }
    public function getFecha_registro()
    {
        return $this->fecha_registro;
    }
    public function setFecha_registro($fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
    }
    public function getMnu_almacen()
    {
        return $this->mnu_almacen;
    }
    public function setMnu_almacen($mnu_almacen)
    {
        $this->mnu_almacen = $mnu_almacen;
    }
    public function getMnu_compras()
    {
        return $this->mnu_compras;
    }
    public function setMnu_compras($mnu_compras)
    {
        $this->mnu_compras = $mnu_compras;
    }
    public function getMnu_ventas()
    {
        return $this->mnu_ventas;
    }
    public function setMnu_ventas($mnu_ventas)
    {
        $this->mnu_ventas = $mnu_ventas;
    }
    public function getMnu_mantenimiento()
    {
        return $this->mnu_mantenimiento;
    }
    public function setMnu_mantenimiento($mnu_mantenimiento)
    {
        $this->mnu_mantenimiento = $mnu_mantenimiento;
    }
    public function getMnu_seguridad()
    {
        return $this->mnu_seguridad;
    }
    public function setMnu_seguridad($mnu_seguridad)
    {
        $this->mnu_seguridad = $mnu_seguridad;
    }
    public function getMnu_consulta_compras()
    {
        return $this->mnu_consulta_compras;
    }
    public function setMnu_consulta_compras($mnu_consulta_compras)
    {
        $this->mnu_consulta_compras = $mnu_consulta_compras;
    }
    public function getMnu_consulta_ventas()
    {
        return $this->mnu_consulta_ventas;
    }
    public function setMnu_consulta_ventas($mnu_consulta_ventas)
    {
        $this->mnu_consulta_ventas = $mnu_consulta_ventas;
    }
    public function getMnu_admin()
    {
        return $this->mnu_admin;
    }
    public function setMnu_admin($mnu_admin)
    {
        $this->mnu_admin = $mnu_admin;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }


    public function getUsuario()
    {
        $query=$this->db()->query("SELECT *, e.telefono as telefono_usuario, e.estado as estado_empleado 
        FROM usuario u 
        inner join empleado e on u.idempleado = e.idempleado 
        inner join sucursal s on s.idsucursal = u.idsucursal 
        WHERE u.estado = 'A'  ");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }

    public function getUsuarioById($id)
    {
        $query=$this->db()->query("SELECT *, e.telefono as telefono_usuario, e.nombre as nombre_empleado, e.email as email_empleado, e.telefono as telefono_empleado,
        e.num_documento as documento_empleado, e.direccion as direccion_empleado
        FROM usuario u 
        inner join empleado e on u.idempleado = e.idempleado 
        inner join sucursal s on s.idsucursal = u.idsucursal 
        WHERE u.estado = 'A' AND u.idusuario = '$id'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }
 
    public function create_usuario($idempleado)
    {
        $query  ="INSERT INTO usuario (idsucursal,idempleado,tipo_usuario,mnu_almacen,mnu_compras,mnu_ventas,mnu_mantenimiento,mnu_seguridad,mnu_consulta_compras,mnu_consulta_ventas,mnu_admin,fecha_registro,estado)
        VALUES(
            '".$_SESSION["idsucursal"]."',
            '$idempleado',
            '".$this->tipo_usuario."',
            '".$this->mnu_almacen."',
            '".$this->mnu_compras."',
            '".$this->mnu_ventas."',
            '".$this->mnu_mantenimiento."',
            '".$this->mnu_seguridad."',
            '".$this->mnu_consulta_compras."',
            '".$this->mnu_consulta_ventas."',
            '".$this->mnu_admin."',
            curdate(),
            '".$this->estado."'
        )";
        $create =$this->db()->query($query);
        return $create;
    }

    public function update_usuario($idusuario)
    {
        $query ="UPDATE usuario SET
                `tipo_usuario`  =  '".$this->tipo_usuario."',
                `mnu_almacen`  =  '".$this->mnu_almacen."',
                `mnu_compras`  =  '".$this->mnu_compras."',
                `mnu_ventas`  =  '".$this->mnu_ventas."',
                `mnu_mantenimiento`  =  '".$this->mnu_mantenimiento."',
                `mnu_seguridad`  =  '".$this->mnu_seguridad."',
                `mnu_consulta_compras`  =  '".$this->mnu_consulta_compras."',
                `mnu_consulta_ventas`  =  '".$this->mnu_consulta_ventas."',
                `mnu_admin`  =  '".$this->mnu_admin."',
                `estado`  =  '".$this->estado."' WHERE `idusuario` = '$idusuario'";
                $update =$this->db()->query($query);
                return $update;
    }
}