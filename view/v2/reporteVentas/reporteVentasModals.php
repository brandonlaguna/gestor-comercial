<!-- Modal Reporte por sucursal-->
<div class="modal fade" id="modalFiltroReporte" tabindex="-1" role="dialog"
    aria-labelledby="modalFiltroReporteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Filtrar reporte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                            <?=$this->component('formSelect',[
                                'title'     => 'Filtrar por sucursales',
                                'class'     => 'selectSucursal',
                                'name'      => 'filtroSucursal[]',
                                'id'        => 'filtroSucursal',
                                'multiple'  => true,
                                'style'     => [
                                    'width'         =>  '100%',
                                    'height'        => 'auto'
                                ],
                                'items'     => $sucursales,
                                'selected'  => 0,
                                'col'       => 'col-sm-12 col-md-4 col-lg-4'
                            ])?>

                            <?=$this->component('formSelect',[
                                'title'     => 'Filtrar por comprobantes',
                                'class'     => 'form-control selectComprobante',
                                'name'      => 'filtroComprobante[]',
                                'id'        => 'filtroComprobante',
                                'multiple'  => true,
                                'style'     => [
                                    'width'         =>  '100%',
                                    'height'        => 'auto'
                                ],
                                'items'     => [],
                                'selected'  => 0,
                                'col'       => 'col-sm-12 col-md-4 col-lg-4'
                            ])?>

                            <?=$this->component('formSelect',[
                                'title'     => 'Filtrar Tipo de pago',
                                'class'     => 'form-control selectTipoPago',
                                'name'      => 'filtroTipoPago[]',
                                'id'        => 'filtroTipoPago',
                                'style'     => [
                                    'width'         =>  '100%',
                                    'height'        => 'auto'
                                ],
                                'items'     => [
                                    ['item_id'=>'Credito', 'item_name'=> 'Credito'],
                                    ['item_id'=>'Contado', 'item_name'=> 'Contado']
                                ],
                                'selected'  => 0,
                                'col'       => 'col-sm-12 col-md-4 col-lg-4'
                            ])?>

                            <?=$this->component('formSelect',[
                                'title'     => 'Filtrar por estado',
                                'class'     => 'form-control selectEstado',
                                'name'      => 'filtroEstado[]',
                                'id'        => 'filtroEstado',
                                'style'     => [
                                    'width'         =>  '100%',
                                    'height'        => 'auto'
                                ],
                                'items'     => [
                                    ['item_id'=>'A', 'item_name'=> 'Activo'],
                                    ['item_id'=>'D', 'item_name'=> 'Cancelado'],
                                    ['item_id'=>'P', 'item_name'=> 'Pendiente'],
                                ],
                                'multiple'  => true,
                                'selected'  => 0,
                                'col'       => 'col-sm-12 col-md-4 col-lg-4'
                            ])?>

                            <?=$this->component('formSelect',[
                                'title'     => 'Buscar clientes',
                                'class'     => 'form-control selectCliente',
                                'name'      => 'filtroCliente[]',
                                'id'        => 'filtroCliente',
                                'items'     => $clientes,
                                'multiple'  => true,
                                'selected'  => 0,
                                'col'       => 'col-sm-12 col-md-4 col-lg-4',
                                'style'     => [
                                    'width'         =>  '100%',
                                    'height'        => 'auto'
                                ],
                                'attr'      => [
                                    'data-live-search' => true
                                ]
                            ])?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="filtrarReportePersonalizado()">Filtrar</button>
            </div>
        </div>
    </div>
</div>

