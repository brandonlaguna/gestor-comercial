<?php
class M_ArticulosInventario extends ModeloBase
{
    private $table;
    public function __construct($adapter){
        $table = "detalle_stock";
        parent::__construct($table, $adapter);
    }

    public function actualizarInventario($dataArticulos){
        $query = $this->fluent()->update('detalle_stock')->set($dataArticulos)->where(['idarticulo ',$dataArticulos['idarticulo'], 'st_idsucursal' => $dataArticulos['st_idsucursal']])->execute();
        return $query;
    }
}