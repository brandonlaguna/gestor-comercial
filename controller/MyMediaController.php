<?php
class MyMediaController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $folder = array(
                "img"=>array(
                    "url"=>"#myMedia/image",
                    "name"=>"Imagenes",
                    "new"=>true,
                ),
                "pdf"=>array(
                    "url"=>"#myMedia/pdf",
                    "name"=>"PDF",
                    "new"=>false
                ),
                "xls"=>array(
                    "url"=>"#myMedia/xls",
                    "name"=>"Hojas de calculo",
                    "new"=>false
                ),
            );
            $this->frameview("mymedia/index",array(
                "folder"=>$folder,
            ));
        }else{
            echo "Forbidden gateway";
        }
    }

    public function pdf()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $support_typefile = ".svg";

            $suppor_media= "pdf";
            $mymedia = new MyMediaFiles($this->adapter);
            $files = $mymedia->getFilesByExt($suppor_media);
            $this->frameview("mymedia/folder/folder",array(
                "files"=>$files,
                "support_typefile"=>$support_typefile
            ));
            javascript(array("controller/script/window-0.1"));
        }else{
            echo "Forbidden Gateway";
        }
    }



}
?>