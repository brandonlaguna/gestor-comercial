<?php
class ComprobantesController extends ControladorBase{
    public $conectar;
	public $adapter;
	
    public function __construct() {
        parent::__construct();
		 
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        
    }

    public function index()
    {
        
    }

    public function informes()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $data = $_GET["data"];
                switch ($data) {
                    case 'general':
                            $comprobante = new Comprobante($this->adapter);
                            $comprobantes = $comprobante->getComprobanteAll();
                            $pos="reporte_general";
                            $control ="comprobantes";
                            $this->frameview("admin/comprobantes/general/index",array(
                                "comprobantes"=>$comprobantes,
                                "control"=>$control,
                                "pos"=>$pos
                            ));

                        break;
                    case 'detallado':
                        $comprobante = new Comprobante($this->adapter);
                        $comprobantes = $comprobante->getComprobanteAll();
                        $pos="reporte_detallado";
                        $control ="comprobantes";
                        $this->frameview("admin/comprobantes/detallado/index",array(
                            "comprobantes"=>$comprobantes,
                            "control"=>$control,
                            "pos"=>$pos
                        ));

                        break;
                    
                    default:
                        # code...
                        break;
                }
                
            }else{}
        }else{
            $error = "No tienes permisos";
            $this->frameview("alert/error/forbidden",array(
                "error"=>$error
            ));
        }
    }

    public function reporte_general()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST) && !empty($_POST)){
                //set variables
                $pos = (isset($_POST["pos"]) && !empty($_POST["pos"]))?$_POST["pos"]:false;
                $control = (isset($_POST["control"]) && !empty($_POST["control"]))?$_POST["control"]:false;
                $idcomprobante = (isset($_POST["idcomprobante"]) && !empty($_POST["idcomprobante"]))?$_POST["idcomprobante"]:false;
                $start_date = (isset($_POST["start_date"]) && !empty($_POST["start_date"]))?$_POST["start_date"]:false;
                $end_date = (isset($_POST["end_date"]) && !empty($_POST["end_date"]))?$_POST["end_date"]:false;
                //models
                $comprobantes = new Comprobante($this->adapter);
                
                //calling functions
                $datacomprobante = $comprobantes->getOnlyComprobanteById($idcomprobante);
                foreach ($datacomprobante as $comprobante) {}

                $tipo_proceso = $comprobante->proceso;
                $serie_comprobante = $comprobante->ultima_serie;
                $start_date = date_format_calendar($_POST["start_date"],"/");
                $end_date = date_format_calendar($_POST["end_date"],"/");

                switch ($tipo_proceso) {
                    case 'Venta':

                        if($start_date && $end_date){
                            $venta = new Ventas($this->adapter);
                            $dataventas = $venta->reporte_general_comprobante($start_date,$end_date,$serie_comprobante);
                        
                            $this->frameview("admin/comprobantes/general/table",array(
                                "detalle"=>$dataventas
                            ));
                        }else{
                            echo "Agrega fechas";
                        }

                        break;
                    case 'Ingreso':
                        if($start_date && $end_date){
                            $compras = new Compras($this->adapter); 
                            $datacompras = $compras->reporte_general_comprobante($start_date,$end_date,$serie_comprobante);
                            $this->frameview("admin/comprobantes/general/table",array(
                                "detalle"=>$datacompras
                            ));
                        }else{
                            echo "Agrega fechas";
                        }
                        break;

                    default:
                        
                        break;
                }
            }else{}
        }else{

        }
    }

    public function reporte_detallado()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST) && !empty($_POST)){

                $pos = (isset($_POST["pos"]) && !empty($_POST["pos"]))?$_POST["pos"]:false;
                $control = (isset($_POST["control"]) && !empty($_POST["control"]))?$_POST["control"]:false;
                $idcomprobante = (isset($_POST["idcomprobante"]) && !empty($_POST["idcomprobante"]))?$_POST["idcomprobante"]:false;
                $start_date = (isset($_POST["start_date"]) && !empty($_POST["start_date"]))?$_POST["start_date"]:false;
                $end_date = (isset($_POST["end_date"]) && !empty($_POST["end_date"]))?$_POST["end_date"]:false;
                //models
                $comprobantes = new Comprobante($this->adapter);
                
                //calling functions
                $datacomprobante = $comprobantes->getOnlyComprobanteById($idcomprobante);
                foreach ($datacomprobante as $comprobante) {}

                $tipo_proceso = $comprobante->proceso;
                $serie_comprobante = $comprobante->ultima_serie;
                $start_date = date_format_calendar($_POST["start_date"],"/");
                $end_date = date_format_calendar($_POST["end_date"],"/");

                switch ($tipo_proceso) {
                    case 'Venta':
            
                        if($start_date && $end_date){
                            $venta = new Ventas($this->adapter);
                            $dataventas = $venta->reporte_detallado_comprobante($start_date,$end_date,$serie_comprobante);
                            $this->frameview("admin/comprobantes/detallado/table",array(
                                "detalle"=>$dataventas
                            ));
                        }else{
                            echo "Agrega fechas";
                        }

                        break;
                    case 'Ingreso':

                        if($start_date && $end_date){
                            $compra = new Compras($this->adapter);
                            $datacompras = $compra->reporte_detallada_comprobante($start_date,$end_date,$serie_comprobante);
                            $this->frameview("admin/comprobantes/detallado/table",array(
                                "detalle"=>$datacompras
                            ));
                        }else{
                            echo "Agrega fechas";
                        }
                        break;
                    
                    default:
                        
                        break;
                    }
            }else{}
            }else{}
    }
}