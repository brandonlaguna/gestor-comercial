<?php foreach ($credito as $credito) {}
$saldo_pendiente = number_format($credito->deuda_total - $credito->total_pago,2,'.',',');
?>
<div class="row">
    <div class="col-sm-12 text-center ">
            <h1 id="deuda">$<?=$saldo_pendiente?></h1>
            
        <div class="legend mb-2 mt-2" style="width:400px;">
        <div class="linearLoading" style="height:10px;"></div>
            <div class="divider line">
            </div>
        <div class="change"><p style="background:white;" >Método de pago: <strong>Efectivo</strong></p></div>
            <form id="pago_credito" finish="proveedor/pago_credito" width="400px">
            <input type="hidden" name="idcredito" id="idcredito" value="<?=$idcredito?>">
            <input type="hidden" id="pos" value="<?=$pos?>" >
            <input type="hidden" name="tipo_pago" value="1">
            <input type="hidden" name="cod_cont_afect" id="cod_cont_afect">
            <input type="hidden" id="msg_total"></input>
                <div class="input-group mb-2">
                    <div class="input-group-prepend" style="background-color: transparent;border: none;">
                        <div class="input-group-text"><img src="media/icon/dollar.svg" alt="" style="width:20px;"></div>
                    </div>
                    <input type="text" class="form-control" id="pago" name="pago" placeholder="$" style="border-left: none;" autocomplete="off">
                </div>
                <?php if($credito->contabilidad ==1){?>
                    <div class="form-group">
                        <label for="retencion">Retencion</label>
                        <select class="form-control" id="retenciones" name="retenciones">
                        <option value="0">No</option>
                        <?php foreach ($retenciones as $retencion) {?>
                            <option value="<?=$retencion->re_id?>"><?=$retencion->re_nombre." (".$retencion->re_porcentaje."%)"?></option>
                        <?php }?>
                        </select>
                    </div>
                    <div class="input-group-prepend" style="background-color: transparent;border: none;">
                    <input type="text" class="form-control" id="codigo_contableby" name="cuenta_pago" attr="<?=$attr?>" param="<?=$param?>" placeholder="Cuenta Contable de pago" autocomplete="off">
                    </div>
			<div class="input-group-prepend mt-2" style="background-color: transparent;border: none;">
                    <input type="text" class="form-control fc-datepicker fc-datepicker-color fc-datepicker-primary" id="" name="start_date" placeholder="YYYY-MM-DD" value="<?=date("m/d/Y")?>" autocomplete="off" readonly>
                    </div>
                    <select name="comprobante" id="" class="comprobante form-control select2 mt-2">
                      <?php foreach ($comprobantes as $comprobante) {?>
                        <option value="<?=$comprobante->iddetalle_documento_sucursal?>"><?=$comprobante->nombre.":".$comprobante->ultima_serie."-".zero_fill($comprobante->ultimo_numero+1,8)?></option>
                      <?php }?>
                    </select>
                <?php }?>
                
                <div class="legend mb-2 mt-2" style="width:400px;">
                    <!--<div class="divider line"></div>
                     <div class="change"><p style="background:white; ">Aplicar retenciones</p></div> -->
                 
            </form>
            
        </div>
        <div>
        <div class="legend mb-2 mt-2" style="width:400px;">
                <div class="divider line"></div>
            <div class="change"><p style="background:white; ">Opciones rapidas</p></div>
        <?php 
            $i=0;
            foreach ($listPrice as $listProice) { ?>
               <button class="btn btn-outline-secondary quick_option" id="<?=$listPrice[$i]?>"><?=$listPrice[$i]?></button>
        <?php $i++;} ?>
        </div>
</div>
<script src="controller/script/puc.js"></script>
<script src="controller/script/CreditoProveedor.js"></script>
		<script>
$('.fc-datepicker').datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
      });
</script>