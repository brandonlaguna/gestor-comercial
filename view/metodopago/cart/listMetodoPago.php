<div class="row">
    <?php foreach ($listaMetodo as $metodoPago) {?>
        <div class="col-sm-12 col-lg-12">
            <div class="form-group">
                <label class="form-control-label"><?=$metodoPago->mp_nombre?>: <span class="tx-danger">*</span></label>
                <input class="form-control" type="text" name="<?=$metodoPago->mp_id?>" value="" id="" placeholder="Monto para metodo depago <?=$metodoPago->mp_nombre?>" autocomplete="off">
            </div>
        </div>
<?php } ?>
</div>