$( document ).ready(init);
function init() {
    objects = [
        "start_date",
        "end_date",
        "porcentaje",
        "sendButton"
    ];
    $(".sendButton").click(refacturacion);
}

 function refacturacion(){

    var x = $("#refacturacion").serializeArray();
    data ="";
    pos= $("#pos").val();
    $.each(x, function(i, field){
    data+=',"'+field.name+'":"'+field.value+'"'
    //data +=',"'+field.name+'":"'+field.value+'"'
    });
    data=JSON.parse('{'+data.substr(1)+'}');
    for(var i=0;i< objects.length;i++){
        document.getElementById(objects[i]).setAttribute("disabled", ""); 
    }
    $.ajax({
        method: "POST",
        url: "refacturacion&action="+pos,
        data: data,
        success : function(r) {
            $(".linearLoading").html(" ");
            console.log(r);
        },
        error : function(xhr, status) {
           
        },
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
        })

    }