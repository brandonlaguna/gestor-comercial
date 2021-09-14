<?php
class M_ReporteVentas extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "ventas";
        parent::__construct($table, $adapter);
    }

    public function getReporteVentas($filter = [])
    {
        $query = $this->fluent()->from('venta V')
                                ->join('persona P ON V.idCliente = P.idpersona')
                                ->join('jgc_users_ USR ON USR.ju_uid = V.idusuario')
                                ->select('P.nombre AS nombreCliente')
                                ;
        if(isset($filter['usr_uid']) && !empty($filter['usr_uid'])){
            $query->where('V.idusuario = '.$filter['usr_uid']);
        }

        if(isset($filter['su_id']) && !empty($filter['su_id'])){
            $query->where('V.idsucursal = '.$filter['su_id']);

        }
        $result = $query->fetchAll();
        return $result;
    }
}