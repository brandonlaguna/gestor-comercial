<?php
class EntidadBase{
    private $table;
    private $db;
    private $conectar;

    public function __construct($table, $adapter) {
        $this->table=(string) $table;
        
		
        require_once 'Conectar.php';
        $this->conectar=new Conectar();
        $this->db=$this->conectar->conexion();
		
		$this->conectar = null;
		$this->db = $adapter;
    }
    
    public function getConetar(){
        return $this->conectar;
        
    }
    
    public function db(){
        return $this->db;
    }

    
    public function getAll(){
        $query=$this->db->query("SELECT * FROM $this->table");
        if($query->num_rows > 0){
        while ($row = $query->fetch_object()) {
           $resultSet[]=$row;
        }
    }else{
        $resultSet =[];
    }
        

        return $resultSet;
    }
    public function getAllByPosition(){
        $query=$this->db->query("SELECT * FROM $this->table ");

        while ($row = $query->fetch_object()) {
           $resultSet[]=$row;
        }
        
        return $resultSet;
    }
    
    public function getById($id){
        $query=$this->db->query("SELECT * FROM $this->table WHERE id=$id");

        if($row = $query->fetch_object()) {
           $resultSet=$row;
        }
        if(!mysqli_num_rows($query) == 0){
        return $resultSet;
        }

    }
     
    public function getBy($column,$value){
        $query=$this->db->query("SELECT * FROM $this->table WHERE $column='$value'");

        while($row = $query->fetch_object()) {
           $resultSet[]=$row;
        }
        
        return $resultSet;
    }

    public function deleteById($id){
        $query=$this->db->query("DELETE FROM $this->table WHERE id=$id"); 
        return $query;
    }
    
    public function deleteBy($column,$value){
        $query=$this->db->query("DELETE FROM $this->table WHERE $column='$value'"); 
        return $query;
    }
    

    public function jsGet($table,$file){
        $query=$this->db->query("SELECT $file FROM $table");
        for ($data = array (); 
        $row = $query->fetch_assoc(); 
        $data[] = $row);
        
        //echo json_encode($data);
        return json_encode($data);
        //echo json_encode($txt,JSON_FORCE_OBJECT);
    }

    
    
    
}

?>
