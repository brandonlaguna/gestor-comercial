<iframe src="<?=$url?>" frameborder="0" width="100%"  height="<?=$file_height?>" name="" id="printpdf"></iframe>
<?php 
if(isset($view) && !empty($view)){}
else{
?>
<a href="#<?=$redirect?>" class="btn btn-primary float-btn shadow" style="background:#17A2B8;"><i class="fas fa-angle-double-left"></i></a>
<?php }?>
<style>
.float-btn{
    position: sticky;
    margin-top: -130;
    margin-left: 10px;
    width: 70;
    height: 70px;
    border-radius: 50%;
}
.fa-angle-double-left{
    font-size:48px;
}
</style>