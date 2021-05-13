<div class="br-pagetitle"></div>
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
                <th class="wd-5p">Comprobante</th>
                  <th class="wd-5p">Fecha</th>
                  <th class="wd-5p">Tercero</th>
                  <th class="wd-5p">Cuenta</th>
                  <th class="wd-5p">Debito</th>
                  <th class="wd-5p">Credito</th>
                  <th class="wd-5p">Estado</th>
                </tr>
              </thead>
                <tbody>
                        
                </tbody>
            </table>
    </div>
</div>

<link href="lib/timepicker/jquery.timepicker.css" rel="stylesheet">

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


    