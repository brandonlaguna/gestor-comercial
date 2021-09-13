<?php
class ArticulosController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();

        $this->loadModel([
            'Articulos/M_Articulos',
            'Categorias/M_Categorias',
            'Sucursales/M_Sucursales'
        ],$this->adapter);
    }

    public function Index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            
            $this->frameview("v2/Articulos/articulos",[]);
            $this->load(['v2/Articulos/articulosTable'],[]);

        }else{
            $this->redirect("Index","");
        }
    }

    public function getArticulosAjax()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $articulos = $this->M_Articulos->getArticulosAjax();
            $listArticulo = [];
            if($articulos){
                foreach ($articulos as $articulo) {
                    $listArticulo[] = [
                        0   =>  $articulo['idarticulo'],
                        1   =>  $articulo['nombre'],
                        2   =>  $articulo['nombre_categoria'],
                        3   =>  $articulo['unidad_medida'],
                        4   =>  $articulo['stock'],
                        5   =>  $articulo['descripcion'],
                        6   =>  $articulo['costo_producto'],
                        7   =>  $articulo['precio_venta'],
                        8   =>  $articulo['estado'] == 'A'?'Activo':'Inactivo',
                    ];
                }
            }

            echo json_encode($listArticulo);
        }else{
            $this->redirect("Index","");
        }
    }

    public function nuevoArticulo()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
                $categorias = $this->M_Categorias->getCategorias();
                
                $unidad_medida = new sGlobal($this->adapter);
                $unidades = $unidad_medida->getUnidadMedidaAll();
    
                $this->frameview("v2/Articulos/nuevoArticulo", array(
                    "unidades" => $unidades,
                    "categorias" => $categorias,
                ));
        }else{
            $this->redirect("Index","");
        }
    }

    public function guardarActualizar()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $validate_articulo = false;
            $alertList = [
                'alert'         =>  'error',
                'title'         =>  'Error al ingresar el articulo',
                'message'       =>  'Hay campos obligatorios que debes llenar para ingresar o actualizar este articulo',
                'typealert'     =>  'toast'
            ];

            if(isset($_POST['nombre_articulo'])){
                $validate_articulo = true;
            }

            $listArticulo =[];
            $listStock = [];
            if($validate_articulo){
                $listArticulo[] = [
                    'idcategoria'           =>  $_POST['categoria'], 
                    'idunidad_medida'       =>  $_POST['unidad_medida'], 
                    'nombre'                =>  $_POST['nombre_articulo'], 
                    'descripcion'           =>  $_POST['descripcion'], 
                    'estado'                =>  'A', 
                    'costo_producto'        =>  $_POST['costo_producto'], 
                    'precio_venta'          =>  $_POST['precio_venta'],
                    'a_cod_contable'        =>  1,
                    'ar_iva_compra'         =>  0,
                    'ar_iva_venta'          =>  0,
                ];

                if(isset($_FILES['image_articulo']) && $_FILES['image_articulo']){
                    array_push($listArticulo,['imagen'  =>  '']); 
                }
                if(isset($_POST['idarticulo']) && !empty($_POST['idarticulo'])){
                    array_push($listArticulo, ['idarticulo' =>  $_POST['idarticulo']]);
                }
                //guardar articulo

                $guardarActualizar = $this->M_Articulos->guardarActualizar($listArticulo);
                
                //obtener sucursales
                $sucursales = $this->M_Sucursales->getSucursales();
                $countSucursales = 0;
                foreach ($sucursales as $sucursal) {
                    $listStock[] = [
                        'st_idsucursal'     =>  $sucursal['idsucursal'],
                        'idarticulo'        =>  $guardarActualizar,
                        'stock'             =>  0,
                        'st_estado'         =>  'A',
                    ];
                    $countSucursales ++;
                }

                //guardar detalle de stock

                $guardarDetalleStock = $this->M_Articulos->guardarStock($listStock);

                if($guardarActualizar){
                    $alertList = [
                        'alert'         =>  'success',
                        'title'         =>  'Articulos almacenados',
                        'message'       =>  'El articulo fue incluido o actualizado en '.$countSucursales.' Sucursales',
                        'typealert'     =>  'toast'
                    ];
                }
            }
            echo json_encode($alertList);

        }else{
            return false;
        }
    }
}