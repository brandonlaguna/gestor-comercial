<div class="br-pagetitle"></div>
<div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea anular esta compra?</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Esta compra esta relacionado a registros, por lo tanto solo puede ser anulada">Esta compra esta relacionado a registros, por lo tanto solo puede ser anulada</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si anular</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
          </div><!-- modal-dialog -->
    </div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
            <a href="#compras/nuevo_ingreso" class="btn btn-primary btn-with-icon">
            <div class="ht-40">
                <span class="icon wd-40"><i class="fa fa-plus"></i></span>
                <span class="pd-x-15">Nueva Compra</span>
            </div>
            </a>
<br>
    <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p"></th>
                  <th class="wd-15p">Proveedor</th>
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
                foreach ($compras as $compras) { 
                    $estado = ($compras->estado_ingreso=='A')?"fa-check-circle":"fa-times-circle";
                    $color = ($compras->estado_ingreso=='A')?"text-success":"text-danger";
                    $message = ($compras->estado_ingreso=='A')?"Aceptado":"Cancelado";
                    ?>
                    <tr>
                        <td><p><?=$i?></p></td>
                        <td><p><?=$compras->nombre_proveedor?></p></td>
                        <td><p class="badge badge-success"><?=$compras->tipo_pago?></p></td>
                        <td><?=$compras->prefijo." ".$compras->serie_comprobante."-".zero_fill($compras->num_comprobante,8)?></td>
                        <td><?=$compras->fecha?></td>
                        <td><?=$compras->subtotal_importe?></td>
                        <td><?=number_format($compras->total,2,'.',',')?></td>
                        <td><i class="fas <?=$estado." "?> <?=$color?>" data-toggle="tooltip-primary" data-placement="top" title="Estado <?=$message?>"></i></td>
                        <td>
                        <a href="#compras/detail/<?=$compras->idingreso?>" ><i class="fas fa-binoculars text-success"></i></a>&nbsp;
                        <a href="#compras/edit_compra/<?=$compras->idingreso?>"><i class="fas fa-pencil-alt text-warning"></i></a>
                        <?php if($compras->estado_ingreso == 'A' ){?>
                          <i class="fas fa-trash text-danger" data-toggle="modal" data-target="#modaldemo2" onclick="sendIdModal('compras/delete/<?=$compras->idingreso?>')"></i>&nbsp;
                        <?php }?>
                        <a href="#file/ingreso/<?=$compras->idingreso?>" target="" ><i class="fas fa-print text-info"></i></a>
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