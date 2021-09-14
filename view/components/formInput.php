
<div class="form-group <?=$col?$col:'col-md-12'?>">
    <p><?=isset($title)?$title:''?> <?=isset($required)?'<span class="tx-danger">*</span>':''?></p>

    <input  type="<?=isset($type)?$type:'text'?>" 
            class="form-control" 
            id="<?=isset($id)?$id:''?>" 
            placeholder="<?=isset($placeholder)?$placeholder:''?>" 
            autocomplete="<?=isset($autocomplete)?$autocomplete:'off'?>"
            name="<?=isset($name)?$name:''?>" 
            value="<?=isset($value)?$value:''?>"
            <?=isset($required)?'required':''?>
            >
</div>