<?php foreach($usuario as $usuario){}?>
<div class="br-pagebody">
<div class="br-section-wrapper">
<div class="form-layout form-layout-1">
<form id="save_empleado" finish="usuarios/save_empleado">
            <div class="row mg-b-25">
              <div class="col-lg-4">
                <div class="form-group">
                    <input type="hidden" name="idusuario" value="<?=$usuario->idusuario?>">
                    <input type="hidden" name="idempleado" value="<?=$usuario->idempleado?>">
                  <label class="form-control-label">Nombre: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="nombre" value="<?=$usuario->nombre_empleado?>" placeholder="Razon Social">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Apellido: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="apellidos" value="<?=$usuario->apellidos?>" placeholder="Apellidos">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Telefono: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="telefono" value="<?=$usuario->telefono_empleado?>" placeholder="Telefono">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Email: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="email" value="<?=$usuario->email_empleado?>" placeholder="Email">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Num Documento: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="num_documento" value="<?=$usuario->documento_empleado?>" placeholder="Pais">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Fecha Nacimiento: <span class="tx-danger">*</span></label>
                  <input id="dateMask" type="text" class="form-control" value="<?=$usuario->fecha_nacimiento?>" placeholder="YYYY-MM-DD" name="fecha_nacimiento">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Direccion: <span class="tx-danger">*</span></label>
                  <input id="dateMask" type="text" name="direccion"class="form-control" value="<?=$usuario->direccion_empleado?>" placeholder="Direccion">
                </div>
              </div><!-- col-4 -->
              
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Tipo Usuario: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Choose one (with optgroup)" name="tipo_usuario">
                      <optgroup label="Seleccionado">
                          <option value="<?=$usuario->tipo_usuario?>"><?=$usuario->tipo_usuario?></option>
                        </optgroup>
                        <optgroup label="Lista de permisos">
                            <option value="Administrador">Administrador</option>
                            <option value="Empleado">Empleado</option>
                        </optgroup>
                        <option label="Choose one"></option>
                    </select>
                </div>
              </div><!-- col-4 -->

            </div><!-- row -->
            </form>
            <div class="form-layout-footer">
              <button class="btn btn-info" id="send" onclick="sendForm('save_empleado')">Actualizar</button>
              <a href="#usuarios/usuarios"><button class="btn btn-secondary">Cancelar</button></a>
            </div><!-- form-layout-footer -->
          </div>
          
</div>
</div>
</div>
<script type="text/javascript">
 $(function(){
        'use strict'
        $('#dateMask').mask('9999-99-99');

    });
    $('.br-toggle').on('click', function(e){
            e.preventDefault();
            $(this).toggleClass('on');
        })
</script>