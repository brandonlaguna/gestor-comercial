<?php foreach ($credito as $credito) {}?>
<?php 
$saldo_pendiente = ($credito->deuda_total - $credito->total_pago);
$estado = ($saldo_pendiente <= 0)?"disabled":"";
?>
<div class="br-pagetitle"></div>
<div id="modaldemo3" class="modal fade">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content tx-size-sm">

                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Pagar cuenta pendiente</h6>
                  <form id="formInfoPago">
                    <input type="hidden" name="id_credito" value="<?=$credito->idcredito?>">
                  </form>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <div class="row">
                    <div class="col-sm-12 text-center ">
                        <h1>$<?=$saldo_pendiente?></h1>
                        <div class="legend mb-2 mt-2">
                          <div class="divider line">

                          </div>
                          <div class="change"><p style="background:white; ">Método de pago</p></div>
                        </div>
                    </div>

                    <?php
                      foreach ($metodosPago as $metodoPago) {?>
                      <div class="col-sm-3 forma-pago" id="<?=$metodoPago->mp_id?>">
                      <div class="">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="media" style="";>
                              <img src="<?=$metodoPago->mp_image?>" class="rounded mx-auto d-block" alt="" style="width:90px;">
                            </div>
                            <div class="col-sm-12 text-center">
                              <p><?=$metodoPago->mp_nombre?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php }?>
                    

                  </div>
                </div><!-- modal-body -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary tx-size-xs sendForm" id="send" data-dismiss="modal" disabled>Save changes</button>
                  <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                </div>
              </div>
              
            </div><!-- modal-dialog -->
          </div><!-- modal -->
<div class="br-pagebody mt-6">
    <div class="">
    <div class="row">

        <div class="col-md-6 col-lg-4">
        <ul class="list-group">
        <?php 
        if($pagos != null){
            $icon = "fa fa-check"; 
            $color ="danger";
        foreach ($pagos as $pago) { 
            $icon = ($pago->estado ==1)?"fa fa-check":"fas fa-times";
            $color =($pago->estado ==1)?"success":"danger";
            ?>
            <li class="list-group-item rounded-top-0 mt-1">
                <p class="mg-b-0">
                    <i class="<?=$icon?> tx-<?=$color?> mg-r-8"></i>
                        <strong class="tx-inverse tx-medium">$<?=number_format($pago->pago_parcial)?> : </strong>
                </p>
                <p>
                <i class="fa fa-arrow-down text-danger"></i>
                        <span class="text-danger">Retencion: $<?=number_format($pago->retencion)?></span>
                        &nbsp;
                        <i class="far fa-calendar-alt text-warning"></i>
                        <span class="text-muted text-warning"><?=date_format(date_create($pago->fecha_pago),'Y/m/d h:i')?></span>
                        <span>
                        <?php if($pago->idcomprobante >0){ ?>
                          <a href="#file/comprobantes/<?=$pago->idcomprobante?>"><i class="fas fa-print text-info"></i></a>
                        <?php }else{?>
                          <a href="#file/cartera/cliente/<?=$pago->idcredito?>"><i class="fas fa-print text-info"></i></a>
                        <?php }?>
                        </span>
                  </p>
                  
            </li>
        <?php }}else{ ?>
            <li class="list-group-item rounded-top-0">
                <p class="mg-b-0">
                    <i class="fas fa-exclamation-triangle tx-warning mg-r-8"></i>
                        <strong class="tx-inverse tx-medium">No hay pagos relacionados</strong>
                        <span class="text-muted"></span>
                </p>
            </li>
        <?php } ?>
        </ul>
        </div>


        <div class="col-md-6 col-lg-7 card widget-18 shadow-base">
        <div class="">
          <div class="wt-content">
            <div class="wt-content-item">
              <h1 class="wt-title"><?=number_format($saldo_pendiente)?></h1>
              <p class="mg-b-30">Saldo pendiente en esta factura</p>

              <div class="d-sm-flex justify-content-center">
                </div><!-- input-group -->
                <button class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-toggle="modal" data-target="#modaldemo3" <?=$estado?>>Generar nuevo pago</button>
              </div>
            </div><!-- tx-center -->
          </div><!-- d-flex -->
        </div>
        </div>
    </div>
    </div>
</div>
<script src="controller/script/ClienteController.js"></script>

    