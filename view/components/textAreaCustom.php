
<div class="form-group <?=$col?$col:'col-md-12'?>">
<label for="<?=isset($id)?$id:''?>"><?=isset($title)?$title:''?> <?=isset($required) && $required == true?'<span class="tx-danger">*</span>':''?></label>
    <textarea  type="<?=isset($type)?$type:'text'?>"
            class="form-control"
            id="<?=isset($id)?$id:''?>"
            placeholder="<?=isset($placeholder)?$placeholder:''?>"
            name="<?=isset($name)?$name:''?>"
            value="<?=isset($value)?$value:''?>"
            <?=isset($required) && $required == true?'required':''?>
            >
    </textarea>
</div>