<?php
class CuentasController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
       //clase cuentas

       $datacuentas = new Cuentas($this->adapter);
       $cuentas = $datacuentas->get_cuentas();

       $this->frameview("admin/cuentas/index",array("cuentas"=>$cuentas));
        
    }

    public function detail()
    {
        $datacuentas = new Cuentas($this->adapter);
        $impuestos =  new Impuestos($this->adapter);
        $retenciones = new Retenciones($this->adapter);
        $centro_costos = new CentroCostos($this->adapter);

        $impuestos = $impuestos->getAll();
        $retenciones =$retenciones->getAll();
        $centro_costos = $centro_costos->getAll();
        $idcuenta=(isset($_GET["data"]) &&!empty($_GET["data"]))? $_GET["data"]:"";
        if($idcuenta){
            $cuenta = $datacuentas->get_cuentas_byId($idcuenta);
            $this->frameview("admin/cuentas/detail",array(
                "cuenta" => $cuenta,
                "impuestos"=>$impuestos,
                "retenciones" => $retenciones,
                "centro_costos"=>$centro_costos
            ));
        }else{
            $cuentas = $datacuentas->get_cuentas();
            $this->frameview("admin/cuentas/index",array("cuentas"=>$cuentas));
        }
    }

    public function save_update_cuenta()
    {
        //class
        $cuentas = new Cuentas($this->adapter);

        if(isset($_POST["cu_nombre"]) && !empty($_POST["cu_codigo"]) && !empty($_POST["cu_caracteristicas"]) && !empty($_POST["cu_impuestos"]) && !empty($_POST["cu_retenciones"]) && !empty($_POST["cu_centro_costos"]) && !empty($_POST["cu_terceros"])){
            $cu_nombre = cln_str($_POST["cu_nombre"]);
            $cu_codigo =cln_str($_POST["cu_codigo"]);
            $cu_caracteristicas =cln_str($_POST["cu_caracteristicas"]);
            $cu_impuestos=cln_str($_POST["cu_impuestos"]);
            $cu_retenciones=cln_str($_POST["cu_retenciones"]);
            $cu_centro_costos=cln_str($_POST["cu_centro_costos"]);
            $cu_terceros=cln_str($_POST["cu_terceros"]);

                $cuentas->setCu_nombre($cu_nombre);
                $cuentas->setCu_codigo($cu_codigo);
                $cuentas->setCu_caracteristicas($cu_caracteristicas);
                $cuentas->setCu_impuestos($cu_impuestos);
                $cuentas->setCu_retenciones($cu_retenciones);
                $cuentas->setCu_centro_costos($cu_centro_costos);
                $cuentas->setCu_terceros($cu_terceros);

            if(!empty($_POST['cu_id'])){
                $id =$_POST['cu_id'];
                $update= $cuentas->update_cuenta($id);
                $alert = ($update == 1)?"Actualizado correctamente":"error al actualizar";

            }else{
                $add =$cuentas->add_cuenta();

                $alert = ($add == 1)?"Guardado correctamente":"error al guardar";
            }


        }else{
            $alert ="Hay campos oblogatorios que debes completar";
        }
        return $this->frameview("alert/basic",array("alert"=>$alert));
    }

}