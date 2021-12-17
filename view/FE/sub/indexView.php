<?php
foreach ($sucursal as $sucursal) {}
?>

<div class="form-layout form-layout-2">
            <div class="row no-gutters">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-control-label">Nombre Compañía: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="firstname" <?=$permission?> value="<?=$sucursal->razon_social?>" placeholder="Enter firstname">
                </div>
              </div><!-- col-4 -->
              <div class="col-md-4 mg-t--1 mg-md-t-0">
                <div class="form-group mg-md-l--1">
                  <label class="form-control-label">NIT: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="lastname" <?=$permission?> value="<?=$sucursal->num_documento?>" placeholder="Enter lastname">
                </div>
              </div><!-- col-4 -->
              <div class="col-md-4 mg-t--1 mg-md-t-0">
                <div class="form-group mg-md-l--1">
                  <label class="form-control-label">Correo: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="email" <?=$permission?> value="<?=$sucursal->email?>" placeholder="Enter email address">
                </div>
              </div><!-- col-4 -->
              <div class="col-md-8">
                <div class="form-group bd-t-0-force">
                  <label class="form-control-label">Direccion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="address" <?=$permission?> value="<?=$sucursal->direccion?>" placeholder="Enter address">
                </div>
              </div><!-- col-8 -->
              <div class="col-md-4">
                <div class="form-group mg-md-l--1 bd-t-0-force">
                  <label class="form-control-label mg-b-0-force">Ciudad: <span class="tx-danger">*</span></label>
                  <select id="select2-a" class="form-control" data-placeholder="Choose country" $permission>
                    <option label="Choose country"></option>
                    <option value="USA" selected>United States of America</option>
                    <option value="UK">United Kingdom</option>
                    <option value="China">China</option>
                    <option value="Japan">Japan</option>
                  </select>
                </div>
              </div><!-- col-4 -->
            </div><!-- row -->
            <div class="form-layout-footer bd pd-20 bd-t-0">
              <button class="btn btn-info">Guardar datos</button>
            </div><!-- form-group -->
          </div><!-- form-layout -->