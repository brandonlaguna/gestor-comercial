<?php
class M_GuardarArticulosVentas extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_venta";
        parent::__construct($table, $adapter);
    }

    public function gurardarArticulosVenta($dataArticulosVenta){
        return $this->fluent()->insertInto('detalle_venta', $dataArticulosVenta)->execute();
    }
}