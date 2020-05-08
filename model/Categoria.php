<?php
class Categoria extends EntidadBase{

    private $idcategoria;
    private $nombre;
    private $estado;
    private $cod_venta;
    private $cod_costos;
    private $cod_devoluciones;
    private $cod_inventario;
    private $imp_compra;
    private $imp_venta;
    private $cod_imp_categoria;


    public function __construct($adapter) {
        $table ="categoria";
        parent:: __construct($table, $adapter);
    }
    
    public function getIdcategoria()
    {
        return $this->idcategoria;
    }
    public function setIdcategoria($idcategoria)
    {
        $this->idcategoria = $idcategoria;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function getCod_venta()
    {
        return $this->cod_venta;
    }
    public function setCod_venta($cod_venta)
    {
        $this->cod_venta = $cod_venta;
    }
    public function getCod_costos()
    {
        return $this->cod_costos;
    }
    public function setCod_costos($cod_costos)
    {
        $this->cod_costos = $cod_costos;
    }
    public function getCod_devoluciones()
    {
        return $this->cod_devoluciones;
    }
    public function setCod_devoluciones($cod_devoluciones)
    {
        $this->cod_devoluciones = $cod_devoluciones;
    }
    public function getCod_inventario()
    {
        return $this->cod_inventario;
    }
    public function setCod_inventario($cod_inventario)
    {
        $this->cod_inventario = $cod_inventario;
    }
    public function getImp_compra()
    {
        return $this->imp_compra;
    }
    public function setImp_compra($imp_compra)
    {
        $this->imp_compra = $imp_compra;
    }
    public function getImp_venta()
    {
        return $this->imp_venta;
    }
    public function setImp_venta($imp_venta)
    {
        $this->imp_venta = $imp_venta;
    }
    public function getCod_imp_categoria()
    {
        return $this->cod_imp_categoria;
    }
    public function setCod_imp_categoria($cod_imp_categoria)
    {
        $this->cod_imp_categoria = $cod_imp_categoria;
    }
    
    public function saveCategoria()
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query ="INSERT INTO `categoria` (nombre, cod_venta, cod_costos, cod_devoluciones,cod_inventario,imp_compra,imp_venta,estado)
            VALUES(
                '".$this->nombre."',
                '".$this->cod_venta."',
                '".$this->cod_costos."',
                '".$this->cod_devoluciones."',
                '".$this->cod_inventario."',
                '".$this->imp_compra."',
                '".$this->imp_venta."',
                '".$this->estado."'
            )";
        $addCategoria=$this->db()->query($query);
        return $addCategoria;

        }
    }

    public function updateCategoria($idcategoria)
    {
        if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $query ="UPDATE categoria SET
            nombre = '".$this->nombre."',
            cod_venta = '".$this->cod_venta."',
            cod_costos = '".$this->cod_costos."',
            cod_devoluciones = '".$this->cod_devoluciones."',
            cod_inventario = '".$this->cod_inventario."',
            imp_compra = '".$this->imp_compra."',
            imp_venta = '".$this->imp_venta."',
            estado = '".$this->estado."' 
            WHERE idcategoria = '$idcategoria'";
        $updateCategoria=$this->db()->query($query);
        return $updateCategoria;
        }
    }
    public function getCategoriaById($idcategoria)
    {
        $query=$this->db()->query("SELECT * FROM categoria WHERE idcategoria = '$idcategoria'");
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