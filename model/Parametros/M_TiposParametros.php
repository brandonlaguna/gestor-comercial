<?php
class M_TiposParametros extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_tipos_parametros";
        parent::__construct($table, $adapter);
    }

    public function obtenerTiposPermisos($filtro = [])
    {
        $query = $this->fluent()->from('tb_tipos_parametros')
                        ->select('tpr_id AS item_id, tpr_nombre as item_name');
        $result = $query->fetchAll();
        return $result;
    }

    public function guardarActualizarTipoParametro($tipoParametro)
    {
        if(isset($tipoParametro['tpr_id']) && !empty($tipoParametro['tpr_id'])){
            $query = $this->fluent()->update('tb_tipos_parametros')->set($tipoParametro)->where('tpr_id', $tipoParametro['tpr_id'])->execute();
        }else{
            $query = $this->fluent()->insertInto('tb_tipos_parametros', $tipoParametro)->execute();
        }
        return $query;
    }
}