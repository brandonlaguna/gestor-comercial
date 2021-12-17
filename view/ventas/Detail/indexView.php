<?php foreach ($venta as $venta) {} ?>
<?php foreach ($calculo as $calculo) {} ?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="form-layout form-layout-1">
            <div class="row mg-b-25">

            <div class="col-lg-4">
                <div class="form-group">
                <input type="hidden" name="contabilidad" id="contabilidad" value="0">
                  <label class="form-control-label">Proveedor: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$venta->nombre_cliente." - ".$venta->num_documento?>" placeholder="" disabled>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de pago: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$venta->tipo_pago?>" placeholder="" disabled>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Comprobante: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$venta->tipo_comprobante.": ".$venta->serie_comprobante."-".zero_fill($venta->num_comprobante,8);?>" placeholder="" disabled>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Fecha: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$venta->fecha?>" placeholder="" disabled>
                </div>
              </div>
              <?php if($venta->fecha_final !="0000-00-00"){?>
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Fecha Final de pago: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$venta->fecha?>" placeholder="" disabled>
                </div>
              </div>
              <?php } ?>

            </div>
        </div>

        <table class="table">
              <thead>
                <th>Descripcion</th>
                <th>Cantidad</th>
                <th>Costo Unitario</th>
                <th>Impuesto</th>
                <th>Costo Total</th>
                <th><i class="far fa-save"></i></th>
              </thead>
              <tbody id="bodycart">
             <?php foreach ($articulos as $articulos) {?>
             <tr>
                <td><?=$articulos->idarticulo." ".$articulos->nombre_articulo?></td>
                <td><?=$articulos->cantidad." ".$articulos->prefijo?></td>
                <td><?=number_format($articulos->precio_unitario)?></td>
                <td><?=number_format($articulos->importe)?></td>
                <td><?=number_format($articulos->precio_total_lote)?></td>
                <td><i class="fas fa-check-circle text-success"></i></td>
             </tr>   
             <?php } ?>
            </tbody>
        </table>
        <div id="calculoCompra">
            <p>Total bruto: <?=number_format($calculo->subtotal)?></p>
            <p>Impuesto: <?=number_format($calculo->iva_compra)?></p>
            <p>Total: <?=number_format($calculo->total)?></p>
            
    </div>
    </div>
</div>
