<?php
foreach ($sucursal as $sucursal) {}
foreach ($empleado as $empleado) {}
?>
<style>
.sec-style{
  margin-bottom:1em;
}
.userDetail{
  width:'100%';
  text-align:right;
}
</style>

<div class="br-pagetitle"></div>

<div class="container-fluid">

    <div class="row">
    <div class="linearLoading"></div>
      <div class="col-12 col-md-8">
        <div class="br-pagebody sec-style">
          <div class="br-section-wrapper">
          <table class="table" id="ItemsToAdd">
        <tbody>
          <tr>
            <td>
                <input type="text" class="form-control" name="codigo_barras" id="codigo_barras" placeholder="codigo de barras" >
            </td>
            <td class="wd-60p">

            <input class="form-control <?=$autocomplete?>" type="text" name="<?=$autocomplete?>"  value="" id="<?=$autocomplete?>" placeholder="Producto" autocomplete="off">
              <input type="hidden" name="<?=$no_use = ($autocomplete == "codigo_contable") ? "autocomplete_articulo" : "codigo_contable"?>" id="<?=$no_use?>" class="<?=$no_use?>" >
              <input type="hidden" name="iditem" id="iditem">
              <input type="hidden" name="idservicio" id="idservicio">
              <input type="hidden" name="idcodigo" id="idcodigo">
              <input type="hidden" name="cod_costos" id="cod_costos">
              <input class="form-control" type="hidden" name="" id="sub_total_venta" disabled>
              <input class="form-control" type="hidden" name="" id="total_venta" disabled>
              <input class="form-control calculate" type="hidden" name="imp_venta" id="imp_venta" disabled>
              <input class="form-control" type="hidden" name="descripcion" id="descripcion">
              <input class="form-control calculate" type="hidden" name="precio_venta" id="precio_venta" placeholder="Precio venta">
            </td>
            <td class="wd-15p"><input class="form-control calculate" type="text" name="cantidad" id="cantidad" placeholder="Cantidad"></td>
            <td><i id="AddItem" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i></td>
          </tr>
        </tbody>
        </table>
          </div>
        </div>
        <!--SEPARADOR-->
        <div class="br-pagebody sec-style">
        <div class="infinite-linear"></div>
          <div class="br-section-wrapper">
              <table class="table">
              <thead>
                <th>Descripcion</th>
                <th>Cantidad</th>
                <th>Costo Unitario</th>
                <th>Impuesto</th>
                <th>Costo Total</th>
                <th><i class="far fa-save"></i></th>
              </thead>
              <tbody id="bodycart">
             <?=$this->frameview("articulo/loadCart", array("items" => $items));?>
              </tbody>
              </table>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="br-pagebody sec-style">
          <div class="br-section-wrapper">
          <form id="formVenta" finish="" class="">

              <input type="hidden" name="idsucursal" value="<?=$sucursal->idsucursal?>" id="idsucursal">
              <input type="hidden" name="idusuario" value="<?=$idusuario?>" id="idusuario">
              <input type="hidden" name="pos" value="<?=$pos?>" id="pos">
              <input type="hidden" name="contabilidad" id="contabilidad" value="<?=$contabilidad?>">
              <input type="hidden" name="" id="idproveedor">
              <input type="hidden" class="form-control fc-datepicker" name="start_date" placeholder="MM/DD/YYYY" value="<?=date("m/d/Y")?>" autocomplete="off">

              <div class="row">

                <div class="col-sm-10 col-lg-8">
                  <div class="form-group">
                      <label class="form-control-label">Cliente: <span class="tx-danger">*</span></label>
                      <input class="form-control codigo_contable" type="text" name="proveedor" value="" id="proveedor" onclick="autocomplete()" placeholder="Ingresa Nombre o documento del cliente" autocomplete="off">
                  </div>
                </div>

                <div class="col-sm-2 col-lg-4">
                  <div class="form-group">
                      <label class="form-control-label">Nuevo/Histoial<span class="tx-danger"></span></label>
                      <a href="" class="btn btn-primary tx-11 tx-uppercase pd-y-11 pd-x-23 tx-mont tx-medium" data-toggle="modal" data-target="#modaldemo3" id="nuevo_tercero"><i class="fas fa-user-plus"></i></a>
                      <a href="" class="btn btn-primary tx-11 tx-uppercase pd-y-11 pd-x-23 tx-mont tx-medium" data-toggle="modal" data-target="#modaldemo3" id="history"><i class="fas fa-history"></i></a>
                  </div>
                </div>

                <div class="col-sm-2 col-lg-4 <?=$hidden?>">
                  <div class="form-group">
                    <select class="form-control select2" data-placeholder="" id="detalleComprobante" name="comprobante">
                      <option value="0" selected>Agrega un comprobante</option>
                        <?php foreach ($comprobantes as $comprobantes) {?>
                          <option value="<?=$comprobantes->iddetalle_documento_sucursal?>" <?=$comprobantes->dds_propertie?> ><b><?=$comprobantes->nombre . ": </b>" . $comprobantes->ultima_serie . "-" . zero_fill($comprobantes->ultimo_numero + 1, 8)?></option>
                        <?php }?>
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-lg-12 <?=$hidden?>">
                  <div class="form-group">
                    <select class="form-control select2" data-placeholder="Choose Browser" id="formaPago" name="formaPago">
                      <?php foreach ($formaspago as $formaspago) {?>
                        <option value="<?=$formaspago->fp_id?>"><?=$formaspago->fp_nombre?></option>
                      <?php }?>
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-lg-12" id="fecha_final">
                  <div class="form-group">
                  <div class="">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                        </div>
                      </div>
                        <input type="text" class="form-control fc-datepicker" name="end_date" placeholder="MM/DD/YYYY" value="" autocomplete="off">
                    </div>
                  </div>
                  </div>
                </div>

                </form><!--form-->

              </div><!--row-->
          </div> <!--  br-pagebody sec-style -->
        </div> 
        <!--SEPARADOR-->
        <div class="br-pagebody sec-style">
          <div class="br-section-wrapper">

              <div class="infinite-linear"></div>

                  <div class="row userDesc">

                      <div class="col-sm-10 col-lg-10">
                        <div class="form-group">
                          <label class="form-control-label">Metodos de pago: <span class="tx-danger">*</span></label>
                            <select class="form-control select2 select2mp" data-placeholder="Choose Browser" id="formaPago" name="formaPago">
                              <?php foreach ($metodospago as $metodo) { ?>
                                <option value="<?=$metodo->mp_id?>"><?=$metodo->mp_nombre?></option>
                              <?php }?>
                            </select>
                        </div>
                      </div>

                      <div class="col-sm-2" style="padding-top:44px">
                      <i id="AddMetodoPago" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i>
                      </div>

                      <div class="col-6 col-md-6">
                        <p>Persona de las ventas</p>
                      </div>

                      <div class="col-6 col-md-6">
                        <div class="userDetail">
                            <p><?=$empleado->nombre." ".$empleado->apellidos?></p>
                        </div>
                      </div>

                  </div>

                  <div id="calculoVenta"></div>
                  <div id="listMetodoPago"></div>

                  <div class="row mt-5 mb-4">
                    <div class="col-sm-12 col-md-4"><a href="#ventas" class="btn btn-oblong btn-outline-danger" style="width:100%;">Regresar</a ></div>
                    <div class="col-sm-12 col-md-8"><button class="btn btn-oblong btn-success modal-effect" data-effect="effect-just-me" id="sendVenta" style="width:100%;">Enviar</button></div>
                  </div>    
          </div><!--br-section-wrapper-->
        </div><!--br-pagebody-->

        <div class="br-pagebody <?=$hidden?>" >
          <div class="br-section-wrapper">
              <div class="row">
                    <div class="col-sm-9 ">
                      <select class="form-control select2re" data-placeholder="Choose Browser" name="retenciones">
                        <?php foreach ($retenciones as $retenciones) {?>
                          <option value="<?=$retenciones->re_id?>"><?=$retenciones->re_nombre?></option>
                        <?php }?>
                      </select>
                    </div><!--col-sm-9-->

                    <div class="col-sm-1 mt-3">
                        <i id="AddRet" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i>
                    </div><!--col-sm-1-->
          
                    <div class="col-sm-9 mt-2">
                      <select class="form-control select2imp" data-placeholder="Choose Browser" name="impuestos">
                        <?php foreach ($impuestos as $impuestos) {?>
                            <option value="<?=$impuestos->im_id?>"><?=$impuestos->im_nombre?></option>
                        <?php }?>
                      </select>
                    </div><!--col-sm-9-->
                    <div class="col-sm-3 mt-4"><i id="AddIm" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i></div><!--col-sm-1-->

              </div><!--row-->
          </div>
        </div>

      </div>
    </div>
</div>

</div>


<div id="modaldemo3" class="modal fade">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Venta</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">

                </div><!-- modal-body -->
                <div class="modal-footer">
                </div>
              </div>
            </div><!-- modal-dialog -->
  </div><!-- modal -->

<style>
.autocomplete-items{
    background:black;
    position:absolute;
    width:100%;
}
</style>
<script src="controller/script/puc.js"></script>
<script src="lib/totast/src/jquery.toast.js"></script>
<link rel="stylesheet" href="lib/totast/src/jquery.toast.css">
<script src="controller/script/VentasController.js"></script>
<script src="controller/script/BarcodeScannerController.js"></script>
<link href="lib/timepicker/jquery.timepicker.css" rel="stylesheet">
<script>
$('.fc-datepicker').datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
      });
</script>
<script>
      $(function(){

        // showing modal with effect
        $('.modal-effect').on('click', function(e){
          e.preventDefault();

          var effect = $(this).attr('data-effect');
          $('#modaldemo8').addClass(effect);
          $('#modaldemo8').modal('show');
        });

        // hide modal with effect
        $('#modaldemo8').on('hidden.bs.modal', function (e) {
          $(this).removeClass (function (index, className) {
              return (className.match (/(^|\s)effect-\S+/g) || []).join(' ');
          });
        });
      });
    </script>


