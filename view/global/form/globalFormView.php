<?php foreach($global as $global){}?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
<div class="br-section-wrapper">
<div class="form-layout form-layout-1">
<form id="update_global" finish="configuracion/update_global">
            <div class="row mg-b-25">
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Razon Social: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="razon_social" value="<?=$global->empresa?>" placeholder="Razon Social">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Direccion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="direccion" value="<?=$global->direccion?>" placeholder="Direccion">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Telefono: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="telefono" value="<?=$global->telefono?>" placeholder="Telefono">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Email: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="email" value="<?=$global->email?>" placeholder="Email">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Pais: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="pais" value="<?=$global->pais?>" placeholder="Pais">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Ciudad: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="ciudad" value="<?=$global->ciudad?>" placeholder="Ciudad">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Nombre Impuesto: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="nombre_impuesto" value="<?=$global->nombre_impuesto?>" placeholder="Nombre Impuesto">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">% Impuesto: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="porcentaje_impuesto" value="<?=$global->porcentaje_impuesto?>" placeholder="Porcentaje Impuesto">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                <span>Logo</span><br>
                    <div class="custom-file">
                        <input type="file" id="file" class="custom-file-input">
                        <label class="custom-file-label"></label>
                    </div>
                </div>
                </div>
            </div><!-- row -->
            </form>
            <div class="form-layout-footer">
              <button class="btn btn-info" id="send" onclick="sendForm('update_global')">Actualizar</button>
              <button class="btn btn-secondary">Cancelar</button>
            </div><!-- form-layout-footer -->
          </div>
          
</div>
</div>