<?php
class IngresoContable extends EntidadBase{

    private $ic_id_transa;
    private $ic_idusuario;
    private $ic_idproveedor;
    private $ic_id_forma_pago;
    private $ic_id_tipo_cpte;
    private $ic_num_cpte;
    private $ic_cons_cpte;
    private $ic_det_fact_prov; ## <--- nuevo
    private $ic_det_subtotal; ## <--- nuevo
    private $ic_fecha_cpte;
    private $ic_fecha_final_cpte; ## <--- nuevo
    private $ic_nit_cpte;
    private $ic_dig_verifi;
    private $ic_ccos_cpte;
    private $ic_fp_cpte;
    private $ic_log_reg;
    private $ic_estado;

    public function __construct($adapter) {
        $table ="ingreso_contable";
        parent:: __construct($table, $adapter);
    }
    public function getIc_id_transa()
    {
        return $this->ic_id_transa;
    }
    public function setIc_id_transa($ic_id_transa)
    {
        $this->ic_id_transa = $ic_id_transa;
    }
    public function getIc_idusuario()
    {
        return $this->ic_idusuario;
    }
    public function setIc_idusuario($ic_idusuario)
    {
        $this->ic_idusuario = $ic_idusuario;
    }
    public function getIc_idproveedor()
    {
        return $this->ic_idproveedor;
    }
    public function setIc_idproveedor($ic_idproveedor)
    {
        $this->ic_idproveedor = $ic_idproveedor;
    }
    public function getIc_id_forma_pago()
    {
        return $this->ic_id_forma_pago;
    }
    public function setIc_id_forma_pago($ic_id_forma_pago)
    {
        $this->ic_id_forma_pago = $ic_id_forma_pago;
    }
    public function getIc_id_tipo_cpte()
    {
        return $this->ic_id_tipo_cpte;
    }
    public function setIc_id_tipo_cpte($ic_id_tipo_cpte)
    {
        $this->ic_id_tipo_cpte = $ic_id_tipo_cpte;
    }
    public function getIc_num_cpte()
    {
        return $this->ic_num_cpte;
    }
    public function setIc_num_cpte($ic_num_cpte)
    {
        $this->ic_num_cpte = $ic_num_cpte;
    }
    public function getIc_cons_cpte()
    {
        return $this->ic_cons_cpte;
    }
    public function setIc_cons_cpte($ic_cons_cpte)
    {
        $this->ic_cons_cpte = $ic_cons_cpte;
    }
    public function getIc_fecha_cpte()
    {
        return $this->ic_fecha_cpte;
    }
    public function setIc_fecha_cpte($ic_fecha_cpte)
    {
        $this->ic_fecha_cpte = $ic_fecha_cpte;
    }
    public function getIc_nit_cpte()
    {
        return $this->ic_nit_cpte;
    }
    public function setIc_nit_cpte($ic_nit_cpte)
    {
        $this->ic_nit_cpte = $ic_nit_cpte;
    }
    public function getIc_dig_verifi()
    {
        return $this->ic_dig_verifi;
    }
    public function setIc_dig_verifi($ic_dig_verifi)
    {
        $this->ic_dig_verifi = $ic_dig_verifi;
    }
    public function getIc_ccos_cpte()
    {
        return $this->ic_ccos_cpte;
    }
    public function setIc_ccos_cpte($ic_ccos_cpte)
    {
        $this->ic_ccos_cpte = $ic_ccos_cpte;
    }
    public function getIc_fp_cpte()
    {
        return $this->ic_fp_cpte;
    }
    public function setIc_fp_cpte($ic_fp_cpte)
    {
        $this->ic_fp_cpte = $ic_fp_cpte;
    }
    public function getIc_log_reg()
    {
        return $this->ic_log_reg;
    }
    public function setIc_log_reg($ic_log_reg)
    {
        $this->ic_log_reg = $ic_log_reg;
    }
    public function getIc_estado()
    {
        return $this->ic_estado;
    }
    public function setIc_estado($ic_estado)
    {
        $this->ic_estado = $ic_estado;
    }

    ##nuevas agregadas 7-may-2020

    public function getIc_det_fact_prov()
    {
        return $this->ic_det_fact_prov;
    }
    public function setIc_det_fact_prov($ic_det_fact_prov)
    {
        $this->ic_det_fact_prov = $ic_det_fact_prov;
    }
    public function getIc_fecha_final_cpte()
    {
        return $this->ic_fecha_final_cpte;
    }
    public function setIc_fecha_final_cpte($ic_fecha_final_cpte)
    {
        $this->ic_fecha_final_cpte = $ic_fecha_final_cpte;
    }

    public function getIc_det_subtotal()
    {
        return $this->ic_det_subtotal;
    }
    public function setIc_det_subtotal($ic_det_subtotal)
    {
        $this->ic_det_subtotal = $ic_det_subtotal;;
    }
    
    public function getCompraAll()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT ic.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*,su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
            (SELECT sum(dic_valor_item) FROM detalle_ingreso_contable dic WHERE dic.dic_d_c_item_det = 'C' and dic.dic_id_trans = ic.ic_id_transa) as credito,
            (SELECT sum(dic_valor_item) FROM detalle_ingreso_contable dic WHERE dic.dic_d_c_item_det = 'D' and dic.dic_id_trans = ic.ic_id_transa) as debito,
            (SELECT sum(dic_valor_item) FROM detalle_ingreso_contable dic INNER JOIN codigo_contable puc on dic.dic_cta_item_det = puc.idcodigo WHERE dic.dic_d_c_item_det = 'C' AND dic.dic_id_trans = ic.ic_id_transa AND puc.centro_costos = 1) as subtotal_credito

            FROM ingreso_contable ic
            INNER JOIN detalle_documento_sucursal dds on ic.ic_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN sucursal su on ic.ic_ccos_cpte = su.idsucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            INNER JOIN usuario u on ic.ic_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN persona pe on ic.ic_idproveedor = pe.idpersona
            INNER JOIN tb_forma_pago fp on ic.ic_id_forma_pago = fp.fp_id 
            WHERE su.idsucursal = '".$_SESSION["idsucursal"]."' ORDER BY ic.ic_id_transa DESC");
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

    public function getCompraById($id)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query =$this->db()->query("SELECT ic.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*, su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
            td.nombre as tipo_comprobante, dds.ultima_serie as serie_comprobante, dds.ultimo_numero as num_comprobante, pe.num_documento as documento_proveedor
            FROM ingreso_contable ic
            INNER JOIN detalle_documento_sucursal dds on ic.ic_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN sucursal su on ic.ic_ccos_cpte = su.idsucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            INNER JOIN usuario u on ic.ic_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN persona pe on ic.ic_idproveedor = pe.idpersona
            INNER JOIN tb_forma_pago fp on ic.ic_id_forma_pago = fp.fp_id
            WHERE ic.ic_id_transa = '$id' AND su.idsucursal = '".$_SESSION["idsucursal"]."' ");
            
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
    public function saveIngresoContable()
    {
        
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = "INSERT INTO ingreso_contable (ic_idusuario,ic_idproveedor,ic_id_forma_pago,ic_id_tipo_cpte, ic_num_cpte, ic_cons_cpte,ic_det_fact_prov, ic_fecha_cpte, ic_fecha_final_cpte, ic_nit_cpte, ic_dig_verifi, ic_ccos_cpte, ic_fp_cpte, ic_log_reg,ic_estado)
                VALUES(
                    '".$this->ic_idusuario."',
                    '".$this->ic_idproveedor."',
                    '".$this->ic_id_forma_pago."',
                    '".$this->ic_id_tipo_cpte."',
                    '".$this->ic_num_cpte."',
                    '".$this->ic_cons_cpte."',
                    '".$this->ic_det_fact_prov."',
                    '".$this->ic_fecha_cpte."',
                    '".$this->ic_fecha_final_cpte."',
                    '".$this->ic_nit_cpte."',
                    '".$this->ic_dig_verifi."',
                    '".$this->ic_ccos_cpte."',
                    '".$this->ic_fp_cpte."',
                    '".$this->ic_log_reg."',
                    '".$this->ic_estado."'
                )";
                $addIngreso=$this->db()->query($query);
                $returnId=$this->db()->query("SELECT ic_id_transa FROM ingreso_contable WHERE ic_ccos_cpte = '".$_SESSION["idsucursal"]."' AND ic_idusuario = '".$_SESSION["usr_uid"]."' ORDER BY ic_id_transa DESC LIMIT 1");
                if($returnId->num_rows > 0){
                    while($row = $returnId->fetch_assoc()) {
                        $ic_id_transa= $row["ic_id_transa"];
                    }
                }
                if($addIngreso){
                    $status = $ic_id_transa;
                }else{
                    $status =false;
                }
            return $status;
        }else{
            return false;
        }
    }

    public function delete_ingreso($idcompra)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("DELETE FROM ingreso_contable WHERE ic_id_transa = '$idcompra' AND ic_estado = 'A' AND ic_ccos_cpte = '".$_SESSION["idsucursal"]."'");
            return $query;
        }else{
            return false;
        }
    }
}