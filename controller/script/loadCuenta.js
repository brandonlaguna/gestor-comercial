$( document ).ready(init);
function init() {
    $(".item-grupo").click(loadCuenta);
}

function loadCuenta(){
    var idgrupo= $(this).attr("gateway");
    var attr="loadCuenta";
    getCuenta(attr,idgrupo);
}

function getCuenta(attr,id){
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