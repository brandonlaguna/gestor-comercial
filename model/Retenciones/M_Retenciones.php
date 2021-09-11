<?php
class M_Retenciones extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_retenciones";
        parent::__construct($table, $adapter);
    }

    public function getRetenciones()
    {
        $query = $this->fluent()->from('tb_retenciones')->select("*");
        $result = $query->fetchAll();
        return $result;
    }
}