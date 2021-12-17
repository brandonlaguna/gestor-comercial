<?php
class ReporteContable extends EntidadBase{

    private $rcc_id;
    PRIVATE $rcc_idsucursal;
    private $rcc_title;
    private $rcc_param1;
    private $rcc_param2;
    private $rcc_param3;
    private $rcc_type;
    private $rcc_start_date;
    private $rcc_end_date;
    private $rcc_filetype;

    public function __construct($adapter) {
        $table ="tb_reporte_contable";
        parent:: __construct($table, $adapter);
    }
    public function getRcc_id()
    {
        return $this->rcc_id;
    }
    public function setRcc_id($rcc_id)
    {
        $this->rcc_id = $rcc_id;
    }
    public function getRcc_idsucursal()
    {
        return $this->rcc_idsucursal;
    }
    public function setRcc_idsucursal($rcc_idsucursal)
    {
        $this->rcc_idsucursal = $rcc_idsucursal;
    }
    public function getRcc_title()
    {
        return $this->rcc_title;
    }
    public function setRcc_title($rcc_title)
    {
        $this->rcc_title = $rcc_title;
    }
    public function getRcc_param1()
    {
        return $this->rcc_param1;
    }
    public function setRcc_param1($rcc_param1)
    {
        $this->rcc_param1 = $rcc_param1;
    }
    public function getRcc_param2()
    {
        return $this->rcc_param2;
    }
    public function setRcc_param2($rcc_param2)
    {
        $this->rcc_param2 = $rcc_param2;
    }
    public function getRcc_param3()
    {
        return $this->rcc_param3;
    }
    public function setRcc_param3($rcc_param3)
    {
        $this->rcc_param3 = $rcc_param3;
    }
    public function getRcc_start_date()
    {
        return $this->rcc_start_date;
    }
    public function setRcc_start_date($rcc_start_date)
    {
        $this->rcc_start_date = $rcc_start_date;
    }
    public function getRcc_end_date()
    {
        return $this->rcc_end_date;
    }
    public function setRcc_end_date($rcc_end_date)
    {
        $this->rcc_end_date = $rcc_end_date;
    }

    public function getRcc_type()
    {
        return $this->rcc_type;
    }
    public function setRcc_type($rcc_type)
    {
        $this->rcc_type = $rcc_type;
    }
    public function getRcc_filetype()
    {
        return $this->rcc_filetype;
    }
    public function setRcc_filetype($rcc_filetype)
    {
        $this->rcc_filetype = $rcc_filetype;
    }

    public function addReport()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = "INSERT INTO tb_reporte_contable (rcc_idsucursal,rcc_title, rcc_param1, rcc_param2, rcc_param3, rcc_type, rcc_filetype, rcc_start_date, rcc_end_date)
                VALUES(
                    '".$this->rcc_idsucursal."',
                    '".$this->rcc_title."',
                    '".$this->rcc_param1."',
                    '".$this->rcc_param2."',
                    '".$this->rcc_param3."',
                    '".$this->rcc_type."',
                    '".$this->rcc_filetype."',
                    '".$this->rcc_start_date."',
                    '".$this->rcc_end_date."'
                )";

                $addReport=$this->db()->query($query);

                $returnId = $this->db()->query("SELECT rcc_id FROM tb_reporte_contable ORDER BY rcc_id DESC LIMIT 1");
                if($returnId->num_rows > 0){
                    while($row = $returnId->fetch_assoc()) {
                        $rcc_id= $row["rcc_id"];
                    }
                }
                return $rcc_id;
        }
    }

    public function getReporteConableById($id)
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $query = $this->db()->query("SELECT * FROM tb_reporte_contable WHERE rcc_id = '$id'");
            if($query->num_rows > 0){
                while ($row = $query->fetch_object()) {
                $resultSet[]=$row;
                }
            }else{
                $resultSet=[];
            }
            return $resultSet;
        }else{
            return [];
        }
    }

}
?>

