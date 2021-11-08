$( document ).ready(init);
function init() {
    $(".item-subcuenta").click(loadAuxiliares);
}

function loadAuxiliares(){
    var idsubcuenta= $(this).attr("gateway");
    var attr="loadAuxiliares";
    getAuxiliares(attr,idsubcuenta);
}

function getAuxiliares(attr,id){
    $("#"+attr).html(attr);
    var url = "index.php?controller=Contables&action="+attr;
    $.ajax({
        method: "POST",
        url: url,
        cache : "false",
        data: {id:id},
        success : function(response) {
            $("#"+attr+id).html(response);
        },
        error : function(xhr, status) {
            
        },
        beforeSend: function(){
            $("#"+attr+id).html(linearLoading());
        }
        });
}