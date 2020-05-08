<?php

class Retenciones Extends EntidadBase{

    private $re_id;
    private $re_nombre;
    private $re_porcentaje;
    private $re_base;
    private $re_cta_contable;
    private $re_im_id;
    private $re_estado;
    private $drc_det_documento_sucursal;
    private $drc_re_id;

    public function __construct($adapter) {
        $table ="tb_retenciones";
        parent:: __construct($table, $adapter);
    }
    public function getRe_id()
    {
        return $this->re_id;
    }
    public function setRe_id($re_id)
    {
        $this->re_id = $re_id;
    }
    public function getRe_nombre()
    {
        return $this->re_nombre;
    }
    public function setRe_nombre($re_nombre)
    {
        $this->re_nombre = $re_nombre;
    }
    public function getRe_porcentaje()
    {
        return $this->re_porcentaje;
    }
    public function setRe_porcentaje($re_porcentaje)
    {
        $this->re_porcentaje = $re_porcentaje;
    }
    public function getRe_base()
    {
        return $this->re_base;
    }
    public function setRe_base($re_base)
    {
        $this->re_base = $re_base;
    }

    public function getDrc_det_documento_sucursal()
    {
        return $this->drc_det_documento_sucursal;
    }
    public function setDrc_det_documento_sucursal($drc_det_documento_sucursal)
    {
        $this->drc_det_documento_sucursal = $drc_det_documento_sucursal;
    }
    public function getDrc_re_id()
    {
        return $this->drc_re_id;
    }
    public function setDrc_re_id($drc_re_id)
    {
        $this->drc_re_id = $drc_re_id;
    }

    public function getRe_cta_contable()
    {
        return $this->re_cta_contable;
    }
    public function setRe_cta_contable($re_cta_contable)
    {
        $this->re_cta_contable = $re_cta_contable;
    }
    public function getRe_im_id()
    {
        return $this->re_im_id;
    }
    public function setRe_im_id($re_im_id)
    {
        $this->re_im_id = $re_im_id;
    }
    public function getRe_estado()
    {
        return $this->re_estado;
    }
    public function setRe_estado($re_estado)
    {
        $this->re_estado = $re_estado;
    }

    public function getRetencionesAll()
    {
        $query=$this->db()->query("SELECT * 
        FROM tb_retenciones re 
        INNER JOIN tb_impuestos im on re.re_im_id = im.im_id
        WHERE  re.re_estado = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function add_retencion()
    {
        $query ="INSERT INTO tb_retenciones (`re_nombre`,`re_porcentaje`,`re_base`,`re_cta_contable`,`re_im_id`,`re_estado`)
        VALUES(
            '".$this->re_nombre."',
            '".$this->re_porcentaje."',
            '".$this->re_base."',
            '".$this->re_cta_contable."',
            '".$this->re_im_id."',
            '".$this->re_estado."'
        )";
        $add_impuesto = $this->db()->query($query);
        return $add_impuesto;
    }

    public function update_retencion($idretencion)
    {
        $query ="UPDATE tb_retenciones SET
            re_nombre ='".$this->re_nombre."',
            re_porcentaje = '".$this->re_porcentaje."',
            re_base = '".$this->re_base."',
            re_cta_contable = '".$this->re_cta_contable."',
            re_im_id = '".$this->re_im_id."',
            re_estado = '".$this->re_estado."'
            WHERE re_id = '$idretencion'";
        $add_impuesto = $this->db()->query($query);
        return $add_impuesto;
    }

    public function getRetencionesByComprobanteId($id)
    {
        //tb_detalle_retenciones_comprobantes
        $query=$this->db()->query("SELECT * from tb_detalle_retenciones_comprobantes drc
        INNER JOIN tb_retenciones r on drc.drc_re_id = r.re_id WHERE drc.drc_det_documento_sucursal = '$id' AND r.re_estado = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getRetencionesById($id)
    {
        $query=$this->db()->query("SELECT * FROM tb_retenciones re
        INNER JOIN tb_impuestos im on re.re_im_id = im.im_id
        WHERE re.re_id = '$id'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function addRetencionComprobante()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $query ="INSERT INTO tb_detalle_retenciones_comprobantes (drc_det_documento_sucursal,drc_re_id)
                VALUES(
                    '".$this->drc_det_documento_sucursal."',
                    '".$this->drc_re_id."'
                )";
            $add_impuesto = $this->db()->query($query);
            return $add_impuesto;
        }else{
            return false;
        }
    }

    public function deleteRetencionByComprobante($idcomprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("DELETE FROM tb_detalle_retenciones_comprobantes WHERE drc_det_documento_sucursal = '$idcomprobante'");
            return $query;
        }else{
            return false;
        }
    }
    
    public function deleteRetencionById($idretencion)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("UPDATE tb_retenciones SET re_estado = 'D' WHERE re_id = '$idretencion'");
            return $query;
        }else{
            return false;
        }
    }

    
}