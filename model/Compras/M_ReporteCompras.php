<?php
class M_ReporteCompras extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "ventas";
        parent::__construct($table, $adapter);
    }

    public function getReporteCompras($filter = [])
    {
        $detalleCompraTable= 'FROM detalle_ingreso WHERE idingreso = I.idingreso';
        $subselect='(SELECT SUM(precio_compra) '.$detalleCompraTable.') AS subtotalCompra,
                    (SELECT SUM(precio_total_lote) '.$detalleCompraTable.') AS totalCompra,
                    (SELECT SUM(iva_compra) '.$detalleCompraTable.') AS totalImpuesto,
                    (SELECT COUNT(*) FROM detalle_credito_proveedor WHERE idcredito_proveedor = (SELECT idcredito_proveedor FROM credito_proveedor WHERE idingreso = I.idingreso) LIMIT 1) AS cantidadAbonos';
        $query = $this->fluent()->from('ingreso I')
                ->join('persona P ON P.idpersona = I.idproveedor')
                ->join('usuario U ON U.idusuario = I.idusuario')
                ->join('empleado E ON E.idempleado = U.idempleado')
                ->join('sucursal S ON S.idsucursal = I.idsucursal')
                ->select('I.*, P.nombre AS nombreProveedor, E.nombre AS nombreEmpleado, S.razon_social as nombreSucursal')
                ->select($subselect);
        //filtros por sucursales
        if(isset($filter['filtroSucursalReporte'])){
            $query->where('I.idsucursal IN ('.$filter['filtroSucursalReporte'].')');
        }
        //filtros por fechas
        if(isset($filter['inicioFiltroFechaReporte'])){
                if(isset($filter['finFiltroFechaReporte'])){
                    $query->where('I.fecha >= "'.$filter['inicioFiltroFechaReporte'].'" AND V.fecha <= "'.$filter['finFiltroFechaReporte'].'"');
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
        //filtro por clientes
        if(isset($filter['filtroClienteReporte'])){
            $query->where("I.idproveedor IN (".$filter['filtroClienteReporte'].")");
        }
        return $query->fetchAll();
    }

}