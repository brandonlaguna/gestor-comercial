<div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
<?php foreach ($auxiliares as $auxiliar) {?>
    <div class="card">
            <div class="card-header" role="tab" id="heading<?=$auxiliar->idcodigo?>">
                <h6 class="mg-b-0">
                  <a class="collapsed transition item-subcuenta" data-toggle="collapse" gateway="<?=$auxiliar->idcodigo?>" data-parent="#accordion2" href="#collapse<?=$auxiliar->idcodigo?>" aria-expanded="false" aria-controls="collapse<?=$auxiliar->idcodigo?>">
                  <i class="fas fa-folder text-warning"></i>&nbsp;<?=$auxiliar->idcodigo." ".$auxiliar->tipo_codigo?>
                  </a>
                </h6>
              </div>
              <div id="collapse<?=$auxiliar->idcodigo?>" class="collapse" role="tabpanel" aria-labelledby="heading<?=$auxiliar->idcodigo?>">
              <div class="container">
                  <div class="row">
                      <div class="col-sm-10">
                          <?php
                              $movimiento = ($auxiliar->movimiento ==1 )?"fas fa-exchange-alt text-success":"";
                              $terceros = ($auxiliar->terceros ==1 )?"fas fa-users text-success":"";
                              $centro_costos = ($auxiliar->centro_costos ==1 )?"fas fa-store-alt text-success":"";
                              $impuesto = ($auxiliar->impuesto ==1 )?"fas fa-percentage text-success":"";
                              $retencion = ($auxiliar->retencion ==1 )?"fas fa-retweet text-success":"";
                          ?>
                          <?php if($movimiento){?><i class="<?=$movimiento?>"></i>&nbsp; Movimiento<br><?php }?>
                          <?php if($terceros){?><i class="<?=$terceros?>"></i>&nbsp;Terceros<br><?php }?>
                          <?php if($centro_costos){?><i class="<?=$centro_costos?>"></i>&nbsp;Centro de costos<br><?php }?>
                          <?php if($impuesto){?><i class="<?=$impuesto?>"></i>&nbsp;Impuesto<br><?php }?>
                          <?php if($retencion){?><i class="<?=$retencion?>"></i>&nbsp;Retencion<br><?php }?>
                      </div>
                      <div class="col-sm-2">
                          <a href="#contables/edit/<?=$auxiliar->idcodigo?>" ><i class="fas fa-pencil-alt text-warning"></i></a>&nbsp;
                          <i class="fas fa-trash text-danger" data-toggle="modal" data-target="#modaldemo2" onclick="sendIdModal('contables/delete/<?=$auxiliar->idcodigo?>')"></i>&nbsp;
                      </div>
                  </div>
                </div>
                <div class="card-block pd-20" id="loadAuxiliares<?=$auxiliar->idcodigo?>">
                </div>
            </div>
        </div> 
<?php } ?>

<?php foreach ($subcuenta as $subcuenta) {}
?>
    <div class="card">
            <div class="card-header" role="tab" id="headingNew<?=$subcuenta->idcodigo?>">
                <h6 class="mg-b-0">
                  <a class="collapsed transition" data-toggle="collapse" gateway="New" data-parent="#accordion2" href="#collapseNew<?=$subcuenta->idcodigo?>" aria-expanded="false" aria-controls="collapseNew<?=$subcuenta->idcodigo?>">
                  <i class="fas fa-folder-plus text-success"></i><span> Nuevo auxiliar de subcuenta</span>
                  </a>
                </h6>
              </div>
              <div id="collapseNew<?=$subcuenta->idcodigo?>" class="collapse" role="tabpanel" aria-labelledby="headingNew<?=$subcuenta->idcodigo?>">
                <div class="card-block pd-20" id="loadAuxiliaresNew">
                    <?php $this->frameview("puc/form/newSubCuenta",array("idcuenta"=>$subcuenta->idcodigo,"nombre_cuenta"=>$subcuenta->tipo_codigo))?>
                </div>
            </div>
        </div> 


</div>
