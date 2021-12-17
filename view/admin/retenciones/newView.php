<div class="br-pagetitle"></div>

<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
        <form id="save_retencion" finish="admin/save_retencion">
            <div class="row mg-b-25">

            <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Nombre de la retencion: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="idretencion" value>
                  <input class="form-control" type="text" name="re_nombre" value="" autocomplete="off" placeholder="Nombre de la retencion">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Porcentaje de retencion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="re_porcentaje" value="" autocomplete="off" placeholder="Porcentaje de la retencion">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Base de la retencion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="re_base" value="" autocomplete="off" placeholder="Base de la retencion">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Sobre IVA: <span class="tx-danger">*</span></label>
                    <select name="re_im_id" id="" class="form-control select2">
                        <?php foreach ($impuestos as $impuesto) {?>
                            <option value="<?=$impuesto->im_id?>"><?=$impuesto->im_nombre?></option>
                        <?php }?>
                    </select>
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Codigo Contable: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" id="codigo_contableby" attr="<?=$attr?>" param="<?=$param?>" name="re_cta_contable" value="" autocomplete="off" placeholder="Codigo contable">
                </div>
              </div><!-- col-12 -->
            </div>
            </div>
        </form>
        <div class="container-fluid mt-4">
        <button class="btn btn-info" id="send" onclick="sendForm('save_retencion')">Agregar</button>
        <a href="#admin/retenciones" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
    </div>
</div>
<script src="controller/script/puc.js"></script>
<script>
      $(function(){

        'use strict' 
        if($().select2) {
    $('.select2').select2({
      minimumResultsForSearch: Infinity,
      placeholder: 'Choose one'
    });

    // Select2 by showing the search
    $('.select2-show-search').select2({
      minimumResultsForSearch: ''
    });

    // Select2 with tagging support
    $('.select2-tag').select2({
      tags: true,
      tokenSeparators: [',', ' ']
    });
  }
  $('.br-toggle').on('click', function(e){
          e.preventDefault();
          $(this).toggleClass('on');
        });

      });
    </script>