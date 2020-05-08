<?php 
class Municipios Extends EntidadBase{

    public function __construct($adapter) {
        $table ="municipios";
        parent:: __construct($table, $adapter);
    }

    public function getAllMunicipios()
    {
        $query =$this->db()->query("SELECT * FROM municipios order by mn_nombre ASC");
        if($query->num_rows > 0){
            while ($row = $query->fetch_object()) {
               $resultSet[]=$row;
            }
        }else{
            $resultSet=[];
        }
        return $resultSet;
    }

    public function getAllDepartamentos()
    {
        $query =$this->db()->query("SELECT * FROM departamentos order by dp_nombre ASC");
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

?>