<?php

class TipoDocumento Extends EntidadBase{

    private $idtipo_documento;
    private $nombre;
    private $prefijo;
    private $operacion;
    private $proceso;
     
    public function __construct($adapter) {
        $table ="tipo_documento";
        parent:: __construct($table, $adapter);
    }

    public function getIdtipo_documento()
    {
        return $this->idtipo_documento;
    }
    public function setIdtipo_documento($idtipo_documento)
    {
        $this->idtipo_documento = $idtipo_documento;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getPrefijo()
    {
        return $this->prefijo;
    }
    public function setPrefijo($prefijo)
    {
        $this->prefijo = $prefijo;
    }
    public function getOperacion()
    {
        return $this->operacion;
    }
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;
    }
    public function getProceso()
    {
        return $this->proceso;
    }
    public function setProceso($proceso)
    {
        $this->proceso = $proceso;
    }

    public function getTipoDocumentoPersona()
    {
        $query = $this->db()->query("SELECT * FROM tipo_documento WHERE operacion = 'Persona'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         return $resultSet;
        }
    }

    public function getDocumentoComprobante()
    {
        $query = $this->db()->query("SELECT * FROM tipo_documento WHERE operacion = 'Comprobante'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         return $resultSet;
        }
    }

    public function getDocumentById($id)
    {
        $query = $this->db()->query("SELECT * FROM tipo_documento WHERE idtipo_documento = '$id' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         return $resultSet;
        }
    }
 
    public function new_documento()
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
        $query = "INSERT INTO tipo_documento (nombre, prefijo, operacion, proceso)
        VALUES(
            '".$this->nombre."',
            '".$this->prefijo."',
            '".$this->operacion."',
            '".$this->proceso."')";

        $add = $this->db()->query($query);
        return $add;
        }else{
            return false;
        }

    }
    
    public function update_documento($id)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
        $query = "UPDATE tipo_documento SET 
            nombre = '".$this->nombre."', 
            prefijo = '".$this->prefijo."', 
            operacion = '".$this->operacion."',
            proceso = '".$this->proceso."'
            WHERE idtipo_documento = '$id';";
        $update = $this->db()->query($query);
        return $update;
        }else{
            return false;
        }
    }

    public function delete_documento($id)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
        $query=$this->db->query("DELETE FROM tipo_documento WHERE idtipo_documento=$id"); 
        return $query;
        }else{
            return false;
        }
    }


}