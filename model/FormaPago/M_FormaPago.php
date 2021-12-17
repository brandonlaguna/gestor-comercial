<?php
class M_FormaPago extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_forma_pago";
        parent::__construct($table, $adapter);
    }

    public function obtenerFormaPago($filter = [])
    {
        $query =    $this->fluent()->from('tb_forma_pago')
                        ->select("fp_id as item_id, fp_nombre as item_name");

        if(isset($filter['fp_proceso'])){
            $query->where('fp_proceso = "'.$filter['fp_proceso'].'"')
                    ->where('fp_estado = "A"');
        }

        if(isset($filter['fp_idsucursal'])){
            $query->where('fp_idsucursal = '.$filter['fp_idsucursal']);
        }

        if(isset($filter['fp_id'])){
            $query->where('fp_id = '.$filter['fp_id']);
            $result = $query->fetch();
        }else{
            $result = $query->fetchAll();
        }
        return $result;
    }
}