<div class="row">
    <div class="col-sm-12 col-md-3 col-lg-3">
    <?=$this->frameview("mymedia/folder/fileList",array("files"=>$files,"support_typefile"=>$support_typefile));?>
    </div>
    <div class="col-sm-12 col-md-8 col-lg-8">
        <?=$this->frameview("mymedia/folder/draggableFile",array());?>
    </div>
</div>
<link rel="stylesheet" href="css/bracket.dark.css">