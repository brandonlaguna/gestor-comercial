<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <form id="reporte_general" finish="ventas/reporte_general" class="form-layout form-layout-1" >
    <div class="row mg-b-25">

            <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Desde: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="pos" id="pos" value="<?=$pos?>">
                  <input type="hidden" name="pos" id="control" value="<?=$control?>">
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="start_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>" readonly>
                </div>
            </div>

            <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Hasta: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="end_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>" readonly>
                </div>
            </div>

    </div>
    </form>
    <div class="col-sm-12 mt-5">
    <div class="linearLoading"></div>
    <div class="table-wrapper" id="reporte">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-3p">Fecha</th>
                  <th class="wd-5p">Sucursal</th>
                  <th class="wd-5p">Pago</th>
                  <th class="wd-5p">Empleado</th>
                  <th class="wd-5p">Proveedor</th>
                  <th class="wd-5p">Comprobante</th>
                  <th class="wd-5p">Impuesto</th>
                  <th class="wd-5p">Retencion</th>
                  <th class="wd-5p">Subtotal</th>
                  <th class="wd-5p">Total</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($compras as $compras) {
                ?>
              <tr>
                  <td><?=$compras->fecha?></td>
                  <td><?=$compras->razon_social?></td>
                  <td><p class="badge badge-success"><?=$compras->tipo_pago?></p></td>
                  <td><?=$compras->nombre_empleado?></td>
                  <td><?=$compras->nombre_proveedor?></td>
                  <td><?=$compras->prefijo." ".$compras->serie_comprobante."".zero_fill($compras->num_comprobante,8)?></td>
                  <td><?=moneda($compras->impuesto)?></td>
                  <td><?=moneda($compras->sub_total)?></td>
                  <td><?=moneda($compras->impuesto)?></td>
                  <td><?=moneda($compras->sub_total + precio($compras->impuesto) - precio($compras->retencion)) ?></td>
              </tr>
            <?php } ?>
              </tbody>
            </table>
    </div>

    </div>
</div>
</div>
<link href="lib/timepicker/jquery.timepicker.css" rel="stylesheet">
<link href="lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
<script src="lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="lib/select2/js/select2.min.js"></script>
<script src="controller/script/ReporteController.js"></script>
<script>
$('.fc-datepicker').datepicker({
        
          showOtherMonths: true,
          selectOtherMonths: true
      });
</script>
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

