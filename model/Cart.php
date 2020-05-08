<?php
class Cart extends EntidadBase{

    private $id;
    private $ipUser;
    private $Token;
    private $NameUser;
    private $EmailUser;
    private $EmailSent;
    //
    private $idProduct;
    private $idCart;

    public function __construct($adapter) {
        $table ="tb_cart";
        parent:: __construct($table, $adapter);
    }
    
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getIpUser()
    {
        return $this->ipUser;
    }
    public function setIpUser($ipUser)
    {
        $this->ipUser = $ipUser;
    }
    public function getToken()
    {
        return $this->Token;
    }
    public function setToken($Token)
    {
        $this->Token = $Token;
    }
    public function getNameUser()
    {
        return $this->NameUser;
    }
    public function setNameUser($NameUser)
    {
        $this->NameUser = $NameUser;
    }
    public function getEmailUser()
    {
        return $this->EmailUser;
    } 
    public function setEmailUser($EmailUser)
    {
        $this->EmailUser = $EmailUser;
    }
    public function getEmailSent()
    {
        return $this->EmailSent;
    }
    public function setEmailSent($EmailSent)
    {
        $this->EmailSent = $EmailSent;
    }
    public function getIdProduct()
    {
        return $this->idProduct;
    }
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;
    }
    public function getIdCart()
    {
        return $this->idCart;
    }
    public function setIdCart($idCart)
    {
        $this->idCart = $idCart;
    }
    public function AuthCart($notouch,$token)
    {
        $query ="SELECT * FROM tb_cart WHERE ipUser = '$notouch' AND Token = '$token'";
        $result = $this->db()->query($query);
        if ($result->num_rows  == 0) {
            
            $query ="INSERT INTO tb_cart (`idCart`,`ipUser`,`Token`,`EmailSent`)
                VALUES (NULL,
                        '".$this->ipUser."',
                        '".$this->Token."',
                        '".$this->EmailSent."')";
        $createcart=$this->db()->query($query);
        if($createcart){
           $status ="";
            }
        return $createcart;
        }
    }

    public function getCart($notouch,$token)
    {
        $query = $this->db()->query("SELECT * FROM tb_cart WHERE ipUser = '$notouch' AND Token = '$token'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
         
         return $resultSet;
        }
    }

    public function addtocart($token,$idProduct)
    {
        $filter= "SELECT * FROM tb_item_cart WHERE Token = '".$this->Token."' AND idProduct = '".$this->idProduct."'";
            $resultfilter = $this->db()->query($filter);
                if ($resultfilter->num_rows  == 0) {
                    $query="INSERT INTO `tb_item_cart` (`id`, `idProduct`,`idCart`,`Quantity`,`Token`) 
                    VALUES (NULL,
                            '".$this->idProduct."',
                            '".$this->idCart."',
                            '1',
                            '".$this->Token."')";
                    $data=$this->db()->query($query);
                        if($data){$stat = array("status" => 1);
                        }else{$stat = array("status" => 0);}
                return json_encode($data);    
            }
        elseif ($resultfilter->num_rows  > 0) {
                $query ="UPDATE `tb_item_cart` SET
                `Quantity`  =  `Quantity`+1 WHERE idProduct = ".$this->idProduct.";";
                $update =$this->db()->query($query);
                if($update){$stat = array("status" => 1);
                }else{$stat = array("status" => 0);}
                }
                else{}
    }

    public function getPayment()
    {
        $query=$this->db()->query("SELECT * FROM tb_pay WHERE Activation = 1 ORDER BY id ASC ");

        while ($row = $query->fetch_object()) {
           $resultSet[]=$row;
        }
        
        return $resultSet;
    }

    public function additem($id)
    {
        $query="INSERT INTO `tb_item_cart` (`id`, `idProduct`,`idCart`,`Quantity`) 
        VALUES (NULL,
                '$id',
                '0',
                '1') WHERE id = '$id'";

            $data=$this->db()->query($query);
            return json_encode($data);
    }
    
    public function jsGetCartValue($token)
    {
        $query ="SELECT * FROM tb_item_cart WHERE Token = '$token'";
        if ($sentencia = $this->db()->prepare($query)) {
    /* ejecutar la consulta */
        $sentencia->execute();
    /* almacenar el resultado */
        $sentencia->store_result();
            $row = array(
                "row" => $sentencia->num_rows,
            );
    return json_encode($row);
    /* cerrar la sentencia */
    $sentencia->close();
}
    }

    public function deleteitem($id)
    {
        $token = $_COOKIE['Token'];
        $query = "DELETE FROM tb_item_cart WHERE idProduct = '$id' AND Token = '$token'";
        $delete = $this->db()->query($query);
        if($delete){
          $status = array("status" => 1);
           
        }else{
            $status =  array("status" => 0);
        }
        return json_encode($status);
        //$this->db()->close();
    }

    public function updatevalue($func,$id,$token)
    {
        $filter = "SELECT * FROM tb_item_cart WHERE idProduct = '$id' AND Token ='$token'";
        $resultfilter = $this->db()->query($filter);
        if ($resultfilter->num_rows  > 0) {
            if($func == 'less'){$action = '-1';}
            elseif($func == 'plus'){$action = '+1';}
            else{$action = '';}
            
            while($row = $resultfilter->fetch_assoc()) {
                if($row["Quantity"] <= 1 && $func == 'less') {
                    $query = "DELETE FROM tb_item_cart WHERE idProduct = '$id' AND Token = '$token'";
                    $update =$this->db()->query($query);
                    $data = array(
                        "status" => 1
                    );
                    return json_encode($data);
                }else{
            $query ="UPDATE `tb_item_cart` SET
                `Quantity`  =  `Quantity`$action WHERE idProduct = '$id' AND Token = '$token';";
                $update =$this->db()->query($query);
                $data = array(
                    "status" => 1
                );
                return json_encode($data);
                }
            }
            }else{
            $data = array(
                "status" => 0
            );
            return json_encode($data);
        }


    }


}
