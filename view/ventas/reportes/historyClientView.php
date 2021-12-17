<table id="datatable1" class="table display responsive nowrap">
    <thead>
        <tr>
            <th class="wd-15p">ID</th>
            <th class="wd-15p">Articulo</th>
            <th class="wd-15p">Cantidad</th>
            <th class="wd-15p">Precio Venta</th>
            <th class="wd-15p">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detalle as $detalle) {?>
            <tr>
                <td><?=$detalle->idarticulo?></td>
                <td><?=$detalle->nombre_articulo?></td>
                <td><?=$detalle->stock_venta." ".$detalle->prefijo?></td>
                <td><?=number_format($detalle->venta_unitario,2,'.',',')?></td>
                <td><?=number_format($detalle->total_venta,2,'.',',')?></td>
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
    <script src="lib/select2/js/select2.min.js"></script>
    <script src="js/controller/tooltip-colored.js"></script>
    <script src="js/controller/popover-colored.js"></script>
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