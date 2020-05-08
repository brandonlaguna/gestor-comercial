<?php
class LoginController extends ControladorBase{
    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }
    public function index()
    {
       $this->frameview("index",array());
    }

    public function newUser()
    {
        if(!empty($_POST)){

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
            if($verifyCode == 1){
                $add= new Login($this->adapter);
                $add->setju_email($email);
                $add->setju_password_($password);
                $add->setju_name($name);
                $add->setju_profile_photo($profile_photo);
                $add->setrc_id($register_code);
                $add->setju_type($type);
                $add->setsc_id(1);
                $saveprofile = $add->createByCredentials();

                $unableCode= $login->unableCode($register_code);
                
                $this->view("Login",array("alert"=>"Te has registrado correctamente, ahora inicia sesion"));
            }else{
                $this->view("Login",array("alert"=>"Lo siento, no tienes permiso para registrarte"));
            }
            
            
        }else{
            $this->redirect("Index","");
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
        if(!empty($_POST)){
            if (!isset($_POST['usr_username']) && !isset($_POST['usr_password']) || empty($_POST['usr_username']) && empty($_POST['usr_password'])) {
                $this->view("index",array("alert"=>"¡Por favor complete el campo de nombre de usuario y contraseña!"));
            }else{
                $usr_username = $_POST['usr_username'];
                $usr_password = $_POST['usr_password'];

                $login = new Login($this->adapter);
                $login->setju_email($usr_username);
                $login->setju_password_($usr_password);
                $session = $login->authentication();
 
                if($session){
                    foreach($session as $login){
                    }
                    $_SESSION['logged_in'] = true;
                    $_SESSION['usr_name'] = $login->ju_name;
                    $_SESSION['usr_uid'] = $login->ju_uid;
                    $_SESSION['profile_photo'] = $login->ju_profile_photo;
                    $_SESSION['permission'] = $login->ju_type;
                    $_SESSION['sucursal'] = $login->razon_social;
                    $_SESSION['sucursal_city'] = $login->direccion;
                    $_SESSION['idsucursal'] = $login->idsucursal;
                    $this->redirect("index","");
                }else{
                    $this->redirect("index","");
                }

            }
        }
        
    }

    public function logout()
    {
        session_destroy();
        $this->redirect("index","");
    }

    public function test()
    {
       echo json_encode($this->frameview("login/index",array()));
    }
}

?>