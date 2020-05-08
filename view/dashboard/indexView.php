<?php 
// foreach ($todaySales[0] as $today) {
// }
?>
<div class="br-pagetitle"></div>
      <div class="br-pagebody pd-x-20 pd-sm-x-30">

        <div class="row no-gutters widget-1 shadow-base">

          <div class="col-sm-6 col-lg-3">
            <?=$this->frameview("dashboard/modules/todaySales",array("total"=>$todaySales[0]))?>
          </div><!-- col-3 -->

          <div class="col-sm-6 col-lg-3 mg-t-1 mg-sm-t-0">
            <?=$this->frameview("dashboard/modules/weekSales",array("total"=>$weekSales[0]))?>
          </div><!-- col-3 -->

          <div class="col-sm-6 col-lg-3 mg-t-1 mg-lg-t-0">
            <?=$this->frameview("dashboard/modules/monthSales",array("total"=>$monthSales[0]))?>
          </div><!-- col-3 -->

          <div class="col-sm-6 col-lg-3 mg-t-1 mg-lg-t-0">
            <?=$this->frameview("dashboard/modules/overallSales",array("total"=>$overallSales[0]))?>
          </div><!-- col-3 -->

        </div><!-- row -->

        <div class="row row-sm mg-t-20">

          <div class="col-sm-6 col-lg-4">
            <?=$this->frameview("dashboard/modules/businessMonitoring",array("dataEmpresa"=>$dataEmpresa))?>

          </div><!-- col-4 -->

          <div class="col-sm-6 col-lg-4 mg-t-20 mg-sm-t-0">
            <?=$this->frameview("dashboard/modules/salesMonitoring",array("dataDeuda"=>$dataDeudaVenta))?>
          </div><!-- col-4 -->

          <div class="col-sm-6 col-lg-4 mg-t-20 mg-lg-t-0">
            <?=$this->frameview("dashboard/modules/buyMonitoring",array("dataDeuda"=>$dataDeudaCompra))?>
          </div><!-- col-4 -->
        </div><!-- row -->

        <div class="row row-sm mg-t-20">
          <?=$this->frameview("dashboard/modules/bestSell",array("bestSell"=>$bestSell))?>
        </div><!-- row -->

        <div class="row row-sm mg-t-20">

          <div class="col-lg-6">
            <?=$this->frameview("dashboard/modules/transactionClient",array("historialPagoCliente"=>$historialPagoCliente))?>
          </div><!-- col-6 -->

          <div class="col-lg-6 mg-t-20 mg-lg-t-0">

        </div><!-- row -->

      </div><!-- br-pagebody -->
      <footer class="br-footer">
        <div class="footer-left">
         
      </footer>

    <script>
      $(function(){
        'use strict'

        // FOR DEMO ONLY
        // menu collapsed by default during first page load or refresh with screen
        // having a size between 992px and 1199px. This is intended on this page only
        // for better viewing of widgets demo.
        $(window).resize(function(){
          minimizeMenu();
        });

        minimizeMenu();

        function minimizeMenu() {
          if(window.matchMedia('(min-width: 992px)').matches && window.matchMedia('(max-width: 1199px)').matches) {
            // show only the icons and hide left menu label by default
            $('.menu-item-label,.menu-item-arrow').addClass('op-lg-0-force d-lg-none');
            $('body').addClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideUp();
          } else if(window.matchMedia('(min-width: 1200px)').matches && !$('body').hasClass('collapsed-menu')) {
            $('.menu-item-label,.menu-item-arrow').removeClass('op-lg-0-force d-lg-none');
            $('body').removeClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideDown();
          }
        }
      });
    </script>

<script src="lib/chart.js/Chart.min.js"></script>
<script src="js/controller/dashboard.js"></script>
<script src="js/controller/widgets.js"></script>
<script src="lib/rickshaw/vendor/d3.min.js"></script>
<script src="lib/rickshaw/vendor/d3.layout.min.js"></script>
<script src="lib/rickshaw/rickshaw.min.js"></script>
<script src="lib/jquery.flot/jquery.flot.js"></script>
<script src="lib/jquery.flot/jquery.flot.resize.js"></script>
<script src="lib/flot-spline/js/jquery.flot.spline.min.js"></script>
<script src="lib/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="js/controller/map.shiftworker.js"></script>
<script src="js/controller/ResizeSensor.js"></script>
<script src="lib/select2/js/select2.full.min.js"></script>
<link href="lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
