<div id="actionToReaction" class="modal fade">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?=$type?></h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <h4 class=" lh-3 mg-b-20"><?=$legend?></h4>
                  <p class="mg-b-5"><?=$message?></p>
                </div><!-- modal-body -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary tx-size-xs" id="reaction" onclick="<?=$function['reaction']?>" <?=$function['inyectHmtl']?> data-dismiss="modal"><?=$function['functionMessage']?></button>
                  <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal" >Cancelar</button>
                </div>
              </div>
            </div><!-- modal-dialog -->
</div><!-- modal -->
