<form id="addSubCuenta<?=$idcuenta?>" finish="contables/addSubCuenta">
    <div class="form-layout form-layout-4">
                <h5 class="br-section-label">Crear nueva subcuenta para <?=$nombre_cuenta?></h5>
                <p class="br-section-text">Codigo de cuenta <?=$idcuenta?></p>
                <div class="row">
                  <label class="col-sm-4 form-control-label">Subcuenta: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="ej: 01" maxlength="2" name="subcuenta">
                    <input type="hidden" name="idcuenta" value="<?=$idcuenta?>">
                  </div>
                </div><!-- row -->
                <div class="row mg-t-7">
                  <label class="col-sm-4 form-control-label">Descripcion: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Descripcion" name="descripcion">
                  </div>
                </div>
                <div class="row mg-t-7">
                  <label class="col-sm-4 form-control-label">Movimiento<span class="tx-danger"></span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <label class="ckbox">
                        <input type="checkbox" name="movimiento">
                        <span></span>
                    </label>
                  </div>
                </div>

                <div class="row mg-t-7">
                  <label class="col-sm-4 form-control-label">Terceros<span class="tx-danger"></span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <label class="ckbox">
                        <input type="checkbox" name="terceros">
                        <span></span>
                    </label>
                  </div>
                </div>

                <div class="row mg-t-7">
                  <label class="col-sm-4 form-control-label">Centro de costos<span class="tx-danger"></span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <label class="ckbox">
                        <input type="checkbox" name="centro_costos">
                        <span></span>
                    </label>
                  </div>
                </div>

            </form>
              </div>
              <div class="">
                  <button class="btn btn-info" onclick="sendForm('addSubCuenta<?=$idcuenta?>')">Agregar a <?=$nombre_cuenta?></button>
                </div><!-- form-layout-footer -->
