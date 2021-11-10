<?php
class M_DetalleRetencionGeneral extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_detalle_retenciones_general";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizar($detalleRetencion)
    {
        if(isset($detalleRetencion['drg_id']) && !empty($detalleRetencion['drg_id'])){
            $query = $this->fluent()->update('tb_detalle_retenciones_general')->set($detalleRetencion)->where('drg_id', $detalleRetencion['drg_id'])->execute();
        }else{
            $query = $this->fluent()->insertInto('tb_detalle_retenciones_general', $detalleRetencion)->execute();
        }
        return $query;
    }

}