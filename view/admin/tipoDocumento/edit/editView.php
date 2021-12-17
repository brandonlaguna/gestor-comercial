<?php foreach ($documento as $documento) {
    # code...
}?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="form-layout form-layout-1">
            <form id="save_documento" finish="admin/save_documento">
            <div class="row mg-b-25">
                <div class="col-lg-12">
                    <div class="form-group">
                    <label class="form-control-label">Nombre: <span class="tx-danger">*</span></label>
                    <input type="hidden" name="iddocumento" id="iddocumento" value="<?=$iddocumento?>">
                    <input class="form-control" type="text" name="nom_documento" value="<?=$documento->nombre?>" placeholder="Nombre del documento">
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label">Prefijo: <span class="tx-danger">*</span></label>
                    <input class="form-control" type="text" name="prefijo" value="<?=$documento->prefijo?>" placeholder="Prefijo del documento ej:Prf">
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label">Operacion: <span class="tx-danger"></span></label>
                        <select class="form-control select2" data-placeholder="" name="operacion">
                                <optgroup label="Seleccionado">
                                    <option value="<?=$documento->operacion?>"><?=$documento->operacion?></option>
                                </optgroup>
                                <optgroup label="Elige uno">
                                    <?php foreach ($operacion as $operacion) { ?>
                                        <option value="<?=$operacion?>"><?=$operacion?></option>
                                    <?php }?>
                                    <option value="">Ninguno</option>
                                </optgroup>
                        </select>
                    </div>
                </div><!-- col-4 -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-control-label">Proceso: <span class="tx-danger">*</span></label>
                        <select class="form-control select2" data-placeholder="" name="proceso">
                                <optgroup label="Seleccionado">
                                    <option value="<?=$documento->proceso?>"><?=$documento->proceso?></option>
                                </optgroup>
                                <optgroup label="Elige uno">
                                    <?php foreach ($proceso as $proceso) { ?>
                                        <option value="<?=$proceso?>"><?=$proceso?></option>
                                    <?php }?>
                                    <option value="">Ninguno</option>
                                </optgroup>
                        </select>
                    </div>
                </div><!-- col-4 -->
                </form>
            </div>
            </div>
            <div class="form-layout-footer">
                    <button class="btn btn-info" id="send" onclick="sendForm('save_documento')">Actualizar</button>
                    <a href="#admin/tipo_documento" class="btn btn-secondary">Cancelar</a>
                </div>
            
        </div>
    </div>
</div>