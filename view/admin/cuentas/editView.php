<?php foreach ($cuenta as $cuenta) {}
?>

<div class="br-pagetitle">
<div class="br-pagebody">
        <div class="br-section-wrapper">
        <form id="update_cuenta" finish="contables/update_cuenta">
        <div class="form-layout form-layout-1">
            <div class="row mg-b-25">

              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Cuenta: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="idcodigo" value="<?=$cuenta->idcodigo?>">
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-7">
                <div class="form-group">
                  <label class="form-control-label">Descripcion: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="tipo_codigo" value="<?=$cuenta->tipo_codigo?>" >
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Estado: <span class="tx-danger"></span></label>
                  <select name="estado_puc" id="" class="form-group select2">
                  <?php
                    $estado = ($cuenta->estado_puc == "A")?"Activo":"Inactivo";
                  ?>
                    <optgroup label="Seleccionado">
                      <option value="<?=$cuenta->estado_puc?>">Estado <?=$estado?></option>
                    </optgroup>
                    <optgroup label="Cambiar por">
                      <option value="A">Estado Activo</option>
                      <option value="D">Estado Inactivo</option>
                    </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label"> <span class="tx-danger"></span></label>
                    <label class="ckbox">
                        <input type="checkbox" name="movimiento" <?=check($cuenta->movimiento)?>>
                        <span>Movimiento</span>
                    </label>  
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                <label class="form-control-label"> <span class="tx-danger"></span></label>
                    <label class="ckbox">
                        <input type="checkbox" name="terceros" <?=check($cuenta->terceros)?>>
                        <span>Terceros</span>
                    </label>  
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-2">
                <div class="form-group">
                <label class="form-control-label"> <span class="tx-danger"></span></label>
                    <label class="ckbox">
                        <input type="checkbox" name="centro_costos" <?=check($cuenta->centro_costos)?>>
                        <span>Centro de costos</span>
                    </label>  
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-2">
                <div class="form-group">
                <label class="form-control-label"> <span class="tx-danger"></span></label>
                    <label class="ckbox">
                        <input type="checkbox" name="impuesto" <?=check($cuenta->impuesto)?>>
                        <span>Impuesto</span>
                    </label>  
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-2">
                <div class="form-group">
                <label class="form-control-label"> <span class="tx-danger"></span></label>
                    <label class="ckbox">
                        <input type="checkbox" name="retencion" <?=check($cuenta->retencion)?>>
                        <span>Retencion</span>
                    </label>  
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-2">
                <div class="form-group">
                <label class="form-control-label"> <span class="tx-danger"></span></label>
                    <label class="ckbox">
                        <input type="checkbox" name="c_cobrar" <?=check($cuenta->c_cobrar)?>>
                        <span>Cta. Cobrar</span>
                    </label>  
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-2">
                <div class="form-group">
                <label class="form-control-label"> <span class="tx-danger"></span></label>
                    <label class="ckbox">
                        <input type="checkbox" name="c_pagar" <?=check($cuenta->c_pagar)?>>
                        <span>Cta. pagar</span>
                    </label>  
                </div>
              </div><!-- col-4 -->

            </div><!-- row -->
            </form>
          </div><!-- form-layout -->

            <button class="btn btn-info" onclick="sendForm('update_cuenta')">Enviar</button>
              <a href="#contables" class="btn btn-secondary">Salir</a>
        </div>
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

    $('.fc-datepicker').datepicker({
        
        showOtherMonths: true,
        selectOtherMonths: true
    });

      });
    </script>