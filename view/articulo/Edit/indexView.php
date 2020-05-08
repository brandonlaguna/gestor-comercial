<?php foreach ($articulo as $articulo) {}?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
<form id="new_articulo" finish="articulo/add">
            <div class="row mg-b-25">
              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label">Nombre del articulo: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="idarticulo" value="<?=$articulo->idarticulo?>">
                  <input class="form-control" type="text" name="nombre_articulo" value="<?=$articulo->nombre_articulo?>" placeholder="Nombre del articulo">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Categoria: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Categoria" name="categoria">
                <optgroup label="Seleccionado">
                  <option value="<?=$articulo->idcategoria?>"><?=$articulo->nombre_categoria?></option>
                </optgroup>
                <optgroup label="Cambiar por">
                    <?php foreach ($categorias as $categorias) {?>
                        <option value="<?=$categorias->idcategoria?>"><?=$categorias->nombre?></option>
                    <?php } ?>
                    </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Unidad de medida: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Unidad de medida" name="unidad_medida">
                    <optgroup label="Seleccionado">
                      <option value="<?=$articulo->idunidad_medida?>"><?=$articulo->nombre_unidad_medida?> (<?=$articulo->prefijo?>)</option>
                    </optgroup>
                    <optgroup label="Cambiar por">
                        <?php foreach ($unidades as $unidades) { ?>
                            <option value="<?=$unidades->idunidad_medida?>"><?=$unidades->nombre?> (<?=$unidades->prefijo?>)</option>
                        <?php }?>
                    </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-12">
                <div class="form-group mg-b-10-force">
                <textarea rows="3" class="form-control mg-t-20" placeholder="Descripcion" name="descripcion"><?=$articulo->descripcion?></textarea>
              </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Costo del producto: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="costo_producto" value="<?=$articulo->costo_producto?>" placeholder="ej: 10000" >
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Precio de venta: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="precio_venta" value="<?=$articulo->precio_venta?>" placeholder="ej: 10000">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Stock: <span class="tx-danger">Solo administradores*</span></label>
                  <input class="form-control" type="text" name="stock" value="<?=$articulo->stock?>" placeholder="ej: 10000">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                <label class="form-control-label">Imagen: <span class="tx-danger"></span></label>
                <div class="custom-file">
                    <input type="file" id="file" class="custom-file-input" name="imagen_producto">
                    <label class="custom-file-label"></label>
                </div>
                </div>
              </div>
              </form>

            </div><!-- row -->
            <div class="form-layout-footer">
            <button class="btn btn-info" id="send" onclick="sendForm('new_articulo')">Actualizar</button>
              <a href="#articulo/" class="btn btn-secondary">Cancelar</a>
            </div>
          </div>
    </div>
</div>