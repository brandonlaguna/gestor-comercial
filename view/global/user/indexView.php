<div class="br-pagetitle"></div>

      <div class="br-pagebody">
      <div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea desactivar este usuario?</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Este usuario está relacionado a registros, por lo tanto solo dejara de ser visible">Este usuario está relacionado a registros, por lo tanto solo dejara de ser visible</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si desactivar</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
            </div><!-- modal-dialog -->
  </div>
      <div class="br-section-wrapper">
      <a href="#configuracion/new_user" class="btn btn-primary btn-with-icon">
            <div class="ht-40">
                <span class="icon wd-40"><i class="fa fa-plus"></i></span>
                <span class="pd-x-15">Nuevo usuario</span>
            </div>
      </a>
      <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-15p">Nombre</th>
                  <th class="wd-15p">Email</th>
                  <th class="wd-20p">Nivel</th>
                  <th class="wd-20p">Sucursal</th>
                  <th class="wd-15p">Telefono</th>
                  <th class="wd-5p">Estado</th>
                  <th class="wd-10p">Fecha Reg.</th>
                  <th class="wd-25p">Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($user as $user) {?>
                    <tr>
                        <td><?=$user->nombre." ".$user->apellidos?></td>
                        <td><?=$user->ju_email?></td>
                        <td><?=$user->ju_type?></td>
                        <td><?=$user->sc_id?></td>
                        <td><?=$user->telefono?></td>
                        <td><?=$user->ju_active?></td>
                        <td><?=$user->date_created?></td>
                        <td>
                            
                        </td>
                    </tr>
                <?php } ?>
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
          <script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          responsive: true,
          language: {
            searchPlaceholder: 'Buscar',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          }
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>