<div class="br-pagetitle"></div>

<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
        <form id="save_impuesto" finish="admin/save_impuesto">
            <div class="row mg-b-25">

            <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Nombre del impuesto: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="idimpuesto" value>
                  <input class="form-control" type="text" name="im_nombre" value="" autocomplete="off" placeholder="Nombre del impuesto">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Porcentaje del impuesto: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="im_porcentaje" value="" autocomplete="off" placeholder="Porcentaje del impuesto">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Base del impuesto: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="im_base" value="" autocomplete="off" placeholder="Base del impuesto">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Codigo Contable: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="cta_contable" value="" autocomplete="off" placeholder="Base del impuesto">
                </div>
              </div><!-- col-12 -->
            </div>
            </div>
        </form>
        <button class="btn btn-info" id="send" onclick="sendForm('save_impuesto')">Agregar</button>
        <a href="#admin/impuestos" class="btn btn-secondary">Cancelar</a>
    </div>
    </div>
</div>