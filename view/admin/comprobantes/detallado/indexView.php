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
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="start_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>">
                </div>
            </div>
            <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Hasta: <span class="tx-danger">*</span></label>
                  <input type="text" class="form-control filter fc-datepicker fc-datepicker-color fc-datepicker-primary" name="end_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>">
                </div>
            </div>

    </div>
    </form>
    <div class="col-sm-12">
    <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
              <tr>
                  <th class="wd-5p">Fecha</th>
                  <th class="wd-5p">Sucursal</th>
                  <th class="wd-5p">Empleado</th>
                  <th class="wd-5p">Cliente</th>
                  <th class="wd-5p">Comprobante</th>
                  <th class="wd-5p">Impuesto</th>
                  <th class="wd-5p">Articulo</th>
                  <th class="wd-5p">Codigo</th>
                  <th class="wd-5p">Cantidad</th>
                  <th class="wd-5p">P. Unitario</th>
                </tr>
              </thead>
                <tbody id="reporte">
                        
                </tbody>
            </table>
    </div>
</div>

<link href="lib/timepicker/jquery.timepicker.css" rel="stylesheet">
<link href="lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
<script src="lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
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
    <script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          responsive: true,
          language: {
            searchPlaceholder: 'Buscar...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          }
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>

    