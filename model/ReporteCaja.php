<?php

class ReporteCaja Extends EntidadBase{

    private $rc_id;
    private $rc_descripcion;
    private $rc_monto;
    private $rc_accion;
    private $rc_fecha;
    private $rc_id_descripcion;
    private $rc_idsucursal;
    private $rc_idusuario;
    private $rc_efectivo;
    private $rc_credito;
    private $rc_debito;
    private $rc_pagos;

    public function __construct($adapter) {
        $table ="tb_reporte_caja";
        parent:: __construct($table, $adapter);
    }
    public function getRc_id()
    {
        return $this->rc_id;
    }
    public function setRc_id($rc_id)
    {
        $this->rc_id = $rc_id;
    }
    public function getRc_descripcion()
    {
        return $this->rc_descripcion;
    }
    public function setRc_descripcion($rc_descripcion)
    {
        $this->rc_descripcion = $rc_descripcion;
    }
    public function getRc_monto()
    {
        return $this->rc_monto;
    }
    public function setRc_monto($rc_monto)
    {
        $this->rc_monto = $rc_monto;
    }
    public function getRc_accion()
    {
        return $this->rc_accion;
    }
    public function setRc_accion($rc_accion)
    {
        $this->rc_accion = $rc_accion;
    }
    public function getRc_fecha()
    {
        return $this->rc_fecha;
    }
    public function setRc_fecha($rc_fecha)
    {
        $this->rc_fecha = $rc_fecha;
    }

    public function getRc_id_descripcion()
    {
        return $this->rc_id_descripcion;
    }
    public function setRc_id_descripcion($rc_id_descripcion)
    {
        $this->rc_id_descripcion = $rc_id_descripcion;
    }

    public function getRc_idsucursal()
    {
        return $this->rc_idsucursal;
    }
    public function setRc_idsucursal($rc_idsucursal)
    {
        $this->rc_idsucursal = $rc_idsucursal;
    }
    public function getRc_idusuario()
    {
        return $this->rc_idusuario;
    }
    public function setRc_idusuario($rc_idusuario)
    {
        $this->rc_idusuario = $rc_idusuario;
    }
    public function getRc_efectivo()
    {
        return $this->rc_efectivo;
    }
    public function setRc_efectivo($rc_efectivo)
    {
        $this->rc_efectivo = $rc_efectivo;
    }
    public function getRc_credito()
    {
        return $this->rc_credito;
    }
    public function setRc_credito($rc_credito)
    {
        $this->rc_credito = $rc_credito;
    }
    public function getRc_debito()
    {
        return $this->rc_debito;
    }
    public function setRc_debito($rc_debito)
    {
        $this->rc_debito = $rc_debito;
    }

    public function getRc_pagos()
    {
        return $this->rc_pagos;
    }
    public function setRc_pagos($rc_pagos)
    {
        $this->rc_pagos = $rc_pagos;
    }

    public function getReportesAll()
    {
        $query = $this->db()->query("SELECT rc.*,u.*,rc.rc_id as idreporte
        FROM tb_reporte_caja rc
        INNER JOIN jgc_users_ u on rc.rc_idusuario = u.ju_uid
        WHERE rc.rc_idsucursal = '".$_SESSION['idsucursal']."'
        ORDER BY rc.rc_id DESC");

        if($query->num_rows > 0){
         while ($row = $query->fetch_object()) {
             $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function addRegistro()
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2){
        $query ="INSERT INTO tb_reporte_caja (rc_descripcion,rc_monto,rc_efectivo,rc_credito,rc_debito,rc_pagos,rc_idsucursal,rc_idusuario,rc_accion,rc_id_descripcion,rc_fecha)
        VALUES (
            '".$this->rc_descripcion."',
            '".$this->rc_monto."',
            '".$this->rc_efectivo."',
            '".$this->rc_credito."',
            '".$this->rc_debito."',
            '".$this->rc_pagos."',
            '".$this->rc_idsucursal."',
            '".$this->rc_idusuario."',
            '".$this->rc_accion."',
            '".$this->rc_id_descripcion."',
            '".$this->rc_fecha."')";
        $addRegistro=$this->db()->query($query);
        return $addRegistro;
        
        }else{
            return false;
        }
    }
    
    public function updateReporte($data)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query ="UPDATE tb_reporte_caja 
            SET
                 rc_monto= '".$this->rc_monto."',
                 rc_efectivo = '".$this->rc_efectivo."',
                 rc_credito = '".$this->rc_credito."',
                 rc_debito= '".$this->rc_debito."',
                 rc_pagos= '".$this->rc_pagos."'
                 WHERE rc_fecha = '$data' ";
                 
            $updateReporte=$this->db()->query($query);
            return $updateReporte;
        }else{
            return false;
        }
    }
    
    public function getReporteById($id)
    {
        $query = $this->db()->query("SELECT rc.*,u.*,rc.rc_id as idreporte
        FROM tb_reporte_caja rc
        INNER JOIN jgc_users_ u on rc.rc_idusuario = u.ju_uid
        WHERE rc.rc_idsucursal = '".$_SESSION['idsucursal']."' AND rc.rc_id = '$id' LIMIT 1");

        if($query->num_rows > 0){
         while ($row = $query->fetch_object()) {
             $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getReporteByDate($date)
    {
        $query = $this->db()->query("SELECT rc.*,u.*,rc.rc_id as idreporte
        FROM tb_reporte_caja rc
        INNER JOIN jgc_users_ u on rc.rc_idusuario = u.ju_uid
        WHERE rc.rc_idsucursal = '".$_SESSION['idsucursal']."' AND rc.rc_fecha = '$date'");

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