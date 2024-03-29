<?php

//manual paginator bradcrumb
function Paginator()
{
    echo "<ul class='breadcrumb'>";
    if (!empty($_GET['controller']) && isset($_GET['controller'])) { // get controller name

        echo "<li><a href='" . $_GET['controller'] . "'>" . $_GET['controller'] . " </a></li>";

        //get action name
        if (!empty($_GET['action']) && isset($_GET['action'])) {

            echo "<li><a href='" . $_GET['controller'] . "?action=" . $_GET['action'] . "'>" . $_GET['action'] . " </a></li>";

        } else {echo "";}
        //get sub funtion ($i)
        if (!empty($_GET['i']) && isset($_GET['i'])) {

            echo "<li><a href='" . $_GET['controller'] . "?action=" . $_GET['action'] . "&i=" . $_GET['i'] . "'>" . $_GET['i'] . " </a></li>";

        } else {echo "";}
    } else {echo "";}
    echo "</ul>";
}

function metainit()
{
    $init = new JBlobal("noload");

    if (!empty($_GET['controller']) && isset($_GET['controller'])) {
        if (!empty($_GET['action']) && isset($_GET['action'])) {

            if (!empty($_GET['i']) && isset($_GET['i'])) {
                echo $init->sitename . " | " . $_GET['controller'] . " | " . $_GET['action'] . " " . $_GET['i'] . " " . $init->MetaDesc;
            } else {
                echo $init->sitename . " | " . $_GET['controller'] . " | " . $_GET['action'] . " " . $init->MetaDesc;
            }
        } else {
            echo $init->sitename . " | " . $_GET['controller'] . " | " . $init->MetaDesc;
        }
    } else {
        echo $init->sitename . " " . $init->MetaDesc;
    }
}

function genAuth()
{
    if (!isset($_COOKIE['Token']) && empty($_COOKIE['Token'])) {
        $charter = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890%!@";
        $vol = 36;
        $Token = "";
        for ($i = 0; $i < $vol; $i++) {
            $Token .= substr($charter, rand(0, strlen($charter)), 1);
        }
        setcookie("Token", $Token);

    } else {}
    if (!isset($_COOKIE['NoTouch']) && empty($_COOKIE['NoTouch'])) {
        //only offline
        //$Ip=file_get_contents("http://ipecho.net/plain");
        $Ip = "127.0.0.1";
        setcookie("NoTouch", $Ip);

    } else {}
}

function genIdRandom()
{
    $charter = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $vol = 28;
    $value = "";
    for ($i = 0; $i < $vol; $i++) {
        $value .= substr($charter, rand(0, strlen($charter)), 1);
    }
    return $value;
}

function topmessage($message)
{
    require_once '_construct/alerts/top_message.php';
}

function cln_str($string)
{

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $string
    );

    $string = str_replace(
        array("\\", "¨", "º", "~",
            "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "<code>", "]",
            "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            " ", "*"),
        ' ',
        $string
    );
    return $string;
}

function cln_number($number)
{

    $number = trim($number);

    $number = str_replace(
        array("\\", "¨", "º", "~",
            "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "<code>", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            " ", "*", "-"),
        '',
        $number
    );
    return $number;
}

function newview($vista, $datos)
{
    foreach ($datos as $id_assoc => $valor) {
        ${$id_assoc} = $valor;
    }
    require_once 'core/AyudaVistas.php';
    $helper = new AyudaVistas();
    require_once 'view/' . $vista . 'View.php';
}

function javascript($location, $properties = [])
{   
    $i =0;
    foreach ($location as $file) {
        $addPropertie = '';
        foreach ($properties as $propertie => $value) {
            if($propertie == $i){
                $addPropertie .= $value;
            }
            $i++;
        }
        echo "<script ".$addPropertie." src='" . $file . ".js'></script>";
    }
}

function html($structure, $param)
{
    echo "<" . $structure . " " . $param . ">";
}
function zero_fill($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}
function using($controller)
{
    require_once "controller/" . $controller . "Controller.php";
    $controlador = ucwords($controller) . 'Controller';
    $controllerObj = new $controlador($controller);
    return $controllerObj;
}

function date_format_calendar($date, $set)
{
    if ($date) {
        $date = $date;
        $array_date = explode($set, $date);
        foreach ($array_date as $date) {}
        return $array_date[2] . "-" . $array_date[0] . "-" . $array_date[1];
    } else {
        return "0000-00-00";
    }
}

function _data_last_month_day() { 
    $month = date('m');
    $year = date('Y');
    $day = date("d", mktime(0,0,0, $month+1, 0, $year));

    return date('m/d/Y', mktime(0,0,0, $month, $day, $year));
}

/** Actual month first day **/
function _data_first_month_day() {
    $month = date('m');
    $year = date('Y');
    return date('m/d/Y', mktime(0,0,0, $month, 1, $year));
}

function check($val)
{
    if ($val) {
        return "checked";
    } else {}
}
function random_color()
{
    $entrada = array("bg-info", "bg-pink", "bg-teal", "bg-indigo", "bg-red", "bg-cyan", "bg-success", "bg-danger", "bg-orange", "bg-purple");
    $claves_aleatorias = array_rand($entrada, 1);
    return $entrada[$claves_aleatorias];
}
function fa($icon, $attr)
{
    echo '<i class="' . $icon . ' ' . $attr . '"></i>&nbsp;';
}

function fetch($method, $url, $data, $auth = [])
{
    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }

            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }

            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }

    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    $username = $auth[0];
    $password = $auth[1];
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        $auth[0],
        $auth[1],
        'Content-Type: application/json',
    ));

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);

    if (!$result) {die(json_encode("Connection Failure"));}
    curl_close($curl);
    return $result;
}

function morrisChart($datos)
{
    foreach ($datos as $id_assoc => $valor) {
        ${$id_assoc} = $valor;
    }
    //require '/_construct\chart\morrisChart.php';
    require '_construct/chart/morrisChart.php';
}

function rickshawChart($datos)
{
    foreach ($datos as $id_assoc => $valor) {
        ${$id_assoc} = $valor;
    }
    require '_construct/chart/rickshawChart.php';
}

function precio($valor)
{
    $valor = preg_replace("/[^0-9.]/", "", $valor);
    if ($valor > 0) {
        return '$' . number_format($valor, 0, ',', '.');
    } else {
        return '$0.00';
    }

}

function import($library = false)
{
        if($library){
            require_once 'lib/'.$library;
        }else{}
}

function buttonAction($route,$type,$method = '')
{
    $href = ($method === 'action')? "href='$route'": 'href="#" onclick="actionToReaction('."'".$route."')".'"';
    switch ($type) {
        case 'view':
           echo "<a href='$route' data-toggle='tooltip' data-placement='top' title='Ver'><i class='fas fa-binoculars text-success'></i></a>&nbsp;";
            break;
        case 'edit':
            echo "<a href='$route' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fas fa-pen-nib text-warning'></i></a>&nbsp;";
            break;
        case 'print':
            echo "<a href='$route' data-toggle='tooltip' data-placement='top' title='Imprimir'><i class='fas fa-print text-primary'></i></a>&nbsp;";
            break;
        case 'check':
            echo "<a href='$route' data-toggle='tooltip' data-placement='top' title='Despachar'><i class='fas fa-check-circle text-success'></i></a>&nbsp;";
                break;
        case 'delete':
            echo "<a href='$route' data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='far fa-trash-alt text-danger'></i></a>&nbsp;";
                break;
        default:
            # code...
            break;
    }
}

function estado($estado)
{
    switch ($estado):
        case 1:
            echo 'Activo';
            break;
        case 0:
            echo 'Inactivo';
            break;
        default:
            # code...
            break;
    endswitch;
}

function validarFormatoFecha($valorFecha, $formatoFecha = 'Y-m-d H:i:s') {
	$objetoFecha = DateTime::createFromFormat($formatoFecha, $valorFecha);
	return $objetoFecha && $objetoFecha->format($formatoFecha) == $valorFecha;
}

function verificarCampo($valorCampo, $tipoCampo = null) {
	if ( $tipoCampo ) {
		if ( !isset($valorCampo) || is_null($valorCampo) || empty($valorCampo) ) {
			if ( $tipoCampo == 'date' ) {
				return '0001-01-01';
			} else if ( $tipoCampo == 'datetime' ) {
				return '0001-01-01 00:00:00';
			} else if ( $tipoCampo == 'text' ) {
				return '';
			} else if ( $tipoCampo == 'number' ) {
				return 0;
			} else {
				return null;
			}
		} else {
			if ( $tipoCampo == 'date' && ( validarFormatoFecha( $valorCampo,'Y-m-d' ) || validarFormatoFecha( $valorCampo,'d/m/Y' ) ) ) {
				return $valorCampo;
			} else if ( $tipoCampo == 'datetime' && validarFormatoFecha( $valorCampo,'Y-m-d H:i:s') ) {
				return $valorCampo;
			} else if ( $tipoCampo == 'text' && ctype_alnum( $valorCampo ) ) {
				return $valorCampo;
			} else if ( $tipoCampo == 'number' && is_numeric( $valorCampo ) ) {
				return $valorCampo;
			} else {
				return null;
			}
		}
	} else {
		$flag = false;
		if (isset($valorCampo) && !empty($valorCampo) && !is_null($valorCampo)) {
			$flag = true;
		}
		return $flag;
	}
}
