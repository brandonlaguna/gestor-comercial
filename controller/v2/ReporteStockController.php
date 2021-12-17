<?php
use Carbon\Carbon;
class ReporteStockController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();

        $this->loadModel([
            'Categorias/M_Categorias',
            'Sucursales/M_Sucursales',
            'Reportes/M_ReporteStock'
        ],$this->adapter);
    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $sucursales     = $this->M_Sucursales->getSucursales();
            $this->frameview('v2/reporteStock/reporteStock',[]);
            $this->load([
                'v2/reporteStock/reporteStockModals',
                'v2/reporteStock/reporteStockScript',
                'v2/reporteStock/reporteStockTable'
            ],[
                'sucursales'    => $sucursales
            ]);
        }
    }

    public function reporteStockAjax()
    {
        $arrayFiltro = [];
        $filtros = isset($_POST['filtrosCuentas'])?json_decode($_POST['filtrosCuentas'],true):null;
        //macro de filtros
        if(isset($filtros) && !empty($filtros)){
            $arrayFiltro = array_merge($arrayFiltro, $filtros);
        }

        $reporteStock = $this->M_ReporteStock->getReporteStock($arrayFiltro);
        $listReporteStock =[];
        foreach ($reporteStock as $stock) {
            $listReporteStock[] =[
                0   => $stock->idarticulo,
                1   => $stock->nombreArticulo,
                2   => $stock->nombreSucursal,
                3   => $stock->totalEntrada,
                4   => moneda($stock->promedioEntrada*$stock->totalEntrada,true),
                5   => $stock->totalSalida,
                6   => moneda($stock->promedioSalida*$stock->totalSalida,true),
                7   => $stock->totalEntrada - $stock->totalSalida,
                8   => (($stock->promedioSalida - $stock->promedioEntrada) / 100).'%'
            ];
        }
        echo json_encode($listReporteStock);
    }
}
