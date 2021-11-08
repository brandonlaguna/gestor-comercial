<script src="lib/jquery/jquery.min.js"></script>
<script src="js/gestor-contable.js"></script>
<script src="node_modules/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://kit.fontawesome.com/eb724d5aec.js" crossorigin="anonymous"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="lib/peity/jquery.peity.min.js"></script>
<script src="lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="lib/jquery-ui/ui/widgets/datepicker.js"></script>
<script src="lib/moment/min/moment.min.js"></script>
<script src="lib/highlightjs/highlight.pack.min.js"></script>
<script src="node_modules/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="node_modules/tippy.js/dist/tippy-bundle.umd.min.js"></script>
<script src="lib/datatablesV1.0.0/datatables.min.js"></script>
<script src="https://uicdn.toast.com/grid/latest/tui-grid.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.3/api/sum().js"></script>
<script src="node_modules/flatpickr/dist/flatpickr.min.js"></script>
<script src="node_modules/select2/dist/js/select2.full.min.js"></script>
<script src="lib/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="lib/ajax-bootstrap-select/dist/js/ajax-bootstrap-select.min.js"></script>
<script src="js/controller/tooltip-colored.js"></script>
<script src="js/controller/popover-colored.js"></script>
    <script>
      $(function(){
        'use strict'
        // FOR DEMO ONLY
        // menu collapsed by default during first page load or refresh with screen
        // having a size between 992px and 1299px. This is intended on this page only
        // for better viewing of widgets demo.
        $(window).resize(function(){
          minimizeMenu();
        });
        minimizeMenu();
        function minimizeMenu() {
          if(window.matchMedia('(min-width: 992px)').matches && window.matchMedia('(max-width: 1299px)').matches) {
            // show only the icons and hide left menu label by default
            $('.menu-item-label,.menu-item-arrow').addClass('op-lg-0-force d-lg-none');
            $('body').addClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideUp();
          } else if(window.matchMedia('(min-width: 1300px)').matches && !$('body').hasClass('collapsed-menu')) {
            $('.menu-item-label,.menu-item-arrow').removeClass('op-lg-0-force d-lg-none');
            $('body').removeClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideDown();
          }
        }
      });
      
    </script>
    
