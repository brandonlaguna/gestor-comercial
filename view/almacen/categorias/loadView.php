<?php foreach ($categoria as $categoria) {}?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
            <div class="row mg-b-25">

              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label">Nombre de la categoria: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="nombre_categoria" placeholder="Nombre de la categoria" value="<?=$categoria->nombre?>" disabled>
                </div>
              </div><!-- col-4 -->
              
              
              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Codigo de venta: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="cod_venta" placeholder="ej: 0000" value="<?=$categoria->cod_venta?>" disabled>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Codigo de costos: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="cod_costos" placeholder="ej: 0000" value="<?=$categoria->cod_costos?>" disabled>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Codigo de devoluciones: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="cod_devoluciones" placeholder="ej: 0000" value="<?=$categoria->cod_devoluciones?>" disabled>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Codigo de inventario: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="cod_inventario" placeholder="ej: 0000" value="<?=$categoria->cod_inventario?>" disabled>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Impuesto de compra: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="imp_compra" placeholder="ej: 19" value="<?=$categoria->imp_compra?>" disabled>
                </div>
              </div><!-- col-8 -->

              <div class="col-lg-6">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Impuesto de venta: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="imp_venta" placeholder="ej: 19" value="<?=$categoria->imp_venta?>" disabled>
                </div>
              </div><!-- col-8 -->

            </div><!-- row -->
            <div class="form-layout-footer">
            </div>
          </div>
    </div>
</div>