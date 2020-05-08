$( document ).ready(init);
function init() {
    $("#pago").keyup(calcularPago);
    $("#pago").change(calcularPago);
    $("#retenciones").change(calcularPago);
    $(".forma-pago ").click(loadPago);
    $(".quick_option").click(quick_option);
    $(".sendForm").click(sendPago);
}

function calcularPago(){
    var pos = $("#pos").val();
    var pago = $("#pago").val();
    var retencion = 0;
    var credito = $("#idcredito").val()
    $.post("index.php?controller="+pos+"&action=calcularCartera",{pago:pago,retencion:retencion,credito:credito}, function(r) {
       //console.log(r);
       response = JSON.parse(r);
       $("#msg_total").html(response.msg+" $"+response.total);
       $("#msg_total").attr("class",response.color);
       if(response.attr == false){
           $("#send").removeAttr("disabled");
       }else{
           $("#send").attr("disabled","disabled");
       }
   });
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
            r =JSON.parse(r); 
            if(r.error){
                console.log(r.error);
            }else{
                $(location).attr('href',"#file/cartera/"+pos+"/"+r.success);
            }
           },
           error : function(xhr, status) {
               
           },
           beforeSend: function(r){
            
           }
           });
        },3000);
   }
