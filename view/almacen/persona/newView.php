<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
    <form id="save_tercero" finish="almacen/save_tercero">
            <div class="row mg-b-25">

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Nombre del tercero: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="nombre_tercero" value="" autocomplete="off" placeholder="Nombre del tercero">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Tipo de documento: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Tipo de docuento" name="tipo_documento">
                    <?php foreach ($documentos as $documentos) {?>
                        <option value="<?=$documentos->idtipo_documento?>"><?=$documentos->nombre?></option>
                    <?php } ?>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Numero de documento: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="numero_documento" value="" autocomplete="off" placeholder="Numero de documento">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Tipo de tercero: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" name="tipo_persona">
                  <option value="Cliente">Cliente</option>
                  <option value="Proveedor">Proveedor</option>
                  <option value="Proveedor">Otro</option>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de regimen: <span class="tx-danger"></span></label>
                  <select class="form-control select2" name="tipo_regimen" >
                  <?php foreach ($tipo_regimen as $tipo_regimen) {?>
                  <option value="<?=$tipo_regimen->idtipo_regimen?>"><?=$tipo_regimen->tr_nombre?></option>
                  <?php }?>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de organizacion: <span class="tx-danger"></span></label>
                  <select class="form-control select2" name="tipo_organizacion" id="">
                  <?php foreach ($tipo_organizacion as $tipo_organizacion) {?>
                  <option value="<?=$tipo_organizacion->idtipo_organizacion?>"><?=$tipo_organizacion->to_nombre?></option>
                  <?php }?>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Responsabilidades Fiscales: <span class="tx-danger"></span></label>
                  <select class="form-control select2" name="tipo_organizacion" id="" name="retenciones[]" multiple>
                  <?php foreach ($fiscales as $fiscales) {?>
                    <option value="<?=$fiscales->idresp_fiscales?>"><?=$fiscales->rf_nombre?></option>
                  <?php }?>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Departamento: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Departamento" name="direccion_departamento">
                    <?php foreach ($departamentos as $departamentos) {?>
                        <option value="<?=$departamentos->dp_nombre?>"><?=$departamentos->dp_nombre?></option>
                    <?php } ?>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Ciudad: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Ciudad" name="direccion_provincia">
                    <?php foreach ($municipios as $municipios) {?>
                        <option value="<?=$municipios->mn_nombre?>"><?=$municipios->mn_nombre?></option>
                    <?php } ?>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Direccion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="direccion_calle" value="" autocomplete="off" placeholder="Direccion ej: Cra 123-456">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Felefono: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="telefono" value="" autocomplete="off" placeholder="Telefono">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Email: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="email" value="" autocomplete="off" placeholder="Email">
                </div>
              </div><!-- col-6 -->

              </form>

            </div><!-- row -->
            <div class="form-layout-footer">
            <button class="btn btn-info" id="send" onclick="sendForm('save_tercero')">Agregar</button>
              <a href="#almacen/terceros" class="btn btn-secondary" data-dismiss="modal" onclick="get_clients()">Cancelar</a>
            </div>
          </div>
    </div>
</div>

<script src="lib/select2/js/select2.min.js"></script>

<script>
      $(function(){

        'use strict' 
        if($().select2) {
    $('.select2').select2({
      minimumResultsForSearch: Infinity,
      placeholder: 'Selecciona uno o varios'
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

    $('.fc-datepicker').datepicker({
        
        showOtherMonths: true,
        selectOtherMonths: true
    });

      });
    </script>