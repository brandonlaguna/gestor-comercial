<div class="card">
              <div class="card-header">
                <h6 class="card-title">Ventas generales</h6>
                <a href=""><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="card-body">
                <span id="spark4">26,30,25,28,23,27,21,23,28,25</span>
                <span>$<?=number_friendly($total["total"])?></span>
              </div><!-- card-body -->
              <div class="card-footer">
                <div>
                  <span class="tx-11">Total bruto</span>
                  <h6 class="tx-inverse">$<?=number_friendly($total["total_bruto"])?></h6>
                </div>
                <div>
                  <span class="tx-11">ventas</span>
                  <h6 class="tx-inverse"><?=$total["total_ventas"]?></h6>
                </div>
                <div>
                  <span class="tx-11">Impuestos</span>
                  <h6 class="tx-danger">$<?=number_friendly($total["total_impuestos"])?></h6>
                </div>
              </div><!-- card-footer -->
            </div><!-- card -->