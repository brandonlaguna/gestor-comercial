<?php 
require_once 'config/global.php';
        //require_once 'config/Api.php';
?>
<head>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel=stylesheet href='https://fonts.googleapis.com/css?family=Poppins%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2Cregular%2Citalic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMontserrat%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2Cregular%2Citalic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMerriweather%3A300%2C300italic%2Cregular%2Citalic%2C700%2C700italic%2C900%2C900italic&amp;subset=latin%2Clatin-ext%2Cdevanagari%2Ccyrillic-ext%2Cvietnamese%2Ccyrillic&amp;' type=text/css media=all>
<?php
foreach(glob("js/*.js") as $script){
    echo "<script src='".$script."'></script>";
}
?>
    <!-- UI DESIGN FRAMEWORK -->
    <link rel="stylesheet" href="lib/select2/css/select2.min.css">
    <link rel="stylesheet" href="css/bracket.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href="lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="lib/rickshaw/rickshaw.min.css" rel="stylesheet">
    <link href="lib/spinkit/css/spinkit.css" rel="stylesheet">
    <link href="lib/timepicker/jquery.timepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="lib/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="lib/infinite-bar/infinite.css">
    <link rel="stylesheet" href="node_modules/tui-grid/dist/tui-grid.min.css" />
    <link rel="stylesheet" href="node_modules/jquery-toast-plugin/dist/jquery.toast.min.css">
    <link rel="stylesheet" type="text/css" href="lib/datatablesV1.0.0/datatables.css">
    <link rel="stylesheet" type="text/css" href="node_modules/flatpickr/dist/flatpickr.min.css"/>
    <link rel="stylesheet" type="text/css" href="node_modules/flatpickr/dist/themes/dark.css"/>
    <link rel="stylesheet" href="node_modules/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="lib/bootstrap-select/dist/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="lib/ajax-bootstrap-select/dist/css/ajax-bootstrap-select.min.css">
    <link rel="stylesheet" href="node_modules/css-toggle-switch/dist/toggle-switch.css">
    <!-- Bracket CSS -->
</head>