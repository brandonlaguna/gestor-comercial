<?php
class M_Impuestos extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_documento_sucursal";
        parent::__construct($table, $adapter);
    }

    public function getImpuestos()
    {
        $query = $this->fluent()->from('tb_impuestos')->select("*");
        $result = $query->fetchAll();
        return $result;
    }
}