<div class="br-pagetitle"></div>
<div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea anular este comprobante?</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Este registro contable de eliminará por completo, seguro desea eliminarlo?">Este registro contable de eliminará por completo, seguro desea eliminarlo?</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si eliminar</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
          </div><!-- modal-dialog -->
    </div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <form id="reporte_general" finish="ventas/reporte_general" class="form-layout form-layout-1" >
    <div class="row mg-b-25">
            <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Desde: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="pos" id="pos" value="<?=$pos?>">
                  <input type="hidden" name="control" id="control" value="<?=$control?>">
                  <select name="idcomprobante" id="" class="form-control select2-show-search filter">
                        <?php foreach ($comprobantes as $comprobante) { ?>
                            <option value="<?=$comprobante->iddetalle_documento_sucursal?>"><?=$comprobante->prefijo." - ".$comprobante->ultima_serie?></option>
                        <?php }?>
                  </select>
                </div>
            </div>

            <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Desde: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="start_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>" readonly>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4">
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
                  <th class="wd-3p">ID</th>
                  <th class="wd-3p">Comprobante</th>
                  <th class="wd-1p">Tercero</th>
                  <th class="wd-5p">Sucursal</th>
                  <th class="wd-3p">Fecha</th>
                  <th class="wd-5p">Debito</th>
                  <th class="wd-5p">Credito</th>
                  <th class="wd-5p">Estado</th>
                  <th class="wd-5p">Accion</th>
                </tr>
              </thead>
                <tbody>
                        
                </tbody>
            </table>
    </div>
</div>

<link href="lib/timepicker/jquery.timepicker.css" rel="stylesheet">
<script src="lib/select2/js/select2.min.js"></script>
<link rel="stylesheet" href="lib/totast/src/jquery.toast.css">
<script src="controller/script/ReporteController.js"></script>
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

    $('.fc-datepicker').datepicker({
        
        showOtherMonths: true,
        selectOtherMonths: true
    });

      });
    </script>

    