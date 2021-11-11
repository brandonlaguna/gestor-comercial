<?php
class M_GuardarCompra extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "ingreso";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizarCompra($compra)
    {
        if(isset($compra['idingreso']) && !empty($compra['idingreso'])){
            $query = $this->fluent()->update('ingreso')->set($compra)->where('idingreso', $compra['idingreso'])->execute();
        }else{
            $query = $this->fluent()->insertInto('ingreso', $compra)->execute();
        }
        return $query;
    }
}