<?php
class M_Articulos extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "articulos";
        parent::__construct($table, $adapter);
    }

    public function getArticulos()
    {
        $idsucursal = $_SESSION['idsucursal'];
        $query = $this->fluent()->from('articulos A')
                                //->join('detalle_stock DS ON DS.idarticulo = A.idarticulo')
                                //->select('DS.*')
                                //->where("DS.st_idsucursal = $idsucursal")
                                ;

        $result = $query->fetchAll();
        return $result;
    }

    public function getArticulosAjax()
    {
        $idsucursal = $_SESSION['idsucursal'];
        $query = $this->fluent()->from('articulo A')
                                ->join('categoria C ON C.idcategoria = A.idcategoria')
                                ->join('unidad_medida UM ON UM.idunidad_medida = A.idunidad_medida')
                                ->join('detalle_stock DS ON DS.idarticulo = A.idarticulo')
                                ->select('C.nombre AS nombre_categoria, UM.nombre AS unidad_medida, DS.stock AS stock')
                                ->where("DS.st_idsucursal = $idsucursal")
                                ;
        $result = $query->fetchAll();
        return $result;
                                
    }

    public function guardarActualizar($articulos)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($articulos['idarticulo']) && !empty($articulos['idarticulo'])){
                $query = $this->fluent->update('articulo')->set($articulos)->where('idarticulo', $articulos['idarticulo'])->execute();
            }else{
                $query = $this->fluent()->insertInto('articulo', $articulos)->execute();
            }
        }
        return $query;
    }

    public function guardarStock($detalleStock)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query = $this->fluent()->insertInto('detalle_stock', $detalleStock)->execute();
        }else{
            return false;
        }
    }
}