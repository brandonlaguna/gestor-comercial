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
        $result = $query->fetchAll();
        return $result;
    }
}