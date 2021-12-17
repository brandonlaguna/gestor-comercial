<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
session_start();
require_once 'core/helper.php';
genAuth();
// PayU Integration
//global funtions
require_once 'config/global.php';

//base for controllers
require_once 'core/ControladorBase.php';

//librerias importantes

//functions for frontal controller
require_once 'core/ControladorFrontal.func.php';
//get controllers and actions

if(isset($_GET["controller"])){

    $controllerObj=cargarControlador($_GET["controller"]);
    lanzarAccion($controllerObj);
}else{
    $controllerObj=cargarControlador(CONTROLADOR_DEFECTO);
    lanzarAccion($controllerObj);
}

?>
