<?php

class Barcode extends EntidadBase
{

    private $cb_id;
    private $cb_barcode;
    private $cb_idarticulo;
    private $cb_estado;
    private $cb_date_created;

    public function __construct($adapter)
    {
        $table = "tb_codigo_barras";
        parent::__construct($table, $adapter);
    }

    public function getCb_id()
    {
        return $this->cb_id;
    }
    public function setCb_id($cb_id)
    {
        $this->cb_id = $cb_id;
    }
    public function getCb_barcode()
    {
        return $this->cb_barcode;
    }
    public function setCb_barcode($cb_barcode)
    {
        $this->cb_barcode = $cb_barcode;
    }
    public function getCb_idarticulo()
    {
        return $this->cb_idarticulo;
    }
    public function setCb_idarticulo($cb_idarticulo)
    {
        $this->cb_idarticulo = $cb_idarticulo;
    }
    public function getCb_estado()
    {
        return $this->cb_estado;
    }
    public function setCb_estado($cb_estado)
    {
        $this->cb_estado = $cb_estado;
    }
    public function getCb_date_created()
    {
        return $this->cb_date_created;
    }
    public function setCb_date_created($cb_date_created)
    {
        $this->cb_date_created = $cb_date_created;
    }

    public function addBarcode()
    {
        if (!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            $query = "INSERT INTO `tb_codigo_barras` (cb_barcode, cb_idarticulo, cb_estado, cb_date_created)
            VALUES(
                '" . $this->cb_barcode . "',
                '" . $this->cb_idarticulo . "',
                '" . $this->cb_estado . "',
                '" . $this->cb_date_created . "',
            )";
            $addCategoria = $this->db()->query($query);
            return $addCategoria;

        }
    }

    public function getBarcodeByItem($idarticulo)
    {
        if (!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 2) {
            $query = $this->db()->query("SELECT * FROM tb_codigo_barras WHERE cb_idarticulo = '$idarticulo'");
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_object()) {
                    $resultSet[] = $row;
                }
            } else {
                $resultSet = [];
            }
            return $resultSet;
        }
    }

    public function updateCategoria($cb_id)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query ="UPDATE tb_codigo_barras SET
            cb_barcode = '".$this->cb_barcode."',
            cb_idarticulo = '".$this->cb_idarticulo."',
            cb_estado = '".$this->cb_estado."',
            cb_date_created = '".$this->cb_date_created."' 
            WHERE cb_id = '$cb_id'";
        $updateCategoria=$this->db()->query($query);
        return $updateCategoria;
        }else{
            return false;
        }
    }
}
