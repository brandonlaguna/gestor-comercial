<?php
class ControladorBase{

    public function __construct() {
		require_once 'Conectar.php';
        require_once 'EntidadBase.php';
        require_once 'ModeloBase.php';
        //Incluir todos los modelos
        foreach(glob("model/*.php") as $file){
            require_once $file;
        }
    }
    
    //Plugins y funcionalidades

    public function view($vista,$datos){
        //check for login
        if (isset($_SESSION['logged_in']) && !empty($_SESSION['logged_in'])) {
            $check = "";
            $session =  true;
        }else{
            $check = "su";
            $session = false;
        }
        require_once 'config/web_config.php';
        require_once 'config/Robot.php';
        require_once 'core/AyudaVistas.php';
        $helper=new AyudaVistas();
        //send to login if session isn't
        if($session){
            foreach ($datos as $id_assoc => $valor) {
                ${$id_assoc}=$valor; 
            }
            $header = new Header('');
            require_once '_construct/sidemenu/sidemenu.php';
            require_once '_construct/heading/heading.php';
            html("div","class='lime-container'");
            html("div","class='lime-body'");
            html("div","class='container-fluid'");
            require_once 'view/'.$vista.'View.php';
            html("/div","");
            html("/div","");
            html("/div","");

        }else{
            require_once 'view/login/indexView.php';
        }
        require_once '_construct/footer/footer.php';
        javascript([
            "dependences/plugins/totast/src/jquery.toast",
            "core/launcher",
            "dependences/plugins/daterangepicker-master/moment.min",
            "dependences/plugins/daterangepicker-master/daterangepicker",
            "dependences/sweetalert/dist/sweetalert2.all.min"
        ]);
        echo "<div class='deep' id='deep'></div>";
        

    }
    
    public function adminview($vista,$datos){
        require_once 'config/reserved.php';
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
        require_once '_construct/heading/suheading.php';    
        require_once 'core/AyudaVistas.php';
        $helper=new AyudaVistas();
        require_once 'view/'.$vista.'View.php';

    }
    public function blogview($vista,$datos)
    {
        require_once 'config/web_config.php';
        require_once 'config/Robot.php';
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
        require_once '_construct/heading/heading.php';
        require_once 'core/AyudaVistas.php';
        $helper=new AyudaVistas();
        require_once 'view/'.$vista.'View.php';
        echo "<div class='deep' id='deep'></div>";
        require_once '_construct/sidemenu/sidemenu.php';
        require_once '_construct/footer/footer.php';
    }
    
    function frameview($vista,$datos)
    {
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
        require_once 'core/AyudaVistas.php';
            $helper=new AyudaVistas();
            require_once 'view/'.$vista.'View.php';
    }
    
    public function redirect($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
        if($accion != ''){
        //header("Location: ".LOCATION_CLIENT."/".$controlador."?action=".$accion);
        echo '<meta http-equiv="refresh" content="0;url='.$controlador.'?action='.$accion.'" >';
        }
        else{
            //header("Location: ".LOCATION_CLIENT."/".$controlador);
            echo '<meta http-equiv="refresh" content="0;url='.$controlador.'" >';
        }
    }
            //Métodos para los controladores

    public function email($vista,$datos){
        
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
            
        }
        require_once 'core/AyudaVistas.php';
        $helper=new AyudaVistas();
        require_once 'mail/'.$vista.'View.php';

    }

    function load($vista,$datos)
    {
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
        
        if(is_array($vista)):
            foreach ($vista as $vista) {
                require 'view/'.$vista.'.php';
            }
        else:
            require 'view/'.$vista.'.php';
        endif;
    }

    function loadController($controller)
    {
        if(is_array($controller)):
            foreach ($controller as $controller) {
                require_once 'controller/'.$controller.'Controller.php';
            }
        else:
            require_once 'controller/'.$controller.'Controller.php';
        endif;
    }

    public function component($component, $params =[])
    {
        if($component){
            foreach ($params as $id_assoc => $valor) {
                ${$id_assoc}=$valor; 
            }
            require 'view/components/'.$component.'.php';
        }
    }

}
?>
