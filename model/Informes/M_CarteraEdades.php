<?php
class M_CarteraEdades extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "credito";
        parent::__construct($table, $adapter);
    }

    public function getCarteraEdades($filtros = [])
    {
        // , 
        $select = 'CASE WHEN P.tipo_persona = "Proveedor" THEN (SELECT fecha_pago FROM credito_proveedor WHERE idingreso = CC.cc_id_transa AND contabilidad = 1 LIMIT 1) ELSE (SELECT fecha_pago FROM credito WHERE idventa = CC.cc_id_transa AND contabilidad = 1 LIMIT 1) END AS fechaProxima,';
        $select .= 'CASE WHEN P.tipo_persona = "Proveedor" THEN (SELECT deuda_total FROM credito_proveedor WHERE idingreso = CC.cc_id_transa AND contabilidad = 1 LIMIT 1) ELSE (SELECT deuda_total FROM credito WHERE idventa = CC.cc_id_transa AND contabilidad = 1 LIMIT 1) END AS deudaTotal,';
        $select .= 'CASE WHEN P.tipo_persona = "Proveedor" THEN (SELECT total_pago FROM credito_proveedor WHERE idingreso = CC.cc_id_transa AND contabilidad = 1 LIMIT 1) ELSE (SELECT total_pago FROM credito WHERE idventa = CC.cc_id_transa AND contabilidad = 1 LIMIT 1) END AS totalPago,';
        $select .= 'CASE WHEN P.tipo_persona = "Proveedor" THEN (SELECT fecha_ultimo_pago FROM credito_proveedor WHERE idingreso = CC.cc_id_transa AND contabilidad = 1 LIMIT 1) ELSE (SELECT fecha_ultimo_pago FROM credito WHERE idventa = CC.cc_id_transa AND contabilidad = 1 LIMIT 1) END AS fechaUltimoPago';
        $query = $this->fluent()->from('comprobante_contable CC')
                ->join('persona P ON P.idpersona = CC.cc_idproveedor')
                ->join('sucursal S ON S.idsucursal = CC.cc_ccos_cpte')
                ->join('tb_forma_pago FP ON FP.fp_id = CC.cc_id_forma_pago')
                ->select('P.nombre AS nombreTercero, P.num_documento AS documentoTercero, S.razon_social AS nombreSucursal, '.$select)
                ->where('CC.cc_estado = "A" AND FP.fp_nombre = "Credito"');
        return $query->fetchAll();
    }
}