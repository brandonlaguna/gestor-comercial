<?php
class AdminController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
       //clase impuesto
       $impuestos = new Impuestos($this->adapter);
       $impuesto = $impuestos->getAll();
       $this->frameview("admin/impuestos/list",array("impuesto"=>$impuesto));
    }

    public function impuestos()
    {
       //clase impuesto
       $impuestos = new Impuestos($this->adapter);
       $impuesto = $impuestos->getImpuestosAll();
       $this->frameview("admin/impuestos/list",array("impuesto"=>$impuesto));
    }

    public function retenciones()
    {
        $dataretenciones = new Retenciones($this->adapter);
        $retenciones = $dataretenciones->getRetencionesAll();
        $this->frameview("admin/retenciones/list",array(
            "retenciones"=>$retenciones
        ));
    }

    public function centro_costos()
    {
        //classes
        $datacentro = new CentroCostos($this->adapter);
        $centro_costos = $datacentro->getCentroCostos();

        $this->frameview("admin/centroCostos/list",array("centro_costos"=>$centro_costos));
    }

    public function nuevo_impuesto()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){

            $this->frameview("admin/impuestos/new",array());
        }else{
            $error ="No tienes permisos.";
            $this->frameview("alert/error/forbidden",array("error"=>$error));
        }
    }

    public function nueva_retencion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $impuesto = new Impuestos($this->adapter);
            $impuestos = $impuesto->getImpuestosAll();

            $this->frameview("admin/retenciones/new",array( 
                "impuestos"=>$impuestos
            ));
        }else{
            $error ="No tienes permisos.";
            $this->frameview("alert/error/forbidden",array("error"=>$error));
        }
    }

    public function update_impuesto()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idimpuesto =$_GET["data"];
                $impuestos = new Impuestos($this->adapter);
                $impuesto = $impuestos->getImpuesto($idimpuesto);
                if($impuesto != null){

                    $this->frameview("admin/impuestos/update",array(
                        "impuesto"=>$impuesto,
                    ));
                }else{
                    $error ="Error desconocido.";
                    $this->frameview("alert/error/forbidden",array("error"=>$error));
                }
            }else{
                $error ="Error desconocido.";
                $this->frameview("alert/error/forbidden",array("error"=>$error));
            }
        }else{
            $error ="No tienes permisos.";
            $this->frameview("alert/error/forbidden",array("error"=>$error));
        }
    }

    public function delete_impuesto()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idimpuesto = $_GET["data"];
                $impuestos = new Impuestos($this->adapter);
                $deleteImpuesto = $impuestos->deleteImpuestoById($idimpuesto);

                if($deleteImpuesto){
                    $success = "Impuesto eliminado";
                    $this->frameview("alert/success/successSmall",array("success"=>$success));
                }else{
                    $error= "Este impuesto no se puede eliminar.";
                    $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
                }
            }else{
                $error= "Este impuesto no se puede eliminar.";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }

        }else{
            $error= "No tiene permisos para esta acción.";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

    public function delete_retencion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idretencion = $_GET["data"];
                $retenciones = new Retenciones($this->adapter);
                $deleteRetencion = $retenciones->deleteRetencionById($idretencion);

                if($deleteRetencion){
                    $success = "Retencion eliminada";
                    $this->frameview("alert/success/successSmall",array("success"=>$success));
                }else{
                    $error= "Esta retencion no se puede eliminar.";
                    $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
                }

            }else{
                $error= "Estaretencion no se puede eliminar.";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error= "No tiene permisos para esta acción.";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }


    public function save_impuesto()
    {
        $alert= "";
        //class impuesto 
        $impuesto = new Impuestos($this->adapter);
        if(isset($_POST["im_nombre"]) && !empty($_POST["im_nombre"]) && !empty($_POST["im_porcentaje"])){
            //Limpiar datos
            $im_nombre =        cln_str($_POST["im_nombre"]);
            $im_porcentaje =    cln_str($_POST["im_porcentaje"]);
            $im_base =          cln_str($_POST["im_base"]);
            $im_cta_contable = cln_str($_POST["cta_contable"]);
            ## $im_proceso = cln_str($_POST["im_proceso"]);
            $idimpuesto = (isset($_POST["idimpuesto"]) && !empty($_POST["idimpuesto"]))?$_POST["idimpuesto"]:false;
            //NIVEL DE PERMISO
            if($_SESSION["permission"]>=4){
                //get class impuesto
                $impuesto->setIm_nombre($im_nombre);
                $impuesto->setIm_porcentaje($im_porcentaje);
                $impuesto->setIm_base($im_base);
                $impuesto->setIm_cta_contable($im_cta_contable);
                $impuesto->setIm_proceso("");
                $impuesto->setIm_estado("A");
                if($idimpuesto){
                    $update_impuesto = $impuesto->update_impuesto($idimpuesto);
                    if($update_impuesto){
                        echo "Impuesto actualizado";
                    }else{ echo "Error en la actualizacion";}
                }else{
                    $add_impuesto= $impuesto->add_impuesto();
                    if($add_impuesto){
                        echo "Impuesto agregado";
                    }else{
                        echo "Error en la actualizacion";
                    }
                }
            }else{
                $alert ="No tienes permisos";
            }
        }else{
            $alert ="El nombre del importe y el porcentaje son obligatorios";
        }
        return $this->frameview("alert/basic",array("alert"=>$alert));
    }

    public function add_retencion()
    {

        $alert ="";
        if(isset($_POST["re_nombre"]) && !empty($_POST["re_nombre"]) && !empty($_POST["re_porcentaje"])){
            //class retencion
            $retencion = new Retenciones($this->adapter);
            //limpiar datos
            $re_nombre =        cln_str($_POST["re_nombre"]);
            $re_porcentaje =    cln_str($_POST["re_porcentaje"]);
            $re_base =          cln_str($_POST["re_base"]);

            if($_SESSION["permission"]>=4){
                $retencion->setRe_nombre($re_nombre);
                $retencion->setRe_porcentaje($re_porcentaje);
                $retencion->setRe_base($re_base);
                $add_retencion=$retencion->add_retencion();
                $alert=($add_retencion == true)?"Retencion agregada":"Hubo un error al agregar retencion";
            }else{
                $alert="No tienes permisos";
            }
        }else{
            $alert ="El nombre de la retencion y el porcentaje son obligatorios";
        }
        return $this->frameview("alert/basic",array("alert"=>$alert));
    }

    public function save_retencion()
    {
       if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
           if(isset($_POST) && !empty($_POST)){
               //modelos
            $retenciones = new Retenciones($this->adapter);
                //seteando variables
            $re_nombre = cln_str($_POST["re_nombre"]);
            $re_porcentaje = cln_str($_POST["re_porcentaje"]);
            $re_base = cln_str($_POST["re_base"]);
            $re_cta_contable = cln_str($_POST["re_cta_contable"]);
            $re_im_id = cln_str($_POST["re_im_id"]);
            if($re_nombre && $re_porcentaje){
                    //seteando funciones
                $retenciones->setRe_nombre($re_nombre);
                $retenciones->setRe_porcentaje($re_porcentaje);
                $retenciones->setRe_base($re_base);
                $retenciones->setRe_cta_contable($re_cta_contable);
                $retenciones->setRe_im_id($re_im_id);
                $retenciones->setRe_estado("A");
                //si id retencion existe se lanza funcion de atualizar
                if(isset($_POST["idretencion"]) && !empty($_POST["idretencion"])){
                    $updateRetencion = $retenciones->update_retencion($_POST["idretencion"]);

                    if($updateRetencion){
                        echo "Retencion actualizada";
                    }else{
                        echo "Error al actualizar retencion";
                    }
                }else{
                    //sino existe id se agrega
                    $addRetencion = $retenciones->add_retencion();
                    if($addRetencion){
                        echo "Retencion agregada";
                    }else{
                        echo "Error al agregar retencion";
                    }
                }
            }else{
                echo "Nombre y Porcentaje son obligatorios";
            }
           }else{
                echo "No hay datos enviados";
           }
       }else{
            echo "Forbidden gateway";
       }
    }

    public function update_retencion()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idretencion = $_GET["data"];
                $retenciones = new Retenciones($this->adapter);
                $impuesto = new Impuestos($this->adapter);
                $retencion = $retenciones->getRetencionesById($idretencion);
                $impuestos = $impuesto->getImpuestosAll();

                $this->frameview("admin/retenciones/update",array(
                    "retencion"=>$retencion,
                    "impuestos"=>$impuestos,
                ));


            }else{
                $error= "Error desconocido.";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error= "No tiene permisos para esta acción.";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

    public function new_centro_costos()
    {

        $datamunicipios = new Municipios($this->adapter);
        $municipios = $datamunicipios->getAllMunicipios();
        $departamentos = $datamunicipios->getAllDepartamentos();
            
            $this->frameview("admin/centroCostos/addCentroCostos",array(
                "departamentos"=>$departamentos,
                "municipios"=>$municipios,
            ));
    }

    public function actualizar_centro_costos()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcentro = $_GET["data"];
                $centro_costos = new CentroCostos($this->adapter);
                $datamunicipios = new Municipios($this->adapter);
                $centro_costo = $centro_costos->getCentroCostosById($idcentro);
                $municipios = $datamunicipios->getAllMunicipios();
                $departamentos = $datamunicipios->getAllDepartamentos();

                $this->frameview("admin/centroCostos/update",array(
                    "centro"=>$centro_costo,
                    "departamentos"=>$departamentos,
                    "municipios"=>$municipios,
                ));
            }else{
                $error ="Error desconocido";
                $this->frameview("alert/error/forbidden",array("error"=>$error));
            }
        }else{
            $error ="No tienes permisos.";
            $this->frameview("alert/error/forbidden",array("error"=>$error));
        }
    }

    public function asave_centro_costos()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($_POST["cc_nombre"]) && !empty($_POST["cc_departamento"]) && !empty($_POST["cc_ciudad"])){
            $alert ="";
            $centro_costos = new CentroCostos($this->adapter);
            $cc_nombre =        cln_str($_POST["cc_nombre"]);
            $cc_departamento =  cln_str($_POST["cc_departamento"]);
            $cc_ciudad =        cln_str($_POST["cc_ciudad"]);

                $centro_costos->setCc_nombre($cc_nombre);
                $centro_costos->setCc_departamento($cc_departamento);
                $centro_costos->setCc_ciudad($cc_ciudad);
                 
                if(isset($_POST["idcentro"]) && !empty($_POST["idcentro"])){
                    $centro = $centro_costos->update_centro_costos($_POST["idcentro"]);
                    if($centro){
                        echo "Centro de costos actualizado";
                    }else{
                        echo "Error al actualizar centro de costos";
                    }
                }else{
                    $centro = $centro_costos->add_centro_costos();
                    if($centro){
                        echo "Centro de costos agregado";
                    }else{
                        echo "Error al crear centro de costos";
                    }
                }

        }else{
            echo "El nombre del centro de costos, la ciudad y departamento son obligatorios";
        }

    }else{
        echo "No tienes permisos";
    }   
        
    }

    public function delete_centro()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcentro = $_GET["data"];
                $centro_costos = new CentroCostos($this->adapter);
                $deleteCentro = $centro_costos->delete_centro_cotos($idcentro);
                if($deleteCentro){
                    $success = "Centro eliminado";
                    $this->frameview("alert/success/successSmall",array("success"=>$success));
                }else{
                    $error= "Error desconocido.";
                    $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
                }
            }else{
                $error= "Error desconocido.";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }
        else{
            $error= "No tienes permisos.";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }

    public function tipo_documento()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $tipoDocumento = new TipoDocumento($this->adapter);
            $documentos = $tipoDocumento->getAll();

            $this->frameview("admin/tipoDocumento/index",array(
                "documentos"=>$documentos,
            ));

        }else{
            echo "Forbidden Gateway";
        }
    }
    public function nuevo_documento()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $tipoDocumento = new TipoDocumento($this->adapter);
                //de lo contrario recupera las vistas de los documentos
                $operacion = ["Persona","Comprobante"];
                $procesos = ["Venta","Ingreso"];

                $this->frameview("admin/tipoDocumento/new/new",array(
                    "operacion"=>$operacion,
                    "proceso"=>$procesos,
                ));

        }else{
            echo "Forbidden Gateway";
        }
    }

    public function save_documento()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $tipoDocumento = new TipoDocumento($this->adapter);
            //si se obtiene algun dato mediante de post
            if(isset($_POST["nom_documento"]) && !empty($_POST["prefijo"]) && !empty($_POST["operacion"])){
                $nombre = cln_str($_POST["nom_documento"]);
                $prefijo = cln_str($_POST["prefijo"]);
                $operacion = cln_str($_POST["operacion"]);
                $proceso = cln_str($_POST["proceso"]);
                //seteando los datos
                $tipoDocumento->setNombre($nombre);
                $tipoDocumento->setPrefijo($prefijo);
                $tipoDocumento->setOperacion($operacion);
                $tipoDocumento->setProceso($proceso);
                //si se envia algun dato llamado iddocumento iremos a actualizarlo
                if(isset($_POST["iddocumento"])){
                    $update = $tipoDocumento->update_documento($_POST["iddocumento"]);
                    if($update){
                        echo "Documento actualizado";
                    }else{
                        echo json_encode(array(
                            "alert"=>"error",
                            "title"=>"No se puede actualizar este documento",
                            "message"=>"error desconocido"
                            ));
                    }
                }else{
                    //sino se almacena como nuevo
                    $save= $tipoDocumento->new_documento();
                    if($save){
                        echo "Documento actualizado";
                    }else{
                        echo json_encode(array(
                            "alert"=>"error",
                            "title"=>"no se puede crear este documento",
                            "message"=>"error desconocido"
                            ));
                    }
                }
                
            }else{
                echo json_encode(array(
                    "alert"=>"warning",
                    "title"=>"Error al guardar el documento",
                    "message"=>"verifica los datos ingresados"
                ));
            }

        }else{
            echo "Forbidden Gateway";
        }
    }

    public function delete_documento()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){

            }else{

            }
        }else{
            echo "No tienes permisos.";
        }
    }

    public function actualizar_documento()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $tipoDocumento = new TipoDocumento($this->adapter);
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $operacion = ["Persona","Comprobante"];
                $procesos = ["Venta","Ingreso"];
                $iddocumento = $_GET["data"];
                $documento = $tipoDocumento->getDocumentById($iddocumento);

                $this->frameview("admin/tipoDocumento/edit/edit",array(
                    "documento"=>$documento,
                    "operacion"=>$operacion,
                    "proceso"=>$procesos,
                    "iddocumento"=>$iddocumento
                ));

            }else{
                echo "Forbidden Gateway";
            }
        }
        else{
            echo "Forbidden Gateway";
        }
    }

    public function conf_comprobante()  
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $comprobante = new Comprobante($this->adapter);
            $comprobantes = $comprobante->getComprobanteAll();

            $this->frameview("admin/comprobantes/index",array(
                "comprobantes"=>$comprobantes
            ));


        }else{
            echo "Forbidden Gateway";
        }
    }
    public function actualizar_comprobante()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcomprobante = $_GET["data"];
                //modelos
                $comprobantes = new Comprobante($this->adapter);
                $impuesto = new Impuestos($this->adapter);
                $retencion = new Retenciones($this->adapter);
                //funciones
                $comprobante = $comprobantes->getComprobanteById($idcomprobante);
                $actual_impuestos = $impuesto->getImpuestosByComprobanteId($idcomprobante);
                $actual_retenciones = $retencion->getRetencionesByComprobanteId($idcomprobante);
                $conf_print = $comprobantes->getConfPrint();
                $impuestos = $impuesto->getImpuestosAll();
                $retenciones = $retencion->getRetencionesAll();

                $listImpueto=[];
                $listRetencion=[];
                foreach ($impuestos as $imp) {
                    $list=[];
                    
                    foreach ($actual_impuestos as $actual) {
                        if($actual->im_id == $imp->im_id){
                            $list[] = "selected";
                            $list[] = $imp->im_id;
                            $list[] = $imp->im_nombre;
                        }else{}  
                    }
                    if($actual->im_id != $imp->im_id){
                            $list[] = "";
                            $list[] = $imp->im_id;
                            $list[] = $imp->im_nombre;
                    }
                    
                    $listImpueto[] = $list; 
                }

                foreach ($retenciones as $res) {
                    $list2=[];
                    foreach ($actual_retenciones as $actual) {
                        if($actual->re_id == $res->re_id){
                            $list2[]= "selected";
                            $list2[]=$res->re_id;
                            $list2[]=$res->re_nombre;
                        }else{}
                    }
                    if(isset($actual->re_id)){
                        if($actual->re_id != $res->re_id){
                            $list2[]= "";
                            $list2[]=$res->re_id;
                            $list2[]=$res->re_nombre;
                    }
                    }else{
                        $list2[]= "";
                            $list2[]=$res->re_id;
                            $list2[]=$res->re_nombre;
                    }
                    $listRetencion[] = $list2;
                }
                

                $this->frameview("admin/comprobantes/update/index",array(
                    "comprobante" => $comprobante,
                    "impuestos" => $listImpueto,
                    "retenciones" => $listRetencion,
                    "conf_print" =>$conf_print,
                ));
                

            }else{
                echo "Forbidden gateway";
            }
        }else{
            echo "Forbidden gateway";
        }
    }

    public function nuevo_comprobante()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $tipoDocumento = new TipoDocumento($this->adapter);
            $documentos = $tipoDocumento->getDocumentoComprobante();

            $impuesto = new Impuestos($this->adapter);
            $retencion = new Retenciones($this->adapter);
            $comprobante = new Comprobante($this->adapter);

            $impuestos= $impuesto->getAll();
            $retenciones = $retencion->getAll();
            $conf_print = $comprobante->getConfPrint();

            $this->frameview("admin/comprobantes/new/new",array(
                "documentos"=>$documentos,
                "impuestos"=>$impuestos,
                "retenciones"=>$retenciones,
                "conf_print" =>$conf_print,
            ));
        }
        else{
            echo "Forbidden Gateway";
        }
    }

    public function save_comprobante()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            if(isset($_POST)){
                if(!empty($_POST["impuestos"])){
                    //Modelos
                    $comprobante = new Comprobante($this->adapter);
                    $tipo_documento = new TipoDocumento($this->adapter);
                    $impuesto = new Impuestos($this->adapter);
                    $retencion = new Retenciones($this->adapter);
                    //variables seteadas
                    $documento = cln_str($_POST["documento"]);
                    $serie = cln_str($_POST["serie"]);
                    $consecutivo =cln_str($_POST["consecutivo"]);
                    $contabilidad = cln_str($_POST["contabilidad"]);
                    $impuestos = $_POST["impuestos"];
                    $retenciones = (isset($_POST["retenciones"]) && !empty($_POST["retenciones"]))?$_POST["retenciones"]:false;
                    $conf_print = $_POST["conf_print"];
                    $resolucion = $_POST["resolucion"];
                    $idcomprobante = (!empty($_POST["idcomprobante"]) && $_POST["idcomprobante"] > 0)?$_POST["idcomprobante"]:false;
                    $comprobante->setIdtipo_documento($documento);
                    $comprobante->setUltima_serie($serie);
                    $comprobante->setUltimo_numero($consecutivo);
                    $comprobante->setContabilidad($contabilidad);
                    $comprobante->setDdc_impuesto_comprobante(1);
                    $comprobante->setDdc_retencion_comprobante(1);
                    $comprobante->setDds_pri_id($conf_print);
                    $comprobante->setActivo(1);
                    //configuracio para actualizacion
                    if($idcomprobante){
                        $addComprobante = $comprobante->update_comprobante($idcomprobante);
                        $comprobanteid = $idcomprobante;
                    }else{
                        $addComprobante = $comprobante->add_comprobante();
                        $comprobanteid = $addComprobante;
                    }
                    //agregar o actualizar pie de factura
                    $comprobante->setPf_iddetalle_documento_sucursal($comprobanteid);
                    $comprobante->setPf_text($resolucion);
                    if($idcomprobante){
                        $updatePiePagina = $comprobante->updatePieFactura($idcomprobante);
                    }
                    else{
                        $addPiePagina = $comprobante->addPieFactura();
                    }
                    //por si es una actualizacion de datos es mas facil eliminar el registro de impuestos y retenciones y volver a ingresarlos por lote
                    //si es un registro nuevo el efecto sera igual
                    $resetImpuestos =$impuesto->deleteImpuestoByComprobante($comprobanteid);

                    if($resetImpuestos){
                        //por cada impuesto seleccionado el el selector de multiple de impuestos
                        for($i=0; $i < count($impuestos); $i++){
                            $impuesto->setDic_det_documento_sucursal($comprobanteid);
                            $impuesto->setDic_im_id($impuestos[$i]);
                            $addImpuestos = $impuesto->addImpuetoComprobante();
                        }
                    }else{}

                    $resetRetenciones = $retencion->deleteRetencionByComprobante($comprobanteid);

                    if($resetRetenciones){
                        //por cada retencion seleccionada el el selector de multiple de retenciones
                        if($retenciones){
                        for($i=0;$i <count($retenciones); $i++){
                            $retencion->setDrc_det_documento_sucursal($comprobanteid);
                            $retencion->setDrc_re_id($retenciones[$i]);
                            $addRetencion = $retencion->addRetencionComprobante();
                        }
                        }else{
                            $addRetencion =true;
                        }
                    }else{}
                    


                    if($addComprobante && $addImpuestos && $addRetencion){
                        if($idcomprobante){
                            echo "Actualizado";
                        }else{
                            echo "Agregado";
                        }
                    }else{
                        echo "Hubo un error en la peticion";
                    }

                }else{
                    echo "Debe agregar impuestos o reteciones a este comprobante";
                }

                
            }
        }else{
            echo "No tienes permisos";
            
        }
    }
    public function delete_comprobante()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idcomprobante = $_GET["data"];
                $comprobante = new Comprobante($this->adapter);
                $deleteComprobante = $comprobante->delete_comprobanteById($idcomprobante);
                if($deleteComprobante){
                    $success = "Comprobante eliminado";
                    $this->frameview("alert/success/successSmall",array("success"=>$success));
                }else{
                    $error = "Error desconocido";
                    $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
                }
            }else{
                $error = "Error desconocido";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error = "No tienes permisos";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }
    
}