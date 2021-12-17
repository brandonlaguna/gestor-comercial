<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="col-sm-12">
    <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p">Articulo</th>
                  <th class="wd-5p">Cant Comp</th>
                  <th class="wd-5p">Costo Prom</th>
                  <th class="wd-5p">Costo T</th>
                  <th class="wd-5p">Cant Ven</th>
                  <th class="wd-5p">Venta u.</th>
                  <th class="wd-5p">Venta t.</th>
                  <th class="wd-5p">Costo v.</th>
                  <th class="wd-5p">Ganancia</th>
                  <th class="wd-5p">Utilidad</th>
                </tr>
              </thead>
              <tbody id="reporte">
              <?php foreach ($array as $array) {?>
              <tr>
                  <td><?=$array[0]?></td>
                  <td><?=$array[1]?></td>
                  <td><?=$array[2]?></td>
                  <td><?=$array[3]?></td>
                  <td><?=$array[4]?></td>
                  <td><?=$array[5]?></td>
                  <td><?=$array[6]?></td>
                  <td><?=$array[7]?></td>
                  <td><?=$array[8]?></td>
                  <td><?=$array[9]?></td>
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
 
      // var data = ;
        $('#datatable1').DataTable({
          responsive: true,
          language: {
            searchPlaceholder: 'Buscar',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          }
          
          
        });
        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>

