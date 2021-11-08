<?php
use Carbon\Carbon;
class CarteraEdadesController extends Controladorbase{

    private $adapter;
    private $conectar;
    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->loadModel([
            'Informes/M_CarteraEdades'
        ],$this->adapter);
    }
    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $this->frameview('v2/carteraEdades/carteraEdades',[]);
            $this->load([
                'v2/carteraEdades/carteraEdadesModals',
                'v2/carteraEdades/carteraEdadesScript',
                'v2/carteraEdades/carteraEdadesTables'
            ],[]);

        }else{
            $this->redirect('Index','');
        }
    }

    public function getCarteraEdadesAjax()
    {
        $carteraEdades = $this->M_CarteraEdades->getCarteraEdades();
        $listCarteraEdades = [];
        foreach ($carteraEdades as $cartera) {
            $start_date = Carbon::now();
            $end_date = Carbon::createFromFormat('Y-m-d', $cartera['cc_fecha_final_cpte']);
            $diffDays = $start_date->diffInDays($end_date);
            $listCarteraEdades[] = [
                0   => substr($cartera['nombreTercero'],0,40),
                1   => $cartera['documentoTercero'],
                2   => $cartera['nombreSucursal'],
                3   => $cartera['cc_num_cpte']."-".$cartera['cc_cons_cpte'],
                4   => $cartera['cc_fecha_cpte'],
                5   => $cartera['cc_fecha_final_cpte'],
                6   => $cartera['fechaProxima'],
                7   => $cartera['deudaTotal'],
                8   => $cartera['totalPago'],
                9   => $diffDays.' Días',
                10  => ($diffDays >= 0 && $diffDays <= 30)? ($cartera['deudaTotal'] - $cartera['totalPago']) : '-',
                11  => ($diffDays >= 31 && $diffDays <= 60)? ($cartera['deudaTotal'] - $cartera['totalPago']) : '-',
                12  => ($diffDays >= 61 && $diffDays <= 90)? ($cartera['deudaTotal'] - $cartera['totalPago']) : '-',
                13  => ($diffDays >= 91 && $diffDays <= 120)? ($cartera['deudaTotal'] - $cartera['totalPago']) : '-',
                14  => ($diffDays >= 121 && $diffDays <= 180)? ($cartera['deudaTotal'] - $cartera['totalPago']) : '-',
                15  => ($diffDays >= 181 && $diffDays <= 360)? ($cartera['deudaTotal'] - $cartera['totalPago']) : '-',
                16  => ($diffDays >= 361)? ($cartera['deudaTotal'] - $cartera['totalPago']) : '-',
                17  => ''
            ];
        }
        echo json_encode($listCarteraEdades);
    }
}