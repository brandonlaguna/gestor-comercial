$( document ).ready(init);
function init() {
    $(".forma-pago").click(loadPago);
    $(".quick_option").click(quick_option);
    $("#pago").keyup(calcularPago);
    $("#pago").change(calcularPago);
}
function loadPago(){
    var pos = $("#pos").val();
    var idPago= $(this).attr("id");
    var comprobante = $("#detalleComprobante option:selected").val();
    var modalPos = "modal-body";
    var url ="index.php?controller=compras&action=forma_pago";
    $.ajax({
        method: "POST",
        url: url,
        cache : "false",
        data: {comprobante:comprobante,idPago:idPago},
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

function quick_option(){
    var mount= $(this).attr("id");
    $("#pago").val(mount);
    calcularPago();
}

function calcularPago(){
    var comprobante =$("#detalleComprobante option:selected").val();
    var pos = $("#pos").val();
    var pago = $("#pago").val();
    $.post("index.php?controller=compras&action=forma_pago",{pago:pago,comprobante:comprobante}, function(r) {
       //console.log(r);
       response = JSON.parse(r);
       console.log(r);
       $("#msg_total").html(response.msg+" $"+response.total);
       $("#msg_total").attr("class",response.color);
       if(response.attr == false){
           $("#send").removeAttr("disabled");
       }else{
           $("#send").attr("disabled","disabled");
       }
   });
   }