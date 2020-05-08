<?php foreach ($sucursales as $sucursal) {?>
<a href="" class="contact-list-link new">
    <div class="d-flex">
        <div class="pos-relative">
            <img src="<?=PATH_CLIENT.$sucursal->logo?>" alt="" height="45px" width="46px">
            <div class="contact-status-indicator bg-success"></div>
        </div>
        <div class="contact-person">
            <p style="font-size:8.5pt;"><?=$sucursal->razon_social?></p>
            <span><?=$sucursal->direccion?></span>
        </div>
        <span class="tx-info tx-12"><span class="square-8 bg-success rounded-circle"></span></span>
    </div><!-- d-flex -->
</a>
<?php }?>