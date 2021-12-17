<!-- comprobante
impuestos
retenciones -->
<?php foreach ($comprobante as $comprobante) {}?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="form-layout form-layout-1">
            <form id="save_comprobante" finish="admin/save_comprobante">
            <div class="row mg-b-25">

                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Documento: <span class="tx-danger">*</span></label>
                        <input type="hidden" name="idcomprobante" value="<?=$comprobante->iddetalle_documento_sucursal?>">
                        <select class="form-control select2" data-placeholder="" name="documento" >
                            <option value="<?=$comprobante->idtipo_documento?>"><?=$comprobante->nombre?></option>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Serie Comprobante: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="serie" value="<?=$comprobante->ultima_serie?>" placeholder="Numero o letra serie del comprobante" >
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Consecutivo: <span class="tx-danger"></span></label>
                        <input class="form-control" type="text" name="consecutivo" value="<?=zero_fill($comprobante->ultimo_numero,8)?>" placeholder="Se recomienda el numero '0' ">
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Afecta Contabilidad?: <span class="tx-danger"></span></label>
                        <select class="form-control select2" data-placeholder="Choose Browser" name="contabilidad">
                            <optgroup label="Seleccionado...">
                                <?php $estado = ($comprobante->contabilidad >0 )?"SI":"NO"?>
                                <option value="<?=$comprobante->contabilidad?>"><?=$estado?></option>
                            </optgroup>
                            <optgroup label="Cambiar por...">
                                <option value="0">NO</option>
                                <option value="1">SI</option>
                            </optgroup>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Impuestos: <span class="tx-danger">*</span></label>
                        <select class="form-control select2" data-placeholder="Choose Browser" name="impuestos[]" multiple>
                            <?php foreach ($impuestos as $impuesto) { 
                                   ?>
                                <option value="<?=$impuesto[1]?>" <?=$impuesto[0]?>><?=$impuesto[2]?></option>
                            <?php  }?>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Retenciones: <span class="tx-danger">*</span></label>
                        <select class="form-control select2" data-placeholder="Choose Browser" name="retenciones[]" multiple>
                            <?php foreach ($retenciones as $retencion) { ?>
                                <option value="<?=$retencion[1]?>" <?=$retencion[0]?>><?=$retencion[2]?></option>
                            <?php }?>
                        </select>
                    </div>
                </div><!-- col-4 -->

                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Tipo de formato: <span class="tx-danger">*</span></label>
                        <select id="conf_print" class="form-control select2" name="conf_print">
                            <optgroup label="Seleccionado...">
                                <option value="<?=$comprobante->pri_id?>"><?=$comprobante->pri_nombre?></option>
                            </optgroup>
                            <optgroup label="Cambiar por...">
                                <?php foreach ($conf_print as $impresion) { ?>
                                    <option value="<?=$impresion->pri_id?>"><?=$impresion->pri_nombre?></option>
                                <?php }?>
                            </optgroup>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Resolucion de la factura: <span class="tx-danger">*</span></label>
                        <textarea rows="5" class="form-control" placeholder="Salto de linea '|' Barra vertical" name="resolucion"><?=$comprobante->pf_text?></textarea>
                    </div>
                </div><!-- col-4 -->
                
                
            </div>
            </form>
            </div>
            <div class="form-layout-footer mt-4">
                    <button class="btn btn-info" id="send" onclick="sendForm('save_comprobante')">Agregar</button>
                    <a href="#admin/conf_comprobante" class="btn btn-secondary">Cancelar</a>
                </div>
            
        </div>
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

      });
    </script>