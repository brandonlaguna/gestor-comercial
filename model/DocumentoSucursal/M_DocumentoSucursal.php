<?php
class M_DocumentoSucursal extends ModeloBase
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

            $query = $this->fluent()->from('detalle_documento_sucursal dds')
                                    ->join('tipo_documento td ON td.idtipo_documento = dds.idtipo_documento')
                                    ->select("td.*")
                                    ;

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
            if(isset($documento['iddetalle_documento_sucursal']) && !empty($documento['iddetalle_documento_sucursal'])){
                $query = $this->fluent->update('detalle_documento_sucursal')->set($documento)->where('iddetalle_documento_sucursal', $documento['idcomprobante'])->execute();
            }else{
                
                $query = $this->fluent()->insertInto('detalle_documento_sucursal', $documento)->execute();
            }
        }
        return $query;
    }

    public function getTiposDocumentoSucursal($idtipo_documento = null)
    {
        $query = $this->fluent()->from('tipo_documento')
                        ->select("idtipo_documento as item_id, nombre as item_name")
                        ->where("proceso <> ''");
        $result = $query->fetchAll();
        return $result;
    }
}