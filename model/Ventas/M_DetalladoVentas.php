<?php
class M_DetalladoVentas extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_ventas";
        parent::__construct($table, $adapter);
    }

    public function getDetalleVentas($filter = [])
    {
        $query = $this->fluent()->from('detalle_venta DV')
                ->join('articulo A ON DV.idarticulo = A.idarticulo')
                ->join('venta V ON V.idventa = DV.idventa')
                ->join('persona P ON P.idpersona = V.idCliente')
                ->join('usuario U ON U.idusuario = V.idusuario')
                ->join('empleado E ON E.idempleado = U.idempleado')
                ->join('sucursal S ON S.idsucursal = V.idsucursal')
                ->select('V.*, P.nombre AS nombreCliente, E.nombre AS nombreEmpleado, S.razon_social as nombreSucursal, A.nombre AS nombreArticulo, DV.cantidad AS cantidadVenta');
                ;
        //filtros por sucursales
        if(isset($filter['filtroSucursalReporte'])){
            $query->where('V.idsucursal IN ('.$filter['filtroSucursalReporte'].')');
        }
        //filtros por fechas
        if(isset($filter['inicioFiltroFechaReporte'])){
            if(isset($filter['finFiltroFechaReporte'])){
                $query->where('V.fecha >= "'.$filter['inicioFiltroFechaReporte'].'" AND V.fecha <= "'.$filter['finFiltroFechaReporte'].'"');
            }else{
                $query->where('V.fecha = "'.$filter['inicioFiltroFechaReporte'].'"');
            }
        }
        //filtro por comprobante
        if(isset($filter['filtroComprobanteReporte'])){
            $query->where("V.serie_comprobante IN (".$filter['filtroComprobanteReporte'].")");
        }
        //filtro por tipo de pago
        if(isset($filter['filtroTipoPagoReporte'])){
            $query->where('V.tipo_pago = "'.$filter['filtroTipoPagoReporte'].'"');
        }

        //filtro por estado
        if(isset($filter['filtroEstadoReporte'])){
            $query->where("V.estado IN (".$filter['filtroEstadoReporte'].")");
        }

        $result = $query->fetchAll();
        return $result;
    }

}