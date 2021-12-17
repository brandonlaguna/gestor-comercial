<?php
 foreach ($items as $item) {?>
    <tr>
    <td><input type="hidden" name="description" value="<?=$item->cdi_idarticulo?>"><?=$item->cdi_idarticulo." - ".$item->cdi_detalle?></td>
    <td><input type="hidden" name="stock_ingreso" value="<?=$item->cdi_tercero?>"><?=$item->cdi_tercero?></td>
    <td><input type="hidden" name="precio_unitario" value="<?=$item->cdi_precio_unitario?>"><?=number_format($item->cdi_precio_unitario,2,'.',',')?></td>
    <td><input type="hidden" name="debito" value="<?=$item->cdi_debito?>"><?=number_format($item->cdi_debito,2,'.',',')?></td>
    <td><input type="hidden" name="credito" value="<?=$item->cdi_credito?>"><?=number_format($item->cdi_credito,2,'.',',')?></td>
    <td><input type="hidden" name="idarticulo" value="<?=$item->cdi_idarticulo?>"><i class="fas fa-minus-circle text-danger pointer" onclick="deleteItem(<?=$item->cdi_id?>)"></i></th>
    </tr>
<?php }?>
