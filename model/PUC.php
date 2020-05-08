<?php
class PUC Extends EntidadBase{
    private $idcodigo;
    private $tipo_codigo;
    private $impuesto;
    private $retencion;
    private $movimiento;
    private $terceros;
    private $centro_costos;
    private $estado_puc;
    
    public function __construct($adapter) {
        $table ="codigo_contable";
        parent:: __construct($table, $adapter);
    }
    public function getIdcodigo()
    {
        return $this->idcodigo;
    }
    public function setIdcodigo($idcodigo)
    {
        $this->idcodigo = $idcodigo;
    }
    public function getTipo_codigo()
    {
        return $this->tipo_codigo;
    }
    public function setTipo_codigo($tipo_codigo)
    {
        $this->tipo_codigo = $tipo_codigo;
    }
    public function getImpuesto()
    {
        return $this->impuesto;
    }
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    }
    public function getRetencion()
    {
        return $this->retencion;
    }
    public function setRetencion($retencion)
    {
        $this->retencion = $retencion;
    }
    public function getMovimiento()
    {
        return $this->movimiento;
    }
    public function setMovimiento($movimiento)
    {
        $this->movimiento = $movimiento;
    }
    public function getTerceros()
    {
        return $this->terceros;
    }
    public function setTerceros($terceros)
    {
        $this->terceros = $terceros;
    }
    public function getCentro_costos()
    {
        return $this->centro_costos;
    }
    public function setCentro_costos($centro_costos)
    {
        $this->centro_costos = $centro_costos;
    }
    public function getEstado_puc()
    {
        return $this->estado_puc;
    }
    public function setEstado_puc($estado_puc)
    {
        $this->estado_puc = $estado_puc;
    }
    public function getClase()
    {
        $query=$this->db()->query("SELECT * FROM codigo_contable WHERE idcodigo > 0 AND idcodigo < 10");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getGrupo($clase)
    {
        $like = $clase."_%";
        $query=$this->db()->query("SELECT * FROM codigo_contable WHERE idcodigo LIKE '$like' AND LENGTH(idcodigo) = 2 AND estado_puc = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getCuenta($grupo)
    {
        $like = $grupo."_%";
        $query=$this->db()->query("SELECT * FROM codigo_contable WHERE idcodigo LIKE '$like' AND LENGTH(idcodigo) = 4 AND estado_puc = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getSubCuenta($cuenta)
    {
        $like = $cuenta."_%";
        $query=$this->db()->query("SELECT * FROM codigo_contable WHERE idcodigo LIKE '$like' AND LENGTH(idcodigo) = 6 AND estado_puc = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getCuentaById($idcuenta)
    {
        $query=$this->db()->query("SELECT * FROM codigo_contable WHERE idcodigo = '$idcuenta' AND LENGTH(idcodigo) = 4 AND estado_puc = 'A'  LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getAuxSubCuenta($subcuenta)
    {
        $like = $subcuenta."_%";
        $query=$this->db()->query("SELECT * FROM codigo_contable WHERE idcodigo LIKE '$like' AND LENGTH(idcodigo) = 8 AND estado_puc = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getAllPuc()
    {
        $query=$this->db()->query("SELECT * FROM codigo_contable WHERE estado_puc = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getPucById($id)
    {
        $query=$this->db()->query("SELECT * FROM codigo_contable WHERE idcodigo = '$id' AND estado_puc = 'A'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }


    public function getAllPucNiif($data)
    {
        $query=$this->db()->query("SELECT * FROM plan_unico_cuenta_niif WHERE id_puc_niif = '$data' OR tipo_codigo LIKE '%$data%' ORDER BY id_puc_niif ASC");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getCodContableCompraBy($data)
    {
        $query=$this->db()->query("SELECT idcodigo, tipo_codigo as descripcion, sum(cdi_credito) as cdi_credito ,sum(cdi_debito) as costo_producto, impuesto as imp_compra, sum(cdi_debito) as sub_total_compra, sum(cdi_debito) as total_compra,
        sum(cdi_debito) as precio_venta, impuesto as imp_venta, sum(cdi_debito) as sub_total_venta, sum(cdi_debito) as total_venta
        FROM codigo_contable 
        INNER JOIN tb_cola_detalle_ingreso
        WHERE idcodigo = '$data' OR tipo_codigo = '$data' AND estado_puc = 'A' LIMIT 1");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function addCodigo()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $query ="INSERT INTO codigo_contable (idcodigo,tipo_codigo,impuesto,retencion,movimiento,terceros,centro_costos,estado_puc)
            VALUES(
                '".$this->idcodigo."',
                '".$this->tipo_codigo."',
                '".$this->impuesto."',
                '".$this->retencion."',
                '".$this->movimiento."',
                '".$this->terceros."',
                '".$this->centro_costos."',
                'A'
            )";
            $addCodigo=$this->db()->query($query);
            return $addCodigo;
        }else{
            return false;
        }
    }

    public function updateCodigo($idcodigo)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $query ="UPDATE codigo_contable
            SET
                tipo_codigo = '".$this->tipo_codigo."',
                impuesto = '".$this->impuesto."',
                retencion = '".$this->retencion."',
                movimiento = '".$this->movimiento."',
                terceros = '".$this->terceros."',
                centro_costos = '".$this->centro_costos."',
                estado_puc = '".$this->estado_puc."'
                WHERE idcodigo = '$idcodigo'";
            $updateCodigo=$this->db()->query($query);
            return $updateCodigo;
        }else{
            return false;
        }
    }

    public function delete_puc($idcuenta)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $query = $this->db()->query("UPDATE codigo_contable SET estado_puc = 'D' WHERE idcodigo = '$idcuenta'");
            return $query;
        }else{
            return false;
        }
    }


}