<?php
class M_MetodosPago extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_metodo_pago";
        parent::__construct($table, $adapter);
    }

    public function obtenerMetodosPago($filter = [])
    {
        $query = $this->fluent()->from('tb_metodo_pago')
                                ->where('mp_estado = "A"')
                                ->select('mp_id AS item_id, mp_nombre as item_name');
        if(isset($filter['mp_idsucursal'])){
            $query->where('mp_idsucursal = '.$filter['mp_idsucursal']);
        }
        return $query->fetchAll();
    }

    public function obtenerMetodoPagoDefecto($filter)
    {
        $query = $this->fluent()->from('tb_metodo_pago')
                                ->where('mp_estado = "A"')
                                ->where('mp_default = 1');
        if(isset($filter['mp_idsucursal'])){
            $query->where('mp_idsucursal = '.$filter['mp_idsucursal']);
        }
        return $query->fetch();
    }

    public function guardarMetodoPagoTransaccion($dataMetodoPago)
    {
        return $this->fluent()->insertInto('tb_detalle_metodo_pago_general', $dataMetodoPago)->execute();
    }
}