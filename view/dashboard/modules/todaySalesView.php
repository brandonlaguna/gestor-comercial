
<div class="card">
    <div class="card-header">
        <h6 class="card-title">Ventas de hoy</h6>
        <a href=""><i class="icon ion-android-more-horizontal"></i></a>
    </div><!-- card-header -->
    <div class="card-body">
        <span id="spark1">5,3,9,6,5,9,7,3,5,2,5,2,4,6</span>
        <span>$<?=number_format($total["total"])?></span>
    </div><!-- card-body -->
    <div class="card-footer">
        <div>
            <span class="tx-11">Total bruto</span>
            <h6 class="tx-inverse">$<?=number_format($total["total_bruto"])?></h6>
        </div>
    <div>
        <span class="tx-11">Cantidad de articulos</span>
        <h6 class="tx-inverse"><?=$total["total_articulo"]?></h6>
    </div>
    <div>
        <span class="tx-11">Impuestos</span>
        <h6 class="tx-danger">$<?=number_format($total["total_impuestos"])?></h6>
    </div>
    </div><!-- card-footer -->
</div><!-- card -->