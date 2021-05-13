<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea eliminar este Codigo contable??</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Este codigo está relacionado a registros, por lo tanto dejará de ser visible solo para algunas configuraciones">Este codigo está relacionado a registros, por lo tanto dejará de ser visible solo para algunas configuraciones</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si eliminar</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
            </div><!-- modal-dialog -->
  </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 col-lg-2">
            <div class="list-group" role="tablist" id="list_clase">
                <?php  foreach ($clases as $clase) {?>
                <a class="list-group-item list-group-item-action list-group-item rounded-top-0 item-clase" gateway="<?=$clase->idcodigo?>" id="list-<?=$clase->idcodigo?>-list" data-toggle="list" href="#list-<?=$clase->idcodigo?>" role="tab"><i class="fas fa-folder text-warning"></i>&nbsp;<?=$clase->idcodigo." ".$clase->tipo_codigo?></a>
                <?php } ?>
                <script src="controller/script/loadCuenta.js"></script>
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3" >
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="list-1" role="tabpanel" aria-labelledby="list-1-list">
                    <div class="list-group" role="tablist" id="loadGrupo">
                        <?php foreach ($grupo as $grupo) {?>
                        <a class="list-group-item list-group-item-action item-grupo" gateway="<?=$grupo->idcodigo?>" id="list-<?=$grupo->idcodigo?>-list" data-toggle="list" href="#list-<?=$grupo->idcodigo?>" role="tab" aria-controls="<?=$grupo->idcodigo?>"><i class="fas fa-folder text-warning"></i>&nbsp;<?=$grupo->idcodigo." ".$grupo->tipo_codigo?></a>
                        <?php } ?>  
                        <script src="controller/script/loadCuenta.js"></script>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-lg-3">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="list-1" role="tabpanel" aria-labelledby="list-1-list">
                    <div class="list-group" role="tablist" id="loadCuenta">
                        <?php foreach ($cuenta as $cuenta) {?>
                        <a class="list-group-item list-group-item-action item-cuenta" gateway="<?=$cuenta->idcodigo?>" id="list-<?=$cuenta->idcodigo?>-list" data-toggle="list" href="#list-<?=$cuenta->idcodigo?>" role="tab" aria-controls="<?=$cuenta->idcodigo?>"><i class="fas fa-folder text-warning"></i>&nbsp;<?=$cuenta->idcodigo." ".$cuenta->tipo_codigo?></a>
                        <?php } 
                        foreach ($nivel as $cuenta) {}
                        ?>
                        <div id="accordion2" class="accordion accordion-head-colored accordion-primary" role="tablist" aria-multiselectable="true">
                        <div class="card">
                            <div class="card-header" role="tab" id="headingNewS">
                                <h6 class="mg-b-0">
                                  <a class="collapsed transition" data-toggle="collapse" gateway="New" data-parent="#accordion2" href="#collapseNewS" aria-expanded="false" aria-controls="collapseNewS">
                                  <i class="fas fa-folder-plus text-success"></i><span> Nueva cuenta</span>
                                  </a>
                                </h6>
                              </div>
                              <div id="collapseNewS" class="collapse" role="tabpanel" aria-labelledby="headingNewS">
                                <div class="card-block pd-20" id="loadAuxiliaresNew">
                                    <?php $this->frameview("puc/form/newSubCuenta",array("idcuenta"=>$cuenta->idcodigo,"nombre_cuenta"=>$cuenta->tipo_codigo))?>
                               </div>
                           </div>
                        </div> 
                    <?php ?>
                    </div>
                        <script src="controller/script/loadSubCuenta.js"></script>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4 col-lg-4"id="loadSubCuenta" >
        
        </div>

    </div>
</div>
</div>

<script src="controller/script/ContablesController.js"></script>