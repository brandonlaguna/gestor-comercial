<?php
class M_Usuarios extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "usuarios";
        parent::__construct($table, $adapter);
    }

    public function obtenerUsuarios($filtro)
    {
        $query = $this->fluent()->from('jgc_users_ JU')
                        ->select('E.nombre AS nombreEmpleado, E.apellidos AS apellidoEmpleado, S.razon_social AS nombreSucursal, rc_id AS codigoUsuario, U.tipo_usuario AS tipoUsuario, U.idusuario')
                        ->leftJoin('usuario U ON U.idusuario = JU.ju_uid')
                        ->leftJoin('empleado E ON E.idempleado = U.idempleado')
                        ->leftJoin('sucursal S ON JU.sc_id = S.idsucursal');

        if(isset($filtro['idusuario'])){
            $query->where('U.idusuario = '.$filtro['idusuario']);
        }
        $result = $query->fetchAll();
        return $result;
    }
}