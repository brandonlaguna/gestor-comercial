<div class="form-group <?=$col?$col:'col-md-12'?>">
    <p><?=isset($title)?$title:''?> <?=isset($required)?'<span class="tx-danger">*</span>':''?></p>
    <?=isset($label)?"<small>$label</small>":''?>
    <select name="<?=isset($name)?$name:''?>" id="<?=isset($id)?$id:''?>" class="<?=isset($class)?$class:'form-control'?>"
    <?=isset($onchange)?'onchange="'.$onchange.'"':''?>
    <?=isset($required)?'required':''?>
    >
        <?php 
        if(isset($items) && !empty($items)):?>
                <option value=0>Selecciona una opcion...</option>
        <?php
            foreach ($items as $item): ?>
                <option value="<?=$item['item_id']?>" <?=$item['item_id'] == $selected?'selected':''?>><?=$item['item_name']?></option>
        <?php 
            endforeach;
        endif;
        ?>
    </select>
</div>