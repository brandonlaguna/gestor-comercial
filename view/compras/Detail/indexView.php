<?php foreach ($compra as $compra) {} ?>
<div class="br-pagetitle"></div>
  
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="form-layout form-layout-1">
            <div class="row mg-b-25">

            <div class="col-lg-4">
                <div class="form-group">
                <input type="hidden" name="contabilidad" id="contabilidad" value="0">
                  <label class="form-control-label">Proveedor: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$compra->nombre_proveedor." - ".$compra->num_documento?>" placeholder="" disabled>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de pago: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$compra->tipo_pago?>" placeholder="" disabled>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Comprobante: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$compra->tipo_comprobante.": ".$compra->serie_comprobante."-".zero_fill($compra->num_comprobante,8);?>" placeholder="" disabled>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Fecha: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$compra->fecha?>" placeholder="" disabled>
                </div>
              </div>
              <?php if($compra->fecha_final !="0000-00-00"){?>
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Fecha Final de pago: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="proveedor" value="<?=$compra->fecha?>" placeholder="" disabled>
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
                <td><?=$articulos->stock_ingreso." ".$articulos->prefijo?></td>
                <td><?=number_format($articulos->precio_compra)?></td>
                <td><?=number_format($articulos->iva_compra)?></td>
                <td><?=number_format($articulos->precio_total_lote)?></td>
                <td><i class="fas fa-check-circle text-success"></i></td>
             </tr>   
             <?php } ?>
            </tbody>
        </table>
        <div class="row">
        <div class="col-sm-12 col-md-8">`
        
        </div>
        <div class="col-sm-12 col-md-4">
          <div id="calculoCompra">
            <p><b class="text-success">Total bruto:</b> <?=number_format($total_bruto)?></p>
            <?php foreach ($impuestos as $impuestos) {?>
                <p class=""><b class="text-success"><?=$impuestos[0]?>:</b> <?=number_format($impuestos[1])?><p>
            <?php }?>
            <?php foreach ($retenciones as $retenciones) { ?>
                <p class=""><b class="text-success"><?=$retenciones[0]?>:</b> <?=number_format($retenciones[1])?></p>
            <?php }?>
            <p><b class="text-success">Total:</b> <?=number_format($total_neto)?></p>
      </div>
    </div>
    </div>
    </div>
</div>
