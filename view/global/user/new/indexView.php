<div class="br-pagetitle"></div>
    <div class="br-pagebody">
      <div class="br-section-wrapper">
      <form id="addUser" finish="Configuracion/save_user">
      <div class="form-layout form-layout-1">
            <div class="row mg-b-25">
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Empleado: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="idsuscripcion" value="<?=$idsuscripcion?>">
                  <input type="hidden" name="nombre_empleado" id="nombre_empleado" value="">
                  <select class="form-control select2" name="empleado" id="empleado">
                  <?php foreach($empleados as $empleado) {?>
                    <option value="<?=$empleado->idempleado?>"><?=$empleado->nombre." ".$empleado->apellidos?></option>
                  <?php }?>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Sucursal: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" name="idsucursal" id="idsucursal">
                    <?php foreach ($sucursales as $sucursal) {?>
                    <option value="<?=$sucursal->idsucursal?>"><?=$sucursal->razon_social?></option>
                    <?php }?>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Permisos: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" name="type_user" id="type_user">
                  <?php foreach ($permisos as $permiso){?>
                    <option value="<?=$permiso->jt_id?>"><?=$permiso->jt_id?></option>
                  <?php }?>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Email: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="email" id="email" value="" >
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Clave secreta: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="password" name="password" id="password" value="" >
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Repetir Clave secreta: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="password" name="password2" id="password2" value="" >
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Avatar: <span class="tx-danger">*</span></label>
                  <img src="" class="img-fluid img-thumbnail" alt="" width="100px" id="avatar">
                  <input type="hidden" name="avatar" id="avatar_s" value="">
                </div>
              </div><!-- col-4 -->

              
            </div><!-- row -->
            </form>
          </div><!-- form-layout -->
          <button class="btn btn-info" onclick="sendForm('addUser')">Crear cuenta</button>
            <a href="#configuracion/usuarios" class="btn btn-secondary">Salir</a>
      </div>
    </div>
<script src="controller/script/ConfiguracionController.js"></script>
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

    $('.fc-datepicker').datepicker({
        
        showOtherMonths: true,
        selectOtherMonths: true
    });

      });
    </script>