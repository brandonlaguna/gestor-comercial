<?php
class TipoRegimen extends EntidadBase{
    private $idtipo_regimen;
    private $tr_nombre;
    private $tr_contabilidad;
    private $tr_activo;

    public function __construct($adapter) {
        $table ="tipo_regimen";
        parent:: __construct($table, $adapter);
    }

    public function getIdtipo_regimen()
    {
        return $this->idtipo_regimen;
    }
    public function setIdtipo_regimen($idtipo_regimen)
    {
        $this->idtipo_regimen = $idtipo_regimen;
    }
    public function getTr_nombre()
    {
        return $this->tr_nombre;
    }
    public function setTr_nombre($tr_nombre)
    {
        $this->tr_nombre = $tr_nombre;
    }
    public function getTr_contabilidad()
    {
        return $this->tr_contabilidad;
    }
    public function setTr_contabilidad($tr_contabilidad)
    {
        $this->tr_contabilidad = $tr_contabilidad;
    }
    public function getTr_activo()
    {
        return $this->tr_activo;
    }
    public function setTr_activo($tr_activo)
    {
        $this->tr_activo = $tr_activo;
    }


}