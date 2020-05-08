<?php foreach ($ventas as $ventas) {?>
   <tr>
    <td><?=$ventas->fecha?></td>
    <td><?=$ventas->idsucursal?></td>
    <td><?=$ventas->nombre_empleado?></td>
    <td><?=$ventas->nombre_cliente?></td>
    <td><?=$ventas->tipo_comprobante." ".$ventas->serie_comprobante."".zero_fill($ventas->num_comprobante,8)?></td>
    <td><?=$ventas->impuesto?></td>
    <td><?=$ventas->sub_total?></td>
    <td><?=$ventas->subtotal_importe?></td>
    <td><?=$ventas->total?></td>
    <td>
    <a href="#ventas/detail/<?=$ventas->idventa?>" ><i class="fas fa-binoculars text-success"></i></a>&nbsp;
    <a href="#file/venta/<?=$ventas->idventa?>" ><i class="fas fa-print text-info"></i></a>
    </td>
   </tr>
<?php } ?>