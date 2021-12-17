<?php
class UsuariosController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function Index()
    {
        //obtener usuarios
        $datausuario = new Usuario($this->adapter);
        $usuarios = $datausuario->getUsuario();

        $this->frameview("global/usuarios/listUsuario",array(
            "usuarios"=>$usuarios
            ));

    }

    public function empleados()
    {
        $datausuario = new Usuario($this->adapter);
        $usuarios = $datausuario->getUsuario();

        $launch=(isset($_GET["data"]) &&!empty($_GET["data"]))? $_GET["data"]:"";
        $id=(isset($_GET["s"]) && !empty($_GET["s"]))?$_GET["s"]:"";

        switch ($launch) {
            case 'edit':
                if($id){
                    $usuario=$datausuario->getUsuarioById($id);
                    $this->frameview("global/usuarios/edit",array("usuario"=>$usuario));
                }else{
                    $this->frameview("global/usuarios/listUsuario",array(
                    "usuarios"=>$usuarios
                    ));
                }
            break;
            
            default:
                    $this->frameview("global/usuarios/listUsuario",array(
                    "usuarios"=>$usuarios
                    ));
            break;
        }
        
    }

    public function nuevo_empleado()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){

            $this->frameview("global/usuarios/new",array());
        }else{
            $error = "No tienes permisos suficientes";
            $this->frameview("alrt/error/forbidden",array("error"=>$error));
        }
    }

    public function delete_empleado()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $idempleado = $_GET["data"];
            $empleados = new Empleado($this->adapter);
            $delete_empleado = $empleados->delete_empleado($idempleado);
            if($delete_empleado == true){
                $success ="Empleado eliminado.";
                $this->frameview("alert/success/successSmall",array("success"=>$success));
            }else{
                $error ="No se pudo eliminar.";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            } 
        }else{
            $error ="No tienes permisos.";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

    public function save_empleado()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
        //clase de Empleados
        $empleado = new Empleado($this->adapter);
        //clase de Usuarios
        $usuario = new Usuario($this->adapter);
        if(isset($_POST)){
                $idusuario=cln_str($_POST["idusuario"]);
                $idempleado=cln_str($_POST["idempleado"]);
                $apellidos = cln_str($_POST["apellidos"]);
                $nombre = cln_str($_POST["nombre"]);
                $num_documento = cln_str($_POST["num_documento"]);
                $direccion = $_POST["direccion"];
                $telefono = cln_str($_POST["telefono"]);
                $email = $_POST["email"];
                $fecha_nacimiento = cln_str($_POST["fecha_nacimiento"]);
                $tipo_usuario = cln_str($_POST["tipo_usuario"]);
                $foto = "https://robohash.org/".cln_str($nombre);
                if($num_documento && $nombre){
                    $empleado->setApellidos($apellidos);
                    $empleado->setNombre($nombre);
                    $empleado->setNum_documento($num_documento);
                    $empleado->setDireccion($direccion);
                    $empleado->setTelefono($telefono);
                    $empleado->setEmail($email);
                    $empleado->setFoto($foto);
                    $empleado->setFecha_nacimiento($fecha_nacimiento);
                    $empleado->setEstado('A');

                    if($tipo_usuario == "Administrador"){
                        $mnu_almacen = 1;
                        $mnu_compras = 1;
                        $mnu_ventas = 1;
                        $mnu_mantenimiento =1;
                        $mnu_seguridad =1;
                        $mnu_consulta_compras =1;
                        $mnu_consulta_ventas=1;
                        $mnu_admin =1;
                        $estado= "A";
                    }else{
                        $mnu_almacen = 0;
                        $mnu_compras = 0;
                        $mnu_ventas = 1;
                        $mnu_mantenimiento =0;
                        $mnu_seguridad =0;
                        $mnu_consulta_compras =0;
                        $mnu_consulta_ventas=0;
                        $mnu_admin =0;
                        $estado= "A";
                    }

                    $usuario->setTipo_usuario($tipo_usuario);
                    $usuario->setMnu_almacen($mnu_almacen);
                    $usuario->setMnu_compras($mnu_compras);
                    $usuario->setMnu_ventas($mnu_ventas);
                    $usuario->setMnu_mantenimiento($mnu_mantenimiento);
                    $usuario->setMnu_seguridad($mnu_seguridad);
                    $usuario->setMnu_consulta_compras($mnu_consulta_compras);
                    $usuario->setMnu_consulta_ventas($mnu_consulta_ventas);
                    $usuario->setMnu_admin($mnu_admin);
                    $usuario->setEstado($estado);

                    if(isset($idempleado) && $idempleado > 0){
                        $update_empleado = $empleado->update_empleado($idempleado);
                        $update_usuario = $usuario->update_usuario($idusuario);
                        if($update_usuario && $update_empleado){
                            echo json_encode(array(
                                "alert"=>"success",
                                "title"=>"Empleado actualizado correctamente",
                                "message"=>""
                                ));
                        }else{
                            echo json_encode(array(
                                "alert"=>"error",
                                "title"=>"No se pudo actualizar",
                                "message"=>""
                                ));
                        }
                        
                    }else{
                        $save_empleado = $empleado->create_empleado();
                        $save_usuario = $usuario->create_usuario($save_empleado);
                        if($save_empleado && $save_usuario){
                            echo json_encode(array(
                                "alert"=>"success",
                                "title"=>"Empleado creado correctamente",
                                "message"=>""
                                ));
                        }else{
                            echo json_encode(array(
                                "alert"=>"warning",
                                "title"=>"No",
                                "message"=>""
                                ));
                        }
                        }
 
                }else{
                  
                    echo json_encode(array(
                        "alert"=>"warning",
                        "title"=>"Numero de documento y cedula son obligatorios",
                        "message"=>""
                        ));
                }
        }else{}
        }else{
            $error = "No tienes permisos suficientes";
            
        }

    }

    public function update_usuario()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
        $alert= "";
        //clase de Empleado
        $empleado = new Empleado($this->adapter);
        //clase de Usuario
        $usuario = new Usuario($this->adapter);
        if(isset($_POST["nombre"])){
            //NIVEL DE PERMISO
            if($_SESSION["permission"]>=4){
                //Limpiando datos
                $idusuario=cln_str($_POST["idusuario"]);
                $idempleado=cln_str($_POST["idempleado"]);
                $apellidos = cln_str($_POST["apellidos"]);
                $nombre = cln_str($_POST["nombre"]);
                $num_documento = cln_str($_POST["num_documento"]);
                $direccion = cln_str($_POST["direccion"]);
                $telefono = cln_str($_POST["telefono"]);
                $email = cln_str($_POST["email"]);
                $fecha_nacimiento = cln_str($_POST["fecha_nacimiento"]);
                $login = cln_str($_POST["login"]);
                $clave = $_POST["clave"];
                $tipo_usuario = cln_str($_POST["tipo_usuario"]);
                
                $empleado->setApellidos($apellidos);
                $empleado->setNombre($nombre);
                $empleado->setNum_documento($num_documento);
                $empleado->setDireccion($direccion);
                $empleado->setTelefono($telefono);
                $empleado->setEmail($email);
                $empleado->setFecha_nacimiento($fecha_nacimiento);
                $empleado->setLogin($login);
                $empleado->setClave($clave);
                $update_usuario = $empleado->update_empleado($idempleado);

                if($tipo_usuario == "Administrador"){
                    $mnu_almacen = 1;
                    $mnu_compras = 1;
                    $mnu_ventas = 1;
                    $mnu_mantenimiento =1;
                    $mnu_seguridad =1;
                    $mnu_consulta_compras =1;
                    $mnu_consulta_ventas=1;
                    $mnu_admin =1;
                    $estado= "A";
                }else{
                    $mnu_almacen = 0;
                    $mnu_compras = 0;
                    $mnu_ventas = 1;
                    $mnu_mantenimiento =0;
                    $mnu_seguridad =0;
                    $mnu_consulta_compras =0;
                    $mnu_consulta_ventas=0;
                    $mnu_admin =0;
                    $estado= "A";
                }

                $usuario->setTipo_usuario($tipo_usuario);
                $usuario->setMnu_almacen($mnu_almacen);
                $usuario->setMnu_compras($mnu_compras);
                $usuario->setMnu_ventas($mnu_ventas);
                $usuario->setMnu_mantenimiento($mnu_mantenimiento);
                $usuario->setMnu_seguridad($mnu_seguridad);
                $usuario->setMnu_consulta_compras($mnu_consulta_compras);
                $usuario->setMnu_consulta_ventas($mnu_consulta_ventas);
                $usuario->setMnu_admin($mnu_admin);
                $usuario->setEstado($estado);
                $update_usuario= $usuario->update_usuario($idusuario);

                $alert ="Actualizado";  
            }else{
                $alert="No tienes permisos";
            }
        }else{
            $alert ="no se enviaron datos";
        }

        return $this->frameview("alert/basic",array("alert"=>$alert));
    }else{}
    
    }

    public function loadEmpleado()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            if(isset($_POST["idempleado"]) && !empty($_POST["idempleado"])){
                $idempleado = $_POST["idempleado"];
                $empleados = new Empleado($this->adapter);
                $loadEmpleado = $empleados->getEmpleadoById($idempleado);
                if($loadEmpleado){
                    foreach ($loadEmpleado as $empleado) {}
                        $result = array(
                            "nombre"=>$empleado->nombre,
                            "email"=>$empleado->email,
                            "avatar"=>$empleado->foto
                        );
                        
                }else{
                    $result = array(
                        "nombre"=>"",
                        "email"=>"",
                        "avatar"=>""
                    );
                }
                echo json_encode($result);
            }else{

            }
        }
    }
}