<?php
class ArticulosController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();
        $this->libraries(['Verificar']);
        $this->Verificar->sesionActiva();
        $this->loadModel([
            'Articulos/M_Articulos',
            'Categorias/M_Categorias',
            'Sucursales/M_Sucursales'
        ],$this->adapter);
    }

    public function Index()
    {
        $categorias     = $this->M_Categorias->getCategorias();
        $unidadesMedida = $this->M_Articulos->obtenerUnidadesMedida();
        $this->frameview("v2/Articulos/articulos",[]);
        $this->load(['v2/Articulos/articulosScript'],[]);
        $this->load(['v2/Articulos/articulosModales'],[
            'categorias'        => $categorias,
            'unidadesMedida'    => $unidadesMedida
        ]);
        $this->load(['v2/Articulos/articulosTable'],[]);
    }

    public function getArticulosAjax()
    {
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
    }

    public function nuevoArticulo()
    {
        $categorias = $this->M_Categorias->getCategorias();
        $unidad_medida = new sGlobal($this->adapter);
        $unidades = $unidad_medida->getUnidadMedidaAll();
        $this->frameview("v2/Articulos/nuevoArticulo", [
            "unidades" => $unidades,
            "categorias" => $categorias,
        ]);
    }

    public function guardarActualizar()
    {
        $validate_articulo = false;
        $alertList = [
            'alert'         =>  'error',
            'title'         =>  'Error al ingresar el articulo',
            'message'       =>  'Hay campos obligatorios que debes llenar para ingresar o actualizar este articulo',
            'typealert'     =>  'toast'
        ];
        if(isset($_POST['nombre'])){
            $validate_articulo = true;
        }
        $listArticulo =[];
        $listStock = [];
        if($validate_articulo){
            $listArticulo[] = [
                'idcategoria'           =>  $_POST['idcategoria'],
                'idunidad_medida'       =>  $_POST['idunidad_medida'],
                'nombre'                =>  $_POST['nombre'],
                'descripcion'           =>  $_POST['descripcion'],
                'estado'                =>  'A',
                'costo_producto'        =>  $_POST['costo_producto'],
                'precio_venta'          =>  $_POST['precio_venta'],
                'a_cod_contable'        =>  1,
                'ar_iva_compra'         =>  0,
                'ar_iva_venta'          =>  0,
            ];
            if(isset($_FILES['image_articulo']) && $_FILES['image_articulo']){
                $listArticulo = array_merge($listArticulo,['imagen'  =>  '']);
            }
            if(isset($_POST['idarticulo']) && !empty($_POST['idarticulo']) && $_POST['idarticulo'] > 0){
                $listArticulo = array_merge($listArticulo, ['idarticulo' =>  $_POST['idarticulo']]);
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
            //guardar detalle de stock pos sucursal
            $this->M_Articulos->guardarStock($listStock);
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
    }

    function editarArticulo()
    {
        $estado = false;
        $respuesta = 'Error';
        $data =[];
        try {
            if(isset($_POST)){
                $data = $this->M_Articulos->getArticuloBy(['idarticulo' => $_POST['idArticulo']]);
                $estado = true;
                $respuesta = 'Articulo Recuperado';
            }else{
                throw new Exception("No se pudo recuperar la informacion del articulo");
            }
        } catch (\Throwable $e) {
            $respuesta = $e->getMessage();
        }
        echo json_encode([
            'estado'    => $estado,
            'respuesta' => $respuesta,
            'data'      => $data
        ]);
    }
}