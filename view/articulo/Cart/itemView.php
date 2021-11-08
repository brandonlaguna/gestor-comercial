<?php
 foreach ($items as $item) {?>
    <tr>
    <td><input type="hidden" name="description" value="<?=$item->nombre_articulo?>"><?=$item->nombre_articulo?></td>
    <td><input type="hidden" name="stock_ingreso" value="<?=$item->stock_ingreso?>"><?=$item->stock_ingreso?></td>
    <td><input type="hidden" name="precio_unitario" value="<?=$item->precio_compa?>"><?=number_format($item->precio_compra,2,'.',',')?></td>
    <td><input type="hidden" name="impuesto" value="<?=$item->importe_categoria?>"><?=$item->importe_categoria?>%</td>
    <td><input type="hidden" name="total" value="<?=$item->precio_total_lote?>"><?=number_format($item->precio_total_lote,2,'.',',')?></td>
    <td><input type="hidden" name="idarticulo" value="<?=$item->idarticulo?>"><i class="fas fa-minus-circle text-danger pointer" onclick="deleteItemDetalle(<?=$item->iddetalle_ingreso?>)"></i></th>
    </tr>
<?php }?>
