<?php
use Carbon\Carbon;
class PermisosController extends Controladorbase{
    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel(['Usuarios/M_Usuarios', 'Permisos/M_Permisos', 'Parametros/M_Parametros'],$this->adapter);
    }

    public function obtenerPermisosUsuario()
    {
        $estado     = false;
        $mensaje    = 'Usuario sin permisos asignados';
        $data       = [];
        if(isset($_POST)){
            try {
                //buscar si éste usuario contiene el permiso enviado por post
                $obtenerPermiso = $this->M_Permisos->getPermisos([
                    'per_u_id'  =>  $_POST['idUsuario']
                ]);
                if($obtenerPermiso){
                    $data = $obtenerPermiso;
                    $estado = true;
                    $mensaje = '';
                }
            } catch (\Throwable $e) {
                $mensaje = $e->getMessage();
            }
        }
        echo json_encode([
            'estado'    => $estado,
            'mensaje'   => $mensaje,
            'data'      => $data
        ]);
    }

    public function guardarActualizarPermiso()
    {
        $estado     = false;
        $mensaje    = 'No se puede afectar éste permisos';
        $accionPermiso = 'agregado';
        if(isset($_POST)){
            $obtenerPermiso = $this->M_Permisos->getPermisos([
                'per_u_id'  =>  $_POST['per_u_id'],
                'per_pr_id' =>  $_POST['per_pr_id'],
            ]);
            //obtener informacion del permiso
            $dataPermiso = [
                'per_u_id'      => $_POST['per_u_id'],
                'per_estado'    => $_POST['per_estado'],
                'per_pr_id'     =>  $_POST['per_pr_id'],
            ];
            //si existe este permiso con este parametro, dejár los datos básicos para su actualización
            if($obtenerPermiso){
                foreach ($obtenerPermiso as $obtenerPermiso) {}
                $dataPermiso = array_merge($dataPermiso,['per_id' => $obtenerPermiso['per_id']]);
                $accionPermiso = 'actualizado';
            }else{
                //Obtener informacion del tipo de parametro
                $parametroPermiso = $this->M_Parametros->obtenerParametrosTipo(['pr_id'=>$_POST['per_pr_id']]);
                if($parametroPermiso){
                    foreach ($parametroPermiso as $parametroPermiso){}
                    $dataPermiso = array_merge($dataPermiso,[
                        'per_tipo_permiso'      => $parametroPermiso['pr_nombre'],
                        'per_fecha_permiso'     => Carbon::now()->format('Y-m-d H:i:s'),
                        'per_fecha_creacion'    => Carbon::now()->format('Y-m-d H:i:s'),
                        'per_nivel_permiso'     => 0,
                    ]);
                }else{
                    throw new Exception("No se encontró permisos con estas carácteristicas");
                }
            }
            //guardar permiso o en su caso actualizar
            $guardarActualizarPermiso = $this->M_Permisos->guardarActualizarPermiso($dataPermiso);
            if($guardarActualizarPermiso){
                $estado = true;
                $mensaje = "Permiso ". $accionPermiso .' correctamente';
            }else{
                $mensaje = 'Permiso no ha sido '. $accionPermiso;
            }
        }
        echo json_encode([
            'estado'    => $estado,
            'mensaje'   => $mensaje,
        ]);
    }
}