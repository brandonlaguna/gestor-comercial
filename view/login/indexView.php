<meta name="viewport" content="initial-scale=1, maximum-scale=1"><!-- 2485684 / 880012-->
<div class=container-fluid style="background-image:url('https://source.unsplash.com/collection/148982/1600x900'); height:100%; background-size:cover;">
    <div class="container">
    <div class="jumbotron" style="background:transparent;">
        <div class=row>
            <div class="col col-sm-12 col-md-12" style="">
                <?php $this->frameview("login/form/clasicForm",array())?>
                <?php if(isset($alert)){$this->frameview("toast/alert",array("alert"=>$alert));}?>
            </div>
        </div>
    </div>
    </div>
   
</div>

<script type="text/javascript">
window.onload = function() {
    $('.toast').toast('show')
};

</script>