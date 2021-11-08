<table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-1p">#</th>
                  <th class="wd-1p">Fecha</th>
                  <th class="wd-1p">Sucursal</th>
                  <th class="wd-5p">Pago</th>
                  <th class="wd-5p">Empleado</th>
                  <th class="wd-5p">Cliente</th>
                  <th class="wd-5p">Comprobante</th>
                  <th class="wd-5p">Sub Total</th>
                  <th class="wd-5p">Impuesto</th>
                  <th class="wd-5p">Total</th>
                </tr>
              </thead>
              <tbody>
          <?php 
          $i=1;
          $total_sub_total =0;
          $total_subtotal_importe =0;
          $total_total=0;

          foreach ($ventas as $ventas) {
            $total_sub_total +=$ventas->sub_total;
            $total_subtotal_importe +=$ventas->subtotal_importe;
            $total_total +=$ventas->total;
            $tipo_pago = ($ventas->tipo_pago == 'Contado')?"success":"info";
            ?>
              <tr>
                  <td><?=$i?></td>
                  <td><?=$ventas->fecha?></td>
                  <td><?=$ventas->idsucursal?></td>
                  <td><p class="badge badge-<?=$tipo_pago?>"><?=$ventas->tipo_pago?></p></td>
                  <td><?=$ventas->nombre_empleado?></td>
                  <td><?=$ventas->nombre_cliente?></td>
                  <td><?=$ventas->tipo_comprobante." ".$ventas->serie_comprobante."".zero_fill($ventas->num_comprobante,8)?></td>
                  <td><?=moneda($ventas->sub_total)?></td>
                  <td><?=moneda($ventas->impuesto)?></td>
                  <td><?=moneda(($ventas->sub_total+precio($ventas->impuesto)) - precio($ventas->retencion))?></td>
              </tr>
            <?php $i++;} ?>
            <?php if($i>1){?>
                  <tr>
                    <td><?=$i?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td><?=moneda($total_sub_total)?></td>
                    <td><?=moneda($total_subtotal_importe)?></td>
                    <td><?=moneda($total_total)?></td>
                  </tr>
            <?php }?>
            </tbody>
</table>
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