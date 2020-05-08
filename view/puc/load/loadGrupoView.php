<?php foreach ($grupo as $grupo) {?>
    <a class="list-group-item list-group-item-action item-grupo" gateway="<?=$grupo->idcodigo?>" id="list-<?=$grupo->idcodigo?>-list" data-toggle="list" href="#list-<?=$grupo->idcodigo?>" role="tab" aria-controls="<?=$grupo->idcodigo?>"><i class="fas fa-folder text-warning"></i>&nbsp;<?=$grupo->idcodigo." ".$grupo->tipo_codigo?></a>
<?php } ?> 
<script src="controller/script/loadCuenta.js"></script>
