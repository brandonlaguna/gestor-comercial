<table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-3p">Fecha</th>
                  <th class="wd-5p">Empleado</th>
                  <th class="wd-5p">Proveedor</th>
                  <th class="wd-5p">Comprobante</th>
                  <th class="wd-5p">Impuesto</th>
                  <th class="wd-1p">Articulo</th>
                  <th class="wd-5p">Ingreso</th>
                  <th class="wd-5p">Stock</th>
                  <th class="wd-5p">Vendido</th>
                </tr>
              </thead>
              <tbody >
              <?php foreach ($compras as $compras) {?>
              <tr>
                  <td><?=$compras->fecha?></td>
                  <td><?=$compras->nombre_empleado?></td>
                  <td><?=$compras->nombre_proveedor?></td>
                  <td><?=$compras->prefijo." ".$compras->serie_comprobante."".zero_fill($compras->num_comprobante,8)?></td>
                  <td><?=$compras->impuesto?></td>
                  <td><?=$compras->nombre_articulo?></td>
                  <td><?=$compras->stock_ingreso?></td>
                  <td><?=$compras->stock?></td>
                  <td>0</td>
              </tr>
            <?php } ?>
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