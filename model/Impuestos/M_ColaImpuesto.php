<?php
class M_ColaImpuesto extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_cola_detalle_impuesto";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizar($detalleImpuesto)
    {
        if(isset($detalleImpuesto['cdim_id']) && !empty($detalleImpuesto['cdim_id'])){
            $query = $this->fluent()->update('tb_cola_detalle_impuesto')->set($detalleImpuesto)->where('cdim_id', $detalleImpuesto['cdim_id'])->execute();
        }else{
            $query = $this->fluent()->insertInto('tb_cola_detalle_impuesto', $detalleImpuesto)->execute();
        }
        return $query;
    }

    public function eliminarColaImpuesto($where)
    {
        return $this->fluent->deleteFrom('tb_cola_detalle_impuesto')->where($where)->execute();
    }

    public function obtenerColaImpuestos($filtro)
    {
        $query = $this->fluent()->from('tb_cola_detalle_impuesto cdim')
                        ->leftJoin('tb_impuestos i ON cdim.cdim_im_id = i.im_id')
                        ->where("cdim.cdim_ci_id = '".$filtro['cdim_ci_id']."'");
        return $query->fetchAll();
    }

}