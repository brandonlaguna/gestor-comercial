<?php
class M_DetalladoCompras extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_ingreso";
        parent::__construct($table, $adapter);
    }

    public function getDetalleCompras($filter = [])
    {
        $query = $this->fluent()->from('detalle_ingreso DI')
                ->join('articulo A ON DI.idarticulo = A.idarticulo')
                ->join('ingreso I ON I.idingreso = DI.idingreso')
                ->leftJoin('persona P ON P.idpersona = I.idproveedor')
                ->leftJoin('usuario U ON U.idusuario = I.idusuario')
                ->leftJoin('empleado E ON E.idempleado = U.idempleado')
                ->leftJoin('sucursal S ON S.idsucursal = I.idsucursal')
                ->select('I.*, P.nombre AS nombreCliente, E.nombre AS nombreEmpleado, S.razon_social as nombreSucursal, A.nombre AS nombreArticulo, DI.stock_ingreso AS cantidadCompra');
                ;
        //filtros por sucursales
        if(isset($filter['filtroSucursalReporte'])){
            $query->where('I.idsucursal IN ('.$filter['filtroSucursalReporte'].')');
        }
        //filtros por fechas
        if(isset($filter['inicioFiltroFechaReporte'])){
            if(isset($filter['finFiltroFechaReporte'])){
                $query->where('I.fecha >= "'.$filter['inicioFiltroFechaReporte'].'" AND I.fecha <= "'.$filter['finFiltroFechaReporte'].'"');
            }else{
                $query->where('I.fecha = "'.$filter['inicioFiltroFechaReporte'].'"');
            }
        }
        //filtro por comprobante
        if(isset($filter['filtroComprobanteReporte'])){
            $query->where("I.serie_comprobante IN (".$filter['filtroComprobanteReporte'].")");
        }
        //filtro por tipo de pago
        if(isset($filter['filtroTipoPagoReporte'])){
            $query->where('I.tipo_pago = "'.$filter['filtroTipoPagoReporte'].'"');
        }

        //filtro por estado
        if(isset($filter['filtroEstadoReporte'])){
            $query->where("I.estado IN (".$filter['filtroEstadoReporte'].")");
        }

        $result = $query->fetchAll();
        return $result;
    }

}