<?php
class M_Empleados extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "usuarios";
        parent::__construct($table, $adapter);
    }

    public function obtenerEmpleadosSinCuenta($filter = [])
    {
        $query = $this->fluent()->from('empleado E')
                        ->select('CONCAT(E.nombre, " ", E.apellidos) AS item_name, E.idempleado AS item_id');
        if(isset($filter['idsucursal'])){
            $query->where('S.idsucursal = '.$filter['idsucursal']);
        }
        $result = $query->fetchAll();
        return $result;
    }

    public function obtenerEmpleado($filter = [])
    {
        $query = $this->fluent()->from('usuario U')
                                ->join('empleado E ON U.idempleado = E.idempleado')
                                ->select('U.*, E.*');
        if(isset($filter['idusuario'])){
            $query->where('U.idusuario = '.$filter['idusuario']);
            return $query->fetch();
        }else{
            return $query->fetchAll();
        }
    }
}