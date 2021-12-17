<?php
class M_GuardarArticulosCompras extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_ingreso";
        parent::__construct($table, $adapter);
    }

    public function gurardarArticulosCompra($dataArticulosCompra)
    {
        return $this->fluent()->insertInto('detalle_ingreso', $dataArticulosCompra)->execute();
    }
}