<?php 
class Header extends EntidadBase{
    private $var;

    public function __construct($adapter){
        $table = "jgc_users_";
        parent:: __construct($table, $adapter);
    }

    public function headerConstructor()
    {
        //niveles de permisos numericos
        $levels = array(0,1,2,3,4,5);
        //niveles de permisos por booleanos
        $permission=array(
            "hard"=>false,
            "middle"=>false,
            "low"=>false,
            "under"=>true
        );
        //obtener id de los permisos
        if(isset($_SESSION['permission']) && !empty($_SESSION['permission'])){
            $permissionlevel = $_SESSION['permission'];
        }
        else{
            $permissionlevel =0;
        }

        //seteando permisos de niveles por logeo
        if($permissionlevel >= 4 && $permissionlevel <= 5){
            $permission["hard"] = true;
            $permission["under"] = false;
        }elseif($permissionlevel >= 2 && $permissionlevel <= 3){
            $permission["middle"] = true;
            $permission["under"] = false;
        }elseif($permissionlevel == 1) {
            $permission["low"] = true;
            $permission["under"] = false;
        }else{
            $permission["under"] = true;
        }


        $this->menuConstructor($permission,$permissionlevel);

    }


    public function menuConstructor($permission,$permissionlevel)
    {
        /*
        1. ten en cuenta los encabezados, si deseas agregar un sub-menu debes ponerlo en un array dentro de cada sub-list
        2. los permisosos de quien puede ver la sublista se agrega dentro de cada encabezado de sub-lista y se debe llamar "level" => "numero maximo del permiso"
        3. la url se agrega de tal manera (#compras/reg_compras) --> (#controlador/funcion)
        4. si se desea pasar un parametro de mas debe ir despues de la funcion (#controlador/funcion/parametro)
            se permiten pasar 2 parametros de mas llamados $s & $t para tenerlos en cuenta cuando se llamen en el controlador/funcion
        5.lo mismo aplica para la lista principal, se le agrega el nivel maximo, la url y obligatoriamente se debe agregar el parametro
          sublist para evitar errores del sistema por no encontrar la sublista
        6. el parametro del icono se debe agregar dentro del encabezado de la lista y se debe lamar "icon"
        */
        $listMenu = array(
            //Encabezado de cada lista
            "Dashboard"=>array(
                "level"=>"5",
                "url"=> "#dashboard",
                "icon"=>"fas fa-tachometer-alt",
                "sublist"=>array(),
            ),
            "Almacen"=>array(
                "level"=>"1",
                "url"=>"",
                "icon"=>"fas fa-box-open",
                "sublist"=>array(
                    "Articulos"=>array(
                        "level"=>"1",
                        "url"=>"#articulos/"
                    ),
                    "Categorias"=>array(
                        "level"=>"1",
                        "url"=>"#Categorias/"
                    ),
                )
            ),
            "Compras"=>array(
                "level"=>"1",
                "url"=>"#",
                "icon"=>"fas fa-cart-arrow-down",
                "sublist"=>array(
                    //encabezado de cada sublista
                    "Reg. Compras"=>array(
                        "level"=>"1",
                        "url"=>"#compras/reg_compras"
                    ),
                    "Reg. Compra contable"=>array(
                        "level"=>"3",
                        "url"=>"#compras/reg_contable"
                    ),
                    "Compra Contabilidad"=>array(
                        "level"=>"3",
                        "url"=>"#compras/nuevo"
                    ),
                )
                ),
            "Ventas"=>array(
                "level"=>"1",
                "url"=>"#",
                "icon"=>"fas fa-store",
                "sublist"=>array(
                    "Reg. Ventas"=>array(
                        "level"=>"3",
                        "url"=>"#ventas",
                    ),
                    "Reg. Ventas Contables"=>array(
                        "level"=>"3",
                        "url"=>"#ventas/reg_contable",
                    )
                )
            ),
            "Admin Comprobantes"=>array(
                "level"=>"4",
                "url"=>"#",
                "icon"=>"fas fa-dolly",
                "sublist"=>array(
                    "Reg. Comprobante"=>array(
                        "level"=>"4",
                        "url"=>"#comprobantes/registro"
                    ),
                    "Informe general"=>array(
                        "level"=>"4",
                        "url"=>"#comprobantes/informes/general"
                    ),
                    "Informe detallado"=>array(
                        "level"=>"4",
                        "url"=>"#comprobantes/informes/detallado"
                    ),
                    "Informe de terceros"=>array(
                        "level"=>"4",
                        "url"=>"#comprobantes/informes/terceros"
                    ),
                    "Informes contables"=>array(
                        "level"=>"4",
                        "url"=>"#comprobantes/menu"
                    ),
                    "Utilidades"=>array(
                        "level"=>"6",
                        "url"=>"#comprobantes/informes/utilidades"
                    ),
                )
            ),
            "Caja"=>array(
                "level"=>"1",
                "url"=>"#",
                "icon"=>"fas fa-cash-register",
                "sublist"=>array(
                    "Monto inicial"=>array(
                        "level"=>"1",
                        "url"=>"#caja/monto_inicial"
                    ),
                    "Cierre de turno"=>array(
                        "level"=>"1",
                        "url"=>"#caja/cierre_turno"
                    ),
                    "Cierre A-Z"=>array(
                        "level"=>"1",
                        "url"=>"#caja/"
                    ),
                    "Reportes"=>array(
                        "level"=>"1",
                        "url"=>"#caja/reportes"
                    ),
                )
            ),
            "Reporte Venta"=>array(
                "level"=>"4",
                "url"=>"#",
                "icon"=>"fas fa-file-contract",
                "sublist"=>array(
                    "General"=>array(
                        "level"=>"3",
                        "url"=>"#ReporteVentas/general"
                    ),
                    "Detallado"=>array(
                        "level"=>"3",
                        "url"=>"#ReporteVentas/detallado"
                    ),
                )
            ),
            "Reporte Compra"=>array(
                "level"=>"4",
                "url"=>"#",
                "icon"=>"fas fa-file-contract",
                "sublist"=>array(
                    "General"=>array(
                        "level"=>"3",
                        "url"=>"#reporteCompras/general"
                    ),
                    "Detallada"=>array(
                        "level"=>"3",
                        "url"=>"#reporteCompras/detallado"
                    ),
                    "Kardex Valorizado"=>array(
                        "level"=>"3",
                        "url"=>"#informe/kardex"
                    ),
                    "Stock Articulos"=>array(
                        "level"=>"3",
                        "url"=>"#reporteStock"
                    ),
                )
            ),
            "Cuentas por pagar"=>array(
                "level"=>"4",
                "url"=>"#",
                "icon"=>"fas fa-wallet",
                "sublist"=>array(
                    "Pagos"=>array(
                        "level"=>"4",
                        "url"=>"#proveedor/deudas"
                    ),
                )
            ),
            "Cuentas por cobrar"=>array(
                "level"=>"4",
                "url"=>"#",
                "icon"=>"fas fa-wallet",
                "sublist"=>array(
                    "Recaudos"=>array(
                        "level"=>"4",
                        "url"=>"#cliente/cartera"
                    ),
                )
            ),
            "Reporte de iva gdo."=>array(
                "level"=>"4",
                "url"=>"#",
                "icon"=>"fas fa-file-contract",
                "sublist"=>array(
                    "IVA generado"=>array(
                        "level"=>"4",
                        "url"=>"#impuestos/reporte"
                    ),
                )
            ),
            "Administrador" =>array(
                "level"=>"5",
                "url"=>"#",
                "icon"=>"fas fa-toolbox",
                "sublist"=>array(
                    "Impuestos"=>array(
                        "level"=>"5",
                        "url"=>"#admin/impuestos"
                    ),
                    "Retenciones"=>array(
                        "level"=>"5",
                        "url"=>"#admin/retenciones"
                    ),
                    "Formas de pago"=>array(
                        "level"=>"5",
                        "url"=>"#admin/formapago"
                    ),
                    "Metodos de pago"=>array(
                        "level"=>"5",
                        "url"=>"#admin/metodopago"
                    ),
                    "Tipo de Documentos"=>array(
                        "level"=>"5",
                        "url"=>"#admin/tipo_documento"
                    ),
                    "Conf. Comprobantes"=>array(
                        "level"=>"5",
                        "url"=>"#documentoSucursal"
                    ),
                    "Centro de Costos"=>array(
                        "level"=>"5",
                        "url"=>"#admin/centro_costos"
                    ),
                    "Cuentas contables"=>array(
                        "level"=>"5",
                        "url"=>"#contables/"
                    ),
                    "Facturacion Electronica"=>array(
                        "level"=>"5",
                        "url"=>"#FacturacionElectronica/"
                    ),
                ),
            ),
            "Configuración"=>array(
                "level"=>"5",
                "url"=>"#",
                "icon"=>"fas fa-sliders-h",
                "sublist"=>array(
                    "Empresa"=>array(
                        "level"=>"5",
                        "url"=>"#configuracion/empresa"
                    ),
                    "Empleados"=>array(
                        "level"=>"5",
                        "url"=>"#usuarios/empleados"
                    ),
                    "Terceros"=>array(
                        "level"=>"1",
                        "url"=>"#almacen/terceros"
                    ),
                    "Usuarios"=>array(
                        "level"=>"5",
                        "url"=>"#usuario"
                    )
                ),
            ),

            "Sistema y seguridad"=>array(
                "level"=>"5",
                "url"=>"#",
                "icon"=>"far fa-hdd",
                "sublist"=>array(
                    "Copias de sguridad"=>array(
                        "level"=>"6",
                        "url"=>"#system/backup",
                    ),
                    "Superadministrador"=>array(
                        "level"=>"5",
                        "url"=>"#refacturacion/ventas",
                    ),
                )
            ),
        );



        //do not touch, head structure V.0.1 from Brandon Laguna 23/02/2020 updated 00/00/0000
        /*
            this work with the arrays located on head from this function, if you want to add a new list menu item or a new item
            you should read description located on head from this function
        */

        foreach ($listMenu as $list => $value) {
            if($value["level"] <= $permissionlevel && $value["level"] > 0){
            echo '<li class="br-menu-item">';
                echo '<a href="'.$value["url"].'" class="br-menu-link with-sub">';
                echo '<i class="menu-item-icon '.$value["icon"].' tx-20"></i>';
                echo'<span class="menu-item-label">'.$list.'</span>';
                echo'</a>';
                if($value["sublist"]){
                echo '<ul class="br-menu-sub">';
                foreach ($value["sublist"] as $subvalue => $attrs){
                    if($attrs["level"] <= $permissionlevel && $attrs["level"] > 0){
                        echo ' <li class="sub-item"><a href="'.$attrs["url"].'" class="sub-link">'.$subvalue.'</a></li>';
                    }
                }
                echo '</ul>';
            }
                echo '</li>';


        }
        }
        
    }

    
}

?>