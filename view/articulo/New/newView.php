<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
<form id="new_articulo" finish="articulo/add">
            <div class="row mg-b-25">
              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label">Nombre del articulo: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="nombre_articulo" value="" placeholder="Nombre del articulo">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Categoria: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Categoria" name="categoria">
                    <?php foreach ($categorias as $categorias) {?>
                        <option value="<?=$categorias->idcategoria?>"><?=$categorias->nombre?></option>
                    <?php } ?>
                  </select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Unidad de medida: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Unidad de medida" name="unidad_medida">
                    <?php foreach ($unidades as $unidades) { ?>
                        <option value="<?=$unidades->idunidad_medida?>"><?=$unidades->nombre?> (<?=$unidades->prefijo?>)</option>
                    <?php }?>
                  </select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                <textarea rows="3" class="form-control mg-t-20" placeholder="Descripcion" name="descripcion"></textarea>
              </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Costo del producto: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="costo_producto" value="" placeholder="ej: 10000" >
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Precio de venta: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="precio_venta" value="" placeholder="ej: 10000">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                <div class="custom-file">
                    <input type="file" id="file" class="custom-file-input" name="imagen_producto">
                    <label class="custom-file-label"></label>
                </div>
                </div>
              </div>
              </form>

            </div><!-- row -->
            <div class="form-layout-footer">
            <button class="btn btn-info" id="send" onclick="sendForm('new_articulo')">Agregar</button>
              <a href="#articulo/" class="btn btn-secondary">Cancelar</a>
            </div>
          </div>
    </div>
</div>