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





    public function __construct($adapter) {
        $table ="credito";
        parent:: __construct($table, $adapter);
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
            $query ="INSERT INTO `credito_proveedor` (idingreso,fecha_pago,total_pago,deuda_total,contabilidad,estado)
            VALUES(
                '".$this->idingreso."',
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
            $query ="INSERT INTO `credito` (idventa,fecha_pago,total_pago,deuda_total,contabilidad,estado)
            VALUES(
                '".$this->idventa."',
                '".$this->fecha_pago."',
                '".$this->total_pago."',
                '".$this->deuda_total."',
                '".$this->contabilidad."',
                '".$this->c_estado."'
                
            )";
        $addCartera=$this->db()->query($query);

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
        ORDER BY i.idingreso DESC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{$resultSet=[];}

        

        return $resultSet;
    }

    public function getCreditoProveedorContableAll()
    {
        $query2=$this->db()->query("SELECT cp.*,p.*,ic.*, ic.ic_id_tipo_cpte as total, cp.estado as estado_credito, ic.ic_estado as estado_ingreso, p.nombre as nombre_proveedor,
        ic.ic_num_cpte as serie_comprobante, ic.ic_cons_cpte as num_comprobante, dic.dic_dato_fact_prove as factura_proveedor, ic.ic_fecha_cpte as fecha, dic.dic_fecha_vcto_item as fecha_final
        FROM credito_proveedor cp
        INNER JOIN ingreso_contable ic on cp.idingreso = ic.ic_id_transa
        INNER JOIN detalle_ingreso_contable dic on ic.ic_id_transa = dic.dic_id_trans
        INNER JOIN persona p on ic.ic_idproveedor = p.idpersona
        WHERE dic.dic_dato_fact_prove != '' GROUP BY dic.dic_dato_fact_prove ORDER BY ic.ic_id_transa DESC");
        if($query2->num_rows > 0){
            while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
            }
        }else{$resultSet=[];}
        return $resultSet;
    }

    public function getCreditoClienteAll()
    {
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
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getCreditoProveedorById($idcredito_proveedor)
    {
        $query=$this->db()->query("SELECT cp.*,p.*,i.*, i.total, cp.estado as estado_credito, i.estado as estado_ingreso, p.nombre as nombre_proveedor
        FROM credito_proveedor cp
        INNER JOIN ingreso i on cp.idingreso = i.idingreso
        INNER JOIN persona p on i.idproveedor = p.idpersona
        WHERE cp.idcredito_proveedor = '$idcredito_proveedor'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getPagoCarteraProveedor($idcredito_proveedor)
    {
        $query=$this->db()->query("SELECT * FROM detalle_credito_proveedor
        WHERE idcredito_proveedor = '$idcredito_proveedor'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=null;
        }
        return $resultSet;
    }

    public function generar_pago_proveedor()
    {
        if(!empty($_SESSION["idsucursal"])){
            $query ="INSERT INTO `detalle_credito_proveedor` (idcredito_proveedor,pago_parcial,deuda_parcial,retencion,monto,tipo_pago,estado)
            VALUES(
                '".$this->idcredito_proveedor."',
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
        $query=$this->db()->query("SELECT c.*,p.*,v.*, v.total, c.estado as estado_credito, v.estado as estado_venta, p.nombre as nombre_cliente
        FROM credito c
        INNER JOIN venta v on v.idventa = c.idventa
        INNER JOIN persona p on v.idCliente = p.idpersona
        WHERE c.idcredito = '$idcredito'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getPagoCarteraCliente($idcredito)
    {
        $query=$this->db()->query("SELECT * FROM detalle_credito
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
        ORDER BY dc.iddetalle_credito DESC LIMIT $limit");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
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
            $query ="INSERT INTO `detalle_credito` (idcredito,pago_parcial,deuda_parcial,retencion,monto,tipo_pago,estado)
            VALUES(
                '".$this->idcredito."',
                '".$this->pago_parcial."',
                '".$this->deuda_parcial."',
                '".$this->retencion."',
                '".$this->monto."',
                '".$this->tipo_pago."',
                '".$this->estado."'
            )";
        $addIngreso=$this->db()->query($query);
           
            $update_cartera = "UPDATE credito SET total_pago = total_pago+'".$this->pago_parcial."'
            WHERE idcredito = '".$this->idcredito."'";
            $update = $this->db()->query($update_cartera);
        }
    }

}