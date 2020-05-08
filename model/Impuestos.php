<?php
class Impuestos extends EntidadBase{

    private $im_id;
    private $im_nombre;
    private $im_porcentaje;
    private $im_base;
    private $im_cta_contable;
    private $im_proceso;
    private $im_estado;
    private $dic_det_documento_sucursal;
    private $dic_im_id;

    public function __construct($adapter) {
        $table ="tb_impuestos";
        parent:: __construct($table, $adapter);
    }

    public function getIm_id()
    {
        return $this->im_id;
    }
    public function setIm_id($im_id)
    {
        $this->im_id = $im_id;
    }

    public function getIm_nombre()
    {
        return $this->im_nombre;
    }
    public function setIm_nombre($im_nombre)
    {
        $this->im_nombre = $im_nombre;
    }

    public function getIm_porcentaje()
    {
        return $this->im_porcentaje;
    }
    public function setIm_porcentaje($im_porcentaje)
    {
        $this->im_porcentaje = $im_porcentaje;
    }

    public function getIm_base()
    {
        return $this->im_base;
    }
    public function setIm_base($im_base)
    {
        $this->im_base = $im_base;
    }

    public function getDic_det_documento_sucursal()
    {
        return $this->dic_det_documento_sucursal;
    }
    public function setDic_det_documento_sucursal($dic_det_documento_sucursal)
    {
        $this->dic_det_documento_sucursal = $dic_det_documento_sucursal;
    }
    public function getDic_im_id()
    {
        return $this->dic_im_id;
    }
    public function setDic_im_id($dic_im_id)
    {
        $this->dic_im_id = $dic_im_id;
    }

    public function getIm_cta_contable()
    {
        return $this->im_cta_contable;
    }
    public function setIm_cta_contable($im_cta_contable)
    {
        $this->im_cta_contable = $im_cta_contable;
    }

    public function getIm_estado()
    {
        return $this->im_estado;
    }
    public function setIm_estado($im_estado)
    {
        $this->im_estado = $im_estado;
    }

    ##07-may-2020
    public function getIm_proceso()
    {
        return $this->im_proceso;
    }
    public function setIm_proceso($im_proceso)
    {
        $this->im_proceso = $im_proceso;
    }

    public function add_impuesto()
    {
        $query ="INSERT INTO tb_impuestos (im_nombre,im_porcentaje,im_cta_contable,im_proceso,im_base,im_estado)
                VALUES(
                    '".$this->im_nombre."',
                    '".$this->im_porcentaje."',
                    '".$this->im_cta_contable."',
                    '".$this->im_proceso."',
                    '".$this->im_base."',
                    '".$this->im_estado."')";
        $add_impuesto = $this->db()->query($query);
        return $add_impuesto;
    }

    public function update_impuesto($idimpuesto)
    {
        $query ="UPDATE tb_impuestos SET
                    im_nombre = '".$this->im_nombre."',
                    im_porcentaje = '".$this->im_porcentaje."',
                    im_cta_contable = '".$this->im_cta_contable."',
                    im_proceso = '".$this->im_proceso."',
                    im_base = '".$this->im_base."',
                    im_estado = '".$this->im_estado."'
                    WHERE im_id = '$idimpuesto'";
        $update_impuesto = $this->db()->query($query);
        return $update_impuesto;
    }

    public function getImpuestosByComprobanteId($id)
    {
        $query=$this->db()->query("SELECT * from tb_detalle_impuestos_comprobantes dic
        INNER JOIN tb_impuestos i ON dic.dic_im_id = i.im_id WHERE dic.dic_det_documento_sucursal = '$id' AND i.im_estado = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getImpuestosById($id)
    {
        $query=$this->db()->query("SELECT * from tb_detalle_impuestos_comprobantes dic
        INNER JOIN tb_impuestos i ON dic.dic_im_id = i.im_id WHERE i.im_id = '$id' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getImpuesto($id)
    {
        $query=$this->db()->query("SELECT * from tb_impuestos WHERE im_id = '$id' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getImpuestosAll()
    {
        $query=$this->db()->query("SELECT * from tb_impuestos WHERE im_estado = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function addImpuetoComprobante()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $query ="INSERT INTO tb_detalle_impuestos_comprobantes (dic_det_documento_sucursal,dic_im_id)
                VALUES(
                    '".$this->dic_det_documento_sucursal."',
                    '".$this->dic_im_id."'
                )";
            $add_impuesto = $this->db()->query($query);
            return $add_impuesto;
        }else{
            return false;
        }
    }
    ################## esta funcion elimina la lista de impuestos que carga el comprobante
    public function deleteImpuestoByComprobante($idcomprobante)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("DELETE FROM tb_detalle_impuestos_comprobantes WHERE dic_det_documento_sucursal = '$idcomprobante'");
            return $query;
        }else{
            return false;
        }
    }
    ################### esta funcion elimina como tal el impuesto de raiz
    public function deleteImpuestoById($idimpuesto)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("UPDATE tb_impuestos SET im_estado = 'D' WHERE im_id = '$idimpuesto'");
            return $query;
        }else{
            return false;
        }
    }

}
    