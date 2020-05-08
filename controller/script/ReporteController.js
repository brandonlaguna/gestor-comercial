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
    data=JSON.parse('{'+data.substr(1)+'}');
console.log(data);
    $.post("index.php?controller="+control+"&action="+pos,data, function(r) {
        //console.log(r);
        $("#reporte").html(r);

    });

    
}