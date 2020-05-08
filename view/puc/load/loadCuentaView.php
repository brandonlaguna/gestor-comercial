<?php foreach ($cuenta as $cuenta) {?>
    <a class="list-group-item list-group-item-action item-cuenta" gateway="<?=$cuenta->idcodigo?>" id="list-<?=$cuenta->idcodigo?>-list" data-toggle="list" href="#list-<?=$cuenta->idcodigo?>" role="tab" aria-controls="<?=$cuenta->idcodigo?>"><i class="fas fa-folder text-warning"></i>&nbsp;<?=$cuenta->idcodigo." ".$cuenta->tipo_codigo?></a>
<?php } ?> 
<script src="controller/script/loadSubCuenta.js"></script>