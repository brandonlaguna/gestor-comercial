<div class="card shadow-base bd-0">
              <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h6 class="card-title tx-uppercase tx-12 mg-b-0">Monitoreo de la empresa</h6>
                <span class="tx-12 tx-uppercase"><?=date("M Y")?></span>
              </div><!-- card-header -->
              <div class="card-body">
                <p class="tx-sm tx-inverse tx-medium mg-b-0">Monitoreo de sucursales</p>
                <p class="tx-12">Ventas: contado y credito</p>
                  <?php foreach ($dataEmpresa as $dataEmpresa) {
                          foreach ($dataEmpresa as $data) {?>
                            <div class="row align-items-center">
                              <div class="col-3 tx-12">SUC <?=$data["idsucursal"]?></div><!-- col-3 -->
                              <div class="col-9">
                                <div class="progress rounded-0 mg-b-0">
                                  <div class="progress-bar <?=$data["color"]?>" style="width:<?=$data["porcentaje"]?>%;" role="progressbar" aria-valuenow="<?=$data["porcentaje"]?>" aria-valuemin="0" aria-valuemax="100"><?=$data["porcentaje"]?>%</div>
                                </div><!-- progress -->
                              </div><!-- col-9 -->
                            </div><!-- row -->
                      <?php }}?>

                <p class="tx-11 mg-b-0 mg-t-15">Mensaje: los calculos son relacionados con el total de ventas credito y contado.</p>

              </div><!-- card-body -->
            </div><!-- card -->