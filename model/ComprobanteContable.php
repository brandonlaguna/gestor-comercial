<?php
class ComprobanteContable extends EntidadBase{

    private $cc_id_transa;
    private $cc_idusuario;
    private $cc_idproveedor;
    private $cc_tipo_comprobante;
    private $cc_id_forma_pago;
    private $cc_id_tipo_cpte;
    private $cc_num_cpte;
    private $cc_cons_cpte;
    private $cc_det_fact_prov; 
    private $cc_det_subtotal;
    private $cc_fecha_cpte;
    private $cc_fecha_final_cpte;
    private $cc_nit_cpte;
    private $cc_dig_verifi;
    private $cc_ccos_cpte;
    private $cc_fp_cpte;
    private $cc_log_reg;
    private $cc_estado;

    public function __construct($adapter) {
        $table ="comprobante_contable";
        parent:: __construct($table, $adapter);
    }

    public function getCc_id_transa()
    {
        return $this->cc_id_transa;
    }
    public function setCc_id_transa($cc_id_transa)
    {
        $this->cc_id_transa = $cc_id_transa;
    }
    public function getCc_idusuario()
    {
        return $this->cc_idusuario;
    }
    public function setCc_idusuario($cc_idusuario)
    {
        $this->cc_idusuario = $cc_idusuario;
    }
    public function getCc_idproveedor()
    {
        return $this->cc_idproveedor;
    }
    public function setCc_idproveedor($cc_idproveedor)
    {
        $this->cc_idproveedor = $cc_idproveedor;
    }
    public function getCc_id_forma_pago()
    {
        return $this->cc_id_forma_pago;
    }
    public function setCc_id_forma_pago($cc_id_forma_pago)
    {
        $this->cc_id_forma_pago = $cc_id_forma_pago;
    }
    public function getCc_id_tipo_cpte()
    {
        return $this->cc_id_tipo_cpte;
    }
    public function setCc_id_tipo_cpte($cc_id_tipo_cpte)
    {
        $this->cc_id_tipo_cpte = $cc_id_tipo_cpte;
    }
    public function getCc_num_cpte()
    {
        return $this->cc_num_cpte;
    }
    public function setCc_num_cpte($cc_num_cpte)
    {
        $this->cc_num_cpte = $cc_num_cpte;
    }
    public function getCc_cons_cpte()
    {
        return $this->cc_cons_cpte;
    }
    public function setCc_cons_cpte($cc_cons_cpte)
    {
        $this->cc_cons_cpte = $cc_cons_cpte;
    }
    public function getCc_det_fact_prov()
    {
        return $this->cc_det_fact_prov;
    }
    public function setCc_det_fact_prov($cc_det_fact_prov)
    {
        $this->cc_det_fact_prov = $cc_det_fact_prov;
    }
    public function getCc_det_subtotal()
    {
        return $this->cc_det_subtotal;
    }
    public function setCc_det_subtotal($cc_det_subtotal)
    {
        $this->cc_det_subtotal = $cc_det_subtotal;
    }
    public function getCc_fecha_cpte()
    {
        return $this->cc_fecha_cpte;
    }
    public function setCc_fecha_cpte($cc_fecha_cpte)
    {
        $this->cc_fecha_cpte = $cc_fecha_cpte;
    }
    public function getCc_fecha_final_cpte()
    {
        return $this->cc_fecha_final_cpte;
    }
    public function setCc_fecha_final_cpte($cc_fecha_final_cpte)
    {
        $this->cc_fecha_final_cpte = $cc_fecha_final_cpte;
    }
    public function getCc_nit_cpte()
    {
        return $this->cc_nit_cpte;
    }
    public function setCc_nit_cpte($cc_nit_cpte)
    {
        $this->cc_nit_cpte = $cc_nit_cpte;
    }
    public function getCc_dig_verifi()
    {
        return $this->cc_dig_verifi;
    }
    public function setCc_dig_verifi($cc_dig_verifi)
    {
        $this->cc_dig_verifi = $cc_dig_verifi;
    }
    public function getCc_ccos_cpte()
    {
        return $this->cc_ccos_cpte;
    }
    public function setCc_ccos_cpte($cc_ccos_cpte)
    {
        $this->cc_ccos_cpte = $cc_ccos_cpte;
    }
    public function getCc_fp_cpte()
    {
        return $this->cc_fp_cpte;
    }
    public function setCc_fp_cpte($cc_fp_cpte)
    {
        $this->cc_fp_cpte = $cc_fp_cpte;
    }
    public function getCc_log_reg()
    {
        return $this->cc_log_reg;
    }
    public function setCc_log_reg($cc_log_reg)
    {
        $this->cc_log_reg = $cc_log_reg;
    }
    public function getCc_estado()
    {
        return $this->cc_estado;
    }
    public function setCc_estado($cc_estado)
    {
        $this->cc_estado = $cc_estado;
    }
    ##nuevo

    public function getCc_tipo_comprobante()
    {
        return $this->cc_tipo_comprobante;
    }
    public function setCc_tipo_comprobante($cc_tipo_comprobante)
    {
        $this->cc_tipo_comprobante = $cc_tipo_comprobante;
    }

    public function getComprobanteAll()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT cc.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*,su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as credito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as debito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo WHERE dcc.dcc_d_c_item_det = 'C' AND dcc.dcc_id_trans = cc.cc_id_transa AND puc.centro_costos = 1) as subtotal_credito

            FROM comprobante_contable cc
            INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN sucursal su on cc.cc_ccos_cpte = su.idsucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            INNER JOIN usuario u on cc.cc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
            INNER JOIN tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id 
            WHERE su.idsucursal = '".$_SESSION["idsucursal"]."' 
            AND cc.cc_estado = 'A' ORDER BY cc.cc_id_transa DESC");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

    public function getComprobanteByRange($range_start,$type)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT cc.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*,su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as credito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as debito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo WHERE dcc.dcc_d_c_item_det = 'C' AND dcc.dcc_id_trans = cc.cc_id_transa AND puc.centro_costos = 1) as subtotal_credito

            FROM comprobante_contable cc
            INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN sucursal su on cc.cc_ccos_cpte = su.idsucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            INNER JOIN usuario u on cc.cc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
            INNER JOIN tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id 
            WHERE su.idsucursal = '".$_SESSION["idsucursal"]."' 
            AND cc.cc_estado = 'A' 
            AND cc.cc_idusuario = '".$_SESSION['usr_uid']."'
            AND cc.cc_id_transa >= '$range_start'
            AND cc.cc_tipo_comprobante = '$type'
            ORDER BY cc.cc_id_transa DESC");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

    public function getComprobanteAllBy($column,$value)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT cc.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*,su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = cc.cc_id_transa) as credito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = cc.cc_id_transa) as debito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc INNER JOIN codigo_contable puc on dcc.dcc_cta_item_det = puc.idcodigo WHERE dcc.dcc_id_trans = cc.cc_id_transa AND puc.centro_costos = 1) as subtotal_credito

            FROM comprobante_contable cc
            INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN sucursal su on cc.cc_ccos_cpte = su.idsucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            INNER JOIN usuario u on cc.cc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
            INNER JOIN tb_forma_pago fp on cc.cc_id_forma_pago = fp.fp_id 
            WHERE su.idsucursal = '".$_SESSION["idsucursal"]."' AND $column='$value' 
            AND cc.cc_estado = 'A' ORDER BY cc.cc_id_transa DESC");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

    public function getComprobanteById($id)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query =$this->db()->query("SELECT cc.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*, su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
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
            WHERE cc.cc_id_transa = '$id' AND su.idsucursal = '".$_SESSION["idsucursal"]."' ");
            
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;

        }else{
            return false;
        }
    }
    public function saveComprobanteContable()
    {
        
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = "INSERT INTO comprobante_contable (cc_idusuario,cc_idproveedor, cc_tipo_comprobante, cc_id_forma_pago,cc_id_tipo_cpte, cc_num_cpte, cc_cons_cpte,cc_det_fact_prov, cc_fecha_cpte, cc_fecha_final_cpte, cc_nit_cpte, cc_dig_verifi, cc_ccos_cpte, cc_fp_cpte, cc_log_reg,cc_estado)
                VALUES(
                    '".$this->cc_idusuario."',
                    '".$this->cc_idproveedor."',
                    '".$this->cc_tipo_comprobante."',
                    '".$this->cc_id_forma_pago."',
                    '".$this->cc_id_tipo_cpte."',
                    '".$this->cc_num_cpte."',
                    '".$this->cc_cons_cpte."',
                    '".$this->cc_det_fact_prov."',
                    '".$this->cc_fecha_cpte."',
                    '".$this->cc_fecha_final_cpte."',
                    '".$this->cc_nit_cpte."',
                    '".$this->cc_dig_verifi."',
                    '".$this->cc_ccos_cpte."',
                    '".$this->cc_fp_cpte."',
                    '".$this->cc_log_reg."',
                    '".$this->cc_estado."'
                )";
                $addIngreso=$this->db()->query($query);
                $returnId=$this->db()->query("SELECT cc_id_transa FROM comprobante_contable WHERE cc_ccos_cpte = '".$_SESSION["idsucursal"]."' AND cc_idusuario = '".$_SESSION["usr_uid"]."' ORDER BY cc_id_transa DESC LIMIT 1");
                if($returnId->num_rows > 0){
                    while($row = $returnId->fetch_assoc()) {
                        $cc_id_transa= $row["cc_id_transa"];
                    }
                }
                if($addIngreso){
                    $status = $cc_id_transa;
                }else{
                    $status =false;
                }
            return $status;
        }else{
            return false;
        }
    }

    public function delete_comprobante($idComprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("DELETE FROM comprobante_contable WHERE cc_id_transa = '$idComprobante' AND cc_estado = 'D' AND cc_ccos_cpte = '".$_SESSION["idsucursal"]."'");
            return $query;
        }else{
            return false;
        }
    }

    ################################################ Reportes

    public function reporte_general_comprobante($start_date,$end_date,$idcomprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
        $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,dds.*,td.*, i.cc_estado as estado_ingreso, pe.nombre as nombre_tercero, em.nombre as nombre_empleado,
        i.cc_num_cpte as serie_comprobante, i.cc_cons_cpte as num_comprobante, td.prefijo as tipo_comprobante, i.cc_fecha_cpte as fecha,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = i.cc_id_transa) as credito,
        (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = i.cc_id_transa) as debito
        FROM comprobante_contable i 
        INNER JOIN usuario u on i.cc_idusuario = u.idusuario 
        INNER JOIN empleado em on u.idempleado = em.idempleado
        INNER JOIN sucursal su on i.cc_ccos_cpte = su.idsucursal
        INNER JOIN persona pe on i.cc_idproveedor = pe.idpersona
        INNER JOIN detalle_documento_sucursal dds on i.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal 
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
        WHERE i.cc_fecha_cpte >= '$start_date' and i.cc_fecha_cpte <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."' 
        and i.cc_id_tipo_cpte = '$idcomprobante' and i.cc_estado='A'
        ORDER BY i.cc_cons_cpte DESC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{
            return [];
        }

    }

    public function reporte_general_tercero($start_date,$end_date,$idpersona,$tipo_proceso = 'C')
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query=$this->db()->query("SELECT i.*,u.*,em.*,su.*,pe.*,dds.*,td.*, i.cc_estado as estado_ingreso, pe.nombre as nombre_tercero, em.nombre as nombre_empleado,
            i.cc_num_cpte as serie_comprobante, i.cc_cons_cpte as num_comprobante, td.prefijo as tipo_comprobante, i.cc_fecha_cpte as fecha,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'C' and dcc.dcc_id_trans = i.cc_id_transa) as credito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc WHERE dcc.dcc_d_c_item_det = 'D' and dcc.dcc_id_trans = i.cc_id_transa) as debito
            FROM comprobante_contable i 
            INNER JOIN usuario u on i.cc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN sucursal su on i.cc_ccos_cpte = su.idsucursal
            INNER JOIN persona pe on i.cc_idproveedor = pe.idpersona
            INNER JOIN detalle_documento_sucursal dds on i.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal 
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            WHERE i.cc_fecha_cpte >= '$start_date' and i.cc_fecha_cpte <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."' 
            and i.cc_estado='A' and i.cc_tipo_comprobante = '$tipo_proceso' and i.cc_idproveedor = '$idpersona'
            ORDER BY i.cc_cons_cpte DESC");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
            }else{
                return [];
            }
    }

    public function reporte_detallado_comprobante($start_date,$end_date,$comprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query=$this->db()->query("SELECT dcc.*,cc.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, co.*, cc.cc_estado as estado_venta, pe.nombre as nombre_tercero, em.nombre as nombre_empleado
            FROM detalle_comprobante_contable dcc
            INNER JOIN codigo_contable co on dcc.dcc_cta_item_det = co.idcodigo
            INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            INNER JOIN usuario u on cc.cc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN sucursal su on cc.cc_ccos_cpte = su.idsucursal
            INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
            INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            WHERE cc.cc_fecha_cpte >= '$start_date' and cc.cc_fecha_cpte <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."'
            and cc.cc_id_tipo_cpte = '$comprobante' AND cc.cc_estado='A'
            ORDER BY cc.cc_cons_cpte DESC");
    
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

    public function reporte_general_comprobante_por_cuenta($start_date,$end_date,$cuenta)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query=$this->db()->query("SELECT dcc.*,cc.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, co.*, cc.cc_estado as estado_venta, pe.nombre as nombre_tercero, em.nombre as nombre_empleado,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc2 WHERE dcc2.dcc_d_c_item_det = 'C' AND dcc2.dcc_cta_item_det = dcc.dcc_cta_item_det and dcc2.dcc_id_trans = cc.cc_id_transa) as credito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc2 WHERE dcc2.dcc_d_c_item_det = 'D' AND dcc2.dcc_cta_item_det = dcc.dcc_cta_item_det and dcc2.dcc_id_trans = cc.cc_id_transa) as debito
            FROM detalle_comprobante_contable dcc
            INNER JOIN codigo_contable co on dcc.dcc_cta_item_det = co.idcodigo
            INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            INNER JOIN usuario u on cc.cc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN sucursal su on cc.cc_ccos_cpte = su.idsucursal
            INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
            INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            WHERE cc.cc_fecha_cpte >= '$start_date' and cc.cc_fecha_cpte <='$end_date' and su.idsucursal= '".$_SESSION["idsucursal"]."'
            and dcc.dcc_cta_item_det = '$cuenta' AND cc.cc_estado='A'
            ORDER BY dcc.dcc_cta_item_det DESC");

            // $query=$this->db()->query("SELECT *, cc.cc_estado as estado_venta, em.nombre as nombre_empleado,
            // (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc2 WHERE dcc2.dcc_d_c_item_det = 'C' AND dcc2.dcc_cta_item_det = dcc.dcc_cta_item_det and dcc2.dcc_id_trans = cc.cc_id_transa) as credito,
            // (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc2 WHERE dcc2.dcc_d_c_item_det = 'D' AND dcc2.dcc_cta_item_det = dcc.dcc_cta_item_det and dcc2.dcc_id_trans = cc.cc_id_transa) as debito
            // FROM detalle_comprobante_contable dcc
            // INNER JOIN codigo_contable co on dcc.dcc_cta_item_det = co.idcodigo
            // INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            // INNER JOIN usuario u on cc.cc_idusuario = u.idusuario 
            // INNER JOIN empleado em on u.idempleado = em.idempleado
            // WHERE cc.cc_fecha_cpte >= '$start_date' and cc.cc_fecha_cpte <='$end_date'
            // AND dcc.dcc_cta_item_det = '$cuenta' AND cc.cc_estado='A'
            // ORDER BY dcc.dcc_cta_item_det DESC");
    
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

    public function reporte_detallado_comprobante_por_cuenta($start_date,$end_date,$cuenta_desde,$cuenta_hasta)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query=$this->db()->query("SELECT dcc.*,cc.*, co.*,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc2 WHERE dcc2.dcc_d_c_item_det = 'C' AND dcc2.dcc_cta_item_det = dcc.dcc_cta_item_det) as credito,
            (SELECT sum(dcc_valor_item) FROM detalle_comprobante_contable dcc2 WHERE dcc2.dcc_d_c_item_det = 'D' AND dcc2.dcc_cta_item_det = dcc.dcc_cta_item_det) as debito
            FROM detalle_comprobante_contable dcc
            INNER JOIN codigo_contable co on dcc.dcc_cta_item_det = co.idcodigo
            INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            WHERE cc.cc_fecha_cpte >= '$start_date' and cc.cc_fecha_cpte <='$end_date' and cc.cc_ccos_cpte= '".$_SESSION["idsucursal"]."' 
            AND cc.cc_estado='A'
            GROUP BY dcc.dcc_cta_item_det ORDER BY left(dcc.dcc_cta_item_det,8) ASC");
    
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

    public function reporte_total_comprobante_por_cuenta($start_date,$end_date,$cuenta,$d_c)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query=$this->db()->query("SELECT dcc.*,cc.*,u.*,em.*,su.*,pe.*,td.*,dds.*,cp.*, co.*, cc.cc_estado as estado_venta, pe.nombre as nombre_tercero, em.nombre as nombre_empleado,
            SUM(dcc_valor_item) as total_cuenta
            FROM detalle_comprobante_contable dcc
            INNER JOIN codigo_contable co on dcc.dcc_cta_item_det = co.idcodigo
            INNER JOIN comprobante_contable cc on cc.cc_id_transa = dcc.dcc_id_trans
            INNER JOIN usuario u on cc.cc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN sucursal su on cc.cc_ccos_cpte = su.idsucursal
            INNER JOIN persona pe on cc.cc_idproveedor = pe.idpersona
            INNER JOIN detalle_documento_sucursal dds on cc.cc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            WHERE cc.cc_fecha_cpte >= '$start_date' AND cc.cc_fecha_cpte <= '$end_date' 
            AND dcc.dcc_cta_item_det = '$cuenta' AND dcc.dcc_d_c_item_det = '$d_c' AND cc.cc_estado='A'");
    
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

    public function listmenu()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){

            $listmenu = array(
                "Balance de comprobacion"=>array(
                    "url"=>"#informe/balance_comprobacion",
                    "icon"=>"media/svg/menu/web.svg",
                    "typeicon"=>"file",
                    "urlicon"=>"media/svg/menu/web.svg",
                
                ),
                "Movimiento de cuenta contable"=>array(
                    "url"=>"#informe/movimiento_cuenta",
                    "icon"=>"media/svg/menu/web.svg",
                    "typeicon"=>"file",
                    "urlicon"=>"media/svg/menu/ranking.svg",
                    
                ),
                "Rango de terceros"=>array(
                    "url"=>"#comprobantes/index",
                    "icon"=>"media/svg/menu/rank.svg",
                    "typeicon"=>"file",
                    "urlicon"=>"media/svg/menu/rank.svg",
                    
                ),
                "Rango de fechas"=>array(
                    "url"=>"#comprobantes/index",
                    "icon"=>"media/svg/menu/calendar.svg",
                    "typeicon"=>"file",
                    "urlicon"=>"media/svg/menu/calendar.svg",
                    
                ),
                "Años anteriores"=>array(
                    "url"=>"#comprobantes/index",
                    "icon"=>"media/svg/menu/year.svg",
                    "typeicon"=>"file",
                    "urlicon"=>"media/svg/menu/year.svg",
                ),
                

            );

            return $listmenu;
        }
    }


    public function setComprobanteFacturacionElectronica(){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = "UPDATE comprobante_contable SET cc_facturacion_electronica";
        }else{
            return false;
        }
    }

    
}