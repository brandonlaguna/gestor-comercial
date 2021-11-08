<link href="https://fonts.googleapis.com/css2?family=Shadows+Into+Light&display=swap" rel="stylesheet">
<div class="container-fluid section-media">
<a href="<?=$url?>" target="_blank" rel="">
    <div class="img-container">
        <img src="media/svg/typefile/xls.svg" alt="Paris" class="center">
        <p class="message">vista previa no disponible</p>
    </div>
    </a>
</div>

<iframe src="<?=$url?>" frameborder="0" width="100%"  height="" name="" id="printpdf"></iframe>
<?php 
if(isset($view) && !empty($view)){}
else{
?>
<a href="#<?=$redirect?>" class="btn btn-primary float-btn shadow" style="background:#17A2B8;"><i class="fas fa-angle-double-left"></i></a>
<?php }?>
<style>
.section-media{
    padding-top:3em;
}
.img-container{
    background: #f4f5f7;
    border-radius: 20px;
    margin: auto;
    width:300px;
    padding: 10px;
    text-align:center;
    
    -webkit-box-shadow: 1px 1px 14px -7px rgba(0,0,0,0.67);
    -moz-box-shadow: 1px 1px 14px -7px rgba(0,0,0,0.67);
    box-shadow: 1px 1px 14px -7px rgba(0,0,0,0.67);
}
.img-container > p{
    font-family: 'Shadows Into Light', cursive;
    text-decoration:none;
    color:black;
}
a:link
{
text-decoration:none;
}
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 150px;
}
.float-btn{
    position: sticky;
    margin-top: 130;
    margin-left: 10px;
    width: 70;
    height: 70px;
    border-radius: 50%;
}
.fa-angle-double-left{
    font-size:48px;
}
</style>