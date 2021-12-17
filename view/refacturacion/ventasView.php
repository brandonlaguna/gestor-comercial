<div class="br-pagetitle"></div>
<div class="br-pagebody">
        <div class="br-section-wrapper">
        <div class="linearLoading"></div>
        <form id="refacturacion" finish="" class="form-layout form-layout-1" >
        
    <div class="row mg-b-25">
            <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Desde: <span class="tx-danger">*</span></label>
                  <input type="hidden" id="pos" name="pos" value="<?=$pos?>">
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="start_date" id="start_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>">
                </div>
            </div>

            <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Hasta: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="end_date" id="end_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>">
                </div>
            </div>

            <div class="col-sm-12 col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Porcentaje: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control " name="porcentaje" id="porcentaje" placeholder="" value="">
                </div>
            </div>
   </form>
            <div class="col-sm-12 col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Iniciar: <span class="tx-danger">*</span></label>
                  <div class="btn btn-primary sendButton" id="sendButton"><i class="fas fa-caret-right mg-r-10"></i>Enviar</div>
                </div>
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
<script>
$('.fc-datepicker').datepicker({
        
          showOtherMonths: true,
          selectOtherMonths: true
      });
</script>