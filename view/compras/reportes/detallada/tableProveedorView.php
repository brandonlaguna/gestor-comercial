<?php foreach ($compras as $compras) {?>
              <tr>
                  <td><?=$compras->fecha?></td>
                  <td><?=$compras->nombre_empleado?></td>
                  <td><?=$compras->nombre_proveedor?></td>
                  <td><?=$compras->prefijo." ".$compras->serie_comprobante."".zero_fill($compras->num_comprobante,8)?></td>
                  <td><?=$compras->impuesto?></td>
                  <td><?=$compras->nombre_articulo?></td>
                  <td><?=$compras->stock_ingreso?></td>
                  <td><?=$compras->stock?></td>
                  <td>0</td>
              </tr>
            <?php } ?>