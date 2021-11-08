$( document ).ready(init);
function init() {
    $(".setmetodopago").change(addMontoMetodoPago);
}

function addMontoMetodoPago() {
    var data= $("#formMetodoPago").serializeArray();
    console.log(data);
    $.ajax({
        method: "POST",
        url: "index.php?controller=MetodoPago&action=addMontoCarro",
        cache : "false",
        data: data,
        success : function(r) {
            try {
                console.log(r);
                calculoVenta();
                addmetodopago();
                // r =JSON.parse(r);
                // if(r.type == "message"){
                //     toastMessage(r.alertType,r.response,r.success);
                //     $(".linearLoading").html("");
                // }else if(r.type == "redirect"){
                //     $(location).attr('href',"#"+r.success);
                // }else{
                //     $(location).attr('href',"#"+r.success);
                // }

            } catch (e) {
                r =JSON.parse(r);
                toastMessage('error',r);
            } 
        },
        error : function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
        })

        return false;
}


function calculoVenta(){
    data = $("#detalleComprobante option:selected").val();
    contabilidad = $("#contabilidad").val();
    $.post("index.php?controller=Ventas&action=calculoVenta",{data:data,contabilidad:contabilidad}, function(r) {
        $("#calculoVenta").html(r);
      });
    
}

function addmetodopago(){
    data = $(".select2mp option:selected").val();
    $.ajax({
        method: "POST",
        url: "index.php?controller=MetodoPago&action=addMetodoPagoToCart",
        cache : "false",
        data: {data:data},
        success : function(r) {
            loadCart();
            $(".linearLoading").html("");
            $("#listMetodoPago").html(r);
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
        })
}