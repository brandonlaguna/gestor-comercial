<?php
 foreach ($items as $item) {?>
    <tr>
    <td><input type="hidden" name="description" value="<?=$item->descripcion?>">
    <p data-toggle="tooltip-primary" data-placement="top" title="<?=$item->descripcion?>"><?=(strlen($item->descripcion) > 55)?substr($item->descripcion,0,55).' [...]':$item->descripcion;?><p>
    </td>
    <td><input type="text" class="input-outline-bottom" style="width:90px;text-align: right;" id="quantity<?=$item->cdi_id?>" name="stock_ingreso" onchange="changeCartValue('quantity<?=$item->cdi_id?>',this.value,'cdi_stock_ingreso','<?=$item->cdi_id?>')" value="<?=$item->cdi_stock_ingreso?>"></td>
    <td><input type="text" class="input-outline-bottom" style="width:120px;text-align: right;"id="precio_unitario<?=$item->cdi_id?>" name="precio_unitario" onchange="changeCartValue('precio_unitario<?=$item->cdi_id?>',this.value,'cdi_precio_unitario','<?=$item->cdi_id?>')" value="<?=$item->cdi_precio_unitario?>"></td>
    <td>
    <div class="input-append btn-group">
                <input class="span2 input-outline-bottom calculate" style="width:100px;" name="impuesto" id="tax<?=$item->cdi_id?>" type="text" value="<?=$item->cdi_importe?>" readonly>
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                <?php foreach ($impuestos as $selectimpuesto) {?>
                  <p class="dropdown-item change_tax" style="margin:0" onclick="changeCartValue('tax<?=$item->cdi_id?>','<?=$selectimpuesto->im_porcentaje?>','cdi_importe','<?=$item->cdi_id?>')"><?=$selectimpuesto->im_nombre?> (<?=$selectimpuesto->im_porcentaje?>%)</p>
                <?php }?>
                </div>
        <!-- <input type="hidden" name="impuesto" value="<?=$item->cdi_importe?>"><?=$item->cdi_importe?>% -->
    </td>
    <td><input type="hidden" name="total" value="<?=$item->cdi_precio_total_lote?>"><?=number_format($item->cdi_precio_total_lote,2,'.',',')?></td>
    <td><input type="hidden" name="idarticulo" value="<?=$item->cdi_idarticulo?>"><i class="fas fa-minus-circle text-danger pointer" onclick="deleteItem(<?=$item->cdi_id?>)"></i></th>
    </tr>
<?php }?>

<script src="js/controller/tooltip-colored.js"></script>
