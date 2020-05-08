<?php foreach ($compras as $compras) {?>
              <tr>
                  <td><?=$compras->fecha?></td>
                  <td><?=$compras->idsucursal?></td>
                  <td><?=$compras->nombre_empleado?></td>
                  <td><?=$compras->nombre_proveedor?></td>
                  <td><?=$compras->prefijo." ".$compras->serie_comprobante."".zero_fill($compras->num_comprobante,8)?></td>
                  <td><?=$compras->impuesto?></td>
                  <td><?=$compras->sub_total?></td>
                  <td><?=$compras->subtotal_importe?></td>
                  <td><?=$compras->total?></td>
                  <td>
                    <a href="#compras/detail/<?=$compras->idingreso?>" ><i class="fas fa-binoculars text-success"></i></a>&nbsp;
                    <a href="#file/venta/<?=$compras->idingreso?>" ><i class="fas fa-print text-info"></i></a>
                  </td>
              </tr>
            <?php } ?>