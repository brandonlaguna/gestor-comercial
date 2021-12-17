<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    
    <div class="row mg-b-25">

            <div class="col-sm-12 col-lg-12">
                <div class="form-group">
                <form id="reporte_general" finish="ventas/reporte_general" class="form-layout form-layout-1" >
                  <label class="form-control-label">Cliente: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="pos" id="pos" value="<?=$pos?>">
                  <input type="hidden" name="control" id="control" value="<?=$control?>">
                  <input type="hidden" name="codigo_contable" id="codigo_contable" value="">
                  <input type="hidden" name="autocomplete_articulo" id="autocomplete_articulo" value="">
                  <input type="text" class="form-control" name="cliente" placeholder="Nombre o Documento del cliente" value="" id="proveedor">
                  </form>
                </div>
                <button class="btn btn-success" onclick="loadReport()" style="width:100%;">Buscar</button>
            </div>
           
    </div>
    
   
    <div class="col-sm-12 mt-5">
    <div class="linearLoading"></div>
    <div class="table-wrapper" id="reporte">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p">Fecha</th>
                  <th class="wd-5p">Sucursal</th>
                  <th class="wd-5p">Pago</th>
                  <th class="wd-5p">Empleado</th>
                  <th class="wd-5p">Cliente</th>
                  <th class="wd-5p">Comprobante</th>
                  <th class="wd-5p">Impuesto</th>
                  <th class="wd-5p">Sub Total</th>
                  <th class="wd-5p">Total Impuesto</th>
                  <th class="wd-5p">Total</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($ventas as $ventas) {
                 $tipo_pago = ($ventas->tipo_pago == 'Contado')?"success":"info";?>
              <tr>
                  <td><?=$ventas->fecha?></td>
                  <td><?=$ventas->idsucursal?></td>
                  <td><p class="badge badge-<?=$tipo_pago?>"><?=$ventas->tipo_pago?></p></td>
                  <td><?=$ventas->nombre_empleado?></td>
                  <td><?=$ventas->nombre_cliente?></td>
                  <td><?=$ventas->tipo_comprobante." ".$ventas->serie_comprobante."".zero_fill($ventas->num_comprobante,8)?></td>
                  <td><?=$ventas->impuesto?></td>
                  <td><?=number_format($ventas->sub_total)?></td>
                  <td><?=number_format($ventas->subtotal_importe)?></td>
                  <td><?=number_format($ventas->total)?></td>
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
<script src="controller/script/puc.js"></script>
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

