<?php foreach ($ventas as $ventas) {?>
              <tr>
                  <td><?=$ventas->fecha?></td>
                  <td><?=$ventas->idsucursal?></td>
                  <td><?=$ventas->nombre_empleado?></td>
                  <td><?=$ventas->nombre_cliente?></td>
                  <td><?=$ventas->tipo_comprobante." ".$ventas->serie_comprobante."".zero_fill($ventas->num_comprobante,8)?></td>
                  <td><?=$ventas->impuesto?></td>
                  <td><?=number_format($ventas->sub_total)?></td>
                  <td><?=number_format($ventas->subtotal_importe)?></td>
                  <td><?=number_format($ventas->total)?></td>
              </tr>
            <?php } ?>