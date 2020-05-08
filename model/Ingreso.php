<?php
class Ingreso extends EntidadBase{

    private $idingreso;
    private $idsucursal;
    private $idusuario;
    private $idproveedor;
    private $tipo_pago;
    private $tipo_comprobante;
    private $serie_comprobante;
    private $num_comprobante;
    private $fecha;
    private $fecha_final;
    private $impuesto;
    private $sub_total;
    private $subtotal_importe;
    private $total;
    private $importe_pagado;
    private $estado;
    private $factura_proveedor;
    public function __construct($adapter) {
        $table ="ingreso";
        parent:: __construct($table, $adapter);
    }

    public function getIdingreso()
    {
        return $this->idingreso;
    }
    public function setIdingreso($idingreso)
    {
        $this->idingreso = $idingreso;
    }
    public function getIdsucursal()
    {
        return $this->idsucursal;
    }
    public function setIdsucursal($idsucursal)
    {
        $this->idsucursal = $idsucursal;
    }
    public function getIdusuario()
    {
        return $this->idusuario;
    }
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }
    public function getIdproveedor()
    {
        return $this->idproveedor;
    }
    public function setIdproveedor($idproveedor)
    {
        $this->idproveedor = $idproveedor;
    }
    public function getTipo_pago()
    {
        return $this->tipo_pago;
    }
    public function setTipo_pago($tipo_pago)
    {
        $this->tipo_pago = $tipo_pago;
    }
    public function getTipo_comprobante()
    {
        return $this->tipo_comprobante;
    }
    public function setTipo_comprobante($tipo_comprobante)
    {
        $this->tipo_comprobante = $tipo_comprobante;
    }
    public function getSerie_comprobante()
    {
        return $this->serie_comprobante;
    }
    public function setSerie_comprobante($serie_comprobante)
    {
        $this->serie_comprobante = $serie_comprobante;
    }
    public function getNum_comprobante()
    {
        return $this->num_comprobante;
    }
    public function setNum_comprobante($num_comprobante)
    {
        $this->num_comprobante = $num_comprobante;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function getFecha_final()
    {
        return $this->fecha_final;
    }
    public function setFecha_final($fecha_final)
    {
        $this->fecha_final = $fecha_final;
    }
    public function getImpuesto()
    {
        return $this->impuesto;
    }
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }
    public function getSub_total()
    {
        return $this->sub_total;
    }
    public function setSub_total($sub_total)
    {
        $this->sub_total = $sub_total;
    }
    public function getSubtotal_importe()
    {
        return $this->subtotal_importe;
    }
    public function setSubtotal_importe($subtotal_importe)
    {
        $this->subtotal_importe = $subtotal_importe;
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function setTotal($total)
    {
        $this->total = $total;
    }
    public function getImporte_pagado()
    {
        return $this->importe_pagado;
    }
    public function setImporte_pagado($importe_pagado)
    {
        $this->importe_pagado = $importe_pagado;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function getFactura_proveedor()
    {
        return $this->factura_proveedor;
    }
    public function setFactura_proveedor($factura_proveedor)
    {
        $this->factura_proveedor = $factura_proveedor;
    }
    

    public function saveIngreso()
    {
        if(!empty($_SESSION["idsucursal"])){
            $query ="INSERT INTO `ingreso` (idusuario,idsucursal,idproveedor,tipo_pago,tipo_comprobante,serie_comprobante,num_comprobante,factura_proveedor,fecha,fecha_final,impuesto,sub_total,subtotal_importe,total,importe_pagado,estado)
            VALUES(
                '".$this->idusuario."',
                '".$this->idsucursal."',
                '".$this->idproveedor."',
                '".$this->tipo_pago."',
                '".$this->tipo_comprobante."',
                '".$this->serie_comprobante."',
                '".$this->num_comprobante."',
                '".$this->factura_proveedor."',
                '".$this->fecha."',
                '".$this->fecha_final."',
                '".$this->impuesto."',
                '".$this->sub_total."',
                '".$this->subtotal_importe."',
                '".$this->total."',
                '".$this->importe_pagado."',
                '".$this->estado."')";
        $addIngreso=$this->db()->query($query);

        $returnId=$this->db()->query("SELECT idingreso FROM ingreso ORDER BY idingreso DESC LIMIT 1");
        if($returnId->num_rows > 0){
            while($row = $returnId->fetch_assoc()) {
                $idingreso= $row["idingreso"];
            }
        }
        
       if($addIngreso){
           $status = $idingreso;
       }else{
            $status =false;
       }
       return $status;


        }else{
            return false;
        }
    }

    public function updateIngreso($idingreso)
    {
        if(isset($_POST["proveedor"]) && !empty($_POST["proveedor"]) && $_POST["comprobante"] > 4){
            $query ="UPDATE ingreso
            SET
                idusuario = '".$this->idusuario."',
                idsucursal = '".$this->idsucursal."',
                idproveedor = '".$this->idproveedor."',
                tipo_pago = '".$this->tipo_pago."',
                factura_proveedor = '".$this->factura_proveedor."',
                fecha = '".$this->fecha."',
                fecha_final = '".$this->fecha_final."',
                impuesto = '".$this->impuesto."',
                sub_total = '".$this->sub_total."',
                subtotal_importe = '".$this->subtotal_importe."',
                total = '".$this->total."',
                importe_pagado = '".$this->importe_pagado."',
                estado = '".$this->estado."'
                WHERE idingreso = '$idingreso'
                ";
        $updateIngreso=$this->db()->query($query);
        
       if($updateIngreso){
           $status = "Actualizada compra ".$idingreso;
       }else{
            $status =false;
       }
       return $status;
        }
    }

    public function addImpuestoIngreso($idingreso)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query ="UPDATE ingreso 
            SET
            subtotal_importe = '".$this->subtotal_importe."'
            WHERE idingreso = '$idingreso'";

            $addImpuesto=$this->db()->query($query);
            return $addImpuesto;
        }
    }


    public function saveArticulos()
    {
        if(!empty($_SESSION["idsucursal"])){
            $query ="INSERT INTO `detalle_ingreso` (idusuario)
            VALUES()";
        $addIngreso=$this->db()->query($query);

        }
    }

}
