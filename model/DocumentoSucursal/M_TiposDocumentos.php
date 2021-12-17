<?php
class M_TiposDocumentos extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tipo_documento";
        parent::__construct($table, $adapter);
    }

    public function obtenerTiposDocumentos($filter = [])
    {
        $query = $this->fluent()->from('tipo_documento');
        if(isset($filter['idtipo_documento'])){
            $query->where('idtipo_documento = '.$filter['idtipo_documento']);
            return $query->fetch();
        }else{
            return $query->fetchAll();
        }
    }

    public function guardarActualizarTipoDocumento($dataTipoDocumento)
    {
        if(isset($dataTipoDocumento['idtipo_documento']) && !empty($dataTipoDocumento['idtipo_documento'])){
            $query = $this->fluent()->update('tipo_documento')->set($dataTipoDocumento)->where('idtipo_documento', $dataTipoDocumento['idtipo_documento'])->execute();
        }else{
            $query = $this->fluent()->insertInto('tipo_documento', $dataTipoDocumento)->execute();
        }
        return $query;
    }

}