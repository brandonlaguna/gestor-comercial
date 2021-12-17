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
        require_once '_construct/modal/default.php';
        $helper=new AyudaVistas();
        //send to login if session isn't
        if($session){
            foreach ($datos as $id_assoc => $valor) {
                ${$id_assoc}=$valor;
            }
            require_once '_construct/heading/heading.php';

            html("div","class='br-mainpanel'");
            javascript(array("core/launcher"));

            require_once 'view/'.$vista.'View.php';
            html("/div","");
        }else{
            require_once 'view/login/indexView.php';
        }
        require_once '_construct/footer/footer.php';

    }
    function frameview($vista,$datos)
    {
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor;
        }
        require_once 'core/AyudaVistas.php';
            $helper=new AyudaVistas();
            require 'view/'.$vista.'View.php';
    }

    public function publicview($vista,$datos)
    {
        require_once 'config/public_web_config.php';
        require_once 'config/Robot.php';
        require_once 'core/AyudaVistas.php';
        require_once '_construct/modal/default.php';
        $helper=new AyudaVistas();

        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
        require_once 'public/view/'.$vista.'View.php';

        require_once '_construct/footer/public_footer.php';

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

    function load($vista,$datos,$return=false)
    {
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor;
        }
        $innerHTML='';
        if(is_array($vista)):
            foreach ($vista as $vista) {
                if($return):
                    ob_start();
                    $innerHTML .= file_get_contents('view/'.$vista.'.php');
                else:
                    require 'view/'.$vista.'.php';
                endif;
            }
        else:
            if($return){
                ob_start();
                include('view/'.$vista.'.php');
                $innerHTML = ob_get_clean();
            }else{
                require 'view/'.$vista.'.php';
            }
        if($return){
            return $innerHTML;
        }
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

    public function loadModel($model=[],$adapter = false)
    {
        if(is_array($model)):
            foreach ($model as $model) {
                $newModel = explode("/", $model);
                $numberModel = count($newModel) -1;
                require_once 'model/'.$model.'.php';
                $nameModel =$newModel[$numberModel];
                $this->$nameModel = new $newModel[$numberModel]($adapter);
            }
        else:
            require_once 'model/'.$model.'.php';
            $newModel = explode("/", $model);
            $numberModel = count($newModel) -1;
            require_once 'model/'.$model.'.php';
            $nameModel =$newModel[$numberModel];
            $this->$nameModel =new $newModel[$numberModel]($adapter);
        endif;
    }
    public function libraries($libraries=[],$adapter = false)
    {
        if(is_array($libraries)):
            foreach ($libraries as $libraries) {
                $newLibrary = explode("/", $libraries);
                $numberLibrary = count($newLibrary) -1;
                require_once 'libraries/'.$libraries.'.php';
                $nameLibrary =$newLibrary[$numberLibrary];
                $this->$nameLibrary = new $newLibrary[$numberLibrary]($adapter);
            }
        else:
            require_once 'libraries/'.$libraries.'.php';
            $newLibrary = explode("/", $libraries);
            $numberLibrary = count($newLibrary) -1;
            require_once 'libraries/'.$libraries.'.php';
            $nameLibrary =$newLibrary[$numberLibrary];
            $this->$nameLibrary =new $newLibrary[$numberLibrary]($adapter);
        endif;
    }

}
?>
