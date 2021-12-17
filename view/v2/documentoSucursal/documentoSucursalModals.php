<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="modalDocumento">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalDocumento"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formDocumento" class="formDocumento">
                    <div class="row">
                        <input type="hidden" name="iddetalle_documento_sucursal" id="iddetalle_documento_sucursal">
                        <?=$this->component('formInput',[
                            'title'         => 'Serie del documento',
                            'name'          => 'ultima_serie',
                            'id'            => 'ultima_serie',
                            'required'      => true,
                            'placeholder'   => 'Serie del documento',
                            'col'           => 'col-lg-9',
                        ])?>

                        <?=$this->component('formInput',[
                            'title'         => 'Consecutivo',
                            'name'          => 'ultimo_numero',
                            'id'            => 'ultimo_numero',
                            'required'      => true,
                            'placeholder'   => 'Consecutivo del documento (0 por defecto)',
                            'col'           => 'col-lg-3',
                            'value'         => 0
                        ])?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Tipo de documento',
                            'name'          => 'idtipo_documento',
                            'id'            => 'idtipo_documento',
                            'required'      => true,
                            'items'         => $tipoDocumentos,
                            'col'           => 'col-lg-4',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Formato',
                            'name'          => 'dds_pri_id',
                            'id'            => 'dds_pri_id',
                            'required'      => true,
                            'items'         => $listaFormatos,
                            'col'           => 'col-lg-4',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Contabilidad',
                            'name'          => 'contabilidad',
                            'id'            => 'contabilidad',
                            'required'      => true,
                            'items'         => $opciones,
                            'col'           => 'col-lg-4',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Mov. Inventario',
                            'name'          => 'dds_afecta_inventario',
                            'id'            => 'dds_afecta_inventario',
                            'required'      => true,
                            'items'         => $opciones,
                            'col'           => 'col-lg-4',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Parametro por defecto',
                            'name'          => 'dds_propertie',
                            'id'            => 'dds_propertie',
                            'required'      => false,
                            'items'         => $listaPropiedades,
                            'col'           => 'col-lg-4',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('textAreaCustom',[
                            'title'         => 'Pie de factura',
                            'name'          => 'pf_text',
                            'id'            => 'pf_text',
                            'required'      => true,
                            'placeholder'   => 'Detalle pie de factura (Ej: Resolución)',
                            'col'           => 'col-lg-12',
                            'value'         => 0
                        ])?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary"
                id="btnformDocumento"
                onclick="guardarActualizarDocumento()"
                >Guardar</button>
            </div>
        </div>
    </div>
</div>