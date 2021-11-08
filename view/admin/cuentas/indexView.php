<div class="br-pagetitle">
        <i class="icon ion-person"></i>
        <div>
          <h4>Cuentas</h4>
        </div>
      </div><!-- d-flex -->
      <div class="br-pagebody">
        <div class="br-section-wrapper">
          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-15p">ID</th>
                  <th class="wd-15p">Codigo</th>
                  <th class="wd-20p">Nombre</th>
                  <th class="wd-20p">Caracteristica</th>
                  <th class="wd-15p">Terceros</th>
                  <th class="wd-10p">Impuestos</th>
                  <th class="wd-25p">Retenciones</th>
                  <th class="wd-25p">Centro costos</th>
                  <th class="wd-25p">Accion</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($cuentas as $cuenta){?>
                <tr>
                  <td><?=$cuenta->cu_id?></td>
                  <td><?=$cuenta->cu_codigo?></td>
                  <td><?=$cuenta->cu_nombre?></td>
                  <td><?=$cuenta->cu_caracteristicas?></td>
                  <td><?=$cuenta->cu_terceros?></td>
                  <td><?=$cuenta->im_porcentaje."%"?></td>
                  <td><?=$cuenta->re_porcentaje."%"?></td>
                  <td><?=$cuenta->cc_nombre?></td>
                  <td><a href="#cuentas/detail/<?=$cuenta->cu_id?>"><i class="fas fa-cog"></i></a></td>
                </tr>
              <?php }?>
                </tbody>
            </table>
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
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          }
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>