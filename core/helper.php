<?php

        //manual paginator bradcrumb
function Paginator()
{
echo "<ul class='breadcrumb'>";
    if(!empty($_GET['controller']) && isset($_GET['controller']))
    {       // get controller name 
        
            echo"<li><a href='".$_GET['controller']."'>".$_GET['controller']." </a></li>";
        
            //get action name
        if(!empty($_GET['action']) && isset($_GET['action'])){
        
            echo"<li><a href='".$_GET['controller']."?action=".$_GET['action']."'>".$_GET['action']." </a></li>";
        
        }else{echo "";}
            //get sub funtion ($i)
        if(!empty($_GET['i']) && isset($_GET['i'])){
        
            echo"<li><a href='".$_GET['controller']."?action=".$_GET['action']."&i=".$_GET['i']."'>".$_GET['i']." </a></li>";
        
        } else{ echo "";}
    }
        else{ echo ""; }
echo "</ul>";
}

function metainit()
{
    $init = new JBlobal("noload");

    if(!empty($_GET['controller']) && isset($_GET['controller'])){
        if(!empty($_GET['action']) && isset($_GET['action'])){ 

            if(!empty($_GET['i']) && isset($_GET['i'])){
                echo $init->sitename." | ".$_GET['controller']." | ".$_GET['action']." ".$_GET['i']." ".$init->MetaDesc;
            }
            else{
                echo $init->sitename." | ".$_GET['controller']." | ".$_GET['action']." ".$init->MetaDesc;
            }     
        }else{
            echo $init->sitename." | ".$_GET['controller']." | ".$init->MetaDesc;
        }
    }else{
            echo $init->sitename." ".$init->MetaDesc;
    }
}

function genAuth()
{
    if(!isset($_COOKIE['Token']) && empty($_COOKIE['Token'])){
        $charter = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890%!@"; 
        $vol=36; 
        $Token = "";
            for($i=0;$i<$vol;$i++)
                {
                $Token .= substr($charter,rand(0,strlen($charter)),1);
                }
                    setcookie("Token", $Token);
                    
                    
    }else{}
    if(!isset($_COOKIE['NoTouch']) && empty($_COOKIE['NoTouch'])){
        //only offline
        //$Ip=file_get_contents("http://ipecho.net/plain");
        $Ip ="127.0.0.1";
        setcookie("NoTouch", $Ip);
                
    }else{}
}

function genIdRandom()
{
    $charter = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; 
    $vol=28; 
    $value = "";
        for($i=0;$i<$vol;$i++)
            {
            $value .= substr($charter,rand(0,strlen($charter)),1);
            }
    return $value;
}

function topmessage($message)
{
    require_once '_construct/alerts/top_message.php';
}

function cln_str($string){
 
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
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    $string = str_replace(
        array("\\", "¨", "º","~",
             "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             " ","*"),
        ' ',
        $string
    );
return $string;
} 

function newview($vista,$datos)
    {
        foreach ($datos as $id_assoc => $valor) {
            ${$id_assoc}=$valor; 
        }
        require_once 'core/AyudaVistas.php';
            $helper=new AyudaVistas();
            require_once 'view/'.$vista.'View.php';
    }

     function javascript($location)
    {
        foreach($location as $file){
            echo "<script src='".$file.".js'></script>";
        }
    }

function html($structure,$param)
{
    echo "<".$structure." ".$param.">";
}
function zero_fill($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}
function using($controller)
{
    require_once ("controller/".$controller."Controller.php");
    $controlador=ucwords($controller).'Controller';
    $controllerObj=new $controlador($controller);
    return $controllerObj;
}

function date_format_calendar($date,$set)
{
    if($date){
    $date = $date;
    $array_date = explode($set, $date);
    foreach ($array_date as $date) {}
    return $array_date[2]."-".$array_date[0]."-".$array_date[1];
    }else{
        return "0000-00-00";
    }
}

function check($val)
{
  if($val){
      return "checked";
  }else{}
}
function random_color()
{
    $entrada = array("bg-info", "bg-pink", "bg-teal", "bg-indigo", "bg-red","bg-cyan","bg-success","bg-danger","bg-orange","bg-purple");
    $claves_aleatorias = array_rand($entrada, 1);
    return $entrada[$claves_aleatorias];
}

?>
