<?php
class VentasAjaxController extends ControladorBase{
    public $conectar;
	public $adapter;
	
    public function __construct() {
        parent::__construct();
		 
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        
    }

    public function Index()
    {
        return false;
    }

    public function getAll()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1){
            $venta = new Ventas($this->adapter);
            $ventas = $venta->getVentasAll();
            $listVentas = [];
            foreach ($ventas as $ventas) {
                $estado = ($ventas->estado_venta=='A')?"fa-check-circle":"fa-times-circle";
                $color = ($ventas->estado_venta=='A')?"text-success":"text-danger";
                $message = ($ventas->estado_venta=='A')?"Aceptado":"Cancelado";
                $tipo_pago = ($ventas->tipo_pago == 'Contado')?"success":"info";

                $infoCredito = "";
                $totalHistorico =0;

                $listVentas[] = [
                    0       =>      $ventas->idventa,
                    1       =>      $ventas->nombre_cliente,
                    2       =>      "<p class='badge badge-$tipo_pago'>$ventas->tipo_pago</p>",
                    3       =>      ' <button type="button" class="btn btn-outline-dark btn-sm btnFuncionVenta"
                                        data-tippy-content="'."<div class='btn-group-vertical'>".$infoCredito.'</div>">
                                        <i class="fas fa-history"></i> <span class="badge badge-dark">'.$totalHistorico.'</span>
                                    </button>',
                    4       =>      $ventas->prefijo." ".$ventas->serie_comprobante."-".zero_fill($ventas->num_comprobante,8),
                    5       =>      $ventas->fecha,
                    6       =>      $ventas->subtotal_importe,
                    7       =>      $ventas->total,
                    8       =>      status($ventas->estado_venta),
                ];
            }
            echo json_encode($listVentas);
        }else{
            return [];
        }
    }

    public function ver_editar_venta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 1){
            $ventas = new Ventas($this->adapter);
            $cartera = new Cartera($this->adapter);
            $alerts = [
                "warning",
                "Esta venta no se puede editar",
                false
            ];
            if(isset($_POST['idventa']) && !empty($_POST['idventa'])){
                //verificar si esta venta es a credito
                $venta = $ventas->getVentaById($_POST['idventa']);
                foreach ($venta as $venta){}

                if($venta->tipo_pago == 'Credito'){
                    //verificar si esta venta tiene saldo en cartera
                    $pagosCartera = $cartera->getPagoCarteraCliente($_POST['idventa']);
                    if($pagosCartera){
                        $count = count($pagosCartera);
                        $alerts =[
                            "error",
                            "No se puede editar esta venta, existen $count pagos en cartera",
                            false
                        ];

                    }
                }else{
                    $alerts = [
                        'success',
                        'Accediendo a editar la venta #',
                        '#ventas/edit_venta/'.$venta->idventa
                    ];
                }
            }

            echo json_encode(array($alerts));
        }else{
            $this->redirect("Index","");
        }
    }
}
