<?php
class M_FormatoImpresion extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_conf_print";
        parent::__construct($table, $adapter);
    }

    public function getFormatoImpresion()
    {
        $query =    $this->fluent()->from('tb_conf_print')
                        ->select("pri_id as item_id, pri_nombre as item_name");
        $result = $query->fetchAll();
        return $result;
    }
}