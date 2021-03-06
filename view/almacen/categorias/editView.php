<?php foreach ($categoria as $categoria) {}?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
<form id="new_categoria" finish="almacen/save_categoria">
            <div class="row mg-b-25">

              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label">Nombre de la categoria: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="idcategoria" value="<?=$categoria->idcategoria?>">
                  <input class="form-control" type="text" name="nombre_categoria" placeholder="Nombre de la categoria" value="<?=$categoria->nombre?>">
                </div>
              </div><!-- col-4 -->
              
              
              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Codigo de venta: <span class="tx-danger">*</span></label>
                  <select name="cod_venta" id="" class="form-control select2">
                    <optgroup label="Seleccionado...">
                        <option value="<?=$categoria->cod_venta?>"><?=$categoria->cod_venta?></option>
                    </optgroup>
                    <optgroup label="Cambiar por...">
                    <?php foreach ($allpuc as $cod_venta) {?>
                            <option value="<?=$cod_venta->idcodigo?>"><?=$cod_venta->idcodigo." ".$cod_venta->tipo_codigo?></option>
                        <?php }?>
                        </optgroup>
                    </select>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Codigo de costos: <span class="tx-danger">*</span></label>
                  <select name="cod_costos" id="" class="form-control select2">
                    <optgroup label="Seleccionado...">
                        <option value="<?=$categoria->cod_costos?>"><?=$categoria->cod_costos?></option>
                    </optgroup>
                    <optgroup label="Cambiar por...">
                    <?php foreach ($allpuc as $cod_costos) {?>
                            <option value="<?=$cod_costos->idcodigo?>"><?=$cod_costos->idcodigo." ".$cod_costos->tipo_codigo?></option>
                        <?php }?>
                        </optgroup>
                  </select>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Codigo de devoluciones: <span class="tx-danger">*</span></label>
                  <select name="cod_devoluciones" id="" class="form-control select2">
                    <optgroup label="Seleccionado...">
                        <option value="<?=$categoria->cod_devoluciones?>"><?=$categoria->cod_devoluciones?></option>
                    </optgroup>
                    <optgroup label="Cambiar por...">
                    <?php foreach ($allpuc as $cod_devoluciones) {?>
                            <option value="<?=$cod_devoluciones->idcodigo?>"><?=$cod_devoluciones->idcodigo." ".$cod_devoluciones->tipo_codigo?></option>
                        <?php }?>
                        </optgroup>
                  </select>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Codigo de inventario: <span class="tx-danger">*</span></label>
                  <select name="cod_inventario" id="" class="form-control select2">
                    <optgroup label="Seleccionado...">
                        <option value="<?=$categoria->cod_inventario?>"><?=$categoria->cod_inventario?></option>
                    </optgroup>
                    <optgroup label="Cambiar por...">
                    <?php foreach ($allpuc as $cod_inventario) {?>
                            <option value="<?=$cod_inventario->idcodigo?>"><?=$cod_inventario->idcodigo." ".$cod_inventario->tipo_codigo?></option>
                        <?php }?>
                        </optgroup>
                  </select>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Impuesto de compra: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="imp_compra" placeholder="ej: 19" value="<?=$categoria->imp_compra?>">
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Impuesto de venta: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="imp_venta" placeholder="ej: 19" value="<?=$categoria->imp_venta?>">
                </div>
              </div><!-- col-8 -->

              
              </form>

            </div><!-- row -->
            <div class="form-layout-footer">
            <button class="btn btn-info" id="send" onclick="sendForm('new_categoria')">Agregar</button>
              <a href="#almacen/categorias" class="btn btn-secondary">Cancelar</a>
            </div>
          </div>
    </div>
</div>