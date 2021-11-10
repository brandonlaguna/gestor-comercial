<?php
class M_DetalleImpuestoGeneral extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_detalle_impuestos_general";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizar($detalleImpuesto)
    {
        if(isset($detalleImpuesto['dig_id']) && !empty($detalleImpuesto['dig_id'])){
            $query = $this->fluent()->update('tb_detalle_impuestos_general')->set($detalleImpuesto)->where('dig_id', $detalleImpuesto['dig_id'])->execute();
        }else{
            $query = $this->fluent()->insertInto('tb_detalle_impuestos_general', $detalleImpuesto)->execute();
        }
        return $query;
    }

}