<div class="br-pagetitle"></div>
<div class="br-pagebody">
<div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea eliminar este tercero?</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Este tercero esta relacionado a registros, por lo tanto dejara de ser visible solo para ingresos y ventas">Este tercero esta relacionado a registros, por lo tanto dejara de ser visible solo para ingresos y ventas</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si eliminar</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
            </div><!-- modal-dialog -->
</div>

    <div class="br-section-wrapper">
      <input type="hidden" name="" id="codigo_contable">
            <a href="#almacen/new_tercero" class="btn btn-primary btn-with-icon">
            <div class="ht-40">
                <span class="icon wd-40"><i class="fa fa-plus"></i></span>
                <span class="pd-x-15">Nuevo Tercero</span>
            </div>
            </a>
<br>
    <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p"></th>
                  <th class="wd-5p">Nombre</th>
                  <th class="wd-5p">Documento</th>
                  <th class="wd-5p">Emaiil</th>
                  <th class="wd-5p">Telefono</th>
                  <th class="wd-5p">Direccion</th>
                  <th class="wd-5p">Tipo de Tercero</th>
                  <th class="wd-5p">Estado</th>
                  <th class="wd-5p">Accion</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $i=1;
                foreach ($personas as $personas) { 
                    $estado = ($personas->estado_persona=='A')?"fa-check-circle":"fa-times-circle";
                    $color = ($personas->estado_persona=='A')?"text-success":"text-danger";
                    $message = ($personas->estado_persona=='A')?"Activo":"Inactivo";
                    $tipo_persona = ($personas->tipo_persona == 'Proveedor')?"success":"info";
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=$personas->nombre_persona?></td>
                        <td><?=$personas->num_documento?></td>
                        <td><?=$personas->email?></td>
                        <td><?=$personas->telefono?></td>
                        <td><?=$personas->direccion_departamento?></td>
                        <td><p class="badge badge-<?=$tipo_persona?>"><?=$personas->tipo_persona?></p></td>
                        <td><i class="fas <?=$estado." "?> <?=$color?>" data-toggle="tooltip-primary" data-placement="top" title="Estado <?=$message?>"></i></td>
                        <td>
                        <a href="#almacen/detail_tercero/<?=$personas->idpersona?>" ><i class="fas fa-binoculars text-success"></i></a>&nbsp;
                        <a href="#almacen/update_tercero/<?=$personas->idpersona?>" ><i class="fas fa-pen-nib text-warning"></i></a>&nbsp;
                        <?php if($personas->estado == 'A'){?>
                          <i class="fas fa-trash text-danger" data-toggle="modal" data-target="#modaldemo2" onclick="sendIdModal('almacen/delete_tercero/<?=$personas->idpersona?>')"></i>&nbsp;
                        <?php }?>
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