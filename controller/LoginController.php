<?php
class LoginController extends ControladorBase
{
    private $adapter;
    private $conectar;

    public function __construct()
    {
        parent::__construct();

        $this->conectar = new Conectar();
        $this->adapter = $this->conectar->conexion();
    }
    public function index()
    {
        $this->frameview("index", array());

    }

    public function newUser()
    {
        if (!empty($_POST)) {

            //clean strings
            $email = cln_str($_POST['new_email']);
            $password = sha1($_POST['new_password']);
            $name = cln_str($_POST['new_name']);
            $profile_photo = cln_str('no');
            $register_code = cln_str($_POST['new_code']);
            $type = cln_str(1);
            //setting data
            $login = new Login($this->adapter);
            $verifyCode = $login->verifyCode($register_code);
            if ($verifyCode == 1) {
                $add = new Login($this->adapter);
                $add->setju_email($email);
                $add->setju_password_($password);
                $add->setju_name($name);
                $add->setju_profile_photo($profile_photo);
                $add->setrc_id($register_code);
                $add->setju_type($type);
                $add->setsc_id(1);
                $saveprofile = $add->createByCredentials();

                $unableCode = $login->unableCode($register_code);

                $this->view("Login", array("alert" => "Te has registrado correctamente, ahora inicia sesion"));
            } else {
                $this->view("Login", array("alert" => "Lo siento, no tienes permiso para registrarte"));
            }

        } else {
            $this->redirect("Index", "");
        }

    }

    public function verifyCode($code)
    {
        $login = new Login($this->adapter);
        $verifyCode = $login->verifyCode($code);
        return $verifyCode;

    }

    public function authentication()
    {
        if (!empty($_POST)) {
            if (!isset($_POST['usr_username']) && !isset($_POST['usr_password']) || empty($_POST['usr_username']) && empty($_POST['usr_password'])) {
                $this->view("index", array("alert" => "¡Por favor complete el campo de nombre de usuario y contraseña!"));
            } else {
                $usr_username = $_POST['usr_username'];
                $usr_password = $_POST['usr_password'];

                $login = new Login($this->adapter);
                $cierreturno = new CierreTurno($this->adapter);

                $login->setju_email($usr_username);
                $login->setju_password_($usr_password);
                $session = $login->authentication();

                if ($session) {
                    foreach ($session as $login) {
                    }
                    $_SESSION['logged_in'] = true;
                    $_SESSION['usr_name'] = $login->ju_name;
                    $_SESSION['usr_uid'] = $login->ju_uid;
                    $_SESSION['profile_photo'] = $login->ju_profile_photo;
                    $_SESSION['permission'] = $login->ju_type;
                    $_SESSION['sucursal'] = $login->razon_social;
                    $_SESSION['sucursal_city'] = $login->direccion;
                    $_SESSION['idsucursal'] = $login->idsucursal;

                    //almacenar datos importantes para los reportes

                    //preparar datos de turnos
                    $start_date = date("Y-m-d");
                    $start_time = date("H:i:s");
                    $cierreturno->setRct_idsucursal($login->idsucursal);
                    $cierreturno->setRct_idusuario($login->ju_uid);
                    $cierreturno->setRct_descripcion("Reporte usuario ".$login->ju_name);
                    $cierreturno->setRct_fecha_inicio($start_date . " " . $start_time);
                    $cierreturno->setRct_date($start_date);

                    $authInicio = $cierreturno->authInicio();
                    if($authInicio){
                    foreach ($authInicio as $authInicio) {}
                    if ($authInicio->rct_fecha_fin != strtotime('0000-00-00 00:00:00') && $authInicio->rct_status == 1) {
                        $cierreturno->addInicio();
                        
                    } else {

                    }
                }else{
                    $cierreturno->addInicio();
                }
                    $this->redirect("index", "");

                } else {
                    $this->redirect("index", "");
                }

            }
        }

    }

    public function logout()
    {

        $cierreturno = new CierreTurno($this->adapter);
        $start_date = date("Y-m-d");
        $cierreturno->setRct_idsucursal($_SESSION['idsucursal']);
        $cierreturno->setRct_idusuario($_SESSION['usr_uid']);
        $cierreturno->setRct_date($start_date);

        $authInicio = $cierreturno->authInicio();
        if($authInicio){
        foreach ($authInicio as $authInicio) {}
        if ($authInicio->rct_fecha_fin === '0000-00-00 00:00:00' && $authInicio->rct_status == 0 && $authInicio->rct_venta_desde >0) {
            if (!$_GET['s']) {
                $function =[];
                $type = "Mensaje del sistema";
                $legend = "Espera ahí!";

                $function[] = array(
                    "id"=>1,
                    "reaction" => "actionToReaction('reaction','modalSystem',[]); return false;",
                    "inyectHmtl" => "finish='login/logout&s=true'",
                    "functionMessage" => "Volveré de nuevo.",
                );
                $message = "hay ventas disponibles del dia de hoy, si desea hacer un cierre de turno ve a '<strong>Caja > Cierre de turno</strong>'";
                $this->frameview("modal/index", array(
                    "type" => $type,
                    "legend" => $legend,
                    "message" => $message,
                    "function" => $function,
                ));
            } else {
                session_destroy();

                $past = time() - 3600;
                foreach ($_COOKIE as $key => $value) {
                    setcookie($key, $value, $past, '/');
                }
                //$this->redirect("index", "");
                echo json_encode(array(
                    "type"=>"redirect",
                    "success"=>PATH_CLIENT
                ));
            }
        } else {

            session_destroy();

            $past = time() - 3600;
            foreach ($_COOKIE as $key => $value) {
                setcookie($key, $value, $past, '/');
            }
            //$this->redirect("index", "");
            echo json_encode(array(
                "type"=>"redirect",
                "success"=>PATH_CLIENT
            ));

        }
    }else{
        session_destroy();

        $past = time() - 3600;
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, $value, $past, '/');
        }
        //$this->redirect("index", "");
        echo json_encode(array(
            "type"=>"redirect",
            "success"=>PATH_CLIENT
        ));
    }

    }

    public function test()
    {
        echo json_encode($this->frameview("login/index", array()));
    }
}
