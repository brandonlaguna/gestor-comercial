<?php
foreach ($sucursal as $sucursal) {}
foreach ($compra as $compra) {}
?>

<div class="br-pagetitle"></div>

    <div class="br-pagebody">
        <div class="br-section-wrapper"> 
        <form id="formCompra" finish="" class="form-layout form-layout-1">
            <input type="hidden" name="idsucursal" value="<?=$sucursal->idsucursal?>" id="idsucursal">
            <input type="hidden" name="idusuario" value="<?=$idusuario?>" id="idusuario">
            <input type="hidden" name="pos" value="<?=$pos?>" id="pos">
            <input type="hidden" name="contabilidad" id="contabilidad" value="<?=$contabilidad?>">
            <input type="hidden" name="idingreso" value="<?=$compra->cc_id_transa?>">
            <input type="hidden" name="" id="idproveedor" value="<?=$compra->idpersona?>">

            <div class="row mg-b-25">
            <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Sucursal: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" name="Sucursal" value="<?=$sucursal->razon_social?>" placeholder="Sucursal" disabled>
                </div>
              </div>

              <div class="col-sm-12 col-lg-6">
                <div class="form-group">
                  <label class="form-control-label">Tercero: <span class="tx-danger">*</span></label>
                  <input class="form-control codigo_contable" autocomplete="off" type="text" name="proveedor" value="<?=$compra->documento_proveedor." - ".$compra->nombre_tercero?>" id="proveedor" onclick="autocomplete()" placeholder="Ingresa el Tercero">
                </div>
              </div>

              <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Comprobante: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" data-placeholder="" id="detalleComprobante" name="comprobante">
                      <option value="<?=$compra->iddetalle_documento_sucursal?>" selected><?=$compra->tipo_comprobante.": ".$compra->serie_comprobante."-".zero_fill($compra->num_comprobante,8)?></option>
                    </select>
                </div>
              </div>

              <div class="col-sm-12 col-lg-4">
                <div class="form-group">
                  <label class="form-control-label">Forma de pago: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" data-placeholder="Choose Browser" id="formaPago" name="formaPago">
                  <optgroup label="Sleccionado">
                  <option value="<?=$compra->fp_id?>"><?=$compra->fp_nombre?></option>
                  </optgroup>
                    <optgroup label="Cambiar por">
                        <?php foreach ($formaspago as $formaspago) { ?>
                            <option value="<?=$formaspago->fp_id?>"><?=$formaspago->fp_nombre?></option>
                        <?php }?>
                    </optgroup>
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
                  <input type="text" class="form-control fc-datepicker" autocomplete="off" name="start_date" placeholder="MM/DD/YYYY" value="<?=get_date_format_calendar($compra->cc_fecha_cpte,"-")?>" readonly>
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
                  <input type="text" class="form-control fc-datepicker" autocomplete="off" name="end_date" value="<?=$date = ($compra->cc_fecha_final_cpte == "0000-00-00")?"":date("m/d/Y",strtotime($compra->cc_fecha_final_cpte))?>" readonly>
                </div>
              </div>
              </div>

              <div class="col-sm-11 col-lg-5" id="factura_proveedor">
                <div class="form-group">
                  <label class="form-control-label">No. Factura: <span class="tx-danger"></span></label>
                  <input class="form-control" type="text" name="factura_proveedor" placeholder="Numero de factura">
                </div>
              </div>

            </div>
        </form>

        <table class="table">
              <thead>
                <th>Descripcion</th>
                <th>Cantidad</th>
                <th>Costo Unitario</th>
                <th>Debito</th>
                <th>Credito</th>
                <th><i class="far fa-save"></i></th>
              </thead>
              <tbody id="bodycart">
             <?=$this->frameview("articulo/loadCartComprobantes",array("items"=>$items));?>
              </tbody>

        </table>
  <div class="card shadow p-1 bg-white rounded">
        <table class="table" id="ItemsToAdd">
        <thead>
        <tr>
            <th>Cuenta</th>
            <th>Descripcion</th>
            <th>Valor</th>
            <th>IVA</th>
            <th>Retencion</th>
            <th>C/D</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <tr>
            <td>
              <input class="form-control <?=$autocomplete?>" type="text" name="<?=$autocomplete?>"  value="" id="<?=$autocomplete?>" placeholder="Cuenta">
              <input type="hidden" name="<?=$no_use=($autocomplete =="codigo_contable")?"autocomplete_articulo":"codigo_contable"?>" id="<?=$no_use?>" class="<?=$no_use?>">
              <input type="hidden" name="iditem" id="iditem">
              <input type="hidden" name="idservicio" id="idservicio">
              <input type="hidden" name="idcodigo" id="idcodigo">
              <input type="hidden" name="cod_costos" id="cod_costos">
            </td>

            <td><input class="form-control calculate" type="text" name="descripcion" id="descripcion"></td>
            <td><input class="form-control calculate" type="text" name="costo_producto" id="costo_producto"></td>
            <td>
                <select name="imp_compra" id="" class="imp_compra calculate form-control select2">
                      <?php foreach ($impuestos as $impuesto) {?>
                        <option value="<?=$impuesto->im_id?>"><?=$impuesto->im_nombre?></option>
                      <?php }?>
                </select>
            </td>
            <td>
              <select name="retencion" id="" class="retencion calculate form-control select2">
                        <option value="0">NO</option>
                      <?php foreach ($retenciones as $retencion) {?>
                        <option value="<?=$retencion->re_id?>"><?=$retencion->re_nombre?></option>
                      <?php }?>
                </select>
            </td>
            <td>
                <select name="method" id="" class="form-control select2">
                        <option value="D">DEBITO</option>
                        <option value="C">CREDITO</option>
                </select>
            </td>
            <td><i id="AddItem" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i></td>
          </tr>
        </tbody>
        </table>
      </div>
        <div id="calculoCompra">
        
        </div>
        <div class="container-fluid ">
            <div class="row mt-5 mb-4">
                <div class="col-sm-12 col-md-4"><a href="#comprobantes/informes/general" class="btn btn-oblong btn-outline-danger" style="width:100%;">Regresar</a ></div>
                <div class="col-sm-12 col-md-8"><button class="btn btn-oblong btn-success" id="sendCompra" style="width:100%;">Guardar Cambios</button></div>
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
<script src="lib/totast/src/jquery.toast.js"></script>
<link rel="stylesheet" href="lib/totast/src/jquery.toast.css">
<script src="controller/script/ComprobantesController.js"></script>
<link href="lib/timepicker/jquery.timepicker.css" rel="stylesheet">
<script>
$('.fc-datepicker').datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
      });
</script>
<script>
      $(function(){
        calculoCompra();
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


             