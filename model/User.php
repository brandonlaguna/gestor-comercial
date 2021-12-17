<?php
class User extends EntidadBase{

    private $ju_uid;
    private $ju_email;
    private $ju_password_;
    private $ju_name;
    private $ju_profile_photo;
    private $ju_type;
    private $rc_id;
    private $sc_id;
    private $ju_active;

    public function __construct($adapter) {
        $table ="jgc_users_";
        parent:: __construct($table, $adapter);
    }

    public function getJu_uid()
    {
        return $this->ju_uid;
    }
    public function setJu_uid($ju_uid)
    {
        $this->ju_uid = $ju_uid;
    }
    public function getJu_email()
    {
        return $this->ju_email;
    }
    public function setJu_email($ju_email)
    {
        $this->ju_email = $ju_email;
    }
    public function getJu_password_()
    {
        return $this->ju_password_;
    }
    public function setJu_password_($ju_password_)
    {
        $this->ju_password_ = $ju_password_;
    }
    public function getJu_name()
    {
        return $this->ju_name;
    }
    public function setJu_name($ju_name)
    {
        $this->ju_name = $ju_name;
    }
    public function getJu_profile_photo()
    {
        return $this->ju_profile_photo;
    }
    public function setJu_profile_photo($ju_profile_photo)
    {
        $this->ju_profile_photo = $ju_profile_photo;
    }
    public function getJu_type()
    {
        return $this->ju_type;
    }
    public function setJu_type($ju_type)
    {
        $this->ju_type = $ju_type;
    }
    public function getRc_id()
    {
        return $this->rc_id;
    }
    public function setRc_id($rc_id)
    {
        $this->rc_id = $rc_id;
    }
    public function getSc_id()
    {
        return $this->sc_id;
    }
    public function setSc_id($sc_id)
    {
        $this->sc_id = $sc_id;
    }
    public function getJu_active()
    {
        return $this->ju_active;
    }
    public function setJu_active($ju_active)
    {
        $this->ju_active = $ju_active;
    }

    public function getUserAll()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
        $query = $this->db()->query("SELECT * 
        FROM jgc_users_ ju
        INNER JOIN jgc_tusr jt on ju.ju_type = jt.jt_id
        INNER JOIN reg_code_ rc on ju.rc_id = rc.rc_id
        INNER JOIN sucursal sc on ju.sc_id = sc.idsucursal
        INNER JOIN empleado e on ju.ju_uid = e.idempleado
        WHERE ju.ju_active = '1'");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{
            return false;
        }
    }

    public function getUserById($ju_uid)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
        $query = $this->db()->query("SELECT * 
        FROM jgc_users_ ju
        INNER JOIN jgc_tusr jt on ju.ju_type = jt.jt_id
        INNER JOIN reg_code_ rc on ju.rc_id = rc.rc_id
        INNER JOIN sucursal sc on ju.sc_id = sc.idsucursal
        INNER JOIN usuario u on ju.ju_uid = u.idusuario
        INNER JOIN empleado e on u.idempleado = e.idempleado
        WHERE ju.ju_uid = '$ju_uid' AND ju.ju_active = '1'");

        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
            $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
        }else{
            return false;
        }
    }

    public function getRegCode()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            $query = $this->db()->query("SELECT * FROM reg_code_ WHERE rc_id > 0 and rc_active = 1 ");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return false;
        }
    }

    public function useRegCode($idcode)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            $query=$this->db()->query("UPDATE reg_code_ SET rc_active = 0 WHERE rc_id = '$idcode'");
            if($query){
                return true;
            }else{
                return false;
            }
        }
    }

    public function getPermisosAll()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            $query = $this->db()->query("SELECT * FROM jgc_tusr WHERE jt_id > 0 and jt_active = 1 ");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return false;
        }
    }

    public function createByCredentials()
    {
        $pwd = sha1($this->ju_password_);
        $query ="INSERT INTO `jgc_users_` (`ju_uid`,`ju_email`, `ju_password_`, `ju_name`, `ju_profile_photo`,`rc_id`, `ju_type`,`sc_id`,`ju_active`)
        VALUES (
                '".$this->ju_uid."',
                '".$this->ju_email."',
                '$pwd',
                '".$this->ju_name."',
                '".$this->ju_profile_photo."',
                '".$this->rc_id."',
                '".$this->ju_type."',
                '".$this->sc_id."',
                '".$this->ju_active."')";
       $saveproduct=$this->db()->query($query);
       if($saveproduct){
           $status =true;
       }else{
            $status = false;
       }
       return $status;

    }

}