<?php
class RespFiscales Extends EntidadBase{

    private $idresp_fiscales;
    private $rf_nombre;
    private $rf_codigo;
    private $rf_contabilidad;
    private $rf_activo;

    public function __construct($adapter) {
        $table ="resp_fiscales";
        parent:: __construct($table, $adapter);
    }
    
    public function getIdresp_fiscales()
    {
        return $this->idresp_fiscales;
    }
    public function setIdresp_fiscales($idresp_fiscales)
    {
        $this->idresp_fiscales = $idresp_fiscales;
    }
    public function getRf_nombre()
    {
        return $this->rf_nombre;
    }
    public function setRf_nombre($rf_nombre)
    {
        $this->rf_nombre = $rf_nombre;
    }
    public function getRf_codigo()
    {
        return $this->rf_codigo;
    }
    public function setRf_codigo($rf_codigo)
    {
        $this->rf_codigo = $rf_codigo;
    }
    public function getRf_contabilidad()
    {
        return $this->rf_contabilidad;
    }
    public function setRf_contabilidad($rf_contabilidad)
    {
        $this->rf_contabilidad = $rf_contabilidad;
    }
    public function getRf_activo()
    {
        return $this->rf_activo;
    }
    public function setRf_activo($rf_activo)
    {
        $this->rf_activo = $rf_activo;
    }

    public function getRespFiscalAll()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query=$this->db()->query("SELECT * FROM resp_fiscales WHERE rf_activo = '1'");
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
}