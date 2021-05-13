$( document ).ready(init);
function init() {
$(".efectivo").keyup(calculador);
}
function calculador(){
    
    var id= $(this).attr("id");
    var monto = $(this).val();
    var denominacion = $(this).attr("denominacion");
    $("#efectivo_"+id).val(monto*denominacion);
}

