<?php
class ComprobanteContableController extends ControladorBase{
    public $conectar;
	public $adapter;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Impuestos/M_Impuestos',
            'Cart/M_Cart',
            'Personas/M_Personas'
        ],$this->adapter);
    }

    public function crearComprobanteContable()
    {
        $estado     = false;
        $mensaje    = 'No se puede crear este comprobante contable';
        $redirect   = false;
        try {
            //cargar informacion necesaria de articulos, impuestos, retenciones, cuentas contables, terceros, sucursales, usuarios
            $dataCarrito = [
                'idsucursal'    => $_SESSION['idsucursal'],
                'idusuario'     => $_SESSION['usr_uid'],
            ];
            $carritoActual = $this->M_Cart->obtenerUltimoCarrito($dataCarrito);
            if($carritoActual){
                $dataCarrito = array_merge($dataCarrito,['ci_id' => $carritoActual['ci_id']]);
            }
            $articulos = $this->M_Cart->obtenerArticulos($dataCarrito);

            //Tercero
            $array = explode(" - ", $_POST["proveedor"]);
            $i =0;
            //se recupera datos del cliente
            foreach ($array as $search) {$getClientes = $this->M_Personas->obtenerPersonas(['documentoPersona'=>$array[$i]]);
                //si se encontro algo en proveedores lo retorna
                foreach ($getClientes as $datacliente) {}
                    $i++;
            }
            if($datacliente){
            }else{
                throw new Exception("No se pudo acceder a este tercero");
            }

            $detalleVentaContable=[
                'cc_idusuario'          => $_SESSION['usr_uid'],
                'cc_idproveedor'        => $datacliente['idpersona'],
                'cc_tipo_comprobante'   => '',
                'cc_id_forma_pago'      => $_POST["formaPago"],
                'cc_id_tipo_cpte'       => '',
                'cc_num_cpte'           => '',
                'cc_cons_cpte'          => '',
                'cc_det_fact_prov'      => '',
                'cc_det_subtotal'       => '',
                'cc_fecha_cpte'         => '',
                'cc_fecha_final_cpte'   => '',
                'cc_nit_cpte'           => '',
                'cc_dig_verifi'         => '',
                'cc_ccos_cpte'          => $_SESSION['idsucursal'],
                'cc_fp_cpte'            => '',
                'cc_log_reg'            => '',
                'cc_estado'             => '',
                'cc_date_modified'      => '',
            ];
        } catch (\Throwable $e) {
            $mensaje = $e->getMessage();
        }
        echo json_encode([
            'estado'    => $estado,
            'mensaje'   => $mensaje,
            'redirect'  => $redirect,
        ]);
    }
}