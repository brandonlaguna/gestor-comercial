<?php foreach ($detalle as $detalle) {?>
              <tr>
                  <td><?=$detalle->fecha?></td>
                  <td><?=$detalle->idsucursal?></td>
                  <td><?=$detalle->nombre_empleado?></td>
                  <td><?=$detalle->nombre_tercero?></td>
                  <td><?=$detalle->tipo_comprobante." ".$detalle->serie_comprobante."".zero_fill($detalle->num_comprobante,8)?></td>
                  <td><?=$detalle->importe_articulo?></td>
                  <td><?=$detalle->nombre_articulo?></td>
                  <td><?=$detalle->idarticulo?></td>
                  <td><?=number_format($detalle->stock_cantidad,2,'.',',')?></td>
                  <td><?=number_format($detalle->precio_unidad,2,'.',',')?></td>
              </tr>
            <?php } ?>