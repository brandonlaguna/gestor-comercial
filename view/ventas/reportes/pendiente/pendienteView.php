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
                  <th class="wd-5p">Fecha</th>
                  <th class="wd-5p">Sucursal</th>
                  <th class="wd-5p">Empleado</th>
                  <th class="wd-5p">Cliente</th>
                  <th class="wd-5p">Comprobante</th>
                  <th class="wd-5p">Impuesto</th>
                  <th class="wd-5p">Retencion</th>
                  <th class="wd-5p">Pendiente</th>
                  <th class="wd-5p">Pagado</th>
                  <th class="wd-5p">Deuda Total</th>
                  <th class="wd-5p">Cobrar</th>
                </tr>
              </thead>
              <tbody >
              <?php foreach ($ventas as $ventas) {?>
              <tr>
                  <td><?=$ventas->fecha?></td>
                  <td><?=$ventas->idsucursal?></td>
                  <td><?=$ventas->nombre_empleado?></td>
                  <td><?=$ventas->nombre_cliente?></td>
                  <td><?=$ventas->serie_comprobante."".zero_fill($ventas->num_comprobante)?></td>
                  <td><?=$ventas->impuesto?></td>
                  <td><?=number_format($ventas->deuda_total - $ventas->total_pago)?></td>
                  <td><?=number_format($ventas->total_pago)?></td>
                  <td><?=number_format($ventas->deuda_total)?></td>
                  <td><a href="#cliente/pagar_deuda/<?=$ventas->idcredito?>"><i class="fas fa-file-invoice-dollar text-success"></i></a></td>
              </tr>
            <?php } ?>
              </tbody>
            </table>
    </div>

    </div>
</div>
</div>
<link href="lib/timepicker/jquery.timepicker.css" rel="stylesheet">

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

