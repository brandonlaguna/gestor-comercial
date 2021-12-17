$( document ).ready(init);
function init() {

    $(".forma-pago ").click(loadPago);

}

function loadPago(){
    var data = $("#formInfoPago").serialize();
    var idPago= $(this).attr("id");
    var modalPos = "modal-body";
    var url ="index.php?controller=proveedor&action=generar_pago_proveedor&data="+idPago;
    $.ajax({
        method: "POST",
        url: url,
        cache : "false",
        data: data,
        success : function(response) {
            $("."+modalPos).html(response);
        },
        error : function(xhr, status) {
            
        },
        beforeSend: function(){
            $("."+modalPos).html(loading());
        }
        });
    //$("."+modalPos).html(idPago);
}

