<div class="br-mailbox-list" style="">
      <div class="br-mailbox-list-header">
        <a href="" id="showMailBoxLeft" class="show-mailbox-left hidden-sm-up">
          <i class="fa fa-arrow-right"></i>
        </a>
        <h6 class="tx-white mg-b-0 tx-13 tx-uppercase">Archivos <span class="tx-roboto"></span></h6>
        <div class="btn-group" role="group" aria-label="Basic example">
          <button type="button" class="btn btn-info disabled pd-x-25"><i class="fa fa-angle-left"></i></button>
          <button type="button" class="btn btn-info pd-x-25"><i class="fa fa-angle-right"></i></button>
        </div>
      </div><!-- br-mailbox-list-header -->
      <?php foreach ($files as $file) {?>

      <div class="br-mailbox-list-body">
        <div class="br-mailbox-list-item unread openWindow" file="<?=$file->mmf_id?>" >
          <div class="d-flex justify-content-between mg-b-5">
            <div>
            </div>
            <span class="tx-12"><?=$file->mmf_date_created?></span>
          </div><!-- d-flex -->
          <img src="media/svg/typefile/<?=$file->mmf_ext.$support_typefile?>" alt="" style="width:25px;"><span class="tx-14 mg-b-10 tx-white"><?=$file->mmf_name.".".$file->mmf_ext?></span>
        </div><!-- br-mailbox-list-item -->
        </div><!-- br-mailbox-list-body -->
        <?php }?>
      
    </div><!-- br-mailbox-list -->
  </div>