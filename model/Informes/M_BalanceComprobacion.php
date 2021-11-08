<?php
class M_BalanceComprobacion extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_comprobante_contable";
        parent::__construct($table, $adapter);
    }

    public function getBalanceComprobacion()
    {
        $query = $this->fluent()->from('detalle_comprobante_contable');
        $result = $query->fetchAll();
        return $result;
    }

}