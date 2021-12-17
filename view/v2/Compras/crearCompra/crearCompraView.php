<?php
foreach ($sucursal as $sucursal) {}
?>
<div class="br-pagetitle"></div>
    <div class="br-pagebody">
        <div class="br-section-wrapper">
        <div class="linearLoading"></div>
        <form id="formCompra" finish="" class="form-layout form-layout-1">
            <input type="hidden" name="idsucursal" value="<?=$sucursal['idsucursal']?>" id="idsucursal">
            <input type="hidden" name="idusuario" value="<?=$idusuario?>" id="idusuario">
            <input type="hidden" name="pos" value="<?=$pos?>" id="pos">
            <input type="hidden" name="contabilidad" id="contabilidad" value="<?=$contabilidad?>">
            <input type="hidden" name="" id="idproveedor">

            <div class="row mg-b-25">
            <?=$this->component('formInput',[
                'title'         => 'Sucursal',
                'name'          => 'Sucursal',
                'id'            => 'Sucursal',
                'required'      => true,
                'placeholder'   => 'Sucursal',
                'col'           => 'col-sm-12 col-lg-6',
                'value'         => $sucursal['razon_social'],
                'readonly'      => true,
                'input_class'   => 'form-control'
            ])?>

              <div class="col-sm-11 col-lg-5">
                <div class="form-group">
                  <label class="form-control-label">Tercero: <span class="tx-danger">*</span></label>
                  <input class="form-control codigo_contable" type="text" name="proveedor" value="" id="proveedor" onclick="autocomplete()" placeholder="Ingresa el Tercero" autocomplete="off">
                </div>
              </div>

              <div class="col-sm-1 col-lg-1">
              <div class="form-group">
              <label class="form-control-label">Nuevo <span class="tx-danger"></span></label>
              <a href="" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-toggle="modal" data-target="#modaldemo3" id="nuevo_tercero"><i class="fas fa-user-plus"></i></a>
              </div>
              </div>
              <?=$this->component('formSelect',[
                  'title'         => 'Comprobante',
                  'name'          => 'comprobante',
                  'id'            => 'detalleComprobante',
                  'required'      => true,
                  'items'         => $comprobantes,
                  'col'           => 'col-sm-12 col-lg-4',
                  'selected'      => 0,
                  'attr'          =>[
                      'data-live-search'  => true
                  ]
              ])?>

              <?=$this->component('formSelect',[
                  'title'         => 'Forma de pago',
                  'name'          => 'formaPago',
                  'id'            => 'formaPago',
                  'required'      => true,
                  'items'         => $formaspago,
                  'col'           => 'col-sm-12 col-lg-4',
                  'selected'      => 0,
                  'attr'          =>[
                      'data-live-search'  => true
                  ]
              ])?>
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
                      <input type="text" autocomplete="off" class="form-control fc-datepicker" name="end_date" placeholder="MM/DD/YYYY">
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
                <?=$this->frameview("articulo/loadCart",array("items"=>$items,"impuestos"=>$impuestos));?>
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
            <!-- <td><input class="form-control calculate" type="text" name="imp_compra" id="imp_compra"></td> -->
            <td>
            <div class="input-append btn-group">
                <input class="span2 form-control calculate" name="imp_compra" id="imp_compra" type="text" readonly>
                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu">
                <?php foreach ($impuestos as $selectimpuesto) {?>
                  <p class="dropdown-item change_tax" style="margin:0" onclick="changeTax('imp_compra','<?=$selectimpuesto['im_porcentaje']?>')"><?=$selectimpuesto['im_nombre']?> (<?=$selectimpuesto['im_porcentaje']?>%)</p>
                <?php }?>
                </div>
            </div>
            </td>
            <td><input class="form-control" type="text" name="" id="sub_total_compra" disabled></td>
            <td><input class="form-control" type="text" name="" id="total_compra" disabled></td>
            <td><i id="AddItem" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i></td>
          </tr>
        </tbody>
        </table>
      </div>
      <div class="row mt-5">
          <div class="col-sm-8 "></div><!--col-sm-8-->
          <?=$this->component('formSelect',[
                  'title'         => '',
                  'name'          => 'colaRetenciones',
                  'id'            => 'colaRetenciones',
                  'items'         => $retenciones,
                  'col'           => 'col-sm-3',
                  'selected'      => 0,
                  'attr'          =>[
                      'data-live-search'  => true
                  ]
              ])?>
          <div class="col-sm-1 mt-3"><i id="AddRet" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i></div><!--col-sm-1-->
          <div class="col-sm-8 mt-2"></div><!--col-sm-8-->
          <?=$this->component('formSelect',[
              'title'         => '',
              'name'          => 'colaImpuestos',
              'id'            => 'colaImpuestos',
              'items'         => $impuestos,
              'col'           => 'col-sm-3',
              'selected'      => 0,
              'attr'          =>[
                  'data-live-search'  => true
              ]
          ])?>
          <div class="col-sm-1 mt-4"><i id="AddIm" class="fas fa-plus-circle text-success" style="font-size:20pt; line-height:10px; cursor:pointer;"></i></div><!--col-sm-1-->
      </div><!--row-->
        <div id="calculoCompra">
        </div>
        <div class="container-fluid ">
            <div class="row mt-5 mb-4">
                <div class="col-sm-12 col-md-4"><a href="#compras/reg_compras" class="btn btn-oblong btn-outline-danger" style="width:100%;">Regresar</a ></div>
                <div class="col-sm-12 col-md-8"><button class="btn btn-oblong btn-success" id="sendCompra" style="width:100%;">Registrar Compra</button></div>
            </div>
        </div>
    </div>
<script src="controller/script/puc.js"></script>