<?php foreach ($formapago as $formapago) {}?>
<div class="br-pagetitle"></div>

<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
        <form id="save_formapago" finish="admin/save_formapago">
            <div class="row mg-b-25">

            <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Nombre: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="fp_id" value="<?=$formapago->fp_id?>">
                  <input class="form-control" type="text" name="fp_nombre" value="<?=$formapago->fp_nombre?>" autocomplete="off" placeholder="Nombre">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Descripcion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="fp_descripcion" value="<?=$formapago->fp_descripcion?>" autocomplete="off" placeholder="Descripcion">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Cuenta Contable: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" id="codigo_contableby" attr="<?=$attr?>" param="<?=$param?>" name="fp_cuenta_contable" value="<?=$cuenta?>" autocomplete="off" placeholder="Cuenta Contable">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Proceso: <span class="tx-danger">*</span></label>
                  <select name="fp_proceso" id="fp_proceso" class="form-control select2">
                    <optgroup label="Seleccionado">
                        <option value="<?=$formapago->fp_proceso?>"><?=$formapago->fp_proceso?></option>
                    </optgroup>
                    <optgroup label="Cambiar por...">
                        <option value="Ingreso">Ingreso</option>
                        <option value="Venta">Venta</option>
                        <option value="Contabilidad">Contabilidad</option>
                    </optgroup>
                  </select>
                </div>
              </div><!-- col-12 -->

            </div>
            </div>
        </form>
        <button class="btn btn-info" id="send" onclick="sendForm('save_formapago')">Agregar</button>
        <a href="#admin/formapago" class="btn btn-secondary">Cancelar</a>
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