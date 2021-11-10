<?php

class Cartera Extends EntidadBase{

    /////////Proveedor
    private $idcredito_proveedor;
    private $idingreso;
    private $fecha_pago;
    private $total_pago;
    private $deuda_total;
    private $cp_estado;
    ///recaudos Proveedor
    private $pago_parcial;
    private $deuda_parcial;
    private $dcp_estado;

    //ambos
    private $monto;
    private $tipo_pago;
    private $contabilidad;

    //Cliente
    private $idcredito;
    private $iddetalle_credito;
    private $idventa;
    // private $fecha_pago;
    // private $total_pago;
    // private $deuda_total;
    private $c_estado;
    ///recaudos
    // private $pago_parcial;
    // private $deuda_parcial;
    private $dc_estado;

    //detalle de cartera
    private $iddetalle_credito_proveedor;
    private $estado;
    private $retencion;

    private $cuenta_contable; ## <---- nuevo
    private $cuenta_contable_pago; ## <---- nuevo
    private $idcomprobante;

    private $idsucursal;


    public function __construct($adapter) {
        $table ="credito";
        parent:: __construct($table, $adapter);
    }
    
    public function getIdsucursal()
    {
        return $this->idsucursal;
    }
    public function setIdsucursal($idsucursal)
    {
        $this->idsucursal = $idsucursal;
    }

    public function getIdcredito_proveedor()
    {
        return $this->idcredito_proveedor;
    }
    public function setIdcredito_proveedor($idcredito_proveedor)
    {
        $this->idcredito_proveedor = $idcredito_proveedor;
    }
    public function getIdingreso()
    {
        return $this->idingreso;
    }
    public function setIdingreso($idingreso)
    {
        $this->idingreso = $idingreso;
    }
    public function getFecha_pago()
    {
        return $this->fecha_pago;
    }
    public function setFecha_pago($fecha_pago)
    {
        $this->fecha_pago = $fecha_pago;
    }
    public function getTotal_pago()
    {
        return $this->total_pago;
    }
    public function setTotal_pago($total_pago)
    {
        $this->total_pago = $total_pago;
    }
    public function getDeuda_total()
    {
        return $this->deuda_total;
    }
    public function setDeuda_total($deuda_total)
    {
        $this->deuda_total = $deuda_total;
    }
    public function getCp_estado()
    {
        return $this->cp_estado;
    }
    public function setCp_estado($cp_estado)
    {
        $this->cp_estado = $cp_estado;
    }
    public function getPago_parcial()
    {
        return $this->pago_parcial;
    }
    public function setPago_parcial($pago_parcial)
    {
        $this->pago_parcial = $pago_parcial;
    }
    public function getDeuda_parcial()
    {
        return $this->deuda_parcial;
    }
    public function setDeuda_parcial($deuda_parcial)
    {
        $this->deuda_parcial = $deuda_parcial;
    }
    public function getDcp_estado()
    {
        return $this->dcp_estado;
    }
    public function setDcp_estado($dcp_estado)
    {
        $this->dcp_estado = $dcp_estado;
    }
    public function getIdcredito()
    {
        return $this->idcredito;
    }
    public function setIdcredito($idcredito)
    {
        $this->idcredito = $idcredito;
    }
    public function getIdventa()
    {
        return $this->idventa;
    }
    public function setIdventa($idventa)
    {
        $this->idventa = $idventa;
    }


    
    public function getC_estado()
    {
        return $this->c_estado;
    }
    public function setC_estado($c_estado)
    {
        $this->c_estado = $c_estado;
    }
    
    
    public function getDc_estado()
    {
        return $this->dc_estado;
    }
    public function setDc_estado($dc_estado)
    {
        $this->dc_estado = $dc_estado;
    }

    public function getIddetalle_credito_proveedor()
    {
        return $this->iddetalle_credito_proveedor;
    }
    public function setIddetalle_credito_proveedor($iddetalle_credito_proveedor)
    {
        $this->iddetalle_credito_proveedor = $iddetalle_credito_proveedor;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getRetencion()
    {
        return $this->retencion;
    }
    public function setRetencion($retencion)
    {
        $this->retencion = $retencion;
    }

    public function getMonto()
    {
        return $this->monto;
    }
    public function setMonto($monto)
    {
        $this->monto = $monto;
    }
    public function getTipo_pago()
    {
        return $this->tipo_pago;
    }
    public function setTipo_pago($tipo_pago)
    {
        $this->tipo_pago = $tipo_pago;
    }

    public function getIddetalle_credito()
    {
        return $this->iddetalle_credito;
    }

    public function setIddetalle_credito($iddetalle_credito)
    {
        $this->iddetalle_credito = $iddetalle_credito;
    }

    public function getContabilidad()
    {
        return $this->contabilidad;
    } 
    public function setContabilidad($contabilidad)
    {
        $this->contabilidad = $contabilidad;
    }

    ##nuevo

    public function getCuenta_contable()
    {
        return $this->cuenta_contable;
    }
    public function setCuenta_contable($cuenta_contable)
    {
        $this->cuenta_contable = $cuenta_contable;
    }

    public function getCuenta_contable_pago()
    {
        return $this->cuenta_contable_pago;
    }
    public function setCuenta_contable_pago($cuenta_contable_pago)
    {
        $this->cuenta_contable_pago = $cuenta_contable_pago;
    }
    public function getIdcomprobante()
    {
        return $this->idcomprobante;
    }
    public function setIdcomprobante($idcomprobante)
    {
        $this->idcomprobante = $idcomprobante;
    }


    public function getCarteraProveedorByIngreso($idingreso)
    {
        $query = $this->db()->query("SELECT * FROM credito_proveedor WHERE idingreso = '$idingreso'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }
    public function getCarteraClienteByVenta($idventa)
    {
        $query = $this->db()->query("SELECT * FROM credito WHERE idventa = '$idventa'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }

    public function generarCarteraProveedor()
    {
        if(!empty($_SESSION["idsucursal"])){
            $query ="INSERT INTO `credito_proveedor` (idingreso,idsucursal,fecha_pago,total_pago,deuda_total,contabilidad,estado)
            VALUES(
                '".$this->idingreso."',
                '".$_SESSION["idsucursal"]."',
                '".$this->fecha_pago."',
                '".$this->total_pago."',
                '".$this->deuda_total."',
                '".$this->contabilidad."',
                '".$this->cp_estado."'  
            )";
        $addIngreso=$this->db()->query($query);

        }
    }
    public function updateCarteraProveedor($idingreso)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $query ="UPDATE credito_proveedor
            SET
            idingreso = '".$this->idingreso."',
            fecha_pago = '".$this->fecha_pago."',
            total_pago = '".$this->total_pago."',
            deuda_total = '".$this->deuda_total."',
            contabilidad = '".$this->contabilidad."',
            estado = '".$this->cp_estado."'
            WHERE idingreso = '$idingreso'";
        $addIngreso=$this->db()->query($query);
        }
    }
    public function deleteCarteraProveedor($idingreso)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("DELETE FROM credito_proveedor WHERE idingreso = '$idingreso'");
            return $query;
        }else{
            return false;
        }
    }

    public function generarCarteraCliente()
    {
        if(!empty($_SESSION["idsucursal"])){
            $query ="INSERT INTO `credito` (idventa,idsucursal,fecha_pago,total_pago,deuda_total,contabilidad,estado)
            VALUES(
                '".$this->idventa."',
                '".$_SESSION["idsucursal"]."',
                '".$this->fecha_pago."',
                '".$this->total_pago."',
                '".$this->deuda_total."',
                '".$this->contabilidad."',
                '".$this->c_estado."'
                
            )";

        $addCartera=$this->db()->query($query);
        if($addCartera){
            return $this->db()->insert_id;
        }else{
            return false;
        }
        }
    }
    public function updateCarteraCliente($idventa)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $query ="UPDATE credito
            SET
            idventa = '".$this->idventa."',
            fecha_pago = '".$this->fecha_pago."',
            total_pago = '".$this->total_pago."',
            deuda_total = '".$this->deuda_total."',
            contabilidad = '".$this->contabilidad."',
            estado = '".$this->cp_estado."'
            WHERE idventa = '$idventa'";
        $updateCartera=$this->db()->query($query);
        return $updateCartera;
        }
    }

    public function deleteCarteraCliente($idventa)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("DELETE FROM credito WHERE idventa = '$idventa'");
            return $query;
        }else{
            return false;
        }
    }

    public function getCreditoProveedorAll()
    {
        $query=$this->db()->query("SELECT cp.*,p.*,i.*, i.total, cp.estado as estado_credito, i.estado as estado_ingreso, p.nombre as nombre_proveedor
        FROM credito_proveedor cp
        INNER JOIN ingreso i on cp.idingreso = i.idingreso
        INNER JOIN persona p on i.idproveedor = p.idpersona
        WHERE cp.idsucursal = '".$_SESSION["idsucursal"]."'
        ORDER BY i.idingreso DESC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{$resultSet=[];}
        $query2=$this->db()->query("SELECT cp.*,p.*,cc.*, cc.cc_id_tipo_cpte as total, cp.estado as estado_credito, cc.cc_estado as estado_ingreso, p.nombre as nombre_proveedor,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante, cc.cc_fecha_cpte as fecha, cc.cc_fecha_cpte as fecha_final, cc_det_fact_prov as factura_proveedor
        FROM credito_proveedor cp
        INNER JOIN comprobante_contable cc on cp.idingreso = cc.cc_id_transa
        INNER JOIN persona p on cc.cc_idproveedor = p.idpersona
        WHERE cc.cc_ccos_cpte = '".$_SESSION["idsucursal"]."' AND cc.cc_tipo_comprobante = 'I' ORDER BY cc.cc_id_transa DESC");
        if($query2->num_rows > 0){
            while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
            }
        }else{
            
        }

        return $resultSet;
    }

    public function getCreditoProveedorContableAll()
    {
        // $query2=$this->db()->query("SELECT cp.*,p.*,ic.*, ic.ic_id_tipo_cpte as total, cp.estado as estado_credito, ic.ic_estado as estado_ingreso, p.nombre as nombre_proveedor,
        // ic.ic_num_cpte as serie_comprobante, ic.ic_cons_cpte as num_comprobante, ic.ic_fecha_cpte as fecha, ic.ic_fecha_cpte as fecha_final, ic_det_fact_prov as factura_proveedor
        // FROM credito_proveedor cp
        // INNER JOIN ingreso_contable ic on cp.idingreso = ic.ic_id_transa
        // INNER JOIN persona p on ic.ic_idproveedor = p.idpersona
        // WHERE ic.ic_ccos_cpte = '".$_SESSION["idsucursal"]."' ORDER BY ic.ic_id_transa DESC");
        // if($query2->num_rows > 0){
        //     while ($row = $query2->fetch_object()) {
        //         $resultSet[]=$row;
        //     }
        // }else{$resultSet=[];}
        // return $resultSet;
    }

    public function getCreditoClienteAll()
    {   
        $resultSet = [];
       $query=$this->db()->query("SELECT c.*,p.*,v.*, v.total, c.estado as estado_credito, v.estado as estado_venta, p.nombre as nombre_cliente
        FROM credito c
        INNER JOIN venta v on c.idventa = v.idventa
        INNER JOIN persona p on v.idCliente = p.idpersona
        WHERE v.idsucursal = '".$_SESSION["idsucursal"]."'
        ORDER BY v.idventa DESC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{}

        $query2=$this->db()->query("SELECT c.*,p.*,cc.*, cc.cc_id_tipo_cpte as total, c.estado as estado_credito, cc.cc_estado as estado_ingreso, p.nombre as nombre_cliente,
        cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante, cc.cc_fecha_cpte as fecha, cc.cc_fecha_final_cpte as fecha_final, cc_det_fact_prov as factura_proveedor
        FROM credito c
        INNER JOIN comprobante_contable cc on c.idventa = cc.cc_id_transa ##aqui
        INNER JOIN persona p on cc.cc_idproveedor = p.idpersona
        WHERE cc.cc_ccos_cpte = '".$_SESSION["idsucursal"]."' ORDER BY cc.cc_id_transa DESC");
        if($query2->num_rows > 0){
            while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
            }
        }else{}
        

        return $resultSet;

        
        $query=$this->db()->query("SELECT c.*,p.*,v.*, v.total, c.estado as estado_credito, v.estado as estado_venta, p.nombre as nombre_cliente
        FROM credito c
        INNER JOIN venta v on c.idventa = v.idventa
        INNER JOIN persona p on v.idCliente = p.idpersona
        WHERE v.idsucursal = '".$_SESSION["idsucursal"]."'
        ORDER BY v.idventa DESC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
        }

        $query2=$this->db()->query("SELECT c.*,p.*,vc.*, vc.vc_id_tipo_cpte as total, c.estado as estado_credito, vc.vc_estado as estado_ingreso, p.nombre as nombre_cliente,
        vc.vc_num_cpte as serie_comprobante, vc.vc_cons_cpte as num_comprobante, vc.vc_fecha_cpte as fecha, vc.vc_fecha_cpte as fecha_final
        FROM credito c
        INNER JOIN venta_contable vc on c.idventa = vc.vc_id_transa
        INNER JOIN persona p on vc.vc_idproveedor = p.idpersona
        WHERE vc.vc_ccos_cpte = '".$_SESSION["idsucursal"]."' ORDER BY vc.vc_id_transa DESC");
        if($query2->num_rows > 0){
            while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
            }
        }else{$resultSet=[];}

        return $resultSet;
    }

    public function getCreditoProveedorById($idcredito_proveedor)
    {
        $query=$this->db()->query("SELECT cp.*,p.*,i.*, i.total, cp.estado as estado_credito, i.estado as estado_ingreso, p.nombre as nombre_proveedor,
        i.factura_proveedor as det_fact
        FROM credito_proveedor cp
        INNER JOIN ingreso i on cp.idingreso = i.idingreso
        INNER JOIN persona p on i.idproveedor = p.idpersona
        WHERE cp.idcredito_proveedor = '$idcredito_proveedor' and cp.contabilidad = 0");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $query=$this->db()->query("SELECT cp.*,p.*,cc.*, cp.deuda_total as total, cp.estado as estado_credito, cc.cc_estado as estado_ingreso, p.nombre as nombre_proveedor,
            cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante, cc.cc_det_fact_prov as det_fact
            FROM credito_proveedor cp
            INNER JOIN comprobante_contable cc on cp.idingreso = cc.cc_id_transa
            INNER JOIN persona p on cc.cc_idproveedor = p.idpersona
            WHERE cp.idcredito_proveedor = '$idcredito_proveedor' and cp.contabilidad = 1");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                    $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
        }
        return $resultSet;
    }

    public function getPagoCarteraProveedor($idcredito_proveedor)
    {
        $query=$this->db()->query("SELECT * FROM detalle_credito_proveedor dcp
        INNER JOIN tb_metodo_pago mp ON mp.mp_id = dcp.tipo_pago
        WHERE dcp.idcredito_proveedor = '$idcredito_proveedor'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=null;
        }
        return $resultSet;
    }

    public function getPagoCarteraProveedorBy($idcredito_proveedor,$column,$value)
    {
        $query=$this->db()->query("SELECT * FROM detalle_credito_proveedor
        WHERE idcredito_proveedor = '$idcredito_proveedor' AND $column = '$value'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=null;
        }
        return $resultSet;
    }

    public function getPagoCarteraClienteBy($idcredito,$column,$value)
    {
        $query=$this->db()->query("SELECT * FROM detalle_credito
        WHERE idcredito = '$idcredito' AND $column = '$value'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=null;
        }
        return $resultSet;
    }

    public function reporte_pago_cartera_cliente($start_date, $end_date, $idusuario =null)
    {
        $placeholder ="";
        if(isset($idusuario) && $idusuario != null){
            $placeholder .= "AND v.idusuario = '".$idusuario."' ";
        }
        $query=$this->db()->query("SELECT *, v.total as total_venta
        FROM detalle_credito dc
        INNER JOIN tb_metodo_pago mp ON dc.tipo_pago = mp.mp_id
        INNER JOIN credito c ON dc.idcredito = c.idcredito
        INNER JOIN venta v ON c.idventa = v.idventa
        INNER JOIN persona p ON v.idCliente = p.idpersona
        WHERE dc.fecha_pago LIKE '$start_date%' AND dc.fecha_pago LIKE '$end_date%'
        AND v.estado='A' AND dc.estado = '1' $placeholder");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function reporte_pago_cartera_cliente_group_by_metodopago($start_date, $end_date, $idusuario =null)
    {
        $placeholder ="";
        if(isset($idusuario) && $idusuario != null){
            $placeholder .= "AND v.idusuario = '".$idusuario."' ";
        }
        $query=$this->db()->query("SELECT *, v.total as total_cartera
        FROM detalle_credito dc
        INNER JOIN tb_metodo_pago mp ON dc.tipo_pago = mp.mp_id
        INNER JOIN credito c ON dc.idcredito = c.idcredito
        INNER JOIN venta v ON c.idventa = v.idventa
        INNER JOIN persona p ON v.idCliente = p.idpersona
        WHERE dc.fecha_pago LIKE '$start_date%' AND dc.fecha_pago LIKE '$end_date%'
        AND v.estado='A' AND dc.estado = '1' $placeholder ORDER BY mp.mp_id ASC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function reporte_pago_cartera_proveedor($start_date, $end_date, $idusuario =null)
    {
        $placeholder ="";
        if(isset($idusuario) && $idusuario != null){
            $placeholder .= "AND v.idusuario = '".$idusuario."' ";
        }
        $query=$this->db()->query("SELECT * FROM detalle_credito_proveedor dcp
        INNER JOIN tb_metodo_pago mp ON dcp.tipo_pago = mp.mp_id
        INNER JOIN credito_proveedor cp ON dcp.idcredito_proveedor = cp.idcredito_proveedor
        INNER JOIN ingreso i ON cp.idingreso = i.idingreso
        INNER JOIN persona p ON i.idproveedor = p.idpersona
        WHERE dcp.fecha_pago LIKE '$start_date%' AND dcp.fecha_pago LIKE '$end_date%'
        AND i.estado='A' AND dcp.estado = '1' $placeholder");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getDetalleMotodoPagoByCompras($start_date, $end_date, $idusuario = null)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $placeholder ='';
            if(isset($idusuario) && $idusuario !=null){
                $placeholder .=" AND i.idusuario = '".$idusuario."' ";
            }
            $query=$this->db()->query("SELECT *
                FROM detalle_credito_proveedor dcp
                INNER JOIN tb_metodo_pago mp ON dcp.tipo_pago = mp.mp_id
                INNER JOIN credito_proveedor cp ON dcp.idcredito_proveedor = cp.idcredito_proveedor
                INNER JOIN ingreso i ON cp.idingreso = i.idingreso
                INNER JOIN persona p ON i.idproveedor = p.idpersona
                WHERE dcp.fecha_pago LIKE '$start_date%' AND dcp.fecha_pago LIKE '$end_date%'
                AND i.estado='A' AND dcp.estado = '1' $placeholder");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{return [];}
    }

    public function getDetalleMotodoPagoByVentas($start_date, $end_date, $idusuario = null)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>0){
            $placeholder ='';
            if(isset($idusuario) && $idusuario !=null){
                $placeholder .=" AND v.idusuario = '".$idusuario."' ";
            }
            $query=$this->db()->query("SELECT *
                FROM detalle_credito dc
                INNER JOIN tb_metodo_pago mp ON dc.tipo_pago = mp.mp_id
                INNER JOIN credito c ON dc.idcredito = c.idcredito
                INNER JOIN venta v ON c.idventa = v.idventa
                INNER JOIN persona p ON v.idCliente = p.idpersona
                WHERE dc.fecha_pago LIKE '$start_date%' AND dc.fecha_pago LIKE '$end_date%'
                AND v.estado='A' AND dc.estado = '1' $placeholder");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{return [];}
    }

    public function generar_pago_proveedor()
    {
        if(!empty($_SESSION["idsucursal"])){
            $query ="INSERT INTO `detalle_credito_proveedor` (idcredito_proveedor,idcomprobante,cuenta_contable,cuenta_contable_pago,pago_parcial,deuda_parcial,retencion,monto,tipo_pago,estado)
            VALUES(
                '".$this->idcredito_proveedor."',
                '".$this->idcomprobante."',
                '".$this->cuenta_contable."',
                '".$this->cuenta_contable_pago."',
                '".$this->pago_parcial."',
                '".$this->deuda_parcial."',
                '".$this->retencion."',
                '".$this->monto."',
                '".$this->tipo_pago."',
                '".$this->estado."'
            )";
        $addIngreso=$this->db()->query($query);
           
            $update_cartera = "UPDATE credito_proveedor SET total_pago = total_pago+'".$this->pago_parcial."'
            WHERE idcredito_proveedor = '".$this->idcredito_proveedor."'";
            $update = $this->db()->query($update_cartera);
    }

    }
    public function getCreditoClienteById($idcredito)
    {
        $query=$this->db()->query("SELECT c.*,p.*,v.*, v.total, c.estado as estado_credito, v.estado as estado_venta, p.nombre as nombre_cliente, concat(v.serie_comprobante,v.num_comprobante) as det_fact
        FROM credito c
        INNER JOIN venta v on v.idventa = c.idventa
        INNER JOIN persona p on v.idCliente = p.idpersona
        WHERE c.idcredito = '$idcredito'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $query=$this->db()->query("SELECT cp.*,p.*,cc.*, cp.deuda_total as total, cp.estado as estado_credito, cc.cc_estado as estado_venta, p.nombre as nombre_cliente,
            cc.cc_num_cpte as serie_comprobante, cc.cc_cons_cpte as num_comprobante, cc.cc_det_fact_prov as det_fact
            FROM credito cp
            INNER JOIN comprobante_contable cc on cp.idventa = cc.cc_id_transa
            INNER JOIN persona p on cc.cc_idproveedor = p.idpersona
            WHERE cp.idcredito = '$idcredito' and cp.contabilidad = 1");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                    $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
        }
        return $resultSet;
    }

    public function getPagoCarteraCliente($idcredito)
    {
        $query=$this->db()->query("SELECT * FROM detalle_credito dc
        INNER JOIN tb_metodo_pago mp ON mp.mp_id = dc.tipo_pago
        WHERE idcredito = '$idcredito'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=null;
        }
        return $resultSet;
    }

    public function getPagoCarteraAll($limit)
    {
        $query=$this->db()->query("SELECT *, dc.fecha_pago as fecha_transaccion 
        FROM detalle_credito dc
        INNER JOIN credito c on dc.idcredito = c.idcredito
        INNER JOIN venta v on c.idventa = v.idventa
        INNER JOIN persona p on v.idCliente = p.idpersona
        ORDER BY dc.iddetalle_credito AND v.idsucursal = '".$_SESSION["idsucursal"]."' DESC LIMIT $limit");
        
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=null;
        }
        $query2=$this->db()->query("SELECT *, dc.fecha_pago as fecha_transaccion, cc_num_cpte as serie_comprobante, cc_cons_cpte as num_comprobante 
        FROM detalle_credito dc
        INNER JOIN credito c on dc.idcredito = c.idcredito
        INNER JOIN comprobante_contable cc on c.idventa = cc.cc_id_transa
        INNER JOIN persona p on cc.cc_idproveedor = p.idpersona
        ORDER BY dc.iddetalle_credito AND cc.cc_ccos_cpte = '".$_SESSION["idsucursal"]."' DESC LIMIT $limit");
        if($query2->num_rows > 0){
            while ($row = $query2->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=null;
        }


        return $resultSet;
    }


    public function generar_pago_cliente()
    {
        if(!empty($_SESSION["idsucursal"])){
            $query ="INSERT INTO `detalle_credito` (idcredito,idcomprobante,cuenta_contable,cuenta_contable_pago,pago_parcial,deuda_parcial,retencion,monto,tipo_pago,estado)
            VALUES(
                '".$this->idcredito."',
                '".$this->idcomprobante."',
                '".$this->cuenta_contable."',
                '".$this->cuenta_contable_pago."',
                '".$this->pago_parcial."',
                '".$this->deuda_parcial."',
                '".$this->retencion."',
                '".$this->monto."',
                '".$this->tipo_pago."',
                '".$this->estado."'
            )";
        $addIngreso=$this->db()->query($query);
           
        if($addIngreso){
            $update_cartera = "UPDATE credito SET total_pago = total_pago+'".$this->pago_parcial."'
            WHERE idcredito = '".$this->idcredito."'";
            $update = $this->db()->query($update_cartera);
            return $update;
        }else{
            return false;
        }
        }
    }

    public function anularCarteraCliente()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query = $this->db()->query("UPDATE credito SET estado = '".$this->estado."' WHERE idcredito = '".$this->idcredito."' AND idsucursal = '".$this->idsucursal."'");

            $detallecartera = $this->db()->query("UPDATE detalle_credito SET estado = 0 WHERE idcredito = '".$this->idcredito."'");
            return $query;
        }else{
            return false;
        }
    }


}
