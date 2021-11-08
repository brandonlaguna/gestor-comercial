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
}