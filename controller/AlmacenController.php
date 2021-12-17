<?php
class AlmacenController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        
    }

    public function categorias()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $categoria = new Categoria($this->adapter);
            $categorias = $categoria->getAll();

            $this->frameview("almacen/categorias/index",array(
                "categorias"=>$categorias
            ));
        }
    }

    public function nueva_categoria()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $categoria = new Categoria($this->adapter);
            $puc = new PUC($this->adapter);
            $allpuc = $puc->getAllPucBy('movimiento','1');
            $idcategoria ="";

            $this->frameview("almacen/categorias/new",array(
                "idcategoria"=>$idcategoria,
                "allpuc"=>$allpuc
            ));
        }
    }
    public function edit_categoria()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcategoria = cln_str($_GET["data"]);
                $categorias = new Categoria($this->adapter);
                $puc = new PUC($this->adapter);
                $allpuc = $puc->getAllPucBy('movimiento','1');
                $categoria = $categorias->getCategoriaById($idcategoria);
                $this->frameview("almacen/categorias/edit",array(
                    "categoria"=>$categoria,
                    "allpuc"=>$allpuc
                ));

            }else{
                echo "Forbidden Gateway";
            }
        }
    }
    public function view_categoria()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcategoria = cln_str($_GET["data"]);
                $categorias = new Categoria($this->adapter);
                $categoria = $categorias->getCategoriaById($idcategoria);
                $this->frameview("almacen/categorias/load",array(
                    "categoria"=>$categoria
                ));

            }else{
                echo "Forbidden Gateway";
            }
        }
    }

    public function save_categoria()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"])){
            if($_SESSION["permission"] >= 1){
                //limpiando datos
                $nombre = cln_str($_POST["nombre_categoria"]);
                $cod_venta = cln_number($_POST["cod_venta"]);
                $cod_costos = cln_number($_POST["cod_costos"]);
                $cod_devoluciones = cln_number($_POST["cod_devoluciones"]);
                $cod_inventario = cln_number($_POST["cod_inventario"]);
                $imp_compra = $_POST["imp_compra"];
                $imp_venta = $_POST["imp_venta"];
                if(!empty($nombre) && !empty($cod_venta) && !empty($cod_costos) && !empty($cod_devoluciones) && !empty($cod_inventario)){
                    $categoria = new Categoria($this->adapter);
                    $categoria->setNombre($nombre);
                    $categoria->setCod_venta($cod_venta);
                    $categoria->setCod_costos($cod_costos);
                    $categoria->setCod_devoluciones($cod_devoluciones);
                    $categoria->setCod_inventario($cod_inventario);
                    $categoria->setImp_compra($imp_compra);
                    $categoria->setImp_venta($imp_venta);
                    $categoria->setEstado("A");
                        if(isset($_POST["idcategoria"]) && !empty($_POST["idcategoria"])){
                            $status = $categoria->updateCategoria($_POST["idcategoria"]);
                        if($status){
                            echo "Categoria Actualizada";
                        }else{
                        echo "Categoria Rechazada";
                        }
                    }else{
                    $status = $categoria->saveCategoria();
                    if($status){
                        echo "Categoria Almacenada";
                    }else{
                        echo "Categoria Rechazada";
                    }
                    }
                }else{
                    echo "Hay campos obligatorios";
                }
                
            }else{
                echo "No tienes permisos";
            }

        }else{
            echo "Forbidden Gateway";
        }
    }

    public function terceros()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"])){
            if($_SESSION["permission"] >= 4){
                $persona = new Persona($this->adapter);
                $personas = $persona->getPersonaAll();

                $this->frameview("almacen/persona/index",array(
                    "personas"=>$personas
                ));
            }
        }
    }
    public function detail_tercero()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >1){
            $idpersona = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
            if($idpersona){ 
            $personas = new Persona($this->adapter);
            $persona =$personas->getPersonaById($idpersona);
            $this->frameview("almacen/persona/detail",array(
                "persona"=>$persona,
            ));
        }else{
            echo "Forbidden gateway";
        }
        echo"Forbidden";
        }
    }

    public function delete_tercero()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idpersona = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
            if($idpersona > 1){
                $personas = new Persona($this->adapter);
                $delete_tercero = $personas->deletePersona($idpersona);
                if($delete_tercero){
                    echo "Tercero eliminado correctamente";
                }else{
                    echo "Hubo un problema al eliinar este tercero";
                }
            }else{
                echo "Hubo un problema al eliminar este tercero";
            }

        }else{
            echo "No tienes permisos necesarios para esta accion";
        }
    }

    public function new_tercero()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            //modelos
            $global = new sGlobal($this->adapter);
            $tipoDocumento = new TipoDocumento($this->adapter);
            $organizacion = new TipoOrganizacion($this->adapter);
            $regimen = new TipoRegimen($this->adapter);
            $respfiscales = new RespFiscales($this->adapter);
            //funciones
            $documentos = $tipoDocumento->getTipoDocumentoPersona();
            $departamentos = $global->getDepartamentos();
            $municipios = $global->getMunicipios();
            $tipo_organizacion = $organizacion->getAll();
            $tipo_regimen = $regimen->getAll();
            $fiscales = $respfiscales->getRespFiscalAll();

            if(isset($_GET["data"]) && !empty($_GET["data"]) && $_GET["data"] =="modal"){
                $view="modalNew";
            }else{
                $view="new";
            }
            $this->frameview("almacen/persona/$view",array(
                "departamentos"=>$departamentos,
                "municipios"=>$municipios,
                "documentos"=>$documentos,
                "tipo_organizacion"=>$tipo_organizacion,
                "tipo_regimen"=>$tipo_regimen,
                "fiscales"=>$fiscales
            ));

        }else{
            
        }
    }

    public function update_tercero()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            $idpersona = (isset($_GET["data"]) && !empty($_GET["data"]))?$_GET["data"]:false;
            if($idpersona){ 
                $global = new sGlobal($this->adapter);
                $tipoDocumento = new TipoDocumento($this->adapter);
                $personas = new Persona($this->adapter);
                $organizacion = new TipoOrganizacion($this->adapter);
                $regimen = new TipoRegimen($this->adapter);

                $documentos = $tipoDocumento->getTipoDocumentoPersona();
                $departamentos = $global->getDepartamentos();
                $municipios = $global->getMunicipios();
                $persona =$personas->getPersonaById($idpersona);
                $tipo_organizacion = $organizacion->getAll();
                $tipo_regimen = $regimen->getAll();

                $this->frameview("almacen/persona/update",array(
                    "persona"=>$persona,
                    "departamentos"=>$departamentos,
                    "municipios"=>$municipios,
                    "documentos"=>$documentos,
                    "tipo_organizacion"=>$tipo_organizacion,
                    "tipo_regimen"=>$tipo_regimen,
                ));

            }else{
                echo "Forbidden Gateway";
            }
        }else{
            echo "Forbidden Gateway";
        }
        
    }

    public function save_tercero()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            if(!empty($_POST["nombre_tercero"]) && !empty($_POST["numero_documento"])){
                $nombre = (!empty($_POST["nombre_tercero"]))?cln_str($_POST["nombre_tercero"]):"";
                $tipo_documento = (!empty($_POST["tipo_documento"]))?cln_str($_POST["tipo_documento"]):"";
                $num_documento = (!empty($_POST["numero_documento"]))?cln_str($_POST["numero_documento"]):"";
                $direccion_departamento = (!empty($_POST["direccion_departamento"]))?cln_str($_POST["direccion_departamento"]):"";
                $direccion_provincia = (!empty($_POST["direccion_provincia"]))?cln_str($_POST["direccion_provincia"]):"";
                $direccion_distrito = (!empty($_POST["direccion_distrito"]))?cln_str($_POST["direccion_distrito"]):"";
                $direccion_calle = (!empty($_POST["direccion_calle"]))?cln_str($_POST["direccion_calle"]):"";
                $email = (!empty($_POST["email"]))?$_POST["email"]:"";
                $telefono = (!empty($_POST["telefono"]))?cln_str($_POST["telefono"]):"";
                $num_cuenta = (!empty($_POST["num_cuenta"]))?cln_str($_POST["num_cuenta"]):"";
                $tipo_persona = (!empty($_POST["tipo_persona"]))?cln_str($_POST["tipo_persona"]):""; 
                $tipo_regimen = (!empty($_POST["tipo_regimen"]))?cln_str($_POST["tipo_regimen"]):"";
                $tipo_organizacion = (!empty($_POST["tipo_organizacion"]))?cln_str($_POST["tipo_organizacion"]):"";

                $persona = new Persona($this->adapter);
                $persona->setNombre($nombre);
                $persona->setTipo_documento($tipo_documento);
                $persona->setNum_documento($num_documento);
                $persona->setDireccion_departamento($direccion_departamento);
                $persona->setDireccion_provincia($direccion_provincia);
                $persona->setDireccion_distrito($direccion_distrito);
                $persona->setDireccion_calle($direccion_calle);
                $persona->setEmail($email);
                $persona->setTelefono($telefono);
                $persona->setNumero_cuenta($num_cuenta);
                $persona->setTipo_persona($tipo_persona);
                $persona->setTipo_regimen($tipo_regimen);
                $persona->setTipo_organizacion($tipo_organizacion);
                $persona->setEstado("A");
                if(isset($_POST["idpersona"]) && !empty($_POST["idpersona"]) && $_POST["idpersona"] >1){
                    $updatePersona = $persona->updatePersona($_POST["idpersona"]);
                    if($updatePersona){
                        echo $tipo_persona." ".$nombre." Actualizado correctamente";
                    }else{
                        echo "Error al actualizar el ".$tipo_persona." ".$nombre;
                    }
                }else{
                    $addPersona = $persona->addPersona();
                    if($addPersona){
                        if($addPersona == 2){
                            echo $tipo_persona." ya existe.";
                        }else{
                        echo $tipo_persona." Aagregado correctamente";
                        }
                    }else{
                        echo "Error al agregar el ".$tipo_persona;
                    }
                }
            }else{
                echo "Hay campos incompletos que debe llenar";
            }

        }else{
            echo "Forbidden Gateway";
        }

    }

}
