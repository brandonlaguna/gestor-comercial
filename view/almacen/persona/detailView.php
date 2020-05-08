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
                  <input class="form-control" type="text" disabled name="nombre_tercero" value="<?=$persona->nombre_persona?>" autocomplete="off" placeholder="Nombre del articulo">
                </div>
              </div><!-- col-12 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Tipo de documento: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Tipo de docuento" disabled name="tipo_documento">
                  <optgroup label="Seleccionado">
                      <option value="<?=$persona->idtipo_documento?>"><?=$persona->nombre_documento?></option>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Numero de documento: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" disabled name="numero_documento" value="<?=$persona->num_documento?>" autocomplete="off" placeholder="Numero de documento">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de tercero: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" disabled name="tipo_persona">
                  <optgroup label="Seleccionado">
                  <option value="<?=$persona->tipo_persona?>"><?=$persona->tipo_persona?></option>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de regimen: <span class="tx-danger"></span></label>
                  <select class="form-control select2" disabled name="tipo_regimen" >
                  <optgroup label="Seleccionado">
                    <option value="<?=$persona->idtipo_regimen?>"><?=$persona->tr_nombre?></option>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo de organizacion: <span class="tx-danger"></span></label>
                  <select class="form-control select2" disabled name="tipo_organizacion" id="">
                  <optgroup label="Seleccionado">
                    <option value="<?=$persona->idtipo_organizacion?>"><?=$persona->to_nombre?></option>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-4 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Departamento: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Departamento" disabled name="direccion_departamento">
                  <optgroup label="Seleccionado">
                    <option value="<?=$persona->direccion_departamento?>"><?=$persona->direccion_departamento?></option>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Ciudad: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Ciudad" disabled name="direccion_provincia">
                  <optgroup label="Seleccionado">
                    <option value="<?=$persona->direccion_departamento?>"><?=$persona->direccion_departamento?></option>
                  </optgroup>
                  </select>
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Distrito: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" disabled name="direccion_distrito" value="<?=$persona->direccion_distrito?>" autocomplete="off" placeholder="Distrito">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Direccion: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" disabled name="direccion_calle" value="<?=$persona->direccion_calle?>" autocomplete="off" placeholder="Direccion ej: Cra 123-456">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Felefono: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" disabled name="telefono" value="<?=$persona->telefono?>" autocomplete="off" placeholder="Telefono">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Email: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" disabled name="email" value="<?=$persona->email?>" autocomplete="off" placeholder="Email">
                </div>
              </div><!-- col-6 -->

              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label">Numero de cuenta: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" disabled name="num_cuenta" value="<?=$persona->numero_cuenta?>" autocomplete="off" placeholder="Numero de cuenta ej: 1234*********">
                </div>
              </div><!-- col-6 -->

              </form>

            </div><!-- row -->
            <div class="form-layout-footer">
              <a href="#almacen/terceros" class="btn btn-secondary">Salir</a>
              <a href="#almacen/update_tercero/<?=$persona->idpersona?>" class="btn btn-info" >Actualizar</a>
            </div>
          </div>
    </div>
</div>