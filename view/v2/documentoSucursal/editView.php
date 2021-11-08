<?php foreach ($documentoSucursal as $documentoSucursal) {}?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="form-layout form-layout-1">
            <form id="save_documento_sucursal" finish="admin/save_documento_sucursal">
            <div class="row mg-b-25">

                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Documento: <span class="tx-danger">*</span></label>
                        <input type="hidden" name="iddocumento_sucursal" value="<?=$documentoSucursal['iddetalle_documento_sucursal']?>">
                        <select class="form-control select2" data-placeholder="" name="documento" >
                            <option value="<?=$documentoSucursal['idtipo_documento']?>"><?=$documentoSucursal['nombre_tipo_documento']?></option>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Serie comprobante: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="serie" value="<?=$documentoSucursal['ultima_serie']?>" placeholder="Numero o letra serie del Comprobante" >
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Consecutivo: <span class="tx-danger"></span></label>
                        <input class="form-control" type="text" name="consecutivo" value="<?=zero_fill($documentoSucursal['ultimo_numero'],8)?>" placeholder="Se recomienda el numero '0' ">
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Afecta Contabilidad?: <span class="tx-danger"></span></label>
                        <select class="form-control select2" data-placeholder="Choose Browser" name="contabilidad">
                            <optgroup label="Seleccionado...">
                                <?php $estado = ($documentoSucursal['contabilidad'] >0 )?"SI":"NO"?>
                                <option value="<?=$documentoSucursal['contabilidad']?>"><?=$estado?></option>
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
                        <label class="form-control-label">Tipo de formato: <span class="tx-danger">*</span></label>
                        <select id="conf_print" class="form-control select2" name="conf_print">
                            <optgroup label="Seleccionado...">
                                <option value="<?=$documentoSucursal['pri_id']?>"><?=$documentoSucursal['pri_nombre']?></option>
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
                        <textarea rows="5" class="form-control" placeholder="Salto de linea '|' Barra vertical" name="resolucion"><?=$documentoSucursal['pf_text']?></textarea>
                    </div>
                </div><!-- col-4 -->
                
                
            </div>
            </form>
            </div>
            <div class="form-layout-footer mt-4">
                    <button class="btn btn-info" id="send" onclick="sendForm('save_documento_sucursal')">Agregar</button>
                    <a href="#documentoSucursal/documentoSucursal" class="btn btn-secondary">Cancelar</a>
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