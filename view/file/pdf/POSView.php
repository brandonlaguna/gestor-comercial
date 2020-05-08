<iframe src="<?=$url?>/file&action=<?=$funcion?>&data=<?=$id?>" frameborder="0" width="100%" height="92.4%" name="" id="printpdf"></iframe>
<a href="#<?=$redirect=($funcion=="POS_venta")?"ventas":"compras/reg_compras"?>" class="btn btn-primary float-btn shadow" style="background:#17A2B8;"><i class="fas fa-angle-double-left"></i></a>
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