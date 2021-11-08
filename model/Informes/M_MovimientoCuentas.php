<?php
class M_MovimientoCuentas extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_comprobante_contable";
        parent::__construct($table, $adapter);
    }

    public function getMovimientoCuentas($filter)
    {
        $query = $this->fluent()->from('detalle_comprobante_contable DCC')
                                ->join('comprobante_contable CC ON CC.cc_id_transa = DCC.dcc_id_trans')
                                ->join('persona P ON P.idpersona = CC.cc_idproveedor');
        if(isset($filter['dcc_cta_item_det'])){
            $query->where(['dcc_cta_item_det'   => $filter['dcc_cta_item_det']]);
        }

        if(isset($filter['fecha_filtro']) && !empty($filter['fecha_filtro'])){
            $fechas = explode(" to ", $filter['fecha_filtro']);
            if(isset($fechas[1])){
                $query->where('(DATE(ddc_fecha_actualizacion) BETWEEN "'.$fechas[0].'" AND "'. $fechas[1].'")');
            }else{
                $query->where(['DATE(ddc_fecha_actualizacion)'    => $fechas[0]]);
            }
        }
        // if(isset($filter['filtro_sucursal'])){
        //     $query->where('');
        // }

        $query->select('CC.cc_num_cpte, CC.cc_cons_cpte, P.nombre AS nombrePersona')
        ->orderBy('CC.cc_num_cpte ASC');
        
        $result = $query->fetchAll();
        return $result;
    }

}