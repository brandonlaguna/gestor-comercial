<div class="container-fluid mt-4 ">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-9">
        </div>
        <div class="col-sm-12 col-md-4 col-lg-3 " style="text-align:left;">
        <p class=""><b class="text-success">Total Bruto:</b> <?=number_format($total_bruto)?></p>
            <?php foreach ($impuestos as $impuestos) {?>
                <p class=""><b class="text-success"><?=$impuestos[0]?>:</b> <?=number_format($impuestos[1])?><p>
            <?php }?>
            <?php foreach ($retenciones as $retenciones) { ?>
                <p class=""><b class="text-success"><?=$retenciones[0]?>:</b> <?=number_format($retenciones[1])?></p>
            <?php }?>
            <p class=""><b class="text-success">Total Neto:</b><?=number_format($total_neto)?></p>
        </div>
    </div>
</div>