<?php foreach($cuenta as $cuenta){}?>
<div class="br-pagebody">
<div class="br-section-wrapper">
<div class="form-layout form-layout-1">
<form id="update_cuenta" finish="cuentas/save_update_cuenta">
            <div class="row mg-b-25">
              <div class="col-lg-4">
                <div class="form-group">
                    <input type="hidden" name="cu_id" value="<?=$cuenta->cu_id?>">
                  <label class="form-control-label">Nombre: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="cu_nombre" value="<?=$cuenta->cu_nombre?>" placeholder="">
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Codigo: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="cu_codigo" value="<?=$cuenta->cu_codigo?>" placeholder="">
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Caracteristica: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="cu_caracteristicas" value="<?=$cuenta->cu_caracteristicas?>" placeholder="">
                </div>
              </div><!-- col-4 -->
              
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Impuestos: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Elige un impuesto" name="cu_impuestos">
                      <optgroup label="Seleccionado">
                          <option value="<?=$cuenta->im_id?>"><?=$cuenta->im_nombre." - ".$cuenta->im_porcentaje."%"?></option>
                        </optgroup>
                        <optgroup label="Lista de Impuestos">
                            <?php foreach ($impuestos as $impuesto) { ?>
                            <option value="<?=$impuesto->im_id?>"><?=$impuesto->im_nombre." - ".$impuesto->im_porcentaje."%"?></option>
                            <?php }?>
                        </optgroup>
                        <option label="Choose one"></option>
                    </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Retenciones: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Elige una retencion" name="cu_retenciones">
                      <optgroup label="Seleccionado">
                          <option value="<?=$cuenta->re_id?>"><?=$cuenta->re_nombre." - ".$cuenta->re_porcentaje."%"?></option>
                        </optgroup>
                        <optgroup label="Lista de Retenciones">
                            <?php foreach ($retenciones as $retencion) { ?>
                            <option value="<?=$retencion->re_id?>"><?=$retencion->re_nombre." - ".$retencion->re_porcentaje."%"?></option>
                            <?php }?>
                        </optgroup>
                        <option label="Choose one"></option>
                    </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Centro de Costos: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Elige un centro de costos" name="cu_centro_costos">
                      <optgroup label="Seleccionado">
                          <option value="<?=$cuenta->cc_id?>"><?=$cuenta->cc_nombre?></option>
                        </optgroup>
                        <optgroup label="Lista de Retenciones">
                            <?php foreach ($centro_costos as $centro_costos) { ?>
                            <option value="<?=$centro_costos->cc_id?>"><?=$centro_costos->cc_nombre?></option>
                            <?php }?>
                        </optgroup>
                        <option label="Choose one"></option>
                    </select>
                </div>
              </div><!-- col-4 -->


              <div class="col-lg-4">
                <div class="form-group">
                    <label class="rdiobox">
                        <input name="cu_terceros" type="radio" <?=$check=($cuenta->cu_terceros ==1 || $cuenta->cu_terceros =="on")?"checked":""; ?>>
                            <span>Terceros?</span>
                        </label>
                </div>
              </div><!-- col-4 -->

            </div><!-- row -->
            </form>
            <div class="form-layout-footer">
              <button class="btn btn-info" id="send" onclick="sendForm('update_cuenta')">Actualizar</button>
              <a href="#usuarios/usuarios"><button class="btn btn-secondary">Cancelar</button></a>
            </div><!-- form-layout-footer -->
          </div>
          
</div>
</div>
</div>
<script type="text/javascript">
    $('.br-toggle').on('click', function(e){
            e.preventDefault();
            $(this).toggleClass('on');
        })
</script>