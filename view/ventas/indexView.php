<?php

?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
      <input type="hidden" name="" id="codigo_contable">
            <a href="#ventas/nueva_venta" class="btn btn-primary btn-with-icon">
            <div class="ht-40">
                <span class="icon wd-40"><i class="fa fa-plus"></i></span>
                <span class="pd-x-15">Nueva Venta</span>
            </div>
            </a>
<br>
    <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p"></th>
                  <th class="wd-15p">Cliente</th>
                  <th class="wd-5p">Pago</th>
                  <th class="wd-20p">Cmpte</th>
                  <th class="wd-15p">Fecha</th>
                  <th class="wd-10p">Impuesto</th>
                  <th class="wd-10p">Total</th>
                  <th class="wd-5p">Estado</th>
                  <th class="wd-15p">Accion</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $i=1;
                foreach ($ventas as $ventas) { 
                    $estado = ($ventas->estado_venta=='A')?"fa-check-circle":"fa-times-circle";
                    $color = ($ventas->estado_venta=='A')?"text-success":"text-danger";
                    $message = ($ventas->estado_venta=='A')?"Aceptado":"Cancelado";
                    $tipo_pago = ($ventas->tipo_pago == 'Contado')?"success":"info";

                    ?>
                    <tr>
                        <td><p><?=$i?></p></td>
                        <td><p><?=$ventas->nombre_cliente?></p></td>
                        <td><p class="badge badge-<?=$tipo_pago?>"><?=$ventas->tipo_pago?></p></td>
                        <td><?=$ventas->prefijo." ".$ventas->serie_comprobante."-".zero_fill($ventas->num_comprobante,8)?></td>
                        <td><?=$ventas->fecha?></td>
                        <td><?=$ventas->subtotal_importe?></td>
                        <td><?=$ventas->total?></td>
                        <td><i class="fas <?=$estado." "?> <?=$color?>" data-toggle="tooltip-primary" data-placement="top" title="Estado <?=$message?>"></i></td>
                        <td>
                        <a href="#ventas/detail/<?=$ventas->idventa?>" ><i class="fas fa-binoculars text-success"></i></a>&nbsp;
                        <a href="#ventas/edit_venta/<?=$ventas->idventa?>" ><i class="fas fa-pen-nib text-warning"></i></a>&nbsp;
                        <a href="#file/venta/<?=$ventas->idventa?>" ><i class="fas fa-print text-info"></i></a>
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
            searchPlaceholder: 'Buscar...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          }
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>