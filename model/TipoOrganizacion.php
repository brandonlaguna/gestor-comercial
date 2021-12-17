<?php
class TipoOrganizacion extends EntidadBase{
    private $idtipo_organizacion;
    private $to_nombre;
    private $to_contabilidad;
    private $to_operacion;
    private $to_activo;

    public function __construct($adapter) {
        $table ="tipo_organizacion";
        parent:: __construct($table, $adapter);
    }
    public function getIdtipo_organizacion()
    {
        return $this->idtipo_organizacion;
    }
    public function setIdtipo_organizacion($idtipo_organizacion)
    {
        $this->idtipo_organizacion = $idtipo_organizacion;
    }
    public function getTo_nombre()
    {
        return $this->to_nombre;
    }
    public function setTo_nombre($to_nombre)
    {
        $this->to_nombre = $to_nombre;
    }
    public function getTo_contabilidad()
    {
        return $this->to_contabilidad;
    }
    public function setTo_contabilidad($to_contabilidad)
    {
        $this->to_contabilidad = $to_contabilidad;
    }
    public function getTo_operacion()
    {
        return $this->to_operacion;
    }
    public function setTo_operacion($to_operacion)
    {
        $this->to_operacion = $to_operacion;
    }
    public function getTo_activo()
    {
        return $this->to_activo;
    }
    public function setTo_activo($to_activo)
    {
        $this->to_activo = $to_activo;
    }
    
}