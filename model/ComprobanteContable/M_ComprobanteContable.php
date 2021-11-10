<?php
class M_ComprobanteContable extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "comprobante_contable";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizarComprobanteContable($comprobante)
    {
        if(isset($comprobante['cc_id_transa']) && !empty($comprobante['cc_id_transa'])){
            $query = $this->fluent()->update('comprobante_contable')->set($comprobante)->where('cc_id_transa', $comprobante['cc_id_transa'])->execute();
        }else{
            $query = $this->fluent()->insertInto('comprobante_contable', $comprobante)->execute();
        }
        return $query;
    }

    public function obtenerComprobanteContable($filtro)
    {
        $consultaTotales = "FROM detalle_comprobante_contable WHERE dcc_id_trans = cc.cc_id_transa AND dcc_cod_art != 0";
        $selectTotales = "
        (SELECT SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) $consultaTotales) AS totales,
        (SELECT SUM(dcc_valor_item) $consultaTotales) AS subtotal,
        (SELECT SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) $consultaTotales) AS cdi_debito,
        (SELECT SUM(dcc_valor_item*((dcc_base_imp_item/100)+1)) $consultaTotales) AS cdi_credito
        ";
        $query = $this->fluent()->from('comprobante_contable cc')
                    ->leftJoin('sucursal su on cc.cc_ccos_cpte = su.idsucursal')
                    ->leftJoin('detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal')
                    ->leftJoin('tipo_documento td on dds.idtipo_documento = td.idtipo_documento')
                    ->leftJoin('tb_conf_print cp on dds.dds_pri_id = cp.pri_id')
                    ->leftJoin('usuario u on cc.cc_idusuario = u.idusuario')
                    ->leftJoin('empleado em on u.idempleado = em.idempleado')
                    ->leftJoin('persona pe on cc.cc_idproveedor = pe.idpersona')
                    ->leftJoin('tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id')
                    ->select('cc.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*, su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
                    td.nombre as tipo_comprobante, dds.ultima_serie as serie_comprobante, cc.cc_cons_cpte as num_comprobante, pe.num_documento as documento_proveedor, em.nombre as nombre_empleado,
                    em.apellidos as apellido_empleado')
                    ->select($selectTotales);

        if(isset($filtro['cc_id_transa'])){
            $query->where('cc.cc_id_transa = '.$filtro['cc_id_transa']);
        }

        if(isset($filtro['idsucursal'])){
            $query->where('su.idsucursal = '.$filtro['idsucursal']);
        }

        return $query->fetchAll();
    }
    /*
    SELECT cc.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*, su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
            td.nombre as tipo_comprobante, dds.ultima_serie as serie_comprobante, cc.cc_cons_cpte as num_comprobante, pe.num_documento as documento_proveedor, em.nombre as nombre_empleado,
            em.apellidos as apellido_empleado
            FROM comprobante_contable cc
            INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN sucursal su on cc.cc_ccos_cpte = su.idsucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            INNER JOIN usuario u on cc.cc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
            INNER JOIN tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id
            WHERE cc.cc_id_transa = '$id' AND su.idsucursal = '".$_SESSION["idsucursal"]."' 

    */

}