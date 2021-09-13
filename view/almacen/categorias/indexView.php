<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
            <a href="#Categorias/nuevaCategoria" class="btn btn-primary btn-with-icon">
            <div class="ht-40">
                <span class="icon wd-40"><i class="fa fa-plus"></i></span>
                <span class="pd-x-15">Nueva Categoria</span>
            </div>
            </a>
<br>
    <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p"></th>
                  <th class="wd-5p">Categoria</th>
                  <th class="wd-5p">Cod. Venta</th>
                  <th class="wd-5p">Cod. Costos</th>
                  <th class="wd-5p">Cod. Devoluciones</th>
                  <th class="wd-5p">Cod. Inventario</th>
                  <th class="wd-5p">Imp. Venta</th>
                  <th class="wd-5p">Imp. Compra</th>
                  <th class="wd-5p">Estado</th>
                  <th class="wd-5p">Accion</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $i=1;
                foreach ($categorias as $categorias) { 
                    $estado = ($categorias->estado=='A')?"fa-check-circle":"fa-times-circle";
                    $color = ($categorias->estado=='A')?"text-success":"text-danger";
                    $message = ($categorias->estado=='A')?"Activo":"Cancelado";
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=$categorias->nombre?></td>
                        <td><?=$categorias->cod_venta?></td>
                        <td><?=$categorias->cod_costos?></td>
                        <td><?=$categorias->cod_devoluciones?></td>
                        <td><?=$categorias->cod_inventario?></td>
                        <td><?=$categorias->imp_compra?></td>
                        <td><?=$categorias->imp_venta?></td>
                        <td><i class="fas <?=$estado." "?> <?=$color?>" data-toggle="tooltip-primary" data-placement="top" title="Estado <?=$message?>"></i></td>
                        <td>
                        <a href="#almacen/view_categoria/<?=$categorias->idcategoria?>" ><i class="fas fa-binoculars text-success"></i></a>&nbsp;
                        <a href="#almacen/edit_categoria/<?=$categorias->idcategoria?>" ><i class="fas fa-pencil-alt text-warning"></i></a>&nbsp;
                        </td>
                        
                    </tr>
                <?php $i++; }?>
            </tbody>
        </div>
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