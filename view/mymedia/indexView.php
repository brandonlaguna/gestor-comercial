<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="row">
        <?php
            foreach ($folder as $folder  => $values) {?>
            <a href="<?=$values["url"]?>">
            <div class="card" style="width: 10rem; border:none; margin:1em;">
            <?= $new = ($values["new"])?'<span class="badge badge-danger wd-40 position-absolute">nuevo</span>':'';?>
            <img src="media/svg/typefile/<?=$folder?>.svg" alt="" style="width:100%;" >
                <div class="card-body">
                    <p class="card-text text-center"><?=$values["name"]?></p>
                </div>
            </div>
            </a>
        <?php }?>
        </div>
    </div>
</div>
