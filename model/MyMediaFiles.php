<?php
class MyMediaFiles extends EntidadBase{

    private $mmf_id;
    private $mmf_name;
    private $mmf_url;
    private $mmf_user;
    private $mmf_idsucursal;
    private $mmf_date_created;
    private $mmf_date_updated;
    private $mmf_ext;
    private $mmf_status;
    private $mmf_viewed;
    private $mmf_window;
    private $mmf_reg;

    public function __construct($adapter) {
        $table ="detalle_ingreso_contable";
        parent:: __construct($table, $adapter);
    }
    
    public function getMmf_id()
    {
        return $this->mmf_id;
    }
    public function setMmf_id($mmf_id)
    {
        $this->mmf_id = $mmf_id;
    }
    public function getMmf_name()
    {
        return $this->mmf_name;
    }
    public function setMmf_name($mmf_name)
    {
        $this->mmf_name = $mmf_name;
    }
    public function getMmf_url()
    {
        return $this->mmf_url;
    }
    public function setMmf_url($mmf_url)
    {
        $this->mmf_url = $mmf_url;
    }
    public function getMmf_user()
    {
        return $this->mmf_user;
    }
    public function setMmf_user($mmf_user)
    {
        $this->mmf_user = $mmf_user;
    }
    public function getMmf_idsucursal()
    {
        return $this->mmf_idsucursal;
    }
    public function setMmf_idsucursal($mmf_idsucursal)
    {
        $this->mmf_idsucursal = $mmf_idsucursal;
    }
    public function getMmf_date_created()
    {
        return $this->mmf_date_created;
    }
    public function setMmf_date_created($mmf_date_created)
    {
        $this->mmf_date_created = $mmf_date_created;
    }
    public function getMmf_date_updated()
    {
        return $this->mmf_date_updated;
    }
    public function setMmf_date_updated($mmf_date_updated)
    {
        $this->mmf_date_updated = $mmf_date_updated;
    }
    public function getMmf_ext()
    {
        return $this->mmf_ext;
    }
    public function setMmf_ext($mmf_ext)
    {
        $this->mmf_ext = $mmf_ext;
    }
    public function getMmf_status()
    {
        return $this->mmf_status;
    }
    public function setMmf_status($mmf_status)
    {
        $this->mmf_status = $mmf_status;
    }
    public function getMmf_window()
    {
        return $this->mmf_window;
    }
    public function setMmf_window($mmf_window)
    {
        $this->mmf_window = $mmf_window;
    }
    public function getMmf_viewed()
    {
        return $this->mmf_viewed;
    }
    public function setMmf_viewed($mmf_viewed)
    {
        $this->mmf_viewed = $mmf_viewed;
    }
    public function getMmf_reg()
    {
        return $this->mmf_reg;
    }
    public function setMmf_reg($mmf_reg)
    {
        $this->mmf_reg = $mmf_reg;
    }

    public function getFilesByExt($ext)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
           $query = $this->db()->query("SELECT * FROM tb_mymedia_files WHERE mmf_status = 1 and mmf_ext ='$ext'");
           if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                    $resultSet[]=$row;
                }
            }else{$resultSet=[];}
        
            return $resultSet;
        }else{
            return [];
        }
    }
    public function getFilesById($id)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $query = $this->db()->query("SELECT * FROM tb_mymedia_files WHERE mmf_status = 1 and mmf_id ='$id'");
            if($query->num_rows > 0){
                 while ($row = $query->fetch_object()) {
                     $resultSet[]=$row;
                 }
             }else{$resultSet=[];}
         
             return $resultSet;
         }else{
             return [];
         }
    }

    public function createFile()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $query = "INSERT INTO `tb_mymedia_files`(`mmf_name`, `mmf_url`, `mmf_user`, `mmf_idsucursal`,`mmf_ext`, `mmf_status`, `mmf_viewed`, `mmf_window`, `mmf_reg`) 
            VALUES(
                '".$this->mmf_name."',
                '".$this->mmf_url."',
                '".$this->mmf_user."',
                '".$this->mmf_idsucursal."',
                '".$this->mmf_ext."',
                '".$this->mmf_status."',
                '".$this->mmf_viewed."',
                '".$this->mmf_window."',
                '".$this->mmf_reg."'
                )";
            $addFile=$this->db()->query($query);
            
            return $addFile;

        }else{
            return false;
        }
    }

    
}