<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="modalTipoDocumento">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalDocumento"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTipoDocumento" class="formTipoDocumento">
                    <div class="row mg-b-25">
                        <input type="hidden" name="idtipo_documento" id="idtipo_documento">
                        <?=$this->component('formInput',[
                            'title'         => 'Nombre del tipo de documento',
                            'name'          => 'nombre',
                            'id'            => 'nombre',
                            'required'      => true,
                            'placeholder'   => 'Nombre del tipo de documento',
                            'col'           => 'col-lg-6',
                        ])?>

                        <?=$this->component('formInput',[
                            'title'         => 'Prefijo',
                            'name'          => 'prefijo',
                            'id'            => 'prefijo',
                            'required'      => true,
                            'placeholder'   => 'Prefijo',
                            'col'           => 'col-lg-3',
                        ])?>

                        <?=$this->component('formInput',[
                            'title'         => 'ID Documento F.E',
                            'name'          => 'type',
                            'id'            => 'type',
                            'placeholder'   => 'ID Documento F.E',
                            'col'           => 'col-lg-3',
                        ])?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Operacion',
                            'name'          => 'operacion',
                            'id'            => 'operacion',
                            'required'      => true,
                            'items'         => $listOperaciones,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Proceso',
                            'name'          => 'proceso',
                            'id'            => 'proceso',
                            'required'      => false,
                            'items'         => $listProcesos,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary"
                id="btnformTipoDocumento"
                onclick="guardarActualizarTipoDocumento()"
                >Guardar</button>
            </div>
        </div>
    </div>
</div>