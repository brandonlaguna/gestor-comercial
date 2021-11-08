<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <form id="download_report" finish="file/XLS_reporte" class="form-layout form-layout-1" >
    <div class="row mg-b-25">

            <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <input type="hidden" name="control" id="control" value="<?=$control?>">
                  <input type="hidden" name="cuenta_hasta" value="0">
                  <input type="hidden" name="rcc_type" id="rcc_type" value="XLS">
                  <input type="hidden" name="filetype" id="filetype" value="xls">
                  <label class="form-control-label">Articulo: <span class="tx-danger">*</span></label>
                    <select class="form-control select2-show-search" data-placeholder="Choose one (with searchbox)" name="idarticulo">
                        <?php foreach ($articulos as $articulo) {?>
                            <option value="<?=$articulo->idarticulo?>"><?=$articulo->idarticulo." ".$articulo->nombre_articulo?></option>
                        <?php }?>
                    </select>
                </div>
            </div><!--col-4-->

            <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Desde: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="start_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>">
                </div>
            </div><!--col-4-->

            <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Hasta: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="end_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>">
                </div>
            </div><!--col-4-->

    </div>
    </form>
</div>
<div style="width:100%;">
    <button class="btn btn-primary" id=sendForm onclick="sendForm('download_report')" style="width:100%;"><i class="fa fa-download mg-r-10"></i> Descargar reporte</button>
    </div>
</div>
<div id="reporte" class="mt-4">

</div><!--reporte response-->
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

      });
    </script>

<script>
      $(function(){

        'use strict' 
        if($().select2) {
    $('.select2').select2({
      minimumResultsForSearch: Infinity,
      placeholder: 'Choose one'
    });

    // Select2 by showing the search
    $('.select2-show-search').select2({
      minimumResultsForSearch: ''
    });

    // Select2 with tagging support
    $('.select2-tag').select2({
      tags: true,
      tokenSeparators: [',', ' ']
    });
  }
  $('.br-toggle').on('click', function(e){
          e.preventDefault();
          $(this).toggleClass('on');
        });

      });
    </script>

