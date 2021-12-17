<?php
class M_CarteraProveedor extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "credito_proveedor";
        parent::__construct($table, $adapter);
    }

    public function obtenerCarteraProveedor()
    {
        $selectAbonos = "(SELECT SUM(pago_parcial-retencion) FROM detalle_credito_proveedor WHERE idcredito_proveedor = CP.idcredito_proveedor AND estado = 1) AS totalAbonoCartera";
        $query = $this->fluent()->from('credito_proveedor CP')
                                ->leftJoin('ingreso I on CP.idingreso = I.idingreso')
                                ->leftJoin('persona P on I.idproveedor = P.idpersona')
                                ->where('CP.idsucursal = "'.$_SESSION["idsucursal"].'"')
                                ->where('CP.contabilidad = 0')
                                ->select('CP.*, I.*, P.*, P.nombre AS nombreProveedor, CONCAT(serie_comprobante, " ", num_comprobante) AS comprobanteCompra, I.estado AS estadoCompra, CP.estado AS estadoCartera')
                                ->select($selectAbonos);
        return $query->fetchAll();
    }

    public function obtenerCarteraProveedorContable()
    {
        $selectAbonos = "(SELECT SUM(pago_parcial-retencion) FROM detalle_credito_proveedor WHERE idcredito_proveedor = CP.idcredito_proveedor AND estado = 1) AS totalAbonoCartera";
        $query = $this->fluent()->from('credito_proveedor CP')
                                ->leftJoin('comprobante_contable CC on CP.idingreso = CC.cc_id_transa')
                                ->leftJoin('persona P on CC.cc_idproveedor = P.idpersona')
                                ->where('CP.idsucursal = "'.$_SESSION["idsucursal"].'"')
                                ->where('CP.contabilidad = 1')
                                ->select('P.*, CC.*, CP.*, P.nombre AS nombreProveedor, CONCAT(cc_num_cpte, " ", cc_cons_cpte) AS comprobanteCompra, CC.cc_estado AS estadoCompra, CP.estado AS estadoCartera')
                                ->select($selectAbonos);
        return $query->fetchAll();
    }

}
// SELECT cp.*,p.*,i.*, i.total, cp.estado as estado_credito, i.estado as estado_ingreso, p.nombre as nombre_proveedor
//         FROM credito_proveedor cp
//         INNER JOIN ingreso i on cp.idingreso = i.idingreso
//         INNER JOIN persona p on i.idproveedor = p.idpersona
//         WHERE cp.idsucursal = '".$_SESSION["idsucursal"]."'
//         ORDER BY i.idingreso DESC"
// "SELECT cp.*,p.*,cc.*, cc.cc_id_tipo_cpte as total, cp.estado as estado_credito, cc.cc_estado as estado_ingreso, p.nombre as nombre_proveedor,
//         cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante, cc.cc_fecha_cpte as fecha, cc.cc_fecha_cpte as fecha_final, cc_det_fact_prov as factura_proveedor
//         FROM credito_proveedor cp
//         INNER JOIN comprobante_contable cc on cp.idingreso = cc.cc_id_transa
//         INNER JOIN persona p on cc.cc_idproveedor = p.idpersona
//         WHERE cc.cc_ccos_cpte = '".$_SESSION["idsucursal"]."' AND cc.cc_tipo_comprobante = 'I' ORDER BY cc.cc_id_transa DESC");