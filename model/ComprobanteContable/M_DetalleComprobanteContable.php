<?php
class M_DetalleComprobanteContable extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_comprobante_contable";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizarDetalle($detalleComprobante)
    {
        if(isset($detalleComprobante['dcc_id_trans']) && !empty($detalleComprobante['dcc_id_trans'])){
            $query = $this->fluent()->update('detalle_comprobante_contable')->set($detalleComprobante)->where('dcc_id_trans', $detalleComprobante['dcc_id_trans'])->execute();
        }else{
            $query = $this->fluent()->insertInto('detalle_comprobante_contable', $detalleComprobante)->execute();
        }
        return $query;
    }
    public function obtenerDetalleComprobanteContable($filtro)
    {
        $query = $this->fluent()->from('detalle_comprobante_contable')
                        ->where('dcc_cta_item_det > 0');
            if(isset($filtro['dcc_id_trans'])){
                $query->where('dcc_id_trans = '.$filtro['dcc_id_trans']);
            }
        return $query->fetchAll();
    }
    public function obtenerTotales($filtro)
    {
        $query = $this->fluent()->from('detalle_comprobante_contable')
                        // ->select('
                        // (SUM(dcc_valor_item*(dcc_base_imp_item/100)+1)) AS total,
                        // (SUM(dcc_valor_item)) AS subtotal,
                        // (SUM(dcc_valor_item*(dcc_base_imp_item/100)+1)) AS cdi_debito,
                        // (SUM(dcc_valor_item*(dcc_base_imp_item/100)+1)) AS cdi_credito'
                        //)
                        ->where('dcc_cod_art != 0')
                        ;
        if(isset($filtro['dcc_id_trans'])){
            $query->where('dcc_id_trans = '.$filtro['dcc_id_trans']);
        }
        return $query->fetchAll();
    }
    public function obtenerImpuestos($filtro)
    {
        $query = $this->fluent()->from('detalle_comprobante_contable')
                    ->select('*,
                    (sum(dcc_valor_item*((dcc_base_imp_item/100)+1))) as cdi_credito,
                    (sum(dcc_valor_item*((dcc_base_imp_item/100)+1))) as cdi_debito,
                    (dcc_base_imp_item as) cdi_importe')
                    ->where('dcc_cod_art != 0')
                    ->groupBy('dcc_base_imp_item');
        if(isset($filtro['dcc_id_trans'])){
            $query->where('dcc_id_trans = '.$filtro['dcc_id_trans']);
        }
        return $query->fetchAll();
    }
}