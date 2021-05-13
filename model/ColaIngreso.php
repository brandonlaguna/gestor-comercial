<?php

class ColaIngreso Extends EntidadBase{
    ######## detalle cola ingreso
    private $cdi_id;
    private $cdi_ci_id;
    private $cdi_idsucursal;
    private $cdi_tercero;
    private $cdi_idusuraio;
    private $cdi_idarticulo;
    private $cdi_detalle;
    private $cdi_stock_ingreso;
    private $cdi_precio_unitario;
    private $cdi_importe;
    private $cdi_im_id;
    private $cdi_base_ret;
    private $cdi_precio_total_lote;
    private $cdi_credito;
    private $cdi_debito;
    private $cdi_cod_costos;
    private $cdi_type;
    ####### cola ingreso
    private $ci_id;
    private $ci_usuario;
    private $ci_idsucursal;
    private $ci_idproveedor;
    private $ci_tipo_pago;
    private $ci_comprobante;
    private $ci_fecha;
    private $ci_fecha_final;

    public function __construct($adapter) {
        $table ="tb_cola_detalle_ingreso";
        parent:: __construct($table, $adapter);
    }

    public function getCdi_id()
    {
        return $this->cdi_id;
    }
    public function setCdi_id($cdi_id)
    {
        $this->cdi_id = $cdi_id;
    }
    public function getCdi_idsucursal()
    {
        return $this->cdi_idsucursal;
    }
    public function setCdi_idsucursal($cdi_idsucursal)
    {
        $this->cdi_idsucursal = $cdi_idsucursal;
    }
    public function getCdi_tercero()
    {
        return $this->cdi_tercero;
    }
    public function setCdi_tercero($cdi_tercero)
    {
        $this->cdi_tercero = $cdi_tercero;
    }
    public function getCdi_idarticulo()
    {
        return $this->cdi_idarticulo;
    }
    public function setCdi_idarticulo($cdi_idarticulo)
    {
        $this->cdi_idarticulo = $cdi_idarticulo;
    }
    public function getCdi_stock_ingreso()
    {
        return $this->cdi_stock_ingreso;
    }
    public function setCdi_stock_ingreso($cdi_stock_ingreso)
    {
        $this->cdi_stock_ingreso = $cdi_stock_ingreso;
    }
    public function getCdi_precio_unitario()
    {
        return $this->cdi_precio_unitario;
    }
    public function setCdi_precio_unitario($cdi_precio_unitario)
    {
        $this->cdi_precio_unitario = $cdi_precio_unitario;
    }
    public function getCdi_importe()
    {
        return $this->cdi_importe;
    }
    public function setCdi_importe($cdi_importe)
    {
        $this->cdi_importe = $cdi_importe;
    }
    public function getCdi_im_id()
    {
        return $this->cdi_im_id;
    } 
    public function setCdi_im_id($cdi_im_id)
    {
        $this->cdi_im_id = $cdi_im_id;
    }
    public function getCdi_precio_total_lote()
    {
        return $this->cdi_precio_total_lote;
    }
    public function setCdi_precio_total_lote($cdi_precio_total_lote)
    {
        $this->cdi_precio_total_lote = $cdi_precio_total_lote;
    }
    public function getCdi_credito()
    {
        return $this->cdi_credito;
    }
    public function setCdi_credito($cdi_credito)
    {
        $this->cdi_credito = $cdi_credito;
    } 
    public function getCdi_debito()
    {
        return $this->cdi_debito;
    }
    public function setCdi_debito($cdi_debito)
    {
        $this->cdi_debito = $cdi_debito;
    } 
    public function getCdi_cod_costos()
    {
        return $this->cdi_cod_costos;
    }
    public function setCdi_cod_costos($cdi_cod_costos)
    {
        $this->cdi_cod_costos = $cdi_cod_costos;
    }
    public function getcdi_type()
    {
        return $this->cdi_type;
    } 
    public function setcdi_type($cdi_type)
    {
        $this->cdi_type = $cdi_type;
    }
    ##### nuevo
    public function getCdi_detalle()
    {
        return $this->cdi_detalle;
    }
    public function setCdi_detalle($cdi_detalle)
    {
        $this->cdi_detalle = $cdi_detalle;
    }

    public function getCdi_base_ret()
    {
        return $this->cdi_base_ret;
    }
    public function setCdi_base_ret($cdi_base_ret)
    {
        $this->cdi_base_ret = $cdi_base_ret;
    }

    ##nuevo 0.5.7
    public function getCi_id()
    {
        return $this->ci_id;
    }
    public function setCi_id($ci_id)
    {
        $this->ci_id = $ci_id;
    }
    public function getCi_usuario()
    {
        return $this->ci_usuario;
    }
    public function setCi_usuario($ci_usuario)
    {
        $this->ci_usuario = $ci_usuario;
    }
    public function getCi_idsucursal()
    {
        return $this->ci_idsucursal;
    }
    public function setCi_idsucursal($ci_idsucursal)
    {
        $this->ci_idsucursal = $ci_idsucursal;
    }
    public function getCi_idproveedor()
    {
        return $this->ci_idproveedor;
    }
    public function setCi_idproveedor($ci_idproveedor)
    {
        $this->ci_idproveedor = $ci_idproveedor;
    }
    public function getCi_tipo_pago()
    {
        return $this->ci_tipo_pago;
    }
    public function setCi_tipo_pago($ci_tipo_pago)
    {
        $this->ci_tipo_pago = $ci_tipo_pago;
    }
    public function getCi_comprobante()
    {
        return $this->ci_comprobante;
    }
    public function setCi_comprobante($ci_comprobante)
    {
        $this->ci_comprobante = $ci_comprobante;
    }
    public function getCi_fecha()
    {
        return $this->ci_fecha;
    }
    public function setCi_fecha($ci_fecha)
    {
        $this->ci_fecha = $ci_fecha;
    }
    public function getCi_fecha_final()
    {
        return $this->ci_fecha_final;
    }
    public function setCi_fecha_final($ci_fecha_final)
    {
        $this->ci_fecha_final = $ci_fecha_final;
    }
    ##tb_cola_detalle_ingreso
    public function getCdi_ci_id()
    {
        return $this->cdi_ci_id;
    }
    public function setCdi_ci_id($cdi_ci_id)
    {
        $this->cdi_ci_id = $cdi_ci_id;
    }
    public function getCdi_idusuraio()
    {
        return $this->cdi_idusuraio;
    } 
    public function setCdi_idusuraio($cdi_idusuraio)
    {
        $this->cdi_idusuraio = $cdi_idusuraio;
    }

    public function addItemToCart()
    {
        $query ="INSERT INTO `tb_cola_detalle_ingreso` (cdi_ci_id,cdi_idsucursal,cdi_idusuraio, cdi_tercero, cdi_idarticulo, cdi_detalle, cdi_stock_ingreso, cdi_precio_unitario, cdi_importe, cdi_im_id, cdi_base_ret, cdi_precio_total_lote, cdi_credito, cdi_debito, cdi_cod_costos, cdi_type)
            VALUES(
                '".$this->cdi_ci_id."',
                '".$this->cdi_idsucursal."',
                '".$this->cdi_idusuraio."',
                '".$this->cdi_tercero."',
                '".$this->cdi_idarticulo."',
                '".$this->cdi_detalle."',
                '".$this->cdi_stock_ingreso."',
                '".$this->cdi_precio_unitario."',
                '".$this->cdi_importe."',
                '".$this->cdi_im_id."',
                '".$this->cdi_base_ret."',
                '".$this->cdi_precio_total_lote."',
                '".$this->cdi_credito."',
                '".$this->cdi_debito."',
                '".$this->cdi_cod_costos."',
                '".$this->cdi_type."')";
        $addItem=$this->db()->query($query);
        //
       if($addItem){
           $status =true;
       }else{
            $status =false;
       }
       return $status;
    }

    public function loadCart()
    {
        $resultSet=[];
        $queryarticulo=$this->db()->query("SELECT cdi.*, a.a_cod_contable as cod_contable, a.descripcion as descripcion 
        FROM tb_cola_detalle_ingreso cdi
        INNER JOIN articulo a ON cdi.cdi_idarticulo = a.idarticulo
        WHERE cdi.cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi.cdi_idusuraio = '".$_SESSION['usr_uid']."'");
        if($queryarticulo->num_rows > 0){
            while ($row = $queryarticulo->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{

        }
        $query2 =$this->db()->query("SELECT cdi.*, cc.idcodigo as cod_contable, cc.tipo_codigo as descripcion FROM tb_cola_detalle_ingreso cdi
        INNER JOIN codigo_contable cc on cdi.cdi_idarticulo = cc.idcodigo 
        WHERE cdi.cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cc.movimiento = 1 AND cdi_idusuraio = '".$_SESSION['usr_uid']."'");
        if($query2->num_rows >0){
            while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
            }
        }else{

        }

        return $resultSet;
    }

    public function loadArtByCart($cart)
    {
        $resultSet=[];
        $queryarticulo=$this->db()->query("SELECT cdi.*, a.a_cod_contable as cod_contable, a.descripcion as descripcion 
        FROM tb_cola_detalle_ingreso cdi
        INNER JOIN articulo a ON cdi.cdi_idarticulo = a.idarticulo
        WHERE cdi.cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi.cdi_ci_id = '$cart' AND cdi.cdi_idusuraio = '".$_SESSION['usr_uid']."'");
        if($queryarticulo->num_rows > 0){
            while ($row = $queryarticulo->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{}
        $query2 =$this->db()->query("SELECT cdi.*, cc.idcodigo as cod_contable, cc.tipo_codigo as descripcion FROM tb_cola_detalle_ingreso cdi
        INNER JOIN codigo_contable cc on cdi.cdi_idarticulo = cc.idcodigo 
        WHERE cdi.cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi.cdi_ci_id = '$cart' AND cdi.cdi_idusuraio = '".$_SESSION['usr_uid']."'");
        if($query2->num_rows >0){
            while ($row = $query2->fetch_object()) {
                $resultSet[]=$row;
            }
        }else{

        }

        return $resultSet;
    }

    public function deleteCart()
    {
        $query = "DELETE FROM tb_cola_ingreso WHERE ci_idsucursal = '".$_SESSION['idsucursal']."' AND ci_usuario = '".$_SESSION["usr_uid"]."'";
        $delete = $this->db()->query($query);
        
        $query2 = "DELETE FROM tb_cola_detalle_ingreso WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi_idusuraio = '".$_SESSION["usr_uid"]."'";
        $delete2 = $this->db()->query($query2);

        if($delete){
          $status = array("status" => 1);
           
        }else{
            $status =  array("status" => 0);
        }
    }

    public function deleteItem($id)
    {
        $query = "DELETE FROM tb_cola_detalle_ingreso WHERE cdi_id = '$id' AND cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi_idusuraio = '".$_SESSION['usr_uid']."'";
        $delete = $this->db()->query($query);
        if($delete){
          $status = array("status" => 1);
           
        }else{
            $status =  array("status" => 0);
        }
    }

    public function getTotal()
    {
        $query=$this->db()->query("SELECT sum(cdi_credito) as cdi_credito, sum(cdi_debito) as cdi_debito 
        FROM tb_cola_detalle_ingreso WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi_idusuraio = '".$_SESSION['usr_uid']."'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getSubTotal($value)
    {//($imp->cdi_debito / (($imp->cdi_importe/100)+1))
        $query=$this->db()->query("SELECT sum(cdi_credito) as cdi_credito, sum(cdi_debito) as cdi_debito, sum(cdi_precio_unitario * cdi_stock_ingreso) as subtotal
        FROM tb_cola_detalle_ingreso WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi_ci_id = '$value' AND cdi_type = 'AR' AND cdi_idusuraio = '".$_SESSION['usr_uid']."'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getImpuestos($value)
    {
        $query=$this->db()->query("SELECT *, sum(cdi_credito) as cdi_credito, sum(cdi_debito) as cdi_debito 
        FROM tb_cola_detalle_ingreso 
        WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi_ci_id = '$value' AND cdi_type = 'AR' AND cdi_idusuraio = '".$_SESSION['usr_uid']."' GROUP BY cdi_importe");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }
    public function getCart()
    {
        $query=$this->db()->query("SELECT * FROM tb_cola_ingreso 
        WHERE ci_idsucursal = '".$_SESSION["idsucursal"]."' AND ci_usuario = '".$_SESSION["usr_uid"]."'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }
    public function getArtByCart($cart)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query=$this->db()->query("SELECT * FROM tb_cola_detalle_ingreso
            WHERE cdi_idsucursal = '".$_SESSION["idsucursal"]."' AND cdi_ci_id = '$cart' AND cdi_idusuraio = '".$_SESSION['usr_uid']."'");
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
    public function getAllCart()
    {
        $query=$this->db()->query("SELECT * FROM tb_cola_detalle_ingreso 
        WHERE cdi_idsucursal = '".$_SESSION["idsucursal"]."' AND cdi_idusuraio = '".$_SESSION['usr_uid']."'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    #################################################

    public function createCart()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query = "INSERT INTO tb_cola_ingreso (ci_usuario,ci_idsucursal,ci_idproveedor,ci_tipo_pago,ci_comprobante,ci_fecha,ci_fecha_final)
            VALUES(
                '".$this->ci_usuario."',
                '".$this->ci_idsucursal."',
                '".$this->ci_idproveedor."',
                '".$this->ci_tipo_pago."',
                '".$this->ci_comprobante."',
                '".$this->ci_fecha."',
                '".$this->ci_fecha_final."'
            )";
            $addCart=$this->db()->query($query);
            $returnId=$this->db()->query("SELECT ci_id FROM tb_cola_ingreso WHERE ci_idsucursal = '".$_SESSION["idsucursal"]."' AND ci_usuario = '".$_SESSION["usr_uid"]."' ORDER BY ci_id DESC LIMIT 1");
            if($returnId->num_rows > 0){
                while($row = $returnId->fetch_assoc()) {
                    $cc_id_transa= $row["ci_id"];
                }
            }
            if($addCart){
                $status = $cc_id_transa;
            }else{
                $status =false;
            }
        return $status;
        }else{
            return [];
        }
    }

    public function updateValue($param,$value,$cdi_id)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query = $this->db()->query("UPDATE tb_cola_detalle_ingreso SET $param = '$value' WHERE cdi_id = '$cdi_id'");
        if($param == 'cdi_importe' && $value < 0.1){
            $query2= $this->db()->query("UPDATE tb_cola_detalle_ingreso SET cdi_precio_total_lote = (cdi_precio_unitario*cdi_stock_ingreso), cdi_credito = (cdi_precio_unitario*cdi_stock_ingreso), cdi_debito = (cdi_precio_unitario*cdi_stock_ingreso) WHERE cdi_id = '$cdi_id'");
            return $query2;
        }else{
            $query2= $this->db()->query("UPDATE tb_cola_detalle_ingreso SET cdi_precio_total_lote = (cdi_precio_unitario*cdi_stock_ingreso*((cdi_importe/100)+1)), cdi_credito = (cdi_precio_unitario*cdi_stock_ingreso*((cdi_importe/100)+1)), cdi_debito = (cdi_precio_unitario*cdi_stock_ingreso*((cdi_importe/100)+1)) WHERE cdi_id = '$cdi_id'");
            return $query2;
        }
        }else{
            return false;
        }
    }

}