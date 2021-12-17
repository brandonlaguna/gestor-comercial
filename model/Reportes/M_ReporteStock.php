<?php
class M_ReporteStock extends EntidadBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "";
        parent::__construct($table, $adapter);
    }

    public function getReporteStock($filter = [])
    {
        $where = '';
        $where_venta = '';

        if(isset($filter['fecha_filtro']) && !empty($filter['fecha_filtro'])){
            $fechas = explode(" to ", $filter['fecha_filtro']);
            if(isset($fechas[1])){
                $where_venta = 'AND (DATE(fecha) BETWEEN "'.$fechas[0].'" AND "'. $fechas[1].'")';
            }else{
                $where_venta = 'AND DATE(fecha) = "'.$fechas[0].'"';
            }
        }

        if(isset($filter['filtro_sucursal']) && !empty($filter['filtro_sucursal'])){
            $where .= 'AND DS.st_idsucursal = '.$filter['filtro_sucursal'];
        }

        $query = $this->db()->query("SELECT 
            A.nombre as nombreArticulo, S.razon_social as nombreSucursal, 
            A.idarticulo,
            (SELECT SUM(cantidad) FROM detalle_venta DV INNER JOIN venta V ON V.idventa = DV.idventa WHERE DV.idarticulo = DS.idarticulo AND V.idsucursal = DS.st_idsucursal $where_venta LIMIT 1) AS totalSalida,
            (SELECT AVG(precio_venta) FROM detalle_venta DV INNER JOIN venta V ON V.idventa = DV.idventa WHERE DV.idarticulo = DS.idarticulo AND V.idsucursal = DS.st_idsucursal $where_venta LIMIT 1) AS promedioSalida,
            (SELECT SUM(stock_ingreso) FROM detalle_ingreso DI INNER JOIN ingreso I ON I.idingreso = DI.idingreso WHERE DI.idarticulo = DS.idarticulo AND I.idsucursal = DS.st_idsucursal $where_venta LIMIT 1) AS totalEntrada,
            (SELECT AVG(precio_compra) FROM detalle_ingreso DI INNER JOIN ingreso I ON I.idingreso = DI.idingreso WHERE DI.idarticulo = DS.idarticulo AND I.idsucursal = DS.st_idsucursal $where_venta LIMIT 1) AS promedioEntrada
            FROM detalle_stock DS 
            INNER JOIN articulo A ON A.idarticulo = DS.idarticulo 
            INNER JOIN sucursal S ON S.idsucursal = DS.st_idsucursal ".$where);
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

}
