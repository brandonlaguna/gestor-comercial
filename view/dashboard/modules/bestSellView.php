
<?php
$i=1;
foreach ($bestSell as $articulo) {?>
<div class="col-sm-6 col-lg-4">
            <div class="card shadow-base bd-0">
              <div id="carousel1" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#carousel1" data-slide-to="0" class="active"></li>
                </ol>

                <div class="carousel-inner" role="listbox">
                  <div class="carousel-item active">
                    <div class="bg-white ht-300 pos-relative overflow-hidden d-flex flex-column align-items-start rounded">
                      <div class="pos-absolute t-15 r-25">
                        
                      </div>
                      <div class="pd-x-30 pd-t-30 mg-b-auto">
                        <p class="tx-info tx-uppercase tx-11 tx-semibold tx-mont mg-b-5">Producto mas vendido #<?=$i?></p>
                        <h5 class="tx-inverse mg-b-20"><?=$articulo->nombre?></h5>
                        <p class="tx-uppercase tx-11 tx-semibold tx-mont mg-b-0">Ventas</p>
                        <h2 class="tx-inverse tx-lato tx-bold mg-b-0"><?=$articulo->cantidad_venta?></h2>
                        <span><?=$articulo->cantidad_venta/date("m")?> Ventas/por mes</span>
                      </div>
                      
                    </div><!-- d-flex -->
                  </div>

                </div><!-- carousel-inner -->
              </div><!-- carousel -->
            </div><!-- card -->
          </div><!-- col-4 -->
<?php $i++;}?>