<?php
class M_Personas extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "persona";
        parent::__construct($table, $adapter);
    }

    public function obtenerPersonas($filter)
    {
        $query = $this->fluent()->from('persona')
                        ->select('nombre as item_name, idpersona as item_id')
                        ->where('estado = "A"');

        if(isset($filter['idTipoPersona'])){
            $query->where('tipo_persona = "'.$filter['idTipoPersona'].'"');
        }
        if(isset($filter['documentoPersona'])){
            $query->where('num_documento = '.$filter['documentoPersona']);
        }
        if(isset($filter['idpersona'])){
            $query->where('idpersona = '.$filter['idpersona']);
        }
        //un solo registro para estos parametros
        if(isset($filter['idpersona']) || isset($filter['documentoPersona'])){
            $result = $query->fetch();
        }else{
            $result = $query->fetchAll();
        }
        return $result;
    }
}
