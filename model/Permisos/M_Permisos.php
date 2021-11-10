<?php
class M_Permisos extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "sucursal";
        parent::__construct($table, $adapter);
    }

    public function getPermisos($filtro = [])
    {
        $query = $this->fluent()->from('tb_permisos PER')
            ->leftJoin('tb_parametros PR ON PR.pr_id = PER.per_pr_id');

        if(isset($filtro['per_u_id'])){
            $query->where('per_u_id = '.$filtro['per_u_id']);
        }

        if(isset($filtro['per_pr_id'])){
            $query->where('per_pr_id = '.$filtro['per_pr_id']);
        }

        $result = $query->fetchAll();
        return $result;
    }

    public function guardarActualizarPermiso($dataPermiso)
    {
        if(isset($dataPermiso['per_id']) && !empty($dataPermiso['per_id'])){
            $query = $this->fluent()->update('tb_permisos')->set($dataPermiso)->where(['per_id' => $dataPermiso['per_id']])->execute();
        }else{
            $query = $this->fluent()->insertInto('tb_permisos', $dataPermiso)->execute();
        }
        return $query;
    }

    public function estado($idPermiso, $idUsuario)
    {
        $query = $this->fluent()->from('tb_permisos')
            ->where('per_pr_id = '.$idPermiso)
            ->where('per_u_id = '.$idUsuario);
            return $query->fetch();
    }
}