$( document ).ready(init);
function init() {
    $("#pago").keyup(calcularPago);
    $("#pago").change(calcularPago);
    $("#codigo_contableby").change(calcularPago);
    $("#retenciones").change(calcularPago);
    $(".forma-pago ").click(loadPago);
    $(".quick_option").click(quick_option);
    $(".sendForm").click(sendPago);
}

function calcularPago(){
    var pos = $("#pos").val();
    var pago = $("#pago").val();
    var retencion = $('#retenciones option:selected').val();
    var credito = $("#idcredito").val();
    var cuenta_pago = $("#codigo_contableby").val();
//     $.post("index.php?controller="+pos+"&action=calcularCartera",{pago:pago,retencion:retencion,credito:credito}, function(r) {
//        //console.log(r);
//        response = JSON.parse(r);
//        $("#msg_total").html(response.msg+" $"+response.total);
//        $("#msg_total").attr("class",response.color);
//        $("#deuda").html("$"+response.deuda);
//        $("#cod_cont_afect").val(response.cod_cont_afect);
//        if(response.status == true){
//            $("#send").removeAttr("disabled");
//        }else{
//             $("#send").attr("disabled","disabled");
//        }
//    });

   $.ajax({
    method: "POST",
    url: "index.php?controller="+pos+"&action=calcularCartera",
    cache : "false",
    data: {pago:pago,retencion:retencion,credito:credito,cuenta_pago:cuenta_pago},
    success : function(r) {
        response = JSON.parse(r);
        $(".linearLoading").html("");
        $("#msg_total").html(response.msg+" $"+response.total);
        $("#msg_total").attr("class",response.color);
        $("#deuda").html("$"+response.deuda);
        $("#cod_cont_afect").val(response.cod_cont_afect);
        if(response.status == true){
           $("#send").removeAttr("disabled");
        }else{
            $("#send").attr("disabled","disabled");
        }
    },
    error : function(xhr, status) {
        $(".linearLoading").html("Error");
    },
    beforeSend: function(){
        $(".linearLoading").html(linearLoading());
        $("#send").attr("disabled","disabled");
    }
    })

   }
   
   function quick_option(){
       var mount= $(this).attr("id");
       $("#pago").val(mount);
       calcularPago();
   }

   

   function sendPago(){
    setTimeout(function () {
    var pos = $("#pos").val();
       var data = $("#pago_credito").serialize();
       var url ="index.php?controller="+pos+"&action=pago_autorizado";
       $.ajax({
           method: "POST",
           url: url,
           cache : "false",
           data: data,
           success : function(r) {
            $(".linearLoading").html("");
            r =JSON.parse(r); 
            if(r.error){
                toastMessage('error',r.error);
            }else{
                $(location).attr('href',"#"+r.success);
            }
           },
           error : function(xhr, status) {
               
           },
           beforeSend: function(r){
            $(".linearLoading").html(linearLoading());
           }
           });
        },3000);
   }
