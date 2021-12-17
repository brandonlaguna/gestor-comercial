<form id="formMetodoPago" action="javascript:void(0);">
<div class="row">
    <?php foreach ($listaMetodo as $metodoPago) {?>
        <div class="col-sm-12 col-lg-12" >
            <div class="form-group">
                <label class="form-control-label"><?=$metodoPago['mp_nombre']?>: <span class="tx-danger">*</span></label>
                <input class="form-control setmetodopago" type="text" name="<?=$metodoPago['mp_id']?>" value="<?=$metodoPago['cdmp_monto']?>" id="<?=$metodoPago['mp_id']?>" placeholder="Monto para metodo de pago <?=$metodoPago['mp_nombre']?>" autocomplete="off">
            </div>
        </div>
<?php } ?>

        <div class="col-6 col-md-6" style="text-align:left;font-size:28;">
                <p class=""><b class="text-<?=$monto_properties["color"]?>"><?=$monto_properties["message"]?>:</b></p>
        </div>
        <div class="col-6 col-md-6" style="text-align:right;font-size:28;">
                <p class="text-<?=$monto_properties["color"]?>"><?=number_format($monto_properties["monto_estado"])?></p>
        </div>
</div>
</form>

<script src="controller/script/MetodoPagoController.js"></script>