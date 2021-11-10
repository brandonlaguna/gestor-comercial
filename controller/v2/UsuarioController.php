<?php
use Carbon\Carbon;
class UsuarioController extends Controladorbase{
    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel(['Sucursales/M_Sucursales', 'Usuarios/M_Usuarios', 'Empleados/M_Empleados', 'Parametros/M_Parametros', ],$this->adapter);
    }

    function index()
    {
        $empleados = $this->M_Empleados->obtenerEmpleadosSinCuenta();
        $sucursales = $this->M_Sucursales->getSucursales();
        $tipos_permisos = $this->M_Parametros->obtenerParametrosTipo(['pr_tpr_id' => 3]);
        $this->frameview("v2/usuarios/usuarios",[]);
        $this->load(['v2/usuarios/usuariosScript'],[]);
        $this->load(['v2/usuarios/usuariosModals'],[
            'empleados'         => $empleados,
            'sucursales'        => $sucursales,
            'tipos_permisos'    => $tipos_permisos
        ]);
        $this->load(['v2/usuarios/usuariosTable'],[]);
    }

    public function usuariosAjax()
    {
        $usuarios = $this->M_Usuarios->obtenerUsuarios([]);
        $arrayUsuarios = [];
        foreach ($usuarios as $usuario) {
            $arrayUsuarios[] =[
                0   => $usuario['idusuario'],
                1   => $usuario['nombreEmpleado'].' '.$usuario['apellidoEmpleado'],
                2   => $usuario['nombreSucursal'],
                3   => $usuario['ju_email'],
                4   => $usuario['tipoUsuario']. ' <small class="text-success">Nivel '.$usuario['ju_type'].'</small>',
                5   => $usuario['codigoUsuario'],
                6   => $usuario['ju_active'] ==1 ?'Activo':'Inactivo',
            ];
        }
        echo json_encode($arrayUsuarios);
    }

    public function guardarActualizarUsuario()
    {
        echo json_encode($_POST);
    }

    public function infoUsuario()
    {
        $mensaje = 'No se puede acceder a esta usuario';
        $estado = false;
        $data=[];
        if(isset($_POST)){
            try {
                $filtro = ['idusuario'=>$_POST['idUsuario']];
                $usuarios = $this->M_Usuarios->obtenerUsuarios($filtro);
                if($usuarios){
                    $data=$usuarios;
                    $mensaje ='';
                    $estado = true;
                }
            } catch (\Throwable $e) {
                $mensaje = $e->getMessage();
            }
        }
        echo json_encode([
            'mensaje'   =>$mensaje,
            'estado'    =>$estado,
            'data'      =>$data
        ]);
    }
}