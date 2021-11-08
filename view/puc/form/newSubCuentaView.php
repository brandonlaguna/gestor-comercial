<form id="addSubCuenta<?=$idcuenta?>" finish="contables/addSubCuenta">
    <div class="form-layout form-layout-4">
                <h5 class="br-section-label">Crear nueva subcuenta para <?=$nombre_cuenta?></h5>
                <p class="br-section-text">Codigo de cuenta <?=$idcuenta?></p>
                <div class="row">
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Sub-cuenta* ej: 01" maxlength="2" name="subcuenta">
                    <input type="hidden" name="idcuenta" value="<?=$idcuenta?>">
                  </div>
                </div><!-- row -->
                <div class="row mg-t-7">
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Descripcion*" name="descripcion">
                  </div>
                </div>
                <div class="row mg-t-7">
                
                  
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <label class="ckbox ckbox-success">
                        <input type="checkbox" name="movimiento">
                        <span>Movimiento</span>
                    </label>
                  </div>
                </div>

                <div class="row mg-t-7">
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <label class="ckbox ckbox-success">
                        <input type="checkbox" name="terceros">
                        <span>Terceros</span>
                    </label>
                  </div>
                </div>

                <div class="row mg-t-7">
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <label class="ckbox ckbox-success">
                        <input type="checkbox" name="centro_costos">
                        <span>Ctro. de costos</span>
                    </label>
                  </div>
                </div>

                <div class="row mg-t-7">
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <label class="ckbox ckbox-success">
                        <input type="checkbox" name="impuesto">
                        <span>Impuesto</span>
                    </label>
                  </div>
                </div>

                <div class="row mg-t-7">
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <label class="ckbox ckbox-success">
                        <input type="checkbox" name="retencion">
                        <span>Retencion</span>
                    </label>
                  </div>
                </div>

                <div class="row mg-t-7">
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <label class="ckbox ckbox-success">
                        <input type="checkbox" name="c_pagar">
                        <span>Cta. por pagar</span>
                    </label>
                  </div>
                </div>

                <div class="row mg-t-7">
                  <div class="col-sm-12 mg-t-12 mg-sm-t-0">
                    <label class="ckbox ckbox-success">
                        <input type="checkbox" name="c_cobrar">
                        <span>C. por cobrar</span>
                    </label>
                  </div>
                </div>

                

            </form>
              </div>
              <div class="">
                  <button class="btn btn-info" onclick="sendForm('addSubCuenta<?=$idcuenta?>')">Agregar a <?=$nombre_cuenta?></button>
                </div><!-- form-layout-footer -->
