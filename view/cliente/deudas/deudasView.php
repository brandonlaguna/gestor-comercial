
<div class="br-pagetitle"></div>
<div class="container-fluid">
<div class="row">
    <div class="col-sm-6 col-lg-4">
        <div class="card shadow-base bd-0">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="card-title tx-uppercase tx-12 mg-b-0">Cartera total</h6>
                <span class="tx-12 tx-uppercase"></span>
            </div><!-- card-header -->
            <div class="card-body d-xs-flex justify-content-between align-items-center">
                <h4 class="mg-b-0 tx-inverse tx-lato tx-bold"><?=number_format($deuda_total)?></h4>
                <p class="mg-b-0 tx-sm"><span class="tx-success"><i class="fa fa-arrow-up"></i> 34.32%</span> Desde la ultima semana</p>
            </div><!-- card-body -->
        </div><!-- card -->
    </div><!-- col-4 -->

    <div class="col-sm-6 col-lg-4">
        <div class="card shadow-base bd-0">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="card-title tx-uppercase tx-12 mg-b-0">Cartera pagada</h6>
                <span class="tx-12 tx-uppercase"></span>
            </div><!-- card-header -->
            <div class="card-body d-xs-flex justify-content-between align-items-center">
                <h4 class="mg-b-0 tx-inverse tx-lato tx-bold"><?=number_format($deuda_pagada)?></h4>
                <p class="mg-b-0 tx-sm"><span class="tx-success"><i class="fa fa-arrow-up"></i> <?=$prcentaje_pago?>%</span> de la deuda pagada</p>
            </div><!-- card-body -->
        </div><!-- card -->
    </div><!-- col-4 -->

    <div class="col-sm-6 col-lg-4">
        <div class="card shadow-base bd-0">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="card-title tx-uppercase tx-12 mg-b-0">Cartera vencida</h6>
                <span class="tx-12 tx-uppercase"></span>
            </div><!-- card-header -->
            <div class="card-body d-xs-flex justify-content-between align-items-center">
                <h4 class="mg-b-0 tx-inverse tx-lato tx-bold"><?=number_format($vencidas)?></h4>
                <p class="mg-b-0 tx-sm"><span class="tx-danger"><i class="fa fa-arrow-down"></i> <?=$porcentaje_vencido?>%</span> Vencido de la deuda</p>
            </div><!-- card-body -->
        </div><!-- card -->
    </div><!-- col-4 -->
</div>



</div>

<div class="br-pagebody mt-4">
    <div class="br-section-wrapper">
    <div class="progress mg-b-20">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-<?=$color?>" style="width:<?=intval($prcentaje_pago)?>%"><?=$prcentaje_pago?>%</div>
    </div>
    <table class="table">
              <thead>
                <th>Proveedor</th>
                <th>Comprobante</th>
                <th>Fecha</th>
                <th>Vence Factura</th>
                <th>Valor abonos</th>
                <th>Valor factura</th>
                <th>Saldo</th>
                <th>Pagar</th>
              </thead>
              <tbody id="bodycart">
              <?php foreach ($cartera as $cartera) { ?>
                  <tr>
                  <td><?=$cartera->nombre_cliente?></td>
                  <td><?=$cartera->tipo_comprobante.": ".$cartera->serie_comprobante."-".zero_fill($cartera->num_comprobante,8)?></td>
                  <td><?=$cartera->fecha?></td>
                  <td><?=$cartera->fecha_final?></td>
                  <td><?=$cartera->total_pago?></td>
                  <td><?=$cartera->deuda_total?></td>
                  <td><?=($cartera->deuda_total - $cartera->total_pago)?></td>
                  <td><a href="#cliente/pagar_deuda/<?=$cartera->idcredito?>"><i class="fas fa-file-invoice-dollar text-success"></i></a></td>
                  </tr>
              <?php }?>

              </tbody>
    </div>
</div>