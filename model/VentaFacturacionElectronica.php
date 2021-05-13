<?php
class VentaFacturacionElectronica extends EntidadBase
{
    private $vfe_id;
    private $vfe_idventa;
    private $vfe_detalle_registro;
    private $vfe_contabilidad;
    private $vfe_response;
    private $vfe_status;
    private $vfe_date_created;

    public function __construct($adapter)
    {
        $table = "tb_venta_facturacion_electronica";
        parent::__construct($table, $adapter);
    }
    public function getVfe_id()
    {
        return $this->vfe_id;
    }
    public function setVfe_id($vfe_id)
    {
        $this->vfe_id = $vfe_id;
    }
    public function getVfe_idventa()
    {
        return $this->vfe_idventa;
    }
    public function setVfe_idventa($vfe_idventa)
    {
        $this->vfe_idventa = $vfe_idventa;
    }
    public function getVfe_detalle_registro()
    {
        return $this->vfe_detalle_registro;
    }
    public function setVfe_detalle_registro($vfe_detalle_registro)
    {
        $this->vfe_detalle_registro = $vfe_detalle_registro;
    }
    public function getVfe_contabilidad()
    {
        return $this->vfe_contabilidad;
    }
    public function setVfe_contabilidad($vfe_contabilidad)
    {
        $this->vfe_contabilidad = $vfe_contabilidad;
    }
    public function getVfe_response()
    {
        return $this->vfe_response;
    }
    public function setVfe_response($vfe_response)
    {
        $this->vfe_response = $vfe_response;
    }
    public function getVfe_status()
    {
        return $this->vfe_status;
    }
    public function setVfe_status($vfe_status)
    {
        $this->vfe_status = $vfe_status;
    }
    public function getVfe_date_created()
    {
        return $this->vfe_date_created;
    }
    public function setVfe_date_created($vfe_date_created)
    {
        $this->vfe_date_created = $vfe_date_created;
    }

    public function addFacturaElectronica()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {

            //verificar si no existe
            $query = "INSERT INTO tb_venta_facturacion_electronica ( vfe_idventa, vfe_detalle_registro, vfe_contabilidad,vfe_response, vfe_status, vfe_date_created)
            VALUES(
                '" . $this->vfe_idventa . "',
                '" . $this->vfe_detalle_registro . "',
                '" . $this->vfe_contabilidad . "',
                '" . $this->vfe_response . "',
                '" . $this->vfe_status . "',
                '" . $this->vfe_date_created . "'
            )";

            $addVenta = $this->db()->query($query);
            $id = $this->db()->inser_id;
            if ($addVenta) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function getFacturaElectronicaByVentaId($idventa, $contabilidad)
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            $query = $this->db()->query("SELECT * FROM tb_venta_facturacion_electronica WHERE vfe_idventa = '$idventa' AND vfe_contabilidad = '$contabilidad'");
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {
                $resultSet = [];
            }
            return $resultSet;
        } else {
            return false;
        }
    }

    public function updateFacturaElectronica()
    {
        if (isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0) {
            $query = "UPDATE tb_venta_facturacion_electronica SET
            vfe_idventa = '" . $this->vfe_idventa . "',
            vfe_detalle_registro = '" . $this->vfe_detalle_registro . "',
            vfe_contabilidad = '" . $this->vfe_contabilidad . "',
            vfe_response = '" . $this->vfe_response . "',
            vfe_status = '" . $this->vfe_status . "',
            vfe_date_created = '" . $this->vfe_date_created . "'
            WHERE vfe_id = '$this->vfe_id' AND st_idsucursal = '" . $_SESSION["idsucursal"] . "'";
            $update_cuenta = $this->db()->query($query);
            return $update_cuenta;
        } else {
            return false;
        }
    }

}
