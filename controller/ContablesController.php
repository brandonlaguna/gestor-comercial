<?php
class ContablesController extends Controladorbase{
    
    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>3){
            $cuentas = new PUC($this->adapter);
            $clases = $cuentas->getClase();
            $grupo = $cuentas->getGrupo(1);
            $cuenta = $cuentas->getCuenta(11);
            $nivel = $cuentas->getPucById(11);
            $this->frameview("puc/index",array(
                "clases" => $clases,
                "grupo"=>$grupo,
                "cuenta"=>$cuenta,
                "nivel"=>$nivel
            ));
        }else{ 

        }
    }

    public function delete()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"]>4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcuenta = $_GET["data"];
                $puc = new PUC($this->adapter);
                $deletePuc = $puc->delete_puc($_GET["data"]);
                if($deletePuc){
                    $success = "Codigo eliminado";
                    $this->frameview("alert/success/successSmall",array("success"=>$success));
                }else{
                    $error= "No se pudo eliminar este codigo";
                    $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
                }
            }else{
                $error= "Error desconocido";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error= "No tiene permisos para esta accion";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

    public function loadGrupo()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST["id"]) && !empty($_POST["id"])){
                $id = $_POST["id"];
                $cuentas = new PUC($this->adapter);
                $grupo = $cuentas->getGrupo($id);
                $this->frameview("puc/load/loadGrupo",array(
                    "grupo"=>$grupo
                ));
            }else{
                $error = "No tienes permisos";
                return $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error = "No tienes permisos";
            return $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

    public function loadCuenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST["id"]) && !empty($_POST["id"])){
                $cuentas = new PUC($this->adapter);
                $id = $_POST["id"];
                $nivel = $cuentas->getPucById($id);
                $cuenta = $cuentas->getCuenta($id);
                $this->frameview("puc/load/loadCuenta",array(
                    "cuenta"=>$cuenta,
                    "nivel"=>$nivel,
                ));
            }else{
                $error = "No tienes permisos";
                return $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error = "No tienes permisos";
            return $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

    public function loadSubCuenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST["id"]) && !empty($_POST["id"])){
                $cuentas = new PUC($this->adapter);
                $id = $_POST["id"];
                $subcuenta = $cuentas->getSubCuenta($id);
                $cuenta = $cuentas->getCuentaById($id);
                $this->frameview("puc/load/loadSubCuenta",array(
                    "subcuenta"=>$subcuenta,
                    "cuenta"=>$cuenta
                ));
            }else{}
        }else{

        }
    }
    public function loadAuxiliares()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST["id"]) && !empty($_POST["id"])){
                $cuentas = new PUC($this->adapter);
                $id = $_POST["id"];
                $auxiliares = $cuentas->getAuxSubCuenta($id);
                $subcuenta = $cuentas->getPucById($id);
                $this->frameview("puc/load/loadAuxiliares",array(
                    "auxiliares"=>$auxiliares,
                    "subcuenta"=>$subcuenta
                ));
            }else{}
        }else{}
    }

    public function addSubCuenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST) && !empty($_POST)){
                $idcuenta = $_POST["idcuenta"];
                $subcuenta = $_POST["subcuenta"];
                $descripcion = $_POST["descripcion"];
                $movimiento = (!empty($_POST["movimiento"]) && $_POST["movimiento"] == "on")?1:0;
                $terceros = (!empty($_POST["terceros"]) && $_POST["terceros"] == "on")?1:0;
                $centro_costos = (!empty($_POST["centro_costos"]) && $_POST["centro_costos"] == "on")?1:0;
                $impuesto = (!empty($_POST["impuesto"]) && $_POST["impuesto"] == "on")?1:0;
                $retencion = (!empty($_POST["retencion"]) && $_POST["retencion"] == "on")?1:0;
                $c_pagar = (!empty($_POST["c_pagar"]) && $_POST["c_pagar"] == "on")?1:0;
                $c_cobrar = (!empty($_POST["c_cobrar"]) && $_POST["c_cobrar"] == "on")?1:0;
                if($subcuenta != null && $descripcion != null){
                    if(strlen($subcuenta) == 2){
                        $puc = new PUC($this->adapter);
                        $puc->setIdcodigo($idcuenta."".$subcuenta);
                        $puc->setTipo_codigo($descripcion);
                        $puc->setImpuesto($impuesto);
                        $puc->setRetencion($retencion);
                        $puc->setMovimiento($movimiento);
                        $puc->setTerceros($terceros);
                        $puc->setCentro_costos($centro_costos);
                        $puc->setC_pagar($c_pagar);
                        $puc->setC_cobrar($c_cobrar);
                        $addCodigo = $puc->addCodigo();
                        if($addCodigo){
                            echo "Agregado";
                        }else{
                            echo "No se puede agregar o este codigo ya existe.";
                        }

                    }else{
                        echo "El codigo de esta subcuenta debe tener dos digitos";
                    }
                }else{
                    echo "Codigo de subcuenta y nombre son necesarios";
                }
            }
        }else{

        }
    }
    public function getPuc()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            
                $num_puc = cln_str($_POST["data"]);
                $puc = new PUC($this->adapter);
                $getPuc = $puc->getPucById($num_puc);
                foreach ($getPuc as $value) {}

                $clase=0;
                $grupo=0;
                $cuenta=0;
                $auxiliar1=0;
                $auxiliar2=0;
                $auxiliar3=0;

                if($value->idcodigo > 0){
                    $numbers= str_split($value->idcodigo,2);
                    $num_clase = str_split($numbers[0]);
                    $clase = $num_clase[0];
                    $grupo = $numbers[0];
                    $cuenta = $numbers[1];
                    //echo json_encode($clase." - ".$grupo." - ".$cuenta);

                    $this->frameview("puc/puc",array());

            }else{}
        }else{}
    }

    public function edit()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcuenta = $_GET["data"];
                if(is_numeric($idcuenta) && strlen($idcuenta) >=4){
                    
                    $puc = new PUC($this->adapter);
                    $cuenta = $puc->getPucById($idcuenta);

                    $this->frameview("admin/cuentas/edit",array(
                        "cuenta"=>$cuenta
                    ));


                }else{
                    $error = "Esta cuenta no es accesible";
                    $this->frameview("alert/error/forbidden",array("error"=>$error)); 
                }
            }else{
                $error = "Error desconocido";
                $this->frameview("alert/error/forbidden",array("error"=>$error));
            }
        }else{
            $error = "No tienes permisos";
            $this->frameview("alert/error/forbidden",array("error"=>$error));
        }
    }

    public function update_cuenta()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_POST) && !empty($_POST)){
                $puc = new PUC($this->adapter);
                $idcodigo = (isset($_POST["idcodigo"]) && !empty($_POST["idcodigo"]))?$_POST["idcodigo"]:false;
                $tipo_codigo = (isset($_POST["tipo_codigo"]) && !empty($_POST["tipo_codigo"]))?$_POST["tipo_codigo"]:false;
                $movimiento = (isset($_POST["movimiento"]) && !empty($_POST["movimiento"]))?1:0;
                $terceros = (isset($_POST["terceros"]) && !empty($_POST["terceros"]))?1:0;
                $centro_costos = (isset($_POST["centro_costos"]) && !empty($_POST["centro_costos"]))?1:0;
                $impuesto = (!empty($_POST["impuesto"]) && $_POST["impuesto"] == "on")?1:0;
                $retencion = (!empty($_POST["retencion"]) && $_POST["retencion"] == "on")?1:0;
                $c_pagar = (!empty($_POST["c_pagar"]) && $_POST["c_pagar"] == "on")?1:0;
                $c_cobrar = (!empty($_POST["c_cobrar"]) && $_POST["c_cobrar"] == "on")?1:0;
                $estado_puc = (isset($_POST["estado_puc"]) && !empty($_POST["estado_puc"]))?$_POST["estado_puc"]:false;
                if($idcodigo && $tipo_codigo){

                    $puc->setTipo_codigo($tipo_codigo);
                    $puc->setMovimiento($movimiento);
                    $puc->setTerceros($terceros);
                    $puc->setCentro_costos($centro_costos);
                    $puc->setEstado_puc($estado_puc);
                    $puc->setImpuesto($impuesto);
                    $puc->setRetencion($retencion);
                    $puc->setC_pagar($c_pagar);
                    $puc->setC_cobrar($c_cobrar);
                    $updatePuc = $puc->updateCodigo($idcodigo);
                    if($updatePuc){
                        echo json_encode(array(
                            "alert"=>"success",
                            "title"=>"Codigo de cuenta actualizado",
                            "message"=>""
                            ));
                    }else{
                        echo json_encode(array(
                            "alert"=>"error",
                            "title"=>"No se ha podido actualizar",
                            "message"=>""
                            ));
                    }

                }else{
                    echo json_encode(array(
                    "alert"=>"error",
                    "title"=>"No se ha podido actualizar",
                    "message"=>"Verifica los campos obligatorios"
                    ));
                }
                // echo json_encode(array(
                //     "alert"=>"success",
                //     "title"=>"Actualizando",
                //     "message"=>""
                // ));
            }
        }else{
            echo json_encode(array(
                "alert"=>"error",
                "title"=>"No tienes permisos",
                "message"=>""
            ));
        }

    }
}