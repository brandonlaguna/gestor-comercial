    <div class="br-pagetitle"></div>

      <div class="br-pagebody">
        <div class="br-section-wrapper">
          <div class="form-layout form-layout-1">
          <form id="asave_centro_costos" finish="admin/asave_centro_costos">
            <div class="row mg-b-25">
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Nombre del centro de costos: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="idcentro" value>
                  <input class="form-control" type="text" name="cc_nombre" placeholder="Nombre del centro de costos">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Departamento: <span class="tx-danger">*</span></label>
                  <select class="form-control select2-show-search" data-placeholder="Elige el departamento" name="cc_departamento">
                    <option label="Elige el departamento"></option>
                    <?php foreach ($departamentos as $departamento) { ?>
                    <option value="<?=$departamento->dp_id?>"><?=$departamento->dp_nombre?></option>
                    <?php }?>
                  </select>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Ciudad: <span class="tx-danger">*</span></label>
                  <select class="form-control select2-show-search" data-placeholder="Elige una ciudad" name="cc_ciudad">
                    <option label="Elige una ciudad"></option>
                    <?php foreach ($municipios as $municipio) { ?>
                    <option value="<?=$municipio->mn_id?>"><?=$municipio->mn_nombre?></option>
                    <?php }?>
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