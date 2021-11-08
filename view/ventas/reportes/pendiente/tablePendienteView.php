<table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-1p">#</th>
                  <th class="wd-1p">Fecha</th>
                  <th class="wd-1p">Sucursal</th>
                  <th class="wd-5p">Empleado</th>
                  <th class="wd-5p">Cliente</th>
                  <th class="wd-5p">Comprobante</th>
                  <th class="wd-5p">Impuesto</th>
                  <th class="wd-5p">Retencion</th>
                  <th class="wd-5p">Pendiente</th>
                  <th class="wd-5p">Estado</th>
                  <th class="wd-5p">Pagado</th>
                  <th class="wd-5p">Deuda Total</th>
                  <th class="wd-5p">Cobrar</th>
                </tr>
              </thead>
              <tbody>
            <?php
            $total_pendiente = 0;
            $total_pagado =0;
            $total_deuda_total =0;
            $i=1;
            foreach ($ventas as $ventas) {
                $total_pendiente += $ventas->deuda_total - $ventas->total_pago;
                $total_pagado +=$ventas->total_pago;
                $total_deuda_total +=$ventas->deuda_total;
                ?>
              <tr>
                 <td><?=$i?></td>
                  <td><p><?=$ventas->fecha?></p></td>
                  <td><p><?=substr($ventas->razon_social,0,20)?></p></td>
                  <td><p><?=substr($ventas->nombre_empleado,0,20)?></p></td>
                  <td><p><?=substr($ventas->nombre_cliente,0,20)?></p></td>
                  <td><p><?=$ventas->serie_comprobante." ".zero_fill($ventas->num_comprobante)?></p></td>
                  <td><p><?=moneda($ventas->impuesto)?></p></td>
                  <td><p><?=moneda($ventas->retencion)?></p></td>
                  <td><p><?=moneda($ventas->deuda_total - $ventas->total_pago)?></p></td>
                  <td><?=status($ventas->estado_venta)?></td>
                  <td><p><?=moneda($ventas->total_pago)?></p></td>
                  <td><p><?=moneda($ventas->deuda_total)?></p></td>
                  <td><a href="#cliente/pagar_deuda/<?=$ventas->idcredito?>"><i class="fas fa-file-invoice-dollar text-success"></i></a></td>
              </tr>
            <?php $i++;} ?>
            <?php if($i>1){?>
                <tr>
                    <td><?=$i+1?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td><?=moneda($total_pendiente)?></td>
                    <td><?=moneda($total_pagado)?></td>
                    <td><?=moneda($total_deuda_total)?></td>
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