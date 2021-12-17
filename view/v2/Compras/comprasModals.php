<div id="modaldemo2" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea anular esta compra?</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <p class="mg-b-5" id="messageFinalModal"
                    placeholder="Esta compra esta relacionado a registros, por lo tanto solo puede ser anulada">Esta
                    compra esta relacionado a registros, por lo tanto solo puede ser anulada</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="sendIdModal"
                    class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium"
                    tread="messageFinalModal" controller="" onclick="sendModal()">Si anular</a>
                    <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium"
                        data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
            </div>
        </div>
    </div><!-- modal-dialog -->
</div>