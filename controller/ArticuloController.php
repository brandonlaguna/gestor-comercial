<?php
class ArticuloController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
       parent::__construct();

       $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            $articulo = new Articulo($this->adapter);
            $articulos = $articulo->getArticuloAll();
            $this->frameview("articulo/index",array(
                "articulos"=>$articulos
            ));
        }else{
            echo "Forbidden Gateway";
        }
        
    }

    public function detail()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idarticulo = $_GET["data"];
                $articulos = new Articulo($this->adapter);
                $articulo = $articulos->getArticuloById($idarticulo);

                $unidad_medida = new sGlobal($this->adapter);
                $unidades= $unidad_medida->getUnidadMedidaAll();

                $categoria = new Categoria($this->adapter);
                $categorias =$categoria->getAll();

                $this->frameview("articulo/Detail/detail",array(
                    "articulo"=>$articulo,
                    "unidades"=>$unidades,
                    "categorias"=>$categorias
                ));

            }else{
                echo "Forbidden Gateway";
            }
        }else{
            echo "Forbidden Gateway";
        }
    }
    public function edit()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            if(isset($_GET["data"]) && !empty($_GET["data"])){
                $idarticulo = $_GET["data"];
                $articulos = new Articulo($this->adapter);
                $articulo = $articulos->getArticuloById($idarticulo);

                $unidad_medida = new sGlobal($this->adapter);
                $unidades= $unidad_medida->getUnidadMedidaAll();

                $categoria = new Categoria($this->adapter);
                $categorias =$categoria->getAll();

                $this->frameview("articulo/Edit/index",array(
                    "articulo"=>$articulo,
                    "unidades"=>$unidades,
                    "categorias"=>$categorias
                ));

            }else{
                echo "Forbidden Gateway";
            }
        }else{
            echo "Forbidden Gateway";
        }
    }

    public function delete()
    {
        if(isset($_GET["data"]) && !empty($_GET["data"])){
            if(!empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 4){
            $idarticulo = $_GET["data"];
            $articulo = new Articulo($this->adapter);
            $deleteArticulo = $articulo->deleteArticulo($idarticulo);
            if($deleteArticulo){
                $success = "Articulo eliminado";
                $this->frameview("alert/success/successSmall",array("success"=>$success));
            }else{
                $error= "No se pudo eliminar este articulo";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
            }else{
                $error= "No tiene permisos para esta accion";
                $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
            }
        }else{
            $error= "No se pudo eliminar este articulo";
            $this->frameview("alert/error/forbiddenSmall",array("error"=>$error));
        }
    }
    

    public function new()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            $unidad_medida = new sGlobal($this->adapter);
            $unidades= $unidad_medida->getUnidadMedidaAll();

            $categoria = new Categoria($this->adapter);
            $categorias =$categoria->getAll();
            
            $this->frameview("articulo/New/new",array(
                "unidades"=>$unidades,
                "categorias"=>$categorias
            ));
        }else{
            echo "Forbidden Gateway";
        }
    }

    public function add()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] >2){
            if(isset($_POST["nombre_articulo"]) && !empty($_POST["nombre_articulo"])){
                //seteando y limpiando datos en variables
                $nombre = cln_str($_POST["nombre_articulo"]);
                $idcategoria = cln_str($_POST["categoria"]);
                $idunidad_medida = cln_str($_POST["unidad_medida"]);
                $costo_producto = intval($_POST["costo_producto"]);
                $precio_venta = intval($_POST["precio_venta"]);
                //$imagen = $_FILES['imagen_producto']['tmp_name'];
                $descripcion = cln_str($_POST["descripcion"]);
                //obtener informacion de la categoria
                $categorias = new Categoria($this->adapter);
                $categoria = $categorias->getCategoriaById($idcategoria);
                //obtener solo un objeto de la lista
                foreach ($categoria as $categoria) {}
                
                $importe_venta_categoria = (($categoria->imp_venta/100+1));
                $importe_compra_categoria = (($categoria->imp_compra/100)+1);
                //llamar la clase de articulos
                $articulo = new Articulo($this->adapter);
                $articulo->setIdcategoria($idcategoria);
                $articulo->setIdunidad_medida($idunidad_medida);
                $articulo->setNombre($nombre);
                $articulo->setDescripcion($descripcion);
                $articulo->setImagen("media");
                $articulo->setEstado("A");
                $articulo->setCosto_producto($costo_producto / $importe_compra_categoria);
                $articulo->setAr_iva_compra("0");
                $articulo->setAr_iva_venta("0");
                $articulo->setPrecio_venta($precio_venta / $importe_venta_categoria);
                if(isset($_POST["idarticulo"]) && !empty($_POST["idarticulo"])){
                    $articulo->setStock($_POST["stock"]);
                    $updateArticulo = $articulo->updateArticulo($_POST["idarticulo"]);
                    if($updateArticulo){
                        echo "Articulo Actualizado";
                    }else{
                        echo "Error al actualizar articulo";
                    }
                }else{
                    $addArticulo = $articulo->addArticulo();
                    if($addArticulo){
                        echo"Articulo Agregado";
                    }else{
                        echo "Error al crear articulo";
                    }
                }

            }else{}
        }else{
            echo "Forbidden Gateway";
        }
    }


    public function getItem()
    {
        if(isset($_POST["data"]) && !empty($_POST["data"])){
            //los campos vienen "sucios" hay que limpiarlos quitarles los caracteres y separarlos por palabra para hacer la busqueda
            //separar por el delimitados " - " (espacio guion espacio)
            $array = explode(" - ", $_POST["data"]);
            //sabiendo que existe este nombre o este codigo hacemos busqueda en cada tabla con articulos, servicios etc que contengan puc
            $articulo = new Articulo($this->adapter);
            //en serie se hace una busqueda en los codigos contables y se trae el dato
            $puc = new PUC($this->adapter);
            $i=0;
            $response = [];
            foreach ($array as $search) {
                $articulos = $articulo->getArticuloBy($array[$i]);
                $codigos = $puc->getCodContableCompraBy($array[$i]);
                //si se encontro algo en articulos lo imprime
                foreach ($articulos as $articulos) {}
                foreach($codigos as $codigos){}
                if($articulos->idarticulo!=null){
                    $response = $articulos;
                }elseif($codigos->idcodigo!=null){
                    $response = $codigos;
                }
                $i++;
            }
            echo json_encode($response);

        }else{
            echo json_encode("Forbidden gateway");
        }
    }

    public function autoCompleteArticulo()
    {
        if(isset($_POST["data"])){
            $articulo = new Articulo($this->adapter);
            $articulos = $articulo->getArticulo($_POST["data"]);
            $response = [];
 
            foreach($articulos as $articulos){
                $response[]=$articulos->idarticulo." - ".$articulos->nombre;
                $response[]=$articulos->nombre." - ".$articulos->idarticulo;
            }

            echo json_encode($response);
        }
    }

    public function loadCart()
    {
        $cart = new ColaIngreso($this->adapter);
        $items = $cart->loadCart();
        echo $this->frameview("articulo/loadCart",array("items"=>$items));
    }
    public function deleteItemToCart()
    {
        if(isset($_POST["data"]) && !empty($_POST["data"])){
            $cart = new ColaIngreso($this->adapter);
            $deleteItem = $cart->deleteItem($_POST["data"]);
        }
    }
    public function addItemToCart($data,$pos,$tercero)
    {
        if(!empty($data) && !empty($pos) && !empty($tercero)){
            $idarticulo = $data["iditem"];
            $cantidad = $data["cantidad"];
            $ivaarticulo = $data["imp_compra"];
            if($pos == "Ingreso"){
                $costo_producto= $data["costo_producto"];
            }else{
                $costo_producto= $data["precio_venta"];
            }
            
            $cod_costos= $data["cod_costos"];
            $total_lote = ($costo_producto * $cantidad) *($ivaarticulo/100+1);
            $debito=0;
            $credito=0;
            if($pos == "Ingreso"){
                $credito = $total_lote;
            }elseif($pos == "Venta"){
                $debito = $total_lote;
            }else{
            }
            //calcular 
            //agregar articulo al carro
            $cart = new ColaIngreso($this->adapter);
            $cart->setCdi_idsucursal($_SESSION["idsucursal"]);
            $cart->setCdi_tercero($tercero);
            $cart->setCdi_idarticulo($idarticulo);
            $cart->setCdi_stock_ingreso($cantidad);
            $cart->setCdi_precio_unitario($costo_producto);
            $cart->setCdi_importe($ivaarticulo);
            $cart->setCdi_precio_total_lote($total_lote);
            $cart->setCdi_credito($credito);
            $cart->setCdi_debito($debito);
            $cart->setCdi_cod_costos($cod_costos);
            $addItem = $cart->addItemToCart();
            return $articulos;
        }
        
    }
}
