<?php
class M_Categorias extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "categoria";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizar($categoria)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($categoria['idcategoria']) && !empty($categoria['idcategoria'])){
                $query = $this->fluent()->update('categoria')->set($categoria)->where('idcategoria', $categoria['idcategoria'])->execute();
            }else{
                $query = $this->fluent()->insertInto('categoria', $categoria)->execute();
            }
        }
        return $query;
    }

    public function getCategoriasAjax()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query = $this->fluent()->from('categoria C')
                            ->select('(SELECT tipo_codigo FROM codigo_contable WHERE C.cod_venta > 0 AND idcodigo = C.cod_venta LIMIT 1) AS codigo_venta')
                            ->select('(SELECT tipo_codigo FROM codigo_contable WHERE C.cod_costos > 0 AND idcodigo = C.cod_costos LIMIT 1) AS codigo_costo')
                            ->select('(SELECT tipo_codigo FROM codigo_contable WHERE C.cod_devoluciones > 0 AND idcodigo = C.cod_devoluciones LIMIT 1) AS codigo_devoluciones')
                            ->select('(SELECT tipo_codigo FROM codigo_contable WHERE C.cod_inventario > 0 AND idcodigo = C.cod_inventario LIMIT 1) AS codigo_inventario');
            $result = $query->fetchAll();
            return $result;

        }
    }

    public function getCategorias($filtro=[])
    {
        $query = $this->fluent()->from('categoria')
                        ->select('idcategoria AS item_id, nombre AS item_name');
            if(isset($filtro['idcategoria'])){
                $query->where(['idcategoria'=>$filtro['idcategoria']]);
            }
        $result = $query->fetchAll();
        return $result;
    }

}