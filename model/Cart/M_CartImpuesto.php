<?php
class M_CartImpuesto extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_cola_detalle_impuesto";
        parent::__construct($table, $adapter);
    }

    public function getImpuestosBy($filter)
    {
        $query = $this->fluent()->from('tb_cola_detalle_impuesto CDIM')
                        ->join('tb_impuestos I ON CDIM.cdim_im_id = I.im_id');
        if(isset($filter['cdim_ci_id'])){
            $query->where('CDIM.cdim_ci_id = '.$filter['cdim_ci_id']);
        }
        return $query->fetchAll();
    }

    public function guardarCartImpuestos($dataImpuestos)
    {
        return $this->fluent()->insertInto('tb_cola_detalle_impuesto', $dataImpuestos)->execute();
    }
}