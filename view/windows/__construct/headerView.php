<?php foreach ($file as $datafile) {}?>
<script>
    $(".closeDragWindow").click(closeWindow);
</script>
<div class="col-sm-12 pl-3 pr-3 pt-1 pb-2" style="border-radius:3px 3px 0px 0px; border: 0px white solid; background:rgb(241, 241, 241);">
    <div class="row">
        <div class="col-sm-6">
            <img src="media/svg/typefile/<?=$datafile->mmf_ext.$support_typefile?>" style="width:20px;"><span class="tx-medium"><?=$datafile->mmf_name.".".$datafile->mmf_ext?></span>
        </div>
        <div class="col-sm-6 text-right">
        <i class="fas fa-circle text-success btn-windows-action disabled"></i>
        <i class="fas fa-circle text-warning btn-windows-action disabled"></i>
        <i class="closeDragWindow fas fa-circle text-danger btn-windows-action" idwindow="<?=$idwindow?>"></i>
        </div>
    </div>
    
</div>