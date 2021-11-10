<?php
class M_Parametros extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_parametros";
        parent::__construct($table, $adapter);
    }

    public function obtenerParametrosTipo($filtro = [])
    {
        $query = $this->fluent()->from('tb_parametros')
                        ->select('pr_id AS item_id, pr_nombre as item_name')
                        ->where('pr_estado = 1');
        if(isset($filtro['pr_tpr_id'])){
            $query->where('pr_tpr_id = '.$filtro['pr_tpr_id']);
        }
        if(isset($filtro['pr_id'])){
            $query->where('pr_id = '.$filtro['pr_id']);
        }
        $result = $query->fetchAll();
        return $result;
    }

    public function guardarActualizarTipoParametro($parametro)
    {
        if(isset($parametro['pr_id']) && !empty($parametro['pr_id'])){
            $query = $this->fluent()->update('tb_parametros')->set($parametro)->where('pr_id', $parametro['pr_id'])->execute();
        }else{
            $query = $this->fluent()->insertInto('tb_parametros', $parametro)->execute();
        }
        return $query;
    }
}