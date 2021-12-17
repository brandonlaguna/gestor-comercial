<?php
class M_CartMetodoPago extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_cola_detalle_metodo_pago";
        parent::__construct($table, $adapter);
    }

    public function guardarMetodoPago($dataMetodoPago)
    {
        return $this->fluent()->insertInto('tb_cola_detalle_metodo_pago', $dataMetodoPago)->execute();
    }

    public function obtenerMetodosPagos($filter = [])
    {
        $query = $this->fluent()->from('tb_cola_detalle_metodo_pago CDMP')
                                ->join('tb_metodo_pago MP ON CDMP.cdmp_mp_id = mp_id')
                                ->select('CDMP.*, MP.*')
                                ->select('SUM(cdmp_monto) AS totalMetodoPagos');
        if(isset($filter['cdmp_ci_id'])){
            $query->where('cdmp_ci_id = '.$filter['cdmp_ci_id']);
        }
        return $query->fetchAll();
    }


}