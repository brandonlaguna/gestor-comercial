<?php
class DocumentoSucursal extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_documento_sucursal";
        parent::__construct($table, $adapter);
    }

    public function getDocumentoSucursal($idComprobante =null)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

            $query = $this->fluent()->from('detalle_documento_sucursal dds')->select("*");

            if($idComprobante){
                $query = $this->fluent()->where('dds.iddetalle_documento_sucursal = '.$idComprobante);
            }

            $result = $query->fetchAll();
            return $result;
        }
        else{
            return false;
        }
    }
    public function guardarActualizar($documento)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($documento['idcomprobante']) && !empty($documento['idcomprobante'])){
                return $documento;
            }else{
                return $documento;
            }
        }
    }

    public function getTiposDocumentos()
    {
        $query = $this->fluent()->from('tipo_documento')->select("*");
        $result = $query->fetchAll();
        return $result;
    }
}