<div class="br-pagetitle"></div>
<div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea anular esta compra?</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Esta compra esta relacionado a registros, por lo tanto solo puede ser anulada">Esta compra esta relacionado a registros, por lo tanto solo puede ser anulada</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si anular</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
          </div><!-- modal-dialog -->
    </div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
            <a href="#compras/nuevo" class="btn btn-primary btn-with-icon">
            <div class="ht-40">
                <span class="icon wd-40"><i class="fa fa-plus"></i></span>
                <span class="pd-x-15">Nueva Compra Contable</span>
            </div>
            </a>
<br>
    <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p"></th>
                  <th class="wd-15p">Proveedor</th>
                  <th class="wd-5p">Pago</th>
                  <th class="wd-20p">Cmpte</th>
                  <th class="wd-15p">Fecha</th>
                  <th class="wd-10p">Credito</th>
                  <th class="wd-10p">Debito</th>
                  <th class="wd-10p">Subtotal</th>
                  <th class="wd-5p">Estado</th>
                  <th class="wd-15p">Accion</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $i=1;
                foreach ($compras as $compras) { 
                    $estado = ($compras->cc_estado=='A')?"fa-check-circle":"fa-times-circle";
                    $color = ($compras->cc_estado=='A')?"text-success":"text-danger";
                    $message = ($compras->cc_estado=='A')?"Aceptado":"Cancelado";
                    ?>
                    <tr>
                    <td><?=$i?></td>
                    <td><?=substr($compras->nombre_tercero,0,40)?></td>
                    <td><p class="badge badge-success"><?=$compras->fp_nombre?></p></td>
                    <td><?=$compras->cc_num_cpte."".$compras->cc_cons_cpte?></td>
                    <td><?=$compras->cc_fecha_cpte?></td>
                    <td><?=number_format(round($compras->credito))?></td>
                    <td><?=number_format(round($compras->debito))?></td>
                    <td><?=number_format(round($compras->subtotal_credito))?></td>
                    <td><i class="fas <?=$estado." "?> <?=$color?>" data-toggle="tooltip-primary" data-placement="top" title="Estado <?=$message?>"></i></td>
                    <td>
                    <a href="#compras/detail_contable/<?=$compras->cc_id_transa?>" ><i class="fas fa-binoculars text-success"></i></a>&nbsp;
                    <?php if($compras->cc_estado == 'A' && $_SESSION["permission"] > 3){?>
                        <a href="#compras/edit_compra_contable/<?=$compras->cc_id_transa?>" ><i class="fas fa-pen-nib text-warning"></i></a>&nbsp;
                          <i class="fas fa-trash text-danger" data-toggle="modal" data-target="#modaldemo2" onclick="sendIdModal('compras/delete_contable/<?=$compras->cc_id_transa?>')"></i>&nbsp;
                        <?php }?>
                        <a href="#file/comprobantes/<?=$compras->cc_id_transa?>" target="" ><i class="fas fa-print text-info"></i></a>
                        <a href="#file/comprobantes/<?=$compras->cc_id_transa?>/standard" target="" ><i class="fas fa-file-pdf text-info"></i></a>
                    </td>
                    </tr>
                <?php $i++; }?>
            </tbody>
        </div>
    </div>
</div>

<link href="lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
    <script src="lib/select2/js/select2.min.js"></script>
    <script src="js/controller/tooltip-colored.js"></script>
    <script src="js/controller/popover-colored.js"></script>
          <script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          responsive: true,
          language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          }
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>