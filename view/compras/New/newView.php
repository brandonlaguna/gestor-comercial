<?php
foreach ($sucursal as $sucursal) {}
?>
<div class="br-pagetitle"></div>
<div id="modaldemo3" class="modal fade">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Nuevo tercero</h6>
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

    <div class="br-pagebody">
        <div class="br-section-wrapper">
        <form id="formCompra" finish="" class="form-layout form-layout-1">
            <input type="hidden" name="idsucursal" value="<?=$sucursal->idsucursal?>" id="idsucursal">
            <input type="hidden" name="idusuario" value="<?=$idusuario?>" id="idusuario">
            <input type="hidden" name="pos" value="<?=$pos?>" id="pos">
            <input type="hidden" name="contabilidad" id="contabilidad" value="<?=$contabilidad?>">
            <input type="hidden" name="" id="idproveedor">

            <div class="row mg-b-25">
            <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Sucursal: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="Sucursal" value="<?=$sucursal->razon_social?>" placeholder="Sucursal" disabled>
                </div>
              </div>

              <div class="col-sm-11 col-lg-5">
                <div class="form-group">
                  <label class="form-control-label">Tercero: <span class="tx-danger">*</span></label>
                  <input class="form-control codigo_contable" type="text" name="proveedor" value="" id="proveedor" onclick="autocomplete()" placeholder="Ingresa el Tercero">
                </div>
              </div>

              <div class="col-sm-1 col-lg-1">
              <div class="form-group">
              <label class="form-control-label">Nuevo <span class="tx-danger"></span></label>
              <a href="" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-toggle="modal" data-target="#modaldemo3" id="nuevo_tercero"><i class="fas fa-user-plus"></i></a>
              </div>
              </div>
              <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Comprobante: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" data-placeholder="" id="detalleComprobante" name="comprobante">
                      <option value="0" selected>Agrega un comprobante</option>
                      <?php foreach ($comprobantes as $comprobantes) {?>
                      <option value="<?=$comprobantes->iddetalle_documento_sucursal?>"><b><?=$comprobantes->nombre.": </b>".$comprobantes->ultima_serie."-".zero_fill($comprobantes->ultimo_numero+1, 8)?></option>
                      <?php } ?>
                    </select>
                </div>
              </div>

              <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Forma de pago: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Choose Browser" id="formaPago" name="formaPago">
                    <?php foreach ($formaspago as $formaspago) { ?>
                      <option value="<?=$formaspago->fp_id?>"><?=$formaspago->fp_nombre?></option>
                    <?php }?>
                    </select>
                </div>
              </div>

              <div class="col-sm-12 col-lg-4">
              <label class="form-control-label">Fecha Inicio: <span class="tx-danger">*</span></label>
              <div class="wd-200 mg-b-30">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                    </div>
                  </div>
                  <input type="text" class="form-control fc-datepicker" name="start_date" placeholder="MM/DD/YYYY" value="<?=date("m/d/Y")?>">
                </div>
              </div>
              </div>
              
              <div class="col-sm-12 col-lg-4" id="fecha_final">
                <label class="form-control-label">Fecha Final: <span class="tx-danger">*</span></label>
                  <div class="wd-200">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="icon ion-calendar tx-16 lh-0 op-6"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control fc-datepicker" name="end_date" placeholder="MM/DD/YYYY">
                    </div>
                  </div>
              </div>

              <div class="col-sm-11 col-lg-5" id="factura_proveedor">
                <div class="form-group">
                  <label class="form-control-label">Factura del proveedor: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="factura_proveedor" placeholder="Numero de factura del proveedor">
                </div>
              </div>


            </div>
        </form>
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
             <?=$this->frameview("articulo/loadCart",array("items"=>$items));?>
              </tbody>

        </table>
  <div class="card shadow p-1 bg-white rounded">
              <div class="infinite-linear">

              </div>
        <table class="table" id="ItemsToAdd">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Descripcion</th>
            <th>Cantidad</th>
            <th>Precio Uni</th>
            <th>IVA</th>
            <th>Sub.</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input class="form-control <?=$autocomplete?>" type="text" name="<?=$autocomplete?>"  value="" id="<?=$autocomplete?>" placeholder="Producto"></td>
            <td>
            <input type="hidden" name="<?=$no_use=($autocomplete =="codigo_contable")?"autocomplete_articulo":"codigo_contable"?>" id="<?=$no_use?>" class="<?=$no_use?>">
              <input type="hidden" name="iditem" id="iditem">
              <input type="hidden" name="idservicio" id="idservicio">
              <input type="hidden" name="idcodigo" id="idcodigo">
              <input type="hidden" name="cod_costos" id="cod_costos">
              <input class="form-control" type="text" name="descripcion" id="descripcion">
            </td>
            <td><input class="form-control calculate" type="text" name="cantidad" id="cantidad"></td>
            <td><input class="form-control calculate" type="text" name="costo_producto" id="costo_producto"></td>
            <td><input class="form-control calculate" type="text" name="imp_compra" id="imp_compra"></td>
            <td><input class="form-control" type="text" name="" id="sub_total_compra" disabled></td>
            <td><input class="form-control" type="text" name="" id="total_compra" disabled></td>
            <td><i id="AddItem" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i></td>
          </tr>
        </tbody>
        </table>
      </div> 
        <div id="calculoCompra">
        
        </div>
        <div class="container-fluid ">
            <div class="row mt-5 mb-4">
                <div class="col-sm-12 col-md-4"><a href="#compras/reg_compras" class="btn btn-oblong btn-outline-danger" style="width:100%;">Regresar</a ></div>
                <div class="col-sm-12 col-md-8"><button class="btn btn-oblong btn-success" id="sendCompra" style="width:100%;">Registrar Compra</button></div>
            </div>
        </div>
    </div>
    
<style>
.autocomplete-items{
    background:black;
    position:absolute;
    width:100%;
}
</style>
<script src="controller/script/puc.js"></script>
<script src="controller/script/ComprasController.js"></script>
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


             