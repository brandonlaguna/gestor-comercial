<?php
//FUNCIONES PARA EL CONTROLADOR FRONTAL

function cargarControlador($controller){
    $unstring ="/";
    $pos = strpos($controller, $unstring);
    if ($pos == true) {
        $controller=  str_replace($unstring, "", $controller);
        header("location: /".INITIAL."/$controller");
    }else{}
    $controlador=ucwords($controller).'Controller';
    $strFileController='controller/'.$controlador.'.php';
    
    if(!is_file($strFileController)){
        //$strFileController=''.ucwords(CONTROLLER_ERROR).''; 
        $strFileController='controller/'.CONTROLLER_ERROR.'Controller.php'; 
        require_once $strFileController;
        $controllerObj=new page404();
    }else{
        //mejorar aqui para una url limpia
        require_once $strFileController;
        $controllerObj=new $controlador($controller);
    }
    
    
    return $controllerObj;
    
}

function cargarAccion($controllerObj,$action){
    $accion=$action;
    $controllerObj->$accion();
    
}

function lanzarAccion($controllerObj){
    
    if(isset($_GET["action"]) && method_exists($controllerObj, $_GET["action"])){
        cargarAccion($controllerObj, $_GET["action"]);
    }else{
        cargarAccion($controllerObj, ACCION_DEFECTO);
    }
}

?>
