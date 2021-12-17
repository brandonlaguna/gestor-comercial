<?php
class M_CartRetencion extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "cola_retencion";
        parent::__construct($table, $adapter);
    }

    public function getRetencionBy($filter)
    {
        $query = $this->fluent()->from('tb_cola_detalle_retencion CDR')
                        ->join('tb_retenciones R ON CDR.cdr_re_id = R.re_id');
        if(isset($filter['cdr_ci_id'])){
            $query->where('CDR.cdr_ci_id = '.$filter['cdr_ci_id']);
        }
        return $query->fetchAll();
    }

    public function guardarCartRetencion($dataRetencion)
    {
        return $this->fluent()->insertInto('tb_cola_detalle_retencion', $dataRetencion)->execute();
    }

}