$( document ).ready(init);
function init() {
    $(".filter").change(loadReport);
}

function loadReport(){
    var x = $("#reporte_general").serializeArray();
    var pos =  $("#pos").val();
    var control =$("#control").val();
    
    data =""; 
    $.each(x, function(i, field){
    data+=',"'+field.name+'":"'+field.value+'"'
    //data +=',"'+field.name+'":"'+field.value+'"'
    });

    // $.each(x, function(i, field){
    //     $this=$(this);
    //     data+=',"'+$this.attr("name")+'":"'+$(this).find('option:selected').val()+'"'
    // });

    data=JSON.parse('{'+data.substr(1)+'}');
    console.log(data);
    
    $.post("index.php?controller="+control+"&action="+pos,data, function(r) {
        //console.log(r);
        $("#reporte").html(r);
    });

    $.ajax({
        method: "POST",
        url: "index.php?controller="+control+"&action="+pos,
        cache : "false",
        data: data,
        success : function(r) {
            $("#reporte").html(r);
            $(".linearLoading").html("");
        },
        error : function(xhr, status) {
            //$(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function(){
            $(".linearLoading").html(linearLoading());
        }
        })

    
}