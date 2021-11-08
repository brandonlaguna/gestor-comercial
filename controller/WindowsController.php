<?php
class WindowsController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
    }

    public function openWindow()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
            if(isset($_POST["file"]) && !empty($_POST["file"])){
                $mymediafiles = new MyMediaFiles($this->adapter);
                
                $idfile = $_POST["file"];
                $file = $mymediafiles->getFilesById($idfile);
                $idwindow = genIdRandom();
                $support_typefile = ".svg";
                foreach ($file as $data) {}
                $mmf_url=$data->mmf_url;
                $defined = array(
                    "?action=",
                    "&data=",
                    "&s=",
                    "&t=",
                    "&u="
                );
                $dirty = explode("/", $mmf_url);
                $clean_url = "";

                for($i=0;$i < count($dirty); $i++) {
                    $clean_url .= $dirty[$i].$defined[$i];
                }

                $this->frameview("windows/__construct/window",array("file"=>$file,"idwindow"=>$idwindow,"url"=>$clean_url,"support_typefile"=>$support_typefile));

            }else{}
        }else{}
    }

    public function loadWindow()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 0){
            
        }else{}
    }
}