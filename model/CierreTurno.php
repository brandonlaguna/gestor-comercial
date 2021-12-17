<?php

class CierreTurno extends EntidadBase
{

    private $rct_id;
    private $rct_idsucursal;
    private $rct_idusuario;
    private $rct_descripcion;
    private $rct_id_descripcion;
    private $rct_venta_desde;
    private $rct_venta_hasta;
    private $rct_comprobante_desde;
    private $rct_comprobante_hasta;
    private $rct_fecha_inicio;
    private $rct_fecha_fin;
    private $rct_date;
    private $rct_status;

    public function __construct($adapter)
    {
        $table = "tb_reporte_cierre_turno";
        parent::__construct($table, $adapter);
    }

    public function getRct_id()
    {
        return $this->rct_id;
    }
    public function setRct_id($rct_id)
    {
        $this->rct_id = $rct_id;
    }
    public function getRct_idsucursal()
    {
        return $this->rct_idsucursal;
    }
    public function setRct_idsucursal($rct_idsucursal)
    {
        $this->rct_idsucursal = $rct_idsucursal;
    }
    public function getRct_idusuario()
    {
        return $this->rct_idusuario;
    }
    public function setRct_idusuario($rct_idusuario)
    {
        $this->rct_idusuario = $rct_idusuario;
    }
    public function getRct_descripcion()
    {
        return $this->rct_descripcion;
    }
    public function setRct_descripcion($rct_descripcion)
    {
        $this->rct_descripcion = $rct_descripcion;
    }
    public function getRct_id_descripcion()
    {
        return $this->rct_id_descripcion;
    }
    public function setRct_id_descripcion($rct_id_descripcion)
    {
        $this->rct_id_descripcion = $rct_id_descripcion;
    }
    public function getRct_venta_desde()
    {
        return $this->rct_venta_desde;
    }
    public function setRct_venta_desde($rct_venta_desde)
    {
        $this->rct_venta_desde = $rct_venta_desde;
    }
    public function getRct_venta_hasta()
    {
        return $this->rct_venta_hasta;
    }
    public function setRct_venta_hasta($rct_venta_hasta)
    {
        $this->rct_venta_hasta = $rct_venta_hasta;
    }
    public function getRct_comprobante_desde()
    {
        return $this->rct_comprobante_desde;
    }
    public function setRct_comprobante_desde($rct_comprobante_desde)
    {
        $this->rct_comprobante_desde = $rct_comprobante_desde;
    }
    public function getRct_comprobante_hasta()
    {
        return $this->rct_comprobante_hasta;
    }
    public function setRct_comprobante_hasta($rct_comprobante_hasta)
    {
        $this->rct_comprobante_hasta = $rct_comprobante_hasta;
    }
    public function getRct_fecha_inicio()
    {
        return $this->rct_fecha_inicio;
    }
    public function setRct_fecha_inicio($rct_fecha_inicio)
    {
        $this->rct_fecha_inicio = $rct_fecha_inicio;
    }
    public function getRct_fecha_fin()
    {
        return $this->rct_fecha_fin;
    }
    public function setRct_fecha_fin($rct_fecha_fin)
    {
        $this->rct_fecha_fin = $rct_fecha_fin;
    }
    public function getRct_date()
    {
        return $this->rct_date;
    }
    public function setRct_date($rct_date)
    {
        $this->rct_date = $rct_date;
    }
    public function getRct_status()
    {
        return $this->rct_status;
    }
    public function setRct_status($rct_status)
    {
        $this->rct_status = $rct_status;
    }

    public function addInicio()
    {
        if (!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            $query = "INSERT INTO tb_reporte_cierre_turno (rct_idsucursal, rct_idusuario, rct_descripcion, rct_fecha_inicio, rct_date)
        VALUES (
            '" . $this->rct_idsucursal . "',
            '" . $this->rct_idusuario . "',
            '" . $this->rct_descripcion . "',
            '" . $this->rct_fecha_inicio . "',
            '" . $this->rct_date . "'
            )";
            $addRegistro = $this->db()->query($query);
            $idRegistro = $this->db()->insert_id;
            return $idRegistro;

        } else {
            return false;
        }
    }

    public function authInicio()
    {
        $query = $this->db()->query("SELECT * FROM tb_reporte_cierre_turno WHERE
        rct_idsucursal = '" . $this->rct_idsucursal . "' AND
        rct_idusuario = '" . $this->rct_idusuario . "' AND
        rct_date = '" . $this->rct_date . "'
        ORDER BY rct_id DESC
        LIMIT 1
        ");

        if ($query->num_rows > 0) {
            while ($row = $query->fetch_object()) {
                $resultSet[] = $row;
            }
        } else {
            $resultSet = false;
        }

        return $resultSet;
    }

    public function addInicioVenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query = $this->db()->query("UPDATE tb_reporte_cierre_turno SET
            rct_venta_desde = '" . $this->rct_venta_desde . "' 
            WHERE  rct_idsucursal = '" . $this->rct_idsucursal . "' AND
            rct_idusuario = '" . $this->rct_idusuario . "' AND
            rct_date = '" . $this->rct_date . "' AND 
            rct_status = '0' ");
        }else{
            
        }
    }

    public function addFinalVenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query = $this->db()->query("UPDATE tb_reporte_cierre_turno SET
            rct_venta_hasta = '" . $this->rct_venta_hasta . "' ,
            rct_status = '1'
            WHERE  rct_idsucursal = '" . $this->rct_idsucursal . "' AND
            rct_idusuario = '" . $this->rct_idusuario . "' AND
            rct_date = '" . $this->rct_date . "' AND
            rct_status = '0'
            ");
        }else{
            
        }
    }

    public function getCierreTurnoAllByDay(){
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query = $this->db()->query("SELECT rct.*, em.nombre as nombre_empleado, em.apellidos as aspellidos_empleado FROM tb_reporte_cierre_turno rct
            INNER JOIN usuario u on rct.rct_idusuario = u.idusuario
            INNER JOIN empleado em on u.idempleado = em.idempleado
            WHERE
            rct_idsucursal = '" . $this->rct_idsucursal . "' AND
            rct_date = '" . $this->rct_date . "'
            ORDER BY rct_id ASC
            ");
    
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {
                $resultSet = [];
            }
    
            return $resultSet;

        }else{
            
        }
    }



 
    
}
