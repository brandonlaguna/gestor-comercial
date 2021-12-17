<?php
class ConfiguracionController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function Index()
    {
        //obtener global
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
        $dataglobal = new sGlobal($this->adapter);
        $global =$dataglobal->getGlobal();

        $this->frameview("global/index",array(
            "global"=>$global,
        ));
    }else{
        echo "Forbidden Gateway";
    }

    }
    public function empresa()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
        //obtener global
        $dataglobal = new sGlobal($this->adapter);
        $global =$dataglobal->getGlobal();

        $this->frameview("global/form/globalForm",array(
            "global"=>$global,
        ));
        }else{
            echo "Forbidden Gateway";
        }
    }

    public function update_global()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
        $alert= "";
        if(isset($_POST["razon_social"])){
            
            $razon_social = cln_str($_POST["razon_social"]);
            $direccion = cln_str($_POST["direccion"]);
            $telefono = cln_str($_POST["telefono"]);
            $email = cln_str($_POST["email"]);
            $pais = cln_str($_POST["pais"]);
            $ciudad =cln_str($_POST["ciudad"]);
            $nombre_impuesto = cln_str($_POST["nombre_impuesto"]);
            $porcentaje_impuesto = cln_str($_POST["porcentaje_impuesto"]);

            $global= new sGlobal($this->adapter);
            $global->setEmpresa($razon_social);
            $global->setDireccion($direccion);
            $global->setTelefono($telefono);
            $global->setEmail($email);
            $global->setPais($pais);
            $global->setCiudad($ciudad);
            $global->setNombre_impuesto($nombre_impuesto);
            $global->setPorcentaje_impuesto($porcentaje_impuesto);
            $updateValues= $global->updateSucursal();

            $alert ="Actualizado";
            }
            
        }else{
            $alert="No tienes permisos";
        }

        $this->frameview("alert/basic",array("alert"=>$alert));
    }

    public function usuarios() 
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            $user = new User($this->adapter);
            $users = $user->getUserAll();

            $this->frameview("global/user/index",array(
                "user"=>$users
            ));
        }else{
            $error = "No tienes permisos";
            return $this->frameview("alert/error/forbidden",array("error"=>$error));

        }
    }

    public function new_user()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            $user = new User($this->adapter);
            $empleado = new Empleado($this->adapter);
            $sucursal = new Sucursal($this->adapter);
            $codigos = $user->getRegCode();
            $empleados =$empleado->getAll();
            $sucursales = $sucursal->getAll();
            $permisos = $user->getPermisosAll();


            if($codigos){
                foreach ($codigos as $codigo) {}
                $idsuscripcion = ($codigo->rc_id)?$codigo->rc_id:false;
            }else{
                $idsuscripcion = false;
            }

            $this->frameview("global/user/new/index",array(
                "idsucursal"=>$_SESSION["idsucursal"],
                "usuario"=>$_SESSION["usr_uid"],
                "idsuscripcion"=>$idsuscripcion,
                "empleados"=>$empleados,
                "sucursales"=>$sucursales,
                "permisos"=>$permisos,
            ));
        }else{ 
            $error = "No tienes permisos";
            return $this->frameview("alert/error/forbidden",array("error"=>$error));  
        }
    }

    public function user_detail()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $ju_uid = $_GET["data"];
                $users = new User($this->adapter);
                $user = $users->getUserById($ju_uid);

                if($user){
                    
                    $this->frameview("global/user/detail/index",array(
                        "user"=>$user
                    ));

                }else{
                    $error= "Usuario no existe";
                    return $this->frameview("alert/error/forbidden",array("error"=>$error));
                }

            }else{
                $error= "Hay un problema con este usuario";
                return $this->frameview("alert/error/forbidden",array("error"=>$error));
            }
        }
    }

    public function edit_user()
    {
        $this->frameview("global/user/edit/index",array(
            
        ));
    }

    public function save_user()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            if(isset($_POST) && !empty($_POST)){
                if(isset($_POST["idsuscripcion"]) && !empty($_POST["idsuscripcion"])){
                    $idsuscripcion = $_POST["idsuscripcion"];
                    $empleado = (isset($_POST["empleado"]) && !empty($_POST["empleado"]))?$_POST["empleado"]:false;
                    $nombre_empleado = (isset($_POST["nombre_empleado"]) && !empty($_POST["nombre_empleado"]))?$_POST["nombre_empleado"]:false;
                    $idsucursal = $idsucursal = (isset($_POST["idsucursal"]) && !empty($_POST["idsucursal"]))?$_POST["idsucursal"]:false;
                    $type_user = (isset($_POST["type_user"]) && !empty($_POST["type_user"]))?$_POST["type_user"]:false;
                    $email = (isset($_POST["email"]) && !empty($_POST["email"]))?$_POST["email"]:false;
                    $password = (isset($_POST["password"]) && !empty($_POST["password"]))?$_POST["password"]:false;
                    $password2 = (isset($_POST["password2"]) && !empty($_POST["password2"]))?$_POST["password2"]:false;
                    $avatar = (isset($_POST["avatar"]) && !empty($_POST["avatar"]))?$_POST["avatar"]:false;
                    if($password == $password2){
                        $usuario = new Usuario($this->adapter);
                        $user = new User($this->adapter);
                        $user->setJu_uid($empleado);
                        $user->setJu_name($nombre_empleado);
                        $user->setJu_email($email);
                        $user->setJu_password_($password);
                        $user->setju_profile_photo($avatar);
                        $user->setRc_id($idsuscripcion);
                        $user->setJu_type($type_user);
                        $user->setSc_id($idsucursal);
                        $user->setJu_active(1);
                        $saveUser = $user->createByCredentials();

                        if($saveUser){
                           
                            $userRegCode = $user->useRegCode($idsuscripcion);
                            echo json_encode(array(
                                "alert"=>"success",
                                "title"=>"Usuario creado",
                                "message"=>""
                            ));
                        }else{
                            echo json_encode(array(
                                "alert"=>"error",
                                "title"=>"No se pudo crear este usuario",
                                "message"=>"Puede que ya exista este usuario"
                            ));
                        }

                    }else{
                        echo json_encode(array(
                            "alert"=>"warning",
                            "title"=>"Clave secreta incorrecta",
                            "message"=>"Las claves secretas no coinciden"
                        ));
                    }

                }else{
                    echo json_encode(array(
                        "alert"=>"warning",
                        "title"=>"No tienes mas suscripciones",
                        "message"=>""
                        ));
                }
            }else{
                echo json_encode(array(
                    "alert"=>"error",
                    "title"=>"No tienes mas suscripciones",
                    "message"=>""
                    ));
            }
        }else{
            echo json_encode(array(
                "alert"=>"error",
                "title"=>"No tienes mas suscripciones",
                "message"=>""
                ));
        }
    }

    
}