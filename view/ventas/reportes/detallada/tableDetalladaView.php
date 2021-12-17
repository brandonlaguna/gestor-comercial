<?php foreach ($ventas as $ventas) {?>
              <tr>
                  <td><?=$ventas->fecha?></td>
                  <td><?=$ventas->idsucursal?></td>
                  <td><?=$ventas->nombre_empleado?></td>
                  <td><?=$ventas->nombre_cliente?></td>
                  <td><?=$ventas->tipo_comprobante." ".$ventas->serie_comprobante."".zero_fill($ventas->num_comprobante,8)?></td>
                  <td><?=$ventas->importe_articulo?></td>
                  <td><?=$ventas->nombre_articulo?></td>
                  <td><?=$ventas->idarticulo?></td>
                  <td><?=$ventas->stock_venta?></td>
                  <td><?=$ventas->precio_unidad?></td>
              </tr>
            <?php } ?>