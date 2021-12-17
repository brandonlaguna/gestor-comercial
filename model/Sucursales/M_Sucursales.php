<?php
class M_Sucursales extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "sucursal";
        parent::__construct($table, $adapter);
    }

    public function getSucursales($filter = [])
    {
        $query = $this->fluent()->from('sucursal')
                        ->select('razon_social as item_name, idsucursal as item_id');
        if(isset($filter['idsucursal'])){
            $query->where('idsucursal = '.$filter['idsucursal']);
            return $query->fetch();
        }else{
            return $query->fetchAll();
        }
    }

}