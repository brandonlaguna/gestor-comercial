<?php 
$i=1;
foreach ($detalle as $detalle) { ?>
    <tr>
        <td><?=$i?></td>
        <td><?=$detalle->tipo_comprobante." ".$detalle->serie_comprobante."".zero_fill($detalle->num_comprobante,8)?></td>
        <td><?=$detalle->nombre_tercero?></td>
        <td><?=$detalle->idsucursal?></td>
        <td><?=$detalle->fecha?></td>
        <td><?=($detalle->total - $detalle->subtotal_importe)?></td>
        <td><?=$detalle->subtotal_importe?></td>
        <td><?=$detalle->total?></td>
    </tr>
<?php $i++;}?>