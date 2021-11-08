$( document ).ready(init);
function init() {
    $(".item-cuenta").click(loadSubCuenta);
}

function loadSubCuenta(){
    var idcuenta= $(this).attr("gateway");
    var attr="loadSubCuenta";
    getSubCuenta(attr,idcuenta);
}


function getSubCuenta(attr,id){
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