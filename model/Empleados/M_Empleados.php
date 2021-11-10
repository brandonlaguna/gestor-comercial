<?php
class M_Empleados extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "usuarios";
        parent::__construct($table, $adapter);
    }

    public function obtenerEmpleadosSinCuenta($filtro = [])
    {
        $query = $this->fluent()->from('empleado E')
                        ->select('CONCAT(E.nombre, " ", E.apellidos) AS item_name, E.idempleado AS item_id');
        // if(isset($filtro['idsucursal'])){
        //     $query->where('S.idsucursal = '.$filtro['idsucursal']);
        // }
        $result = $query->fetchAll();
        return $result;
    }
}