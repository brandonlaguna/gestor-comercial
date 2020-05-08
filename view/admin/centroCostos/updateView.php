<?php 
foreach ($centro as $centro) {}
?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
  <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
    <form id="asave_centro_costos" finish="admin/asave_centro_costos">
      <div class="row mg-b-25">
        <div class="col-lg-4">
          <div class="form-group">
            <label class="form-control-label">Nombre del centro de costos: <span class="tx-danger">*</span></label>
            <input type="hidden" name="idcentro" value="<?=$centro->cc_id?>">
            <input class="form-control" type="text" name="cc_nombre" value="<?=$centro->cc_nombre?>" placeholder="Nombre del centro de costos">
          </div>
        </div><!-- col-4 -->
        <div class="col-lg-4">
          <div class="form-group mg-b-10-force">
            <label class="form-control-label">Departamento: <span class="tx-danger">*</span></label>
            <select class="form-control select2-show-search" data-placeholder="Elige el departamento" name="cc_departamento">
              <optgroup label="Sleccionado...">
                <option value="<?=$centro->dp_id?>" selected><?=$centro->dp_nombre?></option>
              </optgroup>
              <optgroup label="Cambiar por...">
                    <?php foreach ($departamentos as $departamento) { ?>
                <option value="<?=$departamento->dp_id?>"><?=$departamento->dp_nombre?></option>
              </optgroup>
              <?php }?>
            </select>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="form-group mg-b-10-force">
            <label class="form-control-label">Ciudad: <span class="tx-danger">*</span></label>
            <select class="form-control select2-show-search" data-placeholder="Elige una ciudad" name="cc_ciudad">
              <optgroup label="Seleccionado...">
                    <option value="<?=$centro->mn_id?>"><?=$centro->mn_nombre?></option>
              </optgroup>
              <optgroup label="Cambiar por...">
                <?php foreach ($municipios as $municipio) { ?>
                    <option value="<?=$municipio->mn_id?>"><?=$municipio->mn_nombre?></option>
                <?php }?>
              </optgroup>
            </select>
          </div>
        </div>
      </div>
      </form>
        <button class="btn btn-info" onclick="sendForm('asave_centro_costos')">Agregar</button>
        <a href="#admin/centro_costos"><button class="btn btn-secondary">Cancelar</button></a>
  
    </div>

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