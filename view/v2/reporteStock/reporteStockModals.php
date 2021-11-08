<!-- modal de filtro avanzado -->

<div class="modal fade bd-example-modal-lg" id="filtroAvanzado" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Filtro avanzado</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="modal-body">
            <form style="width: 700px;" id="formFiltro">
                <div class="form-row">
                    
                    <div class='form-group col-sm-6 text-center'>
                        <p>Rango de fecha</p>
                        <input size="16" type="text" class="form-control filtro_rango_fecha" id="" name="fecha_filtro">
                    </div>

                        <?=$this->component('formSelect',[
                            'title'             =>  'Sucursales',
                            'col'               =>  'col-sm-12 col-md-6 col-lg-6 text-center',
                            'name'              =>  'filtro_sucursal',
                            'id'                =>  'filtro_sucursal',
                            'items'             =>  $sucursales,
                            'selected'          =>  0,
                            'class'             =>  'form-control'
                        ])?>
                </div>
            </form>
        </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btnFiltrarRegistro">Filtrar</button>
        </div>
    </div>
    </div>
</div>