<?php foreach ($persona as $persona) {}?>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <div class="form-layout form-layout-1">
    <form id="save_tercero" finish="almacen/save_tercero">
            <div class="row mg-b-25">

              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label">Nombre del tercero: <span class="tx-danger">*</span></label>
                  <input type="hidden" name="idpersona" value="<?=$persona->idpersona?>">
                  <input class="form-control" type="text" name="nombre_tercero" value="<?=$persona->nombre_persona?>" autocomplete="off" placeholder="Nombre del articulo">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Tipo de documento: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Tipo de docuento" name="tipo_documento">
                  <optgroup label="Seleccionado">
                      <option value="<?=$persona->idtipo_documento?>"><?=$persona->nombre_documento?></option>
                  </optgroup>
                  <optgroup label="Cambiar por">
                    <?php foreach ($documentos as $documentos) {?>
                        <option value="<?=$documentos->idtipo_documento?>"><?=$documentos->nombre?></option>
                    <?php } ?>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Numero de documento: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="numero_documento" value="<?=$persona->num_documento?>" autocomplete="off" placeholder="Numero de documento">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de tercero: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" name="tipo_persona">
                  <optgroup label="Seleccionado">
                  <option value="<?=$persona->tipo_persona?>"><?=$persona->tipo_persona?></option>
                  </optgroup>
                  <optgroup label="Cambiar por">
                    <option value="Cliente">Cliente</option>
                    <option value="Proveedor">Proveedor</option>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de regimen: <span class="tx-danger"></span></label>
                  <select class="form-control select2" name="tipo_regimen" >
                  <optgroup label="Seleccionado">
                    <option value="<?=$persona->idtipo_regimen?>"><?=$persona->tr_nombre?></option>
                  </optgroup>
                  <optgroup label="Cambiar por">
                    <?php foreach ($tipo_regimen as $tipo_regimen) {?>
                      <option value="<?=$tipo_regimen->idtipo_regimen?>"><?=$tipo_regimen->tr_nombre?></option>
                    <?php }?>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de organizacion: <span class="tx-danger"></span></label>
                  <select class="form-control select2" name="tipo_organizacion" id="">
                  <optgroup label="Seleccionado">
                    <option value="<?=$persona->idtipo_organizacion?>"><?=$persona->to_nombre?></option>
                  </optgroup>
                  <optgroup label="Cambiar por">
                      <?php foreach ($tipo_organizacion as $tipo_organizacion) {?>
                        <option value="<?=$tipo_organizacion->idtipo_organizacion?>"><?=$tipo_organizacion->to_nombre?></option>
                      <?php }?>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Departamento: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Departamento" name="direccion_departamento">
                  <optgroup label="Seleccionado">
                    <option value="<?=$persona->direccion_departamento?>"><?=$persona->direccion_departamento?></option>
                  </optgroup>
                  <optgroup label="Cambiar por">
                    <?php foreach ($departamentos as $departamentos) {?>
                        <option value="<?=$departamentos->dp_nombre?>"><?=$departamentos->dp_nombre?></option>
                    <?php } ?>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Ciudad: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Ciudad" name="direccion_provincia">
                  <optgroup label="Seleccionado">
                    <option value="<?=$persona->direccion_departamento?>"><?=$persona->direccion_departamento?></option>
                  </optgroup>
                  <optgroup label="Cambiar por">
                    <?php foreach ($municipios as $municipios) {?>
                        <option value="<?=$municipios->mn_nombre?>"><?=$municipios->mn_nombre?></option>
                    <?php } ?>
                    </optgroup>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Distrito: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="direccion_distrito" value="<?=$persona->direccion_distrito?>" autocomplete="off" placeholder="Distrito">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Direccion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="direccion_calle" value="<?=$persona->direccion_calle?>" autocomplete="off" placeholder="Direccion ej: Cra 123-456">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Felefono: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="telefono" value="<?=$persona->telefono?>" autocomplete="off" placeholder="Telefono">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Email: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="email" value="<?=$persona->email?>" autocomplete="off" placeholder="Email">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label">Numero de cuenta: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="num_cuenta" value="<?=$persona->numero_cuenta?>" autocomplete="off" placeholder="Numero de cuenta ej: 1234*********">
                </div>
              </div><!-- col-6 -->

              </form>

            </div><!-- row -->
            <div class="form-layout-footer">
            <button class="btn btn-info" id="send" onclick="sendForm('save_tercero')">Agregar</button>
              <a href="#almacen/terceros" class="btn btn-secondary">Cancelar</a>
            </div>
          </div>
    </div>
</div>