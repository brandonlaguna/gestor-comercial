<?php 
?>
<div class="row">
    <div class="col-sm-12 text-center ">
            <h1>$<?=$total?></h1>
        <div class="legend mb-2 mt-2" style="width:400px;">
            <div class="divider line">
            </div>
        <div class="change"><p style="background:white;" >Método de pago: <strong>Efectivo</strong></p></div>
            <form id="pago_credito" finish="proveedor/pago_credito" width="400px">
            <h5 id="msg_total"></h5>
                <div class="input-group mb-2">
                    <div class="input-group-prepend" style="background-color: transparent;border: none;">
                        <div class="input-group-text"><img src="media/icon/dollar.svg" alt="" style="width:20px;"></div>
                    </div>
                    <input type="text" class="form-control" id="pago" name="pago" placeholder="$" style="border-left: none;">
                </div>
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

<script src="controller/script/FormaPagoController.js"></script>