<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="modalGuardarActualizarProducto">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Producto <span id="nombreProducto"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formGuardarActualizarProducto" class="formArticulo">
                    <div class="row mg-b-25">
                        <input type="hidden" name="idarticulo" id="idarticulo">
                        <?=$this->component('formInput',[
                            'title'         => 'Nombre del Articulo',
                            'name'          => 'nombre',
                            'id'            => 'nombre',
                            'required'      => true,
                            'placeholder'   => 'Nombre del articulo',
                            'col'           => 'col-lg-12',
                        ])?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Categoria',
                            'name'          => 'idcategoria',
                            'id'            => 'idcategoria',
                            'required'      => true,
                            'items'         => $categorias,
                            'col'           => 'col-lg-6',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Unidad de Medida',
                            'name'          => 'idunidad_medida',
                            'id'            => 'idunidad_medida',
                            'required'      => true,
                            'items'         => $unidadesMedida,
                            'col'           => 'col-lg-6',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('textAreaCustom',[
                            'title'         => 'Descripción',
                            'name'          => 'descripcion',
                            'id'            => 'descripcion',
                            'required'      => true,
                            'col'           => 'col-12',
                        ]);?>

                        <?=$this->component('formInput',[
                            'title'         => 'Costo Producto',
                            'name'          => 'costo_producto',
                            'id'            => 'costo_producto',
                            'required'      => true,
                            'placeholder'   => 'Costo Producto',
                            'col'           => 'col-lg-6',
                            'value'         => 0
                        ])?>

                        <?=$this->component('formInput',[
                            'title'         => 'Precio Venta',
                            'name'          => 'precio_venta',
                            'id'            => 'precio_venta',
                            'required'      => true,
                            'placeholder'   => 'Precio Venta',
                            'col'           => 'col-lg-6',
                            'value'         => 0
                        ])?>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetModalForm('modalGuardarActualizarProducto','btnformGuardarActualizarProducto')">Cerrar</button>
                <button type="button" class="btn btn-primary"
                id="btnformGuardarActualizarProducto"
                onclick="guardarActualizarProducto('formGuardarActualizarProducto', 'modalGuardarActualizarProducto')"
                >Guardar</button>
            </div>
        </div>
    </div>
</div>