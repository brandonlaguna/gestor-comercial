<?php

class Comprobante Extends EntidadBase{

    private $iddetalle_documento_sucursal;
    private $idsucursal;
    private $idtipo_documento;
    private $ultima_serie;
    private $ultimo_numero;
    private $contabilidad;
    private $ddc_impuesto_comprobante;
    private $ddc_retencion_comprobante;
    private $activo;
    private $dds_pri_id;
    //configuracion de impresion
    private $pri_id;
    private $pri_nombre;
    private $pri_conf;
    private $pri_estado;
    //configuracion de resolucion
    private $pf_id;
    private $pf_iddetalle_documento_sucursal;
    private $pf_text;
     
    public function __construct($adapter) {
        $table ="detalle_documento_sucursal";
        parent:: __construct($table, $adapter);
    }
    
    public function getIddetalle_documento_sucursal()
    {
        return $this->iddetalle_documento_sucursal;
    }
    public function setIddetalle_documento_sucursal($iddetalle_documento_sucursal)
    {
        $this->iddetalle_documento_sucursal = $iddetalle_documento_sucursal;
    }
    public function getIdsucursal()
    {
        return $this->idsucursal;
    }
    public function setIdsucursal($idsucursal)
    {
        $this->idsucursal = $idsucursal;
    }
    public function getIdtipo_documento()
    {
        return $this->idtipo_documento;
    }
    public function setIdtipo_documento($idtipo_documento)
    {
        $this->idtipo_documento = $idtipo_documento;
    }
    public function getUltima_serie()
    {
        return $this->ultima_serie;
    }
    public function setUltima_serie($ultima_serie)
    {
        $this->ultima_serie = $ultima_serie;
    }
    public function getUltimo_numero()
    {
        return $this->ultimo_numero;
    }
    public function setUltimo_numero($ultimo_numero)
    {
        $this->ultimo_numero = $ultimo_numero;
    }
    public function getContabilidad()
    {
        return $this->contabilidad;
    }
    public function setContabilidad($contabilidad)
    {
        $this->contabilidad = $contabilidad;
    }
    public function getDdc_impuesto_comprobante()
    {
        return $this->ddc_impuesto_comprobante;
    }
    public function setDdc_impuesto_comprobante($ddc_impuesto_comprobante)
    {
        $this->ddc_impuesto_comprobante = $ddc_impuesto_comprobante;
    }
    public function getDdc_retencion_comprobante()
    {
        return $this->ddc_retencion_comprobante;
    }
    public function setDdc_retencion_comprobante($ddc_retencion_comprobante)
    {
        $this->ddc_retencion_comprobante = $ddc_retencion_comprobante;
    }
    public function getActivo()
    {
        return $this->activo;
    }
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    public function getDds_pri_id()
    {
        return $this->dds_pri_id;
    }
    public function setDds_pri_id($dds_pri_id)
    {
        $this->dds_pri_id = $dds_pri_id;
    }

    public function getPf_id()
    {
        return $this->pf_id;
    }
    public function setPf_id($pf_id)
    {
        $this->pf_id = $pf_id;
    }
    public function getPf_iddetalle_documento_sucursal()
    {
        return $this->pf_iddetalle_documento_sucursal;
    }
    public function setPf_iddetalle_documento_sucursal($pf_iddetalle_documento_sucursal)
    {
        $this->pf_iddetalle_documento_sucursal = $pf_iddetalle_documento_sucursal;
    }
    public function getPf_text()
    {
        return $this->pf_text;
    }
    public function setPf_text($pf_text)
    {
        $this->pf_text = $pf_text;
    }

    public function getConfPrint()
    {
        $query = $this->db()->query("SELECT * FROM tb_conf_print");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function add_comprobante()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
        $query = "INSERT INTO detalle_documento_sucursal (idsucursal,idtipo_documento,ultima_serie,ultimo_numero,contabilidad,ddc_impuesto_comprobante,ddc_retencion_comprobante,dds_pri_id,activo)
        VALUES(
            '".$_SESSION["idsucursal"]."',
            '".$this->idtipo_documento."',
            '".$this->ultima_serie."',
            '".$this->ultimo_numero."',
            '".$this->contabilidad."',
            '".$this->ddc_impuesto_comprobante."',
            '".$this->ddc_retencion_comprobante."',
            '".$this->dds_pri_id."',
            '".$this->activo."')";
            $addComprobante=$this->db()->query($query);

            $returnId=$this->db()->query("SELECT iddetalle_documento_sucursal as id FROM detalle_documento_sucursal ORDER BY iddetalle_documento_sucursal DESC LIMIT 1");
            if($returnId->num_rows > 0){
                while($row = $returnId->fetch_assoc()) {
                    $idcomprobante= $row["id"];
                }
            }else{$idcomprobante=0;}

            return $idcomprobante;
            
        }else{
            return false;
        }
    }
    public function update_comprobante($idcomprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $query ="UPDATE detalle_documento_sucursal SET
            idtipo_documento = '".$this->idtipo_documento."',
            ultima_serie = '".$this->ultima_serie."',
            ultimo_numero = '".$this->ultimo_numero."',
            contabilidad = '".$this->contabilidad."',
            ddc_impuesto_comprobante = '".$this->ddc_impuesto_comprobante."',
            ddc_retencion_comprobante = '".$this->ddc_retencion_comprobante."',
            dds_pri_id = '".$this->dds_pri_id."',
            activo = '".$this->activo."'
            WHERE iddetalle_documento_sucursal = '$idcomprobante'";
            $update = $this->db()->query($query);
            return $update;

        }else{
            return false;
        }
    } 

    public function getComprobanteAll()
    {
        $query=$this->db()->query("SELECT * FROM detalle_documento_sucursal dds
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
        INNER JOIN tb_conf_print pri on dds.dds_pri_id = pri.pri_id
        WHERE dds.idsucursal = '".$_SESSION['idsucursal']."' AND dds.activo = 1 ");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getComprobanteContable($pos_proceso)
    {
        $query=$this->db()->query("SELECT * FROM detalle_documento_sucursal dds
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
        WHERE dds.idsucursal = '".$_SESSION['idsucursal']."' AND dds.activo = 1 AND dds.contabilidad = 1 AND td.proceso = '$pos_proceso'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }

    public function getAllComprobanteContable()
    {
        $query=$this->db()->query("SELECT * FROM detalle_documento_sucursal dds
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
        WHERE dds.idsucursal = '".$_SESSION['idsucursal']."' AND dds.activo = 1 AND dds.contabilidad = 1 ");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }

    public function getComprobante($pos_proceso)
    {
        $query=$this->db()->query("SELECT * FROM detalle_documento_sucursal dds
        INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
        WHERE dds.idsucursal = '".$_SESSION['idsucursal']."' AND dds.activo = 1 AND td.proceso = '$pos_proceso'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

   public function usarComprobante($id)
   {
    $plus = zero_fill(1, 8);
    $query ="UPDATE detalle_documento_sucursal SET 
    `ultimo_numero` = ultimo_numero+$plus
     WHERE iddetalle_documento_sucursal = '$id' ";
    $update = $this->db()->query($query);
    return $update;
   }

   public function getComprobanteById($id)
   {
    $query=$this->db()->query("SELECT * FROM detalle_documento_sucursal dds
    INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
    INNER JOIN tb_pie_factura pf on dds.iddetalle_documento_sucursal = pf.pf_iddetalle_documento_sucursal
    INNER JOIN tb_conf_print pri on dds.dds_pri_id = pri.pri_id
    WHERE dds.idsucursal = '".$_SESSION['idsucursal']."' AND dds.iddetalle_documento_sucursal = '$id' LIMIT 1");
    if($query->num_rows > 0){
        while ($row = $query->fetch_object()) {
        $resultSet[]=$row;
        }
    }else{
        $resultSet=[];
    }
    return $resultSet;
   }

   public function delete_comprobanteById($idcomprobante)
   {
    if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
        $query ="UPDATE detalle_documento_sucursal SET
        activo = 0
        WHERE iddetalle_documento_sucursal = '$idcomprobante'";
        $update = $this->db()->query($query);
        return $update;
    }else{
        return false;
    }
   }
   public function getOnlyComprobanteById($id)
   {
    $query=$this->db()->query("SELECT * FROM detalle_documento_sucursal dds
    INNER JOIN tipo_documento td on dds.idtipo_documento = td.idtipo_documento 
    WHERE dds.idsucursal = '".$_SESSION['idsucursal']."' AND dds.iddetalle_documento_sucursal = '$id' LIMIT 1");
    if($query->num_rows > 0){
        while ($row = $query->fetch_object()) {
        $resultSet[]=$row;
        }
    }else{
        $resultSet=[];
    }
    return $resultSet;
   }


   public function addPieFactura()
   {
       $query = "INSERT INTO tb_pie_factura (pf_iddetalle_documento_sucursal,pf_text)
       VALUES (
        '".$this->pf_iddetalle_documento_sucursal."',
        '".$this->pf_text."')";
        $addPieFactura=$this->db()->query($query);
        return $addPieFactura;
   }

   public function updatePieFactura($idcomprobante)
   {
       $query ="UPDATE tb_pie_factura SET
       pf_text = '".$this->pf_text."'
       WHERE pf_iddetalle_documento_sucursal = '$idcomprobante'";
       $update = $this->db()->query($query);
       return $update;
   }

    
}