<?php
class CalculoCarritoController extends Controladorbase{

    private $adapter;
    private $conectar;
    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Cart/M_Cart',
            'Articulos/M_Articulos',
            'Impuestos/M_ColaImpuesto',
            'Retenciones/M_ColaRetencion',
            'Impuestos/M_Impuestos',
            'Retenciones/M_Retenciones'
        ],$this->adapter);
    }

    public function index()
    {
        $respuesta ='';
        try {
            if(isset($_POST)){
                $cart = $this->M_Cart->obtenerUltimoCarrito([
                    'idsucursal'    => $_SESSION['idsucursal'],
                    'idusuario'     => $_SESSION['usr_uid'],
                ]);
                if($cart){
                    $impuestos = $this->M_ColaImpuesto->obtenerColaImpuestos([
                        'cdim_ci_id'            => $cart['ci_id']
                    ]);
                    $retenciones = $this->M_ColaRetencion->obtenerColaRetencion([
                        'cdr_ci_id'            => $cart['ci_id'],
                    ]);

                    $totales = $this->obtenerTotales($cart['ci_id']);
                    echo json_encode($totales);

                    //echo $this->frameview("ventas/New/POS/calculoVenta",array(
                    //    "retenciones"=>$retenciones,
                    //    "impuestos"=>$impuestos,
                    //    "total_bruto"=>number_format(0,0,'.',','),
                    //    "total_neto"=>0,
                    //));

                }
            }
        } catch (\Throwable $e) {
            $respuesta = $e->getMessage();
        }
       // echo $respuesta;
    }

    public function obtenerTotales($ci_id = null)
    {
        $colaingreso = new ColaIngreso($this->adapter);
        //funcion reutilizable por funcion o por consulta mediante post
        $ci_id = $ci_id != null?$ci_id:$_POST['ci_id'];
        $subtotal = $colaingreso->getSubTotal($ci_id);

        if(isset($_POST['ci_id'])){
            echo $subtotal;
        }else{
            return $subtotal;
        }
    }

}