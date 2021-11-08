<?php
class M_ReporteVentas extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "ventas";
        parent::__construct($table, $adapter);
    }

    public function getReporteVentas($filter = [])
    {
        $detalleVentaTable= 'FROM detalle_venta WHERE idventa = V.idventa';
        $subselect='(SELECT SUM(precio_venta) '.$detalleVentaTable.') AS subtotalVenta,
                    (SELECT SUM(precio_venta * cantidad) '.$detalleVentaTable.') AS totalVenta,
                    (SELECT SUM(iva_compra) '.$detalleVentaTable.') AS totalImpuesto';
        $query = $this->fluent()->from('venta V')
                ->join('persona P ON P.idpersona = V.idCliente')
                ->join('usuario U ON U.idusuario = V.idusuario')
                ->join('empleado E ON E.idempleado = U.idempleado')
                ->join('sucursal S ON S.idsucursal = V.idsucursal')
                ->select('V.*, P.nombre AS nombreCliente, E.nombre AS nombreEmpleado, S.razon_social as nombreSucursal')
                ->select($subselect);
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
        //filtro por clientes
        if(isset($filter['filtroClienteReporte'])){
            $query->where("V.idCliente IN (".$filter['filtroClienteReporte'].")");
        }

        $result = $query->fetchAll();
        return $result;
    }

    public function getReporteVentasCredito($filter = [])
    {
        $detalleVentaTable= 'FROM detalle_venta WHERE idventa = V.idventa';
        $subselect='(SELECT SUM(precio_venta) '.$detalleVentaTable.') AS subtotalVenta,
                    (SELECT SUM(precio_venta * cantidad) '.$detalleVentaTable.') AS totalVenta,
                    (SELECT SUM(iva_compra) '.$detalleVentaTable.') AS totalImpuesto
                    (SELECT SUM(retencion_compra) '.$detalleVentaTable.') AS totalRetencion';
        $query = $this->fluent()->from('venta V')
                ->join('persona P ON P.idpersona = V.idCliente')
                ->join('usuario U ON U.idusuario = V.idusuario')
                ->join('empleado E ON E.idempleado = U.idempleado')
                ->join('sucursal S ON S.idsucursal = V.idsucursal')
                ->select('V.*, P.nombre AS nombreCliente, E.nombre AS nombreEmpleado, S.razon_social as nombreSucursal')
                ->select($subselect)
                ->where('V.tipo_pago = "Credito"');
        $result = $query->fetchAll();
        return $result;
    }

}