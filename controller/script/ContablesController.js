$( document ).ready(init);
function init() {
    $(".item-clase").click(loadGrupo);
}

function loadGrupo(){
    var idclase= $(this).attr("gateway");
    var attr="loadGrupo";
    sendCup(attr,idclase);
}

function sendCup(attr,id){
    $("#"+attr).html(attr);
    var url = "index.php?controller=Contables&action="+attr;
    $.ajax({
        method: "POST",
        url: url,
        cache : "false",
        data: {id:id},
        success : function(response) {
            $("#"+attr).html(response);
        },
        error : function(xhr, status) {
            
        },
        beforeSend: function(){
            $("#"+attr).html(linearLoading());
        }
        });
}