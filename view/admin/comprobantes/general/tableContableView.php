
<div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-3p">ID</th>
                  <th class="wd-3p">Comprobante</th>
                  <th class="wd-1p">Tercero</th>
                  <th class="wd-5p">Sucursal</th>
                  <th class="wd-3p">Fecha</th>
                  <th class="wd-5p">Debito</th>
                  <th class="wd-5p">Credito</th>
                  <th class="wd-5p">Estado</th>
                  <th class="wd-5p">Accion</th>
                </tr>
              </thead>
                <tbody>
                        
<?php 
$i=1;
foreach ($detalle as $detalle) { 
        if($detalle->estado == "A" && $detalle->debito ==$detalle->credito){
            $estado = "fa-check-circle";
            $color = "text-success";
            $message ="Aceptado";
        }elseif($detalle->estado =="A" && $detalle->debito != $detalle->credito || $detalle->debito !=""){
            $estado = "fa-exclamation-circle";
            $color = "text-warning";
            $message ="Alerta";
        }
        else{
            $estado = "fa-times-circle";
            $color = "text-dange";
            $message ="Cancelado";
        }
    ?>
    <tr>
        <td><?=$i?></td>
        <td><?=$detalle->serie_comprobante."".zero_fill($detalle->num_comprobante,8)?></td>
        <td><p data-toggle="tooltip-primary" data-placement="top" title="<?=$detalle->nombre_tercero." - ".$detalle->documento_tercero.' - Tel: '.$detalle->telefono_tercero?>"><?=(strlen($detalle->nombre_tercero) > 30)?substr($detalle->nombre_tercero,0,30):$detalle->nombre_tercero;?><p></td>
        <td><?=$detalle->idsucursal?></td>
        <td><?=$detalle->fecha?></td>
        <td><?=moneda($detalle->debito)?></td>
        <td><?=moneda($detalle->credito)?></td>
        <td><i class="fas <?=$estado." "?> <?=$color?>" data-toggle="tooltip-primary" data-placement="top" title="Estado <?=$message?>"></i></td>
        <td>
            <a href="#comprobantes/edit/<?=$detalle->cc_id_transa?>"><i class="fas fa-pencil-alt text-warning"></i></a>
            <?php if($detalle->estado == 'A' ){?>
                <i class="fas fa-trash text-danger" data-toggle="modal" data-target="#modaldemo2" onclick="sendIdModal('comprobantes/delete/<?=$detalle->cc_id_transa?>')"></i>&nbsp;
            <?php }?>
            <a href="#file/comprobantes/<?=$detalle->cc_id_transa?>" target="" ><i class="fas fa-print text-info"></i></a>&nbsp;
            <a href="#file/comprobantes/<?=$detalle->cc_id_transa?>/standard" target="" ><i class="fas fa-print text-info"></i></a>
        </td>
    </tr>
<?php $i++;}?>

        </tbody>
    </table>
</div>
<link href="lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
<script src="lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="lib/datatables.net/js/dataTables.buttons.min.js"></script>
<script src="lib/datatables.net/js/buttons.flash.min.js"></script>
<script src="lib/datatables.net/js/jszip.min.js"></script>
<script src="lib/datatables.net/js/pdfmake.min.js"></script>
<script src="lib/datatables.net/js/vfs_fonts.js"></script>
<script src="lib/datatables.net/js/buttons.html5.min.js"></script>
<script src="lib/datatables.net/js/buttons.print.min.js"></script>
<link href="lib/datatables.net/css/buttons.dataTables.min.css" rel="stylesheet">
<script src="js/controller/tooltip-colored.js"></script>
<script src="js/controller/popover-colored.js"></script>
<script src="lib/select2/js/select2.min.js"></script>

<script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
          responsive: true,
          language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          },
          "columnDefs": [
            {"className": "dt-right", "targets": [1,2,3,4,5,6]}
        ],
        destroy: true
          
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>
