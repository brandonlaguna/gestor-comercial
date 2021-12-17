<div class="br-pagetitle"></div>
<div class="br-pagebody">
<div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea eliminar este articulo?</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Este articulo está relacionado a registros, por lo tanto dejara de ser visible solo para ingresos y ventas">Este articulo está relacionado a registros, por lo tanto dejara de ser visible solo para ingresos y ventas</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si eliminar</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
            </div><!-- modal-dialog -->
  </div>
    <div class="br-section-wrapper">
    <div class="table-wrapper">
    
    <a href="#articulo/new" class="btn btn-primary btn-with-icon">
  <div class="ht-40">
    <span class="icon wd-40"><i class="fa fa-plus"></i></span>
    <span class="pd-x-15">Nuevo Articulo</span>
  </div>
</a>
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p">ID</th>
                  <th class="wd-15p">Nombre</th>
                  <th class="wd-15p">Categoria</th>
                  <th class="wd-6p">U. Medida</th>
                  <th class="wd-6p">Stock</th>
                  <th class="wd-15p">Descripcion</th>
                  <th class="wd-15p">Costo</th>
                  <th class="wd-15p">Precio Venta</th>
                  <th class="wd-15p">Estado</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php
                $i=1;
                foreach ($articulos as $articulos) {
                    $estado = ($articulos->st_estado=='A')?"fa-check-circle":"fa-times-circle";
                    $color = ($articulos->st_estado=='A')?"text-success":"text-danger";
                    $message = ($articulos->st_estado=='A')?"Activo":"Inactivo"; 
                    ?>
                    <tr>
                    <td><?=$articulos->idarticulo?></td>
                    <td><?=$articulos->nombre_articulo?></td>
                    <td><?=$articulos->nombre_categoria?></td>
                    <td><?=$articulos->prefijo?></td>
                    <td><?=$articulos->stock?></td>
                    <td><p data-toggle="tooltip-primary" data-placement="top" title="<?=$articulos->descripcion_articulo?>"><?=(strlen($articulos->descripcion_articulo) > 30)?substr($articulos->descripcion_articulo,0,30):$articulos->descripcion_articulo;?><p></td>
                    <td><?=$articulos->costo_producto?></td>
                    <td><?=$articulos->precio_venta?></td>
                    <td><i class="fas <?=$estado." "?> <?=$color?>" data-toggle="tooltip-primary" data-placement="top" title="Estado <?=$message?>"></i></td>
                    <td>
                        <a href="#articulo/detail/<?=$articulos->idarticulo?>" ><i class="fas fa-binoculars text-success"></i></a>&nbsp;
                        <a href="#articulo/edit/<?=$articulos->idarticulo?>" ><i class="fas fa-pencil-alt text-warning"></i></a>&nbsp;
                        <i class="fas fa-trash text-danger" data-toggle="modal" data-target="#modaldemo2" onclick="sendIdModal('articulo/delete/<?=$articulos->idarticulo?>')"></i>&nbsp;
                    </td>
                    </tr>
                <?php } ?>
              </tbody>

    </div>
</div>


    <link href="lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
    <script src="lib/select2/js/select2.min.js"></script>
    <script src="js/controller/tooltip-colored.js"></script>
    <script src="js/controller/popover-colored.js"></script>
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
