<?php
class CategoriasController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();

        $this->loadModel([
            'Categorias/M_Categorias'
        ],$this->adapter);

    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){

            $this->frameview("v2/Categorias/categorias",[]);
            
            $this->load(['v2/Categorias/categoriasScript'],[]);
            $this->load(['v2/Categorias/categoriasTable'],[]);
        }
    }

    public function nuevaCategoria()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $categoria = new Categoria($this->adapter);
            $puc = new PUC($this->adapter);
            $allpuc = $puc->getAllPucBy('movimiento','1');
            $idcategoria ="";

            $this->frameview("v2/Categorias/nuevaCategoria",array(
                "idcategoria"=>$idcategoria,
                "allpuc"=>$allpuc
            ));
        }
    }

    public function guardarActualizar()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] >3){
            $validate_categoria = false;
            $alertList = [
                'alert'         =>  'error',
                'title'       =>  'Error al ingresar la categoria',
                'message'         =>  'Hay campos obligatorios que debes llenar para ingresar o actualizar esta categoria',
                'typealert'     => 'toast'
            ];
            if(isset($_POST['nombre_categoria'])){
                $validate_categoria = true;
            }

            

            if($validate_categoria){
                $arrayCategoria = [
                    'nombre'      => isset($_POST['nombre_categoria'])?$_POST['nombre_categoria']:'',
                    'cod_venta'             => isset($_POST['cod_venta'])?$_POST['cod_venta']:0,
                    'cod_costos'            => isset($_POST['cod_costos'])?$_POST['cod_costos']:0,
                    'cod_devoluciones'      => isset($_POST['cod_devoluciones'])?$_POST['cod_devoluciones']:0,
                    'cod_inventario'        => isset($_POST['cod_inventario'])?$_POST['cod_inventario']:0,
                    'imp_compra'            => isset($_POST['imp_compra'])?$_POST['imp_compra']:0,
                    'imp_venta'             => isset($_POST['imp_venta'])?$_POST['imp_venta']:0,
                    'estado'                =>  'A',
                    'cod_imp_categoria'     =>  0
                ];

                if(isset($_POST['idcategoria']) && !empty($_POST['idcategoria'])){
                    array_push($arrayCategoria,['idcategoria'=>$_POST['idcategoria']]);
                }
                
                $guardarActualizar = $this->M_Categorias->guardarActualizar($arrayCategoria);
                if($guardarActualizar){
                    $alertList = [
                        'alert'          =>  'success',
                        'title'       =>  'Todo en orden',
                        'message'   =>  'Categoria guardada o actualizada correctamente',
                        'idcategoria'   =>  $guardarActualizar,
                        'typealert'     => 'toast'
                    ];
                }else{
                    $alertList = [
                        'alert'          =>  'error',
                        'title'        =>  'Error interno',
                        'message'          =>  'Ocurrio una falla al momento de realizar este proceso, contacta un tecnico para su revision',
                        'typealert'     => 'toast'
                    ];
                }

                echo json_encode($alertList);

            }else{

            }

        }else{
            $this->redirect("Index","");
        }
    }
    public function getCategoriasAjax()
    {
        if(isset($_SESSION["idsucursal"]) && $_SESSION["permission"] >0){
            $categorias = $this->M_Categorias->getCategoriasAjax();
            $listCategoria = [];
            foreach ($categorias as $categoria) {
                $listCategoria[] = [
                    0   => $categoria['idcategoria'],
                    1   => $categoria['nombre'],
                    2   => $categoria['codigo_venta'],
                    3   => $categoria['codigo_costo'],
                    4   => $categoria['codigo_devoluciones'],
                    5   => $categoria['codigo_inventario'],
                    6   => $categoria['imp_venta'],
                    7   => $categoria['imp_compra'],
                    8   => $categoria['estado'] == 'A'?'Activo':'Inactivo'
                ];
            }

            echo json_encode($listCategoria);
        }else{
            echo json_encode([]);
        }
    }
}