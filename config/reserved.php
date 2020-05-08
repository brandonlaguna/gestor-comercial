<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?php
foreach(glob("dependences/materializeV1.0.0/*.css") as $style){
    echo "<link rel='stylesheet' type='text/css' href='/".INITIAL."/".$style."'> ";
}
foreach(glob("dependences/materializeV1.0.0/*.js") as $script){
    echo "<script src='/".INITIAL."/".$script."'> </script>";
}
foreach(glob("dependences/adm/css/*.css") as $style){
    echo "<link rel='stylesheet' type='text/css' href='/".INITIAL."/".$style."'> ";
}
foreach(glob("dependences/adm/js/*.js") as $animate){
    echo "<script src='/".INITIAL."/".$animate."'> </script>";
}