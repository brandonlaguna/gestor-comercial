<?php
class M_CuentasContables extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "categoria";
        parent::__construct($table, $adapter);
    }

    public function getCuentasContables($filter = [])
    {
        $query = $this->fluent()->from('codigo_contable')
                        ->select('tipo_codigo AS item_name, idcodigo AS item_id')
                        ->where('estado_puc = "A"');
        if(isset($filter)){
            $query->where($filter);
        }
        $result = $query->fetchAll();
        return $result;
    }

}
