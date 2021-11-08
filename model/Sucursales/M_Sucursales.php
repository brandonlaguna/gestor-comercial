<?php
class M_Sucursales extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "sucursal";
        parent::__construct($table, $adapter);
    }

    public function getSucursales()
    {
        $query = $this->fluent()->from('sucursal')
                        ->select('razon_social as item_name, idsucursal as item_id');
        $result = $query->fetchAll();
        return $result;
    }
}