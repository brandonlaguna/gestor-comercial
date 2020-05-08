<div class="br-pagetitle"></div>
<div class="br-pagebody">
<div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea eliminar este comprobante?</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Este comprobante está relacionado a registros, por lo tanto solo dejará de ser visible para algunas configuraciones">Este comprobante está relacionado a registros, por lo tanto solo dejará de ser visible para algunas configuraciones</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si eliminar</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
            </div><!-- modal-dialog -->
  </div>
    <div class="br-section-wrapper">
    <a href="#admin/nuevo_comprobante" class="btn btn-primary btn-with-icon">
            <div class="ht-40">
                <span class="icon wd-40"><i class="fa fa-plus"></i></span>
                <span class="pd-x-15">Nuevo Comprobante</span>
            </div>
            </a>
    <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p">#</th>
                  <th class="wd-16p">Tipo Documento</th>
                  <th class="wd-16p">Num. Comprobante</th>
                  <th class="wd-12p">Consecutivo</th>
                  <th class="wd-2p">Contabilidad</th>
                  <th class="wd-2p">Formato</th>
                  <th class="wd-6p">Opcion</th>
                </tr>
              </thead>
              <tbody>
              <?php 
              $i = 1;
              foreach ($comprobantes as $comprobantes) {
                  $icon = ($comprobantes->contabilidad ==1)?"fas fa-toggle-on":"fas fa-toggle-off";
                  $color = ($comprobantes->contabilidad ==1)?"success":"danger";
                  ?>
                  <tr>
                  <td><?=$i?></td>
                  <td><?=$comprobantes->nombre?></td>
                  <td><?=$comprobantes->ultima_serie?></td>
                  <td><?=zero_fill($comprobantes->ultimo_numero,8)?></td>
                  <td><i class="<?=$icon?> text-<?=$color?>"></i></td>
                  <td><i class="<?=$comprobantes->pri_icon?> text-info" data-toggle="tooltip-primary" data-placement="top" title="<?=$comprobantes->pri_nombre?>"></i></td>
                  <td>
                  <a href="#admin/actualizar_comprobante/<?=$comprobantes->iddetalle_documento_sucursal?>"><i class="fas fa-pen-nib text-warning"></i></a>
                  <i class="fas fa-trash text-danger" data-toggle="modal" data-target="#modaldemo2" onclick="sendIdModal('admin/delete_comprobante/<?=$comprobantes->iddetalle_documento_sucursal?>')"></i>&nbsp;
                  </td> 
                  </tr>
              <?php $i++;}?>
              </tbody>
            </table>
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
