<?php
class M_Cart extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "cola_ingreso";
        parent::__construct($table, $adapter);
    }

    public function sendItem($items)
    {
        return $this->fluent()->insertInto('tb_cola_detalle_ingreso', $items)->execute();
    }

    public function obtenerArticulos($filter)
    {
        $query = $this->fluent()->from('tb_cola_detalle_ingreso')
                        ->where('cdi_idsucursal = '.$filter['idsucursal'])
                        ->where('cdi_idusuraio  = '.$filter['idusuario'])
                        ->where('cdi_ci_id      = '.$filter['ci_id']);
        $result = $query->fetchAll();
        return $result;
    }

    public function obtenerUltimoCarrito($filter)
    {
        $query = $this->fluent()->from('tb_cola_ingreso')
                        ->where('ci_idsucursal  = '.$filter['idsucursal'])
                        ->where('ci_usuario     = '.$filter['idusuario'])
                        ->orderBy('ci_id DESC')
                        ->limit(1);
        $result = $query->fetch();
        return $result;
    }
}