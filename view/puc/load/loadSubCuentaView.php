<div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
<?php foreach ($subcuenta as $subcuenta) {?>
        <div class="card">
            <div class="card-header" role="tab" id="heading<?=$subcuenta->idcodigo?>">
                <h6 class="mg-b-0">
                  <a class="collapsed transition item-subcuenta" data-toggle="collapse" gateway="<?=$subcuenta->idcodigo?>" data-parent="#accordion2" href="#collapse<?=$subcuenta->idcodigo?>" aria-expanded="false" aria-controls="collapse<?=$subcuenta->idcodigo?>">
                  <i class="fas fa-folder text-warning"></i>&nbsp;<?=$subcuenta->idcodigo." ".$subcuenta->tipo_codigo?>
                  </a>
                </h6>
              </div>
              <div id="collapse<?=$subcuenta->idcodigo?>" class="collapse" role="tabpanel" aria-labelledby="heading<?=$subcuenta->idcodigo?>">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-10">
                            <?php
                                $movimiento = ($subcuenta->movimiento ==1 )?"fas fa-exchange-alt text-success":"";
                                $terceros = ($subcuenta->terceros ==1 )?"fas fa-users text-success":"";
                                $centro_costos = ($subcuenta->centro_costos ==1 )?"fas fa-store-alt text-success":"";
                            ?>
                        </div>
                        <div class="row-sm-2 align-right">
                            <a href="#contables/edit/<?=$subcuenta->idcodigo?>" ><i class="fas fa-pencil-alt text-warning"></i></a>&nbsp;
                            <i class="fas fa-trash text-danger" data-toggle="modal" data-target="#modaldemo2" onclick="sendIdModal('contables/delete/<?=$subcuenta->idcodigo?>')"></i>&nbsp;
                        </div>
                    </div>
                </div>
                <div class="card-block pd-20" id="loadAuxiliares<?=$subcuenta->idcodigo?>">
                    
                </div>
            </div>
        </div> 
    <?php } 
    foreach ($cuenta as $cuenta) {}
    ?>
        <div class="card">
            <div class="card-header" role="tab" id="headingNew">
                <h6 class="mg-b-0">
                  <a class="collapsed transition" data-toggle="collapse" gateway="New" data-parent="#accordion2" href="#collapseNew" aria-expanded="false" aria-controls="collapseNew">
                  <i class="fas fa-folder-plus text-success"></i><span> Nueva subcuenta</span>
                  </a>
                </h6>
              </div>
              <div id="collapseNew" class="collapse" role="tabpanel" aria-labelledby="headingNew">
                <div class="card-block pd-20" id="loadAuxiliaresNew">
                    <?php $this->frameview("puc/form/newSubCuenta",array("idcuenta"=>$cuenta->idcodigo,"nombre_cuenta"=>$cuenta->tipo_codigo))?>
                </div>
            </div>
        </div> 
    <?php ?>   
</div>

<script src="controller/script/loadAuxiliares.js"></script>