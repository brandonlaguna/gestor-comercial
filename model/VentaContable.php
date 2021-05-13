<?php
class VentaContable extends EntidadBase{

    private $vc_id_transa;
    private $vc_idusuario;
    private $vc_idcliente;
    private $vc_id_forma_pago;
    private $vc_id_tipo_cpte;
    private $vc_num_cpte;
    private $vc_cons_cpte;
    private $vc_det_fact_cli; ## <--- nuevo
    private $vc_fecha_cpte;
    private $vc_fecha_final_cpte; ## <--- nuevo
    private $vc_nit_cpte;
    private $vc_dig_verifi;
    private $vc_ccos_cpte;
    private $vc_fp_cpte;
    private $vc_log_reg;
    private $vc_estado;

    public function __construct($adapter) {
        $table ="venta_contable";
        parent:: __construct($table, $adapter);
    }
    public function getVc_id_transa()
    {
        return $this->vc_id_transa;
    }
    public function setVc_id_transa($vc_id_transa)
    {
        $this->vc_id_transa = $vc_id_transa;
    }
    public function getVc_idusuario()
    {
        return $this->vc_idusuario;
    }
    public function setVc_idusuario($vc_idusuario)
    {
        $this->vc_idusuario = $vc_idusuario;
    }
    public function getVc_idcliente()
    {
        return $this->vc_idcliente;
    }
    public function setVc_idcliente($vc_idcliente)
    {
        $this->vc_idcliente = $vc_idcliente;
    }
    public function getVc_id_forma_pago()
    {
        return $this->vc_id_forma_pago;
    }
    public function setVc_id_forma_pago($vc_id_forma_pago)
    {
        $this->vc_id_forma_pago = $vc_id_forma_pago;
    }
    public function getVc_id_tipo_cpte()
    {
        return $this->vc_id_tipo_cpte;
    }
    public function setVc_id_tipo_cpte($vc_id_tipo_cpte)
    {
        $this->vc_id_tipo_cpte = $vc_id_tipo_cpte;
    }
    public function getVc_num_cpte()
    {
        return $this->vc_num_cpte;
    }
    public function setVc_num_cpte($vc_num_cpte)
    {
        $this->vc_num_cpte = $vc_num_cpte;
    }
    public function getVc_cons_cpte()
    {
        return $this->vc_cons_cpte;
    }
    public function setVc_cons_cpte($vc_cons_cpte)
    {
        $this->vc_cons_cpte = $vc_cons_cpte;
    }
    public function getVc_fecha_cpte()
    {
        return $this->vc_fecha_cpte;
    }
    public function setVc_fecha_cpte($vc_fecha_cpte)
    {
        $this->vc_fecha_cpte = $vc_fecha_cpte;
    }
    public function getVc_nit_cpte()
    {
        return $this->vc_nit_cpte;
    }
    public function setVc_nit_cpte($vc_nit_cpte)
    {
        $this->vc_nit_cpte = $vc_nit_cpte;
    }
    public function getVc_dig_verifi()
    {
        return $this->vc_dig_verifi;
    }
    public function setVc_dig_verifi($vc_dig_verifi)
    {
        $this->vc_dig_verifi = $vc_dig_verifi;
    }
    public function getVc_ccos_cpte()
    {
        return $this->vc_ccos_cpte;
    }
    public function setVc_ccos_cpte($vc_ccos_cpte)
    {
        $this->vc_ccos_cpte = $vc_ccos_cpte;
    }
    public function getVc_fp_cpte()
    {
        return $this->vc_fp_cpte;
    }
    public function setVc_fp_cpte($vc_fp_cpte)
    {
        $this->vc_fp_cpte = $vc_fp_cpte;
    }
    public function getVc_log_reg()
    {
        return $this->vc_log_reg;
    }
    public function setVc_log_reg($vc_log_reg)
    {
        $this->vc_log_reg = $vc_log_reg;
    }
    public function getVc_estado()
    {
        return $this->vc_estado;
    }
    public function setVc_estado($vc_estado)
    {
        $this->vc_estado = $vc_estado;
    }

    ##nuevo agregado 07-may-2020
    public function getVc_det_fact_cli()
    {
        return $this->vc_det_fact_cli;
    }
    public function setVc_det_fact_cli($vc_det_fact_cli)
    {
        $this->vc_det_fact_cli = $vc_det_fact_cli;
    }

    public function getVc_fecha_final_cpte()
    {
        return $this->vc_fecha_final_cpte;
    }
    public function setVc_fecha_final_cpte($vc_fecha_final_cpte)
    { 
        $this->vc_fecha_final_cpte = $vc_fecha_final_cpte;
    }
    
    public function getVentaAll()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT vc.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*,su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
            (SELECT sum(dvc_valor_item) FROM detalle_venta_contable dvc WHERE dvc.dvc_d_c_item_det = 'C' and dvc.dvc_id_trans = vc.vc_id_transa) as credito,
            (SELECT sum(dvc_valor_item) FROM detalle_venta_contable dvc WHERE dvc.dvc_d_c_item_det = 'D' and dvc.dvc_id_trans = vc.vc_id_transa) as debito,
            (SELECT sum(dvc_valor_item) FROM detalle_venta_contable dvc INNER JOIN codigo_contable puc on dvc.dvc_cta_item_det = puc.idcodigo WHERE dvc.dvc_d_c_item_det = 'D' and dvc.dvc_id_trans = vc.vc_id_transa AND puc.centro_costos = 1) as subtotal_credito
            FROM venta_contable vc
            INNER JOIN detalle_documento_sucursal dds on vc.vc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN sucursal su on vc.vc_ccos_cpte = su.idsucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            INNER JOIN usuario u on vc.vc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN persona pe on vc.vc_idcliente = pe.idpersona
            INNER JOIN tb_forma_pago fp on vc.vc_id_forma_pago = fp.fp_id 
            WHERE su.idsucursal = '".$_SESSION["idsucursal"]."' ORDER BY vc.vc_id_transa DESC");
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

    public function getVentaById($id)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query =$this->db()->query("SELECT vc.*, dds.*, td.*, cp.*, u.*, em.*, pe.*, fp.*, su.*, pe.nombre as nombre_tercero, pe.telefono as telefono_proveedor,
            pe.num_documento as numero_documento
            FROM venta_contable vc
            INNER JOIN detalle_documento_sucursal dds on vc.vc_id_tipo_cpte = dds.iddetalle_documento_sucursal
            INNER JOIN sucursal su on vc.vc_ccos_cpte = su.idsucursal
            INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento
            INNER JOIN tb_conf_print cp on dds.dds_pri_id = cp.pri_id
            INNER JOIN usuario u on vc.vc_idusuario = u.idusuario 
            INNER JOIN empleado em on u.idempleado = em.idempleado
            INNER JOIN persona pe on vc.vc_idcliente = pe.idpersona
            INNER JOIN tb_forma_pago fp on vc.vc_id_forma_pago = fp.fp_id
            WHERE vc.vc_id_transa = '$id' AND su.idsucursal = '".$_SESSION["idsucursal"]."' ");
            
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

    public function reporte_detallado($start_date,$end_date)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){

        }
    }
    public function saveVentaContable()
    {
        
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = "INSERT INTO venta_contable (vc_idusuario,vc_idcliente,vc_id_forma_pago,vc_id_tipo_cpte, vc_num_cpte, vc_cons_cpte, vc_det_fact_cli, vc_fecha_cpte, vc_fecha_final_cpte, vc_nit_cpte, vc_dig_verifi, vc_ccos_cpte, vc_fp_cpte, vc_log_reg,vc_estado)
                VALUES(
                    '".$this->vc_idusuario."',
                    '".$this->vc_idcliente."',
                    '".$this->vc_id_forma_pago."',
                    '".$this->vc_id_tipo_cpte."',
                    '".$this->vc_num_cpte."',
                    '".$this->vc_cons_cpte."',
                    '".$this->vc_det_fact_cli."',
                    '".$this->vc_fecha_cpte."',
                    '".$this->vc_fecha_final_cpte."',
                    '".$this->vc_nit_cpte."',
                    '".$this->vc_dig_verifi."',
                    '".$this->vc_ccos_cpte."',
                    '".$this->vc_fp_cpte."',
                    '".$this->vc_log_reg."',
                    '".$this->vc_estado."'
                )";
                $addIngreso=$this->db()->query($query);
                $returnId=$this->db()->query("SELECT vc_id_transa FROM venta_contable WHERE vc_ccos_cpte = '".$_SESSION["idsucursal"]."' ORDER BY vc_id_transa DESC LIMIT 1");
                if($returnId->num_rows > 0){
                    while($row = $returnId->fetch_assoc()) {
                        $vc_id_transa= $row["vc_id_transa"];
                    }
                }
                if($addIngreso){
                    $status = $vc_id_transa;
                }else{
                    $status =false;
                }
            return $status;
        }else{
            return false;
        }
    }

    public function delete_venta($idventa)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("DELETE FROM venta_contable WHERE vc_id_transa = '$idventa' AND vc_estado = 'A'");
            return $query;
        }else{
            return false;
        }
    }

    

    
}