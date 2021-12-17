<div class="card">
    <div class="card-header">
        <h6 class="card-title">Ventas de la semana</h6>
        <a href=""><i class="icon ion-android-more-horizontal"></i></a>
    </div><!-- card-header -->
    <div class="card-body">
        <span id="spark2">2,8,7,8,2,6,5,3,5,2</span>
        <span class="tx-medium tx-inverse tx-32">$<?=number_format($total["total"])?></span>
    </div><!-- card-body -->
    <div class="card-footer">
        <div>
            <span class="tx-11">Total Bruto</span>
            <h6 class="tx-inverse">$<?=number_format($total["total_bruto"])?></h6>
        </div>
    <div>
        <span class="tx-11">Ventas</span>
        <h6 class="tx-inverse"><?=$total["total_ventas"]?></h6>
    </div>
    <div>
        <span class="tx-11">Impuestos</span>
        <h6 class="tx-danger">$<?=number_format($total["total_impuestos"])?></h6>
    </div>
    </div><!-- card-footer -->
</div><!-- card -->