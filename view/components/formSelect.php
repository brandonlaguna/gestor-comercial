<div class="form-group <?=$col?$col:'col-md-12'?>">
    <p><?=isset($title)?$title:''?> <?=isset($required) && $required == true?'<span class="tx-danger">*</span>':''?></p>
    <?=isset($label)?"<small>$label</small>":''?>
    <select name="<?=isset($name)?$name:''?>" id="<?=isset($id)?$id:''?>" class="<?=isset($class)?$class:'form-control form-select-sm selectpicker'?>"
    <?=isset($onchange)?'onchange="'.$onchange.'"':''?>
    <?=isset($required) && $required == true?'required':''?>
    <?=isset($multiple)?'multiple':''?>
    <?php if(isset($style)){
        echo 'style="';
        foreach ($style as $class => $value) {
            echo $class.':'.$value.';';
    } echo '"';}?>
    <?php if(isset($attr)):
        foreach ($attr as $attr => $value) {
            echo $attr.'="'.$value.'"';
        }
    endif;?>
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