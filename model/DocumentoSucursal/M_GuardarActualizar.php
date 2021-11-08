<?php
class M_DocumentoSucursal extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "detalle_documento_sucursal";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizar()
    {
        
    }



}