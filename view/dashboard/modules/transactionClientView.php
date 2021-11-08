
            <div class="card shadow-base bd-0">
              <div class="card-header bg-transparent pd-20">
                <h6 class="card-title tx-uppercase tx-12 mg-b-0">Historial de pago cartera cliente</h6>
              </div><!-- card-header -->
              <table class="table table-responsive mg-b-0 tx-12" id="datatable1">
                <thead>
                  <tr class="tx-10">
                    <th class="wd-10p pd-y-5">Factura</th>
                    <th class="pd-y-5">Usuario</th>
                    <th class="pd-y-5">Pago parcial</th>
                    <th class="pd-y-5">Estado</th>
                    <th class="pd-y-5">Fecha</th>
                  </tr>
                </thead>
                <tbody>
                <?php 

                foreach ($historialPagoCliente as $historial){
                    $estado = ($historial->estado == "A")?"Aceptado":"Denegado";
                    $color = ($historial->estado == "A")?"bg-success":"bg-danger";
                    ?>
                  <tr>
                    <td>
                        <?=$historial->serie_comprobante."".zero_fill($historial->num_comprobante,8)?>
                    </td>
                    <td>
                      <p class="tx-inverse tx-14 tx-medium d-block"><?=$historial->nombre?></p>
                      <span class="tx-11 d-block">TRANSID: <?=$historial->iddetalle_credito?></span>
                    </td>
                    <td>
                    <?=$historial->monto?>
                    </td>
                    <td class="tx-12">
                      <span class="square-8 <?=$color?> mg-r-5 rounded-circle"></span> <?=$estado?>
                    </td>
                    <td><?=$historial->fecha_transaccion?></td>
                  </tr>
                <?php }?>
                </tbody>
              </table>
              <div class="card-footer tx-12 pd-y-15 bg-transparent">
                <!-- <a href=""><i class="fa fa-angle-down mg-r-5"></i>View All Transaction History</a> -->
              </div><!-- card-footer -->
            </div><!-- card -->