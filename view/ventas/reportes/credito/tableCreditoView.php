<?php foreach ($ventas as $ventas) {?>
              <tr>
                  <td><?=$ventas->fecha?></td>
                  <td><?=$ventas->idsucursal?></td>
                  <td><?=$ventas->nombre_empleado?></td>
                  <td><?=$ventas->nombre_cliente?></td>
                  <td><?=$ventas->tipo_comprobante." ".$ventas->serie_comprobante."".zero_fill($ventas->num_comprobante,8)?></td>
                  <td><?=$ventas->impuesto?></td>
                  <td><?=number_format($ventas->deuda_total -$ventas->total_pago )?></td>
                  <td><?=number_format($ventas->total_pago)?></td>
                  <td><?=number_format($ventas->deuda_total)?></td>
              </tr>
            <?php } ?>