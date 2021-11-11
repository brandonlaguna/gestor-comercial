<?php
class M_GuardarVenta extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "venta";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizarVenta($venta)
    {
        if(isset($venta['idventa']) && !empty($venta['idventa'])){
            $query = $this->fluent()->update('venta')->set($venta)->where('idventa', $venta['idventa'])->execute();
        }else{
            $query = $this->fluent()->insertInto('venta', $venta)->execute();
        }
        return $query;
    }
}