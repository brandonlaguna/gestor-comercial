<?php foreach ($cuenta as $cuenta) {?>
    <a class="list-group-item list-group-item-action item-cuenta" gateway="<?=$cuenta->idcodigo?>" id="list-<?=$cuenta->idcodigo?>-list" data-toggle="list" href="#list-<?=$cuenta->idcodigo?>" role="tab" aria-controls="<?=$cuenta->idcodigo?>"><i class="fas fa-folder text-warning"></i>&nbsp;<?=$cuenta->idcodigo." ".$cuenta->tipo_codigo?></a>
<?php 
}
    foreach ($nivel as $cuenta) {}
    ?>
    <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
        <div class="card">
            <div class="card-header" role="tab" id="headingNewC">
                <h6 class="mg-b-0">
                  <a class="collapsed transition" data-toggle="collapse" gateway="New" data-parent="#accordion2" href="#collapseNewC" aria-expanded="false" aria-controls="collapseNewC">
                  <i class="fas fa-folder-plus text-success"></i><span> Nueva cuenta</span>
                  </a>
                </h6>
              </div>
              <div id="collapseNewC" class="collapse" role="tabpanel" aria-labelledby="headingNewC">
                <div class="card-block pd-20" id="loadAuxiliaresNew">
                    <?php $this->frameview("puc/form/newSubCuenta",array("idcuenta"=>$cuenta->idcodigo,"nombre_cuenta"=>$cuenta->tipo_codigo))?>
                </div>
            </div>
        </div> 
    <?php ?>
    </div>
<script src="controller/script/loadSubCuenta.js"></script>