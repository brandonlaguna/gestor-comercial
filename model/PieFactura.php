<?php
class PieFactura extends EntidadBase{

    private $pf_id;
    private $pf_iddetalle_documento_sucursal;
    private $pf_text;

    public function __construct($adapter) {
        $table ="tb_pie_factura";
        parent:: __construct($table, $adapter);
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
    
    public function getPieFacturaByComprobanteId($idcomprobante)
    {
        $query = $this->db()->query("SELECT * FROM tb_pie_factura WHERE pf_iddetalle_documento_sucursal = '$idcomprobante'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;

    }
}