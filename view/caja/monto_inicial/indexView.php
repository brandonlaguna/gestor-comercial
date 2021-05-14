<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="form-layout form-layout-1">
            <form id="form_monto" finish="caja/form_monto_inicial">
                <div class="row mg-b-25">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">Monto inicial: <span class="tx-danger">*</span></label>
                            <input class="form-control" type="number" name="monto" value="" placeholder="Monto inicial">
                        </div>
                    </div><!-- col-4 -->

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">Fecha: <span class="tx-danger">*</span></label>
                            <input class="form-control" type="date" name="fecha_monto">
                        </div>
                    </div><!-- col-4 -->

                </div><!-- row -->
            </form>

            <div class="form-layout-footer">
                <button class="btn btn-info" id="send" onclick="sendForm('form_monto')">Agregar</button>
                <a href="#dashboard" class="btn btn-secondary">Cancelar</a>
            </div>

        </div>
    </div>
</div>
