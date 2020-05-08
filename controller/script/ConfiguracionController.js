$( document ).ready(init);
function init() {
    $("#empleado").change(loadEmpleado);
    $("#avatar").hide();
}

function loadEmpleado(){
    var idempleado = $("#empleado option:selected").val();
    $.post("index.php?controller=usuarios&action=loadEmpleado",{idempleado:idempleado}, function(r) {
        $("#calculoCompra").html(r);
        r = JSON.parse(r);
        $("#email").val(r.email);
        $("#avatar").attr("src",r.avatar);
        $("#avatar_s").val(r.avatar);
        $("#nombre_empleado").val(r.nombre);
        $("#avatar").show();
      });
}