<?php
class Login extends EntidadBase{
    private $ju_uid;
    private $ju_email;
    private $ju_name;
    private $ju_profile_photo;
    private $ju_type;
    private $rc_id;
    private $ju_password_;

    public function __construct($adapter){
        $table = "jgc_users_";
        parent:: __construct($table, $adapter);
    }

    public function getju_uid()
    {
        return $this->ju_uid;
    }
    public function setju_uid($ju_uid)
    {
        $this->ju_uid = $ju_uid;
    }

    public function getju_email()
    {
        return $this->ju_email;
    }
    public function setju_email($ju_email)
    {
        $this->ju_email = $ju_email;
    }

    public function getju_name()
    {
        return $this->ju_name;
    }
    public function setju_name($ju_name)
    {
        $this->ju_name = $ju_name;
    }

    public function getju_profile_photo()
    {
        return $this->ju_profile_photo;
    }
    public function setju_profile_photo($ju_profile_photo)
    {
        $this->ju_profile_photo = $ju_profile_photo;
    }

    public function getju_type()
    {
        return $this->ju_type;
    }
    public function setju_type($ju_type)
    {
        $this->ju_type = $ju_type;
    }
    
    public function getju_password_()
    {
        return $this->ju_password_;
    }
    public function setju_password_($ju_password_)
    {
        $this->ju_password_ = $ju_password_;
    }

    public function getrc_id()
    {
        return $this->rc_id;
    }
    public function setrc_id($rc_id)
    {
        $this->rc_id = $rc_id;
    }

    public function getsc_id()
    {
        return $this->sc_id;
    }
    public function setsc_id($sc_id)
    {
        $this->sc_id = $sc_id;
    }

    public function verifyCode($code)
    {
        if($code){
            $query = $this->db()->query("SELECT * FROM reg_code_ WHERE rc_id = '$code'");
            if($query->num_rows > 0){
                while($row = $query->fetch_assoc()) {
                   $resultSet=$row['rc_active'];
                
            }}else{$resultSet = 2;}
        }else{
            $resultSet = 2;
        }
        return $resultSet;
    }

    public function authentication()
    { 
        $email = $this->ju_email;
        $password = sha1($this->ju_password_);
        $query = $this->db()->query("SELECT * FROM jgc_users_ INNER JOIN sucursal sc on jgc_users_.sc_id = sc.idsucursal  WHERE ju_email = '$email' AND ju_password_ = '$password'");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
            }else{
                $resultSet= false;
            }
         
         return $resultSet;
    }
    public function unableCode($code)
    {
        $query ="UPDATE jgc_";
    }
}
?>