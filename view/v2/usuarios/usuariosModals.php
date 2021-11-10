<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="modalNuevoUsuario">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Categoria <span id="nombreCategoria"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNuevoUsuario">
                    <div class="row mg-b-25">
                        <?=$this->component('formSelect',[
                            'title'         => 'Empleado',
                            'name'          => 'empleado',
                            'id'            => 'empleado',
                            'required'      => true,
                            'items'         => $empleados,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Sucursal',
                            'name'          => 'sucursal',
                            'id'            => 'sucursal',
                            'required'      => true,
                            'items'         => $sucursales,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Permisos',
                            'name'          => 'permisos',
                            'id'            => 'permisos',
                            'required'      => true,
                            'items'         => [
                                ['item_id' => 1, 'item_name' => 'Básico'],
                                ['item_id' => 2, 'item_name' => 'Medio Bajo'],
                                ['item_id' => 3, 'item_name' => 'Intermedio'],
                                ['item_id' => 4, 'item_name' => 'Avanzado'],
                                ['item_id' => 5, 'item_name' => 'Administrador'],
                            ],
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formInput',[
                            'title'         => 'Contraseña',
                            'name'          => 'password',
                            'id'            => 'password',
                            'type'          => 'password',
                            'required'      => true,
                            'placeholder'   => 'Contraseña',
                            'col'           => 'col-lg-6',
                        ])?>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnformNuevaCategoria"
                    onclick="guardarActualizarUsuario('formNuevoUsuario', 'modalNuevoUsuario')">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal editar usuario -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="modalEditarUsuario">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Usuario <span id="nombreUsuario"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario">
                    <div class="row mg-b-25">
                        <?=$this->component('formSelect',[
                            'title'         => 'Empleado',
                            'name'          => 'empleadoE',
                            'id'            => 'empleadoE',
                            'required'      => true,
                            'items'         => $empleados,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Sucursal',
                            'name'          => 'sucursalE',
                            'id'            => 'sucursalE',
                            'required'      => true,
                            'items'         => $sucursales,
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formSelect',[
                            'title'         => 'Permisos',
                            'name'          => 'permisosE',
                            'id'            => 'permisosE',
                            'required'      => true,
                            'items'         => [
                                ['item_id' => 1, 'item_name' => 'Básico'],
                                ['item_id' => 2, 'item_name' => 'Medio Bajo'],
                                ['item_id' => 3, 'item_name' => 'Intermedio'],
                                ['item_id' => 4, 'item_name' => 'Avanzado'],
                                ['item_id' => 5, 'item_name' => 'Administrador'],
                            ],
                            'col'           => 'col-lg-6',
                            'class'         => 'form-control selectpicker',
                            'selected'      => 0,
                            'attr'          =>[
                                'data-live-search'  => true
                            ]
                        ]);?>

                        <?=$this->component('formInput',[
                            'title'         => 'Contraseña <mall>Se actualizará cuando se ingrese una nueva contraseña</small>',
                            'name'          => 'password',
                            'id'            => 'passwordE',
                            'type'          => 'passwordE',
                            'required'      => true,
                            'placeholder'   => 'Contraseña',
                            'col'           => 'col-lg-6',
                        ])?>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnformNuevaCategoria"
                    onclick="guardarActualizarUsuario('formEditarUsuario', 'modalEditarUsuario')">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal permisos -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="modalPermisosUsuario">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="width:100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Permisos <span id="nombreUsuario"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPermisosUsuario">
                    <div class="row mg-b-25">
                        <input type="hidden" name="idUsuarioPermiso" id="idUsuarioPermiso">
                        <?=$this->component('switchBox',[
                            'name'          => 'permiso',
                            'id'            => 'permiso',
                            'items'         => $tipos_permisos,
                            //'onclick'       => 'guardarActualizarPermiso',
                            'class'         => 'guardarActualizarPermiso',
                            'attr'          => [
                                'permiso'   => 'id'
                            ]
                        ]);?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnformNuevaCategoria"
                    onclick="guardarActualizarUsuario('formEditarUsuario', 'modalEditarUsuario')">Guardar</button>
            </div>
        </div>
    </div>
</div>