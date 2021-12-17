<?php
class M_ConfPrint extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_conf_print";
        parent::__construct($table, $adapter);
    }

    public function obtenerTiposFormatos()
    {
        $query = $this->fluent()->from('tb_conf_print')
                            ->select('pri_id AS item_id, pri_nombre AS item_name')
                            ->where('pri_estado = "A"');
        return $query->fetchAll();
    }
}