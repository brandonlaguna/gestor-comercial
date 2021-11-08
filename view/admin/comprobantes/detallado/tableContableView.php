<div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
              <tr>
                <th class="wd-5p">Comprobante --</th>
                  <th class="wd-5p">Fecha</th>
                  <th class="wd-5p">Tercero</th>
                  <th class="wd-5p">Cuenta</th>
                  <th class="wd-5p">Debito</th>
                  <th class="wd-5p">Credito</th>
                  <th class="wd-5p">Estado</th>
                </tr>
              </thead>
                <tbody>

<?php foreach ($detalle as $detalle) {
    $debito = ($detalle->dcc_d_c_item_det == "D")?$detalle->dcc_valor_item:0;
    $credito = ($detalle->dcc_d_c_item_det == "C")?$detalle->dcc_valor_item:0;

    if($detalle->cc_estado == "A" ){
      $estado = "fa-check-circle";
      $color = "text-success";
      $message ="Aceptado";
  }elseif($detalle->cc_estado =="A" && !$detalle->dcc_cta_item_det){
      $estado = "fa-exclamation-circle";
      $color = "text-warning";
      $message ="Alerta";
  }
  else{
      $estado = "fa-times-circle";
      $color = "text-dange";
      $message ="Cancelado";
  }

  ?>
              <tr>
                <td><?=$detalle->cc_num_cpte."".zero_fill($detalle->cc_cons_cpte,8)?></td>
                  <td><?=$detalle->cc_fecha_cpte?></td>
                  <td><?=$detalle->nombre_tercero?></td>
                  <td><?=$detalle->idcodigo?></td>
                  <td><?=number_format($debito,2,'.',',')?></td>
                  <td><?=number_format($credito,2,'.',',')?></td>
                  <td><i class="fas <?=$estado." "?> <?=$color?>" data-toggle="tooltip-primary" data-placement="top" title="Estado <?=$message?>"></i></td>
              </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>

<link href="lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
<script src="lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="lib/datatables.net/js/dataTables.buttons.min.js"></script>
<script src="lib/datatables.net/js/buttons.flash.min.js"></script>
<script src="lib/datatables.net/js/jszip.min.js"></script>
<script src="lib/datatables.net/js/pdfmake.min.js"></script>
<script src="lib/datatables.net/js/vfs_fonts.js"></script>
<script src="lib/datatables.net/js/buttons.html5.min.js"></script>
<script src="lib/datatables.net/js/buttons.print.min.js"></script>
<link href="lib/datatables.net/css/buttons.dataTables.min.css" rel="stylesheet">
<script src="lib/select2/js/select2.min.js"></script>

<script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
          responsive: true,
          language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          },
          "columnDefs": [
            {"className": "dt-right", "targets": [1,2,3,4,5,6]}
        ],
        destroy: true
          
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>