<?php
use Carbon\Carbon;
class MovimientoCuentasController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();

        $this->loadModel([
            'Categorias/M_Categorias',
            'Informes/M_MovimientoCuentas',
            'DocumentoSucursal/M_DocumentoSucursal',
            'CuentasContables/M_CuentasContables',
            'Sucursales/M_Sucursales'
        ],$this->adapter);
    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $sucursales     = $this->M_Sucursales->getSucursales(); 
            $this->frameview('v2/movimientoCuentas/movimientoCuentas',[]);
            $this->load([
                'v2/movimientoCuentas/movimientoCuentasModals',
                'v2/movimientoCuentas/movimientoCuentasScript',
                'v2/movimientoCuentas/movimientoCuentasTable'
            ],[
                'sucursales'    => $sucursales
            ]);
        }
    }

    public function getMovimientoCuentasAjax()
    {
        $cuentas = $this->M_CuentasContables->getCuentasContables(['movimiento'=>1]);
        $listMovimientoCuentas =[];
        foreach ($cuentas as $cuenta) {
            $arrayFiltro = ['dcc_cta_item_det' => $cuenta['idcodigo']];
            $filtros = isset($_POST['filtrosCuentas'])?json_decode($_POST['filtrosCuentas'],true):null;
            //macro de filtros
            if(isset($filtros) && !empty($filtros)){
                $arrayFiltro = array_merge($arrayFiltro, $filtros);
            }

            $movimientos = $this->M_MovimientoCuentas->getMovimientoCuentas($arrayFiltro);
            foreach ($movimientos as $movimiento) {
                $code = str_split($movimiento['dcc_cta_item_det'], 2);
                $listMovimientoCuentas[] =[
                    0   => isset($code[0])?$code[0]:'',
                    1   => isset($code[1])?$code[1]:'',
                    2   => isset($code[2])?$code[2]:'',
                    3   => isset($code[3])?$code[3]:'',
                    4   => substr($movimiento['nombrePersona'],0,40),
                    5   => $movimiento['cc_num_cpte'],
                    6   => $movimiento['cc_cons_cpte'],
                    7   => $movimiento['dcc_d_c_item_det'] == 'D'? $movimiento['dcc_valor_item']:0,
                    8   => $movimiento['dcc_d_c_item_det'] == 'C'? $movimiento['dcc_valor_item']:0,
                ];
            }
        }
        
        echo json_encode($listMovimientoCuentas);
    }
}