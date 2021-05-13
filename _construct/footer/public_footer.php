<script src="public/js/libs/jquery-3.5.1.min.js"></script> 
<script src="public/js/libs/jquery-migrate-1.4.1.min.js"></script>   
<script src="public/js/components/slick.js"></script>
<script src="public/js/components/syotimer.js"></script>
<?php
foreach(glob("public/js/*.js") as $script){
    echo "<script src='".$script."'></script>";
}
?>
</body>