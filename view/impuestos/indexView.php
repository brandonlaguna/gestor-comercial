<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <form id="reporte" finish="impuestos/generar_reporte" class="form-layout form-layout-1" >
    <div class="row mg-b-25">

            <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Desde: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control fc-datepicker fc-datepicker-color fc-datepicker-primary" autocomplete="off" name="start_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>">
                </div>
            </div>

            <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Hasta: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control fc-datepicker fc-datepicker-color fc-datepicker-primary" autocomplete="off"name="end_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>">
                </div>
            </div>
    </div>
    </form>
    <div class="col-sm-12">
    <button class="btn btn-oblong btn-success" onclick="sendForm('reporte')" style="width:100%;">Descargar reporte</button>
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

