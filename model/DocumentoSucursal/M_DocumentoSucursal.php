<?php
class M_DocumentoSucursal extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_documento_sucursal";
        parent::__construct($table, $adapter);
    }

    public function getDocumentoSucursal($idComprobante =null, $filter = [])
    {
            $query = $this->fluent()->from('detalle_documento_sucursal dds')
                                    ->join('tipo_documento td ON td.idtipo_documento = dds.idtipo_documento')
                                    ->leftJoin('tb_pie_factura PF ON PF.pf_iddetalle_documento_sucursal = dds.iddetalle_documento_sucursal')
                                    ->select("td.nombre as nombre_documento, CONCAT(dds.ultima_serie,' - ',dds.ultimo_numero)as item_name, dds.iddetalle_documento_sucursal as item_id, PF.pf_text");

            if(isset($filter['proceso'])){
                $query->where('td.proceso = "'.$filter['proceso'].'"');
            }
            if($idComprobante){
                $query = $query->where('dds.iddetalle_documento_sucursal = '.$idComprobante);
                $result = $query->fetch();
            }else{
                $result = $query->fetchAll();
            }
            return $result;
    }
    public function guardarActualizar($documento)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($documento['iddetalle_documento_sucursal']) && !empty($documento['iddetalle_documento_sucursal'])){
                $query = $this->fluent()->update('detalle_documento_sucursal')->set($documento)->where('iddetalle_documento_sucursal', $documento['iddetalle_documento_sucursal'])->execute();
            }else{
                $query = $this->fluent()->insertInto('detalle_documento_sucursal', $documento)->execute();
            }
        }
        return $query;
    }

    public function getTiposDocumentoSucursal($tipoProceso = null)
    {
        $query = $this->fluent()->from('tipo_documento')
                        ->select("idtipo_documento as item_id, nombre as item_name")
                        ->where("proceso <> ''");
        if($tipoProceso){
            $query->where('proceso = "'.$tipoProceso.'"');
        }
        $result = $query->fetchAll();
        return $result;
    }
    public function obtenerDocumentosSucursal($filter = [])
    {
        $query = $this->fluent()->from('detalle_documento_sucursal DDS')
                    ->join('tipo_documento TD ON TD.idtipo_documento = DDS.idtipo_documento')
                    ->leftJoin('tb_conf_print PRI ON PRI.pri_id = DDS.dds_pri_id')
                    ->leftJoin('tb_pie_factura PF ON PF.pf_iddetalle_documento_sucursal = DDS.iddetalle_documento_sucursal')
                    ->select("TD.nombre AS nombreTipoDocumento, PRI.pri_nombre AS nombreTipoImpresion, PF.pf_text");
        if(isset($filter['idSucursal'])){
            $query->where('idsucursal IN ('.$filter['idSucursal'].')');
        }
        $result = $query->fetchAll();
        return $result;
    }

    public function usarDocumentoSucursal($dataDocumento)
    {
        $query = $this->fluent()->update('detalle_documento_sucursal')
            ->set($dataDocumento)
            ->where('iddetalle_documento_sucursal', $dataDocumento['iddetalle_documento_sucursal'])
            ->execute();
        return $query;
    }

    public function guardarPieFactura($dataPieFaactura)
    {
        $query = $this->fluent()->insertInto('tb_pie_factura', $dataPieFaactura)->execute();
        return $query;
    }
}
