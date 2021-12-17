<?php
class M_GuardarCompra extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "ventas";
        parent::__construct($table, $adapter);
    }

    public function guardarCompra($dataCompra)
    {
        $query = $this->fluent()->insertInto('ingreso', $dataCompra)->execute();
        return $this->informacionCompra($query);
    }

    public function informacionCompra($idingreso)
    {
        $query = $this->fluent()->from('ingreso I')
                        ->where('idingreso = '.$idingreso);
        return $query->fetch();
    }

    public function actualizarCompra($dataCompra)
    {
        $query = $this->fluent()->update('ingreso')->set($dataCompra)->where('idingreso', $dataCompra['idingreso'])->execute();
        return $query;
    }
}