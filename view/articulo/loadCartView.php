<?php
 foreach ($items as $item) {?>
    <tr>
    <td><input type="hidden" name="description" value="<?=$item->descripcion?>"><?=$item->descripcion?></td>
    <td><input type="hidden" name="stock_ingreso" value="<?=$item->cdi_stock_ingreso?>"><?=$item->cdi_stock_ingreso?></td>
    <td><input type="hidden" name="precio_unitario" value="<?=$item->cdi_precio_unitario?>"><?=number_format($item->cdi_precio_unitario,2,'.',',')?></td>
    <td><input type="hidden" name="impuesto" value="<?=$item->cdi_importe?>"><?=$item->cdi_importe?>%</td>
    <td><input type="hidden" name="total" value="<?=$item->cdi_precio_total_lote?>"><?=number_format($item->cdi_precio_total_lote,2,'.',',')?></td>
    <td><input type="hidden" name="idarticulo" value="<?=$item->cdi_idarticulo?>"><i class="fas fa-minus-circle text-danger pointer" onclick="deleteItem(<?=$item->cdi_id?>)"></i></th>
    </tr>
<?php }?>
