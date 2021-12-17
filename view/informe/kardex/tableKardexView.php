<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p">Fecha</th>
                  <th class="wd-15p">Ingresa</th>
                  <th class="wd-5p">Costo Total</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($listCompra as $compra) {?>
                    <tr>
                        <td><?=$compra->fecha?></td>
                        <td><?=$compra->stock_total_compras?></td>
                        <td><?=number_format($compra->precio_total_compras,2,'.',',')?></td>
                        
                    </tr>
                <?php }?>
                    <?php foreach ($detailCompra as $detailCompra) {}?>
                <tr>
                    <td></td>
                    <td><?=number_format($detailCompra[0],2)?></td>
                    <td><?=number_format($detailCompra[1],2,'.',',');?></td>
                </tr>
              </tbody>
            </table>
            </div> <!--table wrapper-->
        </div><!--col 4-->

        <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="table-wrapper">
                    <table id="datatable2" class="table display responsive nowrap">
                        <thead>
                            <tr>
                                <th class="wd-20p">Fecha</th>
                                <th class="wd-15p">Sale</th>
                                <th class="wd-10p">Precio Unit.</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($listVenta as $venta) {?>
                            <tr>
                                <td><?=$venta->fecha?></td>
                                <td><?=$venta->stock_total_ventas?></td>
                                <td><?=number_format($venta->precio_total_ventas,2,'.',',')?></td>
                            </tr>
                        <?php }?>
                        <?php foreach ($detailVenta as $detailVenta) {}?>
                        <tr>
                            <td></td>
                            <td><?=number_format($detailVenta[0],2)?></td>
                            <td><?=number_format($detailVenta[1],2,'.',',');?></td>
                        </tr>
                        </tbody>
                    </table>
            </div><!--table wrapper-->
        </div><!--col 4-->
<!--
        <div class="col-sm-12 col-md-12 col-lg-4">
            <div class="table-wrapper">
                    <table id="datatable3" class="table display responsive nowrap">
                        <thead>
                            <tr>
                                <th class="wd-20p">Cant</th>
                                <th class="wd-15p">Costo Total</th>
                                <th class="wd-10p">Costo Unit.</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
            </div>
        </div> col 4-->

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
        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
        $('#datatable2').DataTable( {
        
        } );

      });
    </script>