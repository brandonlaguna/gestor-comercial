<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="form-layout form-layout-1">
            <form id="save_comprobante" finish="admin/save_comprobante">
            <div class="row mg-b-25">

                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Documento: <span class="tx-danger">*</span></label>
                        <input type="hidden" name="idcomprobante">
                        <select class="form-control select2" data-placeholder="" name="documento">
                        <?php foreach ($documentos as $documentos) { ?>
                            <option value="<?=$documentos->idtipo_documento?>"><?=$documentos->nombre?></option>
                        <?php }?>
                    </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Serie Comprobante: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="serie" value="" placeholder="Numero o letra serie del comprobante">
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Consecutivo: <span class="tx-danger"></span></label>
                        <input class="form-control" type="text" name="consecutivo" value="" placeholder="Para iniciar consecutivo use el numero '0' cero">
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Afecta Contabilidad?: <span class="tx-danger"></span></label>
                        <select class="form-control select2" data-placeholder="Choose Browser" name="contabilidad">
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Impuestos: <span class="tx-danger">*</span></label>
                        <select class="form-control select2" data-placeholder="Choose Browser" name="impuestos[]" multiple>
                            <?php foreach ($impuestos as $impuestos) { ?>
                                <option value="<?=$impuestos->im_id?>"><?=$impuestos->im_nombre?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Retenciones: <span class="tx-danger">*</span></label>
                        <select class="form-control select2" data-placeholder="Choose Browser" name="retenciones[]" multiple>
                            <?php foreach ($retenciones as $retenciones) { ?>
                                <option value="<?=$retenciones->re_id?>"><?=$retenciones->re_nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div><!-- col-4 -->

                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Tipo de formato: <span class="tx-danger">*</span></label>
                        <select id="conf_print" class="form-control select2" name="conf_print">
                            <?php foreach ($conf_print as $impresion) { ?>
                                <option value="<?=$impresion->pri_id?>"><?=$impresion->pri_nombre?></option>
                            <?php }?>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-control-label">Resolucion de la factura: <span class="tx-danger">*</span></label>
                        <textarea rows="5" class="form-control mg-t-20" placeholder="Salto de linea '|' Barra vertical" name="resolucion"></textarea>
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