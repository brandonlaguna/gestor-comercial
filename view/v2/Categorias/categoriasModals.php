<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="modalNuevaCategoria">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Categoria <span id="nombreCategoria"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNuevaCategoria" class="formCategoria">
                    <div class="row mg-b-25">
                        <input type="hidden" name="idcategoria" id="idcategoria">
                        <?=$this->component('formInput',[
                            'title'         => 'Nombre de la categoria',
                            'name'          => 'nombre',
                            'id'            => 'nombre',
                            'required'      => true,
                            'placeholder'   => 'Nombre de la categoria',
                            'col'           => 'col-lg-12',
                        ])?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Codigo de venta',
                            'name'          => 'cod_venta',
                            'id'            => 'cod_venta',
                            'required'      => false,
                            'items'         => $allpuc,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Codigo de costos',
                            'name'          => 'cod_costos',
                            'id'            => 'cod_costos',
                            'required'      => false,
                            'items'         => $allpuc,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Codigo de devoluciones',
                            'name'          => 'cod_devoluciones',
                            'id'            => 'cod_devoluciones',
                            'required'      => false,
                            'items'         => $allpuc,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Codigo de inventario',
                            'name'          => 'cod_inventario',
                            'id'            => 'cod_inventario',
                            'required'      => false,
                            'items'         => $allpuc,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formInput',[
                            'title'         => 'Impuesto de compra',
                            'name'          => 'imp_compra',
                            'id'            => 'imp_compra',
                            'required'      => false,
                            'placeholder'   => 'Impuesto de compra',
                            'col'           => 'col-lg-6',
                            'value'         => 0
                        ])?>

                        <?=$this->component('formInput',[
                            'title'         => 'Impuesto de venta',
                            'name'          => 'imp_venta',
                            'id'            => 'imp_venta',
                            'required'      => false,
                            'placeholder'   => 'Impuesto de venta',
                            'col'           => 'col-lg-6',
                            'value'         => 0
                        ])?>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary"
                id="btnformNuevaCategoria"
                onclick="guardarActualizarCategoria('formNuevaCategoria', 'modalNuevaCategoria')"
                >Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="modalEditarCategoria">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Categoria <span id="nombreCategoria"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarCategoria" class="formCategoria">
                    <div class="row mg-b-25">
                        <input type="hidden" name="idcategoriaE" id="idcategoriaE">
                        <?=$this->component('formInput',[
                            'title'         => 'Nombre de la categoria',
                            'name'          => 'nombreE',
                            'id'            => 'nombreE',
                            'required'      => true,
                            'placeholder'   => 'Nombre de la categoria',
                            'col'           => 'col-lg-12',
                        ])?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Codigo de venta',
                            'name'          => 'cod_ventaE',
                            'id'            => 'cod_ventaE',
                            'required'      => false,
                            'items'         => $allpuc,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Codigo de costos',
                            'name'          => 'cod_costosE',
                            'id'            => 'cod_costosE',
                            'required'      => false,
                            'items'         => $allpuc,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Codigo de devoluciones',
                            'name'          => 'cod_devolucionesE',
                            'id'            => 'cod_devolucionesE',
                            'required'      => false,
                            'items'         => $allpuc,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Codigo de inventario',
                            'name'          => 'cod_inventarioE',
                            'id'            => 'cod_inventarioE',
                            'required'      => false,
                            'items'         => $allpuc,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formInput',[
                            'title'         => 'Impuesto de compra',
                            'name'          => 'imp_compraE',
                            'id'            => 'imp_compraE',
                            'required'      => false,
                            'placeholder'   => 'Impuesto de compra',
                            'col'           => 'col-lg-6',
                            'value'         => 0
                        ])?>

                        <?=$this->component('formInput',[
                            'title'         => 'Impuesto de venta',
                            'name'          => 'imp_ventaE',
                            'id'            => 'imp_ventaE',
                            'required'      => false,
                            'placeholder'   => 'Impuesto de venta',
                            'col'           => 'col-lg-6',
                            'value'         => 0
                        ])?>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary"
                id="btnformEditarCategoria"
                onclick="guardarActualizarCategoria('formEditarCategoria','modalEditarCategoria')"
                >Actualizar</button>
            </div>
        </div>
    </div>
</div>