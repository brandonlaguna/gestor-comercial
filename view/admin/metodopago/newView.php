<div class="br-pagetitle"></div>

<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
        <form id="save_metodo_pago" finish="admin/save_metodo_pago" enctype="multipart/form-data">
            <div class="row mg-b-25">

            <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Nombre: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="mp_id" value>
                  <input class="form-control" type="text" name="mp_nombre" value="" autocomplete="off" placeholder="Nombre">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Descripcion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="mp_descripcion" value="" autocomplete="off" placeholder="Descripcion">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Cuenta Contable: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" id="codigo_contableby" attr="<?=$attr?>" param="<?=$param?>" name="mp_cuenta_contable" value="" autocomplete="off" placeholder="Cuenta Contable">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Icono: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="file" id="mp_image" name="mp_image">
                </div>
              </div><!-- col-12 -->

            </div>
            </div>
        </form>
        <button class="btn btn-info" id="send" onclick="sendForm('save_metodo_pago')">Agregar</button>
        <a href="#admin/metodopago" class="btn btn-secondary">Cancelar</a>
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