<?php
define("CONTROLADOR_DEFECTO", "Index");
define("ACCION_DEFECTO", "index");
define("CONTROLLER_ERROR", "404");
define("INITIAL","/");
define("DATABASE","bd_ecounts");
define("PATH_CLIENT","http://ecounts.psi-web.co/".INITIAL."");
define("LOCATION_CLIENT","http://ecounts.psi-web.co/".INITIAL."");
define('FPDF_VERSION','1.82');
define('FPDF_FONTPATH','font/');

define("FACTURACION_ELECTRONICA",array(
    "user"=>"info@psi-web.co",
    "password"=>"Colombia2020**",
    //"url"=>"http://restapi.psi-web.co/users"
    "url"=>"https://app.sifactura.co/api/v1/invoice/process/new/batch/json/HAB",
));
date_default_timezone_set('America/Bogota');
?>