<?php
class M_DetalleImpuestoComprobantes extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "tb_detalle_impuestos_comprobantes";
        parent::__construct($table, $adapter);
    }

    public function obtenerImpuestosComprobante($filter =[]){
        $query = $this->fluent()->from('tb_detalle_impuestos_comprobantes DIC')
                        ->join('tb_impuestos IM ON DIC.dic_im_id = IM.im_id')
                        ->where('IM.im_estado = "A"')
                        ->select('DIC.*, IM.im_id   ');
        if(isset($filter['dic_det_documento_sucursal'])){
            $query->where('DIC.dic_det_documento_sucursal = '.$filter['dic_det_documento_sucursal']);
        }
        return $query->fetchAll();
    }
}