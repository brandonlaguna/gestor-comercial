    <div class="row">
        <div class="col-6 col-md-6">
                <p class=""><b class="text-success">Subtotal:</b></p>
        </div>
        
        <div class="col-6 col-md-6" style="text-align:right; font-size:16;">
                <p><?=$total_bruto?></p>
        </div>

        <div class="col-6 col-md-6">
            Impuestos:
        </div>

        <div class="col-6 col-md-6" style="text-align:right; font-size:14;">
            <?php foreach ($impuestos as $impuestos) {?>
                <p class=""><b class="text-success"><?=$impuestos[0]?>:</b> <?=number_format($impuestos[1])?> <span class="fas fa-minus text-danger" style="cursor:pointer;" onclick="removeColaImpuesto('<?=$impuestos[2]?>')"></span><p>
            <?php }?>
        </div>

        <div class="col-6 col-md-6">
            Retenciones:
        </div>

        <div class="col-6 col-md-6" style="text-align:right; font-size:14;">
            <?php foreach ($retenciones as $retenciones) { ?>
                <p class=""><b class="text-success"><?=$retenciones[0]?>:</b> <?=number_format($retenciones[1])?> <span class="fas fa-minus text-danger" style="cursor:pointer;" onclick="removeColaRetencion('<?=$retenciones[2]?>')"></span></p>
            <?php }?>
        </div>

        <div class="col-6 col-md-6" style="text-align:left;font-size:24;">
            <p class=""><b class="text-success">Total Neto:</b></p>
        </div>
            
        <div class="col-6 col-md-6" style="text-align:right;font-size:24;">
            <p><?=number_format($total_neto)?></p>
        </div>
        
    </div>