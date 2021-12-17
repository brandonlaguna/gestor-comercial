<?php
class Tokenization extends EntidadBase{

    private $tz_id;
    private $tz_ju_uid;
    private $tz_datacenter;
    private $tz_uid;
    private $tz_token;
    private $tz_reg_code;

    public function __construct($adapter) {
        $table ="jgc_tokenization_";
        parent:: __construct($table, $adapter);
    }

    
    public function getTz_id()
    {
        return $this->tz_id;
    }
    public function setTz_id($tz_id)
    {
        $this->tz_id = $tz_id;
    }
    public function getTz_ju_uid()
    {
        return $this->tz_ju_uid;
    }
    public function setTz_ju_uid($tz_ju_uid)
    {
        $this->tz_ju_uid = $tz_ju_uid;
    }
    public function getTz_datacenter()
    {
        return $this->tz_datacenter;
    }
    public function setTz_datacenter($tz_datacenter)
    {
        $this->tz_datacenter = $tz_datacenter;
    }
    public function getTz_uid()
    {
        return $this->tz_uid;
    }
    public function setTz_uid($tz_uid)
    {
        $this->tz_uid = $tz_uid;
    }
    public function getTz_token()
    {
        return $this->tz_token;
    }
    public function setTz_token($tz_token)
    {
        $this->tz_token = $tz_token;
    }
    public function getTz_reg_code()
    {
        return $this->tz_reg_code;
    }
    public function setTz_reg_code($tz_reg_code)
    {
        $this->tz_reg_code = $tz_reg_code;
    }

    public function setTokenization()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $query = "INSERT INTO jgc_tokenization_ (tz_ju_uid, tz_datacenter, tz_uid, tz_token, tz_reg_code,tz_date_start,tz_validation)
                    SELECT * FROM (SELECT 
                        '".$this->tz_ju_uid."',
                        '".$this->tz_datacenter."',
                        '".$this->tz_uid."',
                        '".$this->tz_token."',
                        '".$this->tz_reg_code."',
                        CURRENT_TIMESTAMP(),
                        '1'
                        ) AS tmp
                        WHERE NOT EXISTS (SELECT tz_ju_uid FROM jgc_tokenization_ WHERE tz_ju_uid = '".$this->tz_ju_uid."' and tz_validation > 0 and tz_token = '".$this->tz_token."') LIMIT 1
                    ";
                    $tokenization=$this->db()->query($query);
                    
                    if($tokenization){
                        $status =true;
                    }else{
                         $status = false;
                    }
                    return $tokenization;

        }else{
            return false;
        }
    }

    public function getTokenization()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $query = $this->db()->query("SELECT * FROM jgc_tokenization_ WHERE tz_ju_uid = '".$_SESSION["usr_uid"]."' AND tz_token = '".$_COOKIE["Token"]."' AND tz_validation = '1' LIMIT 1");
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
}
?>