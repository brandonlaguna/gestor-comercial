<?php foreach ($file as $datafile) {}?>
<script>
 $( function() {
    $( "#set .drag-window" ).draggable({ stack: "#set div" });
    loadWindow("<?=$url?>","<?=$idwindow?>");

  });
</script>
<div class="drag-window ui-widget-content shadow" id="<?=$idwindow?>">
      <div class="container-fluid">
        <div class="row">

            <?=$this->frameview("windows/__construct/header",array("idwindow"=>$idwindow,"file"=>$file,"support_typefile"=>$support_typefile));?>

            <div class="col-sm-12 bg-white" style="padding: 0;">
                <div style="overflow:auto; height:515px" class="window-body window-body-<?=$idwindow?>">

                </div><!--window-body -->
            </div>

        </div><!--row -->
      </div><!--container fluid -->
  </div><!--drag-window -->