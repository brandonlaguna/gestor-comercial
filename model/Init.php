<?php
 class jBlobal extends EntidadBase{
    public $offline = '0';
	public $offline_message = 'Este sitio está cerrado por tareas de mantenimiento.<br /> Por favor, inténtelo nuevamente más tarde.';
	public $display_offline_message = '1';
	public $offline_image = '';
	public $sitename = 'ECounts';
	public $MetaDesc = 'Gestion y Control';
	public $editor = 'tinymce';
	public $captcha = '0';
	public $email = 'brandonlagunarl@gmail.com';
	public $list_limit = '20';
	public $access = '1';
	public $debug = '0';
	public $debug_lang = '0';
	public $dbtype = 'mysqli';
	public $host = 'localhost';
	public $user = 'root';
	public $password = ''; //Nh14OtIKMVEt
    public $db = '';
	public $right = '';
	public function __construct($adapter) {
        $table ="product";
        parent:: __construct($table,$adapter);
	}
	public function MetaDesc()
	{
		echo $this->MetaDesc;
	}
	public function sitename()
	{
		echo $this->sitename;
	}
	
	public function getAgents()
	{
		$query=$this->db()->query("SELECT * FROM tb_agents_hendels INNER JOIN municipios ON tb_agents_hendels.AgentCity = municipios.id_municipio");
        
        if($query ->num_rows > 0){
           
        while ($row = $query->fetch_object()) {
           $data[]=$row;
           $rows = $query->num_rows;
       		 		}
    		}
    	else{
        	return $data = array("data"=> 0);
    	}
        return json_encode($data);
	}
	public function AllAgents()
	{
		$query=$this->db()->query("SELECT * FROM tb_agents_hendels INNER JOIN municipios ON tb_agents_hendels.AgentCity = municipios.id_municipio INNER JOIN departamentos on municipios.departamento_id = departamentos.id_departamento");
        
        if($query->num_rows > 0){
           
			while ($row = $query->fetch_object()) {
			   $resultSet[]=$row;
			   $rows = $query->num_rows;
			}
		}
		else{
			$resultSet = array("data" => 0);
		}
			return $resultSet;
	
	}

	public function email(){ 
		echo $this->email;
	}

	public function getCity(){
		$query=$this->db()->query("SELECT municipio,departamento_id,Adapted FROM municipios WHERE Adapted = 1");
        for ($data = array (); 
        $row = $query->fetch_assoc(); 
        $data[] = $row);
        //echo json_encode($data);
        return json_encode($data);
        //echo json_encode($txt,JSON_FORCE_OBJECT);
	}
	
	public function getRates($city)
	{
		$query=$this->db()->query("SELECT * FROM municipios WHERE municipio = '$city' OR municipio LIKE '$city%'");
		if($query->num_rows > 0){
           
			while ($row = $query->fetch_object()) {
			   $resultSet[]=$row;
			   $rows = $query->num_rows;
			}
		}
		else{
			$resultSet = array("data" => 0);
		}
			return $resultSet;
	}
}

?>