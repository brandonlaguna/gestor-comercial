<?php
    if(isset($items) && !empty($items)):?>
<?php
    foreach ($items as $item): ?>
<label class="switch-light row <?=isset($class)?$class:''?>" style="width:100%;  padding:1em;"
    <?php if(isset($attr)): foreach ($attr as $attr => $value) {
        if($value == 'id'){
            echo $attr.'="'.$item['item_id'].'"';
        }else{
            echo $attr.'="'.$value.'"';
        }
    } endif;?>
    <?php if(isset($onclick)): echo 'onclick="'.$onclick.'('.$item['item_id'].')"'; endif;?>
    id="<?=isset($id)?$id.'_'.$item['item_id']:''?>"
    >
    <input type="checkbox" value="<?=$item['item_id']?>">
    <strong class="col-sm-10">
            <?=$item['item_name']?>
    </strong>
    <span class="progress col-sm-2">
        <span aria-label="Off" title="Off">Off</span>
        <span aria-label="On" title="On">On</span>
        <a class="progress-bar"></a>
    </span>
</label>
<?php
    endforeach;
endif;
?>