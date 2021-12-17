<?php
class M_GuardarCartera extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "credito_proveedor";
        parent::__construct($table, $adapter);
    }

    public function guardarCarteraProveedor($dataCarteraProveedor)
    {
        return $this->fluent()->insertInto('credito_proveedor', $dataCarteraProveedor)->execute();
    }

    public function guardarCarteraCliente($dataCarteraCliente)
    {
        return $this->fluent()->insertInto('credito', $dataCarteraCliente)->execute();
    }
}