<?php
class M_Impuestos extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_documento_sucursal";
        parent::__construct($table, $adapter);
    }

    public function getImpuestos()
    {
        $query = $this->fluent()->from('tb_impuestos')
                        ->select('im_nombre as item_name, im_id as item_id');
        $result = $query->fetchAll();
        return $result;
    }
    
    public function guardarImpuestoDocumento($impuesto)
    {
            $query = $this->fluent()->insertInto('tb_detalle_impuestos_comprobantes', $impuesto)->execute();

        return $query;

    }
}