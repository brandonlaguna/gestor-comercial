<div class="card shadow-base bd-0">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="card-title tx-uppercase tx-12 mg-b-0">Monitoreo de ventas credito</h6>
        <span class="tx-12 tx-uppercase"><?=date("M Y")?></span>
    </div><!-- card-header -->
    <div class="card-body">
        <p class="tx-sm tx-inverse tx-medium mg-b-0">Cartera clientes</p>
        <p class="tx-12"><?=$_SESSION["sucursal"]?></p>
    <div class="row align-items-center">
        <div class="col-3 tx-12 tx-bold">Pagada</div><!-- col-3 -->
            <div class="col-9">
            <div class="progress rounded-0 mg-b-0">
                <div class="progress-bar bg-teal" style="width:<?=$dataDeuda[1]?>%;" role="progressbar" aria-valuemin="0" aria-valuemax="100"><?=$dataDeuda[1]?>%</div>
            </div><!-- progress -->
            </div><!-- col-9 -->
        </div><!-- row -->
        <div class="row align-items-center mg-t-5">
            <div class="col-3 tx-12 tx-bold">Vencida</div><!-- col-3 -->
            <div class="col-9">
            <div class="progress rounded-0 mg-b-0">
                <div class="progress-bar bg-info" style="width:<?=$dataDeuda[0]?>%;" role="progressbar" aria-valuemin="0" aria-valuemax="100"><?=$dataDeuda[0]?>%</div>
            </div><!-- progress -->
            </div><!-- col-9 -->
        </div><!-- row -->
        <p class="tx-11 mg-b-0 mg-t-15">Mensaje: El calculo realizado refleja la cartera de clientes del año <?=date("Y")?>.</p>
        </div><!-- card-body -->
</div><!-- card -->