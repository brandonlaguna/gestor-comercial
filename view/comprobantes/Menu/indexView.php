<style>
.card-list-menu{
    transition-duration: 500ms !important;
}
.card-list-menu:hover{
-webkit-box-shadow: 0px 0px 36px -20px rgba(0,0,0,0.83) !important;
-moz-box-shadow: 0px 0px 36px -20px rgba(0,0,0,0.83) !important;
box-shadow: 0px 0px 36px -20px rgba(0,0,0,0.83) !important;
transition-duration: 500ms !important;
cursor:pointer;
}
</style>
<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="row">
        
            <?php foreach ($listmenu as $list => $value) { ?>
                <div class="col-sm-4 col-md-4 col-lg-2 p-3 center">
                
                <a href="<?=$value["url"]?>">
                    <div class="shadow-sm text-center card-list-menu" style="min-height:194px; border-radius:14px;">
                        <div class="pl-2 pr-2">
                            <div class="d-flex justify-content-center" style="">
                                <div class="text-center d-flex justify-content-center mt-1" style="background:#f0f7fb; height:100px; width:100px; border-radius:50%; ">
                                    <div style="margin-top:15%;">
                                    <img src="<?=$value["urlicon"]?>" alt="" class="" style="width:66px; height:66px;">
                                    </div>
                                </div>
                            </div>
                 </a>
                        </div>
                        <div class="p-3 text-center main-menu-text">
                        <p class="font-weight-bold"><?=$list?></p>
                        </div>

                    </div>
                </div> 
            <?php }?>
            
        </div><!--row-->
    </div><!--section-wrapper-->
</div><!--pagebody-->