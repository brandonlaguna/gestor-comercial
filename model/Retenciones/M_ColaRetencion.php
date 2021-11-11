<?php
class M_ColaRetencion extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_cola_detalle_retencion";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizar($detalleRetencion)
    {
        if(isset($detalleRetencion['cdr_id']) && !empty($detalleRetencion['cdr_id'])){
            $query = $this->fluent()->update('tb_cola_detalle_retencion')->set($detalleRetencion)->where('cdr_id', $detalleRetencion['cdr_id'])->execute();
        }else{
            $query = $this->fluent()->insertInto('tb_cola_detalle_retencion', $detalleRetencion)->execute();
        }
        return $query;
    }

    public function eliminarColaImpuesto($where)
    {
        return $this->fluent->deleteFrom('tb_cola_detalle_retencion')->where($where)->execute();
    }

    public function obtenerColaRetencion($filtro)
    {
        $query = $this->fluent()->from('tb_cola_detalle_retencion cdr')
                        ->leftJoin('re_id i ON cdr.cdr_re_id = i.re_id')
                        ->where("cdr.cdr_ci_id = '".$filtro['cdr_ci_id']."'");
        return $query->fetchAll();
    }

}