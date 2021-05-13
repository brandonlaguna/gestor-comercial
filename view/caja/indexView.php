<div class="br-pagetitle"></div>
<div class="br-pagebody">
<div class="br-section-wrapper">
<div class="row">

            <div class="col-xl-12">
            <form id="save_az" finish="caja/cierre">
            <input type="hidden" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="date" placeholder="MM/DD/YYYY" id="date" value="<?=date("m/d/Y")?>">
                    <input type="hidden" class="form-control efectivo" denominacion="100000" id="1" name="100000">
                    <input type="hidden" class="form-control efectivo" denominacion="50000" id="2" name="50000">
                    <input type="hidden" class="form-control efectivo" denominacion="20000" id="3" name="20000">
                    <input type="hidden" class="form-control efectivo" denominacion="10000" id="4" name="10000">
                    <input type="hidden" class="form-control efectivo" denominacion="5000" id="5" name="5000">
                    <input type="hidden" class="form-control efectivo" denominacion="2000" id="6" name="2000">
                    <input type="hidden" class="form-control efectivo" denominacion="1000" id="7" name="1000">
                    <input type="hidden" class="form-control efectivo" denominacion="500" id="8" name="500">
                    <input type="hidden" class="form-control efectivo" denominacion="200" id="9" name="200">
                    <input type="hidden" class="form-control efectivo" denominacion="100" id="10" name="100">
                    <input type="hidden" class="form-control efectivo" denominacion="50" id="11" name="50">
                    <input type="hidden" class="form-control"  name="debito">
                    <input type="hidden" class="form-control" name="credito">
                    <input type="hidden" class="form-control" name="pagos">

                <!-- <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">100,000: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  >
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">50,000: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_2">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">20,000: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_3">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">10,000: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_4">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">5,000: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_5">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">2,000: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_6">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">1,000: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                   
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_7">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">500: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                   
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_8">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">200: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_9">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">100: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_10">
                  </div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">50: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-sm-t-0">
                  <input type="text" class="form-control" disabled id="efectivo_11">
                  </div>
                </div>
                
                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">Debito: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-t-10 mg-sm-t-0"></div>
                </div>
                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">Credito: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-t-10 mg-sm-t-0"></div>
                </div>

                <div class="row mg-t-10">
                  <label class="col-sm-3 form-control-label">Pagos: <span class="tx-danger">*</span></label>
                  <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                    
                  </div>
                  <div class="col-sm-5 mg-t-10 mg-sm-t-0"></div>
                </div> -->
            
            </form>
            <button class="btn btn-info" style="width:100%;" onclick="sendForm('save_az')">Generar Reporte A-Z</button>
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