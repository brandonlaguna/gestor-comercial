jQuery(window).on('hashchange', function(){
    var tread = $(this).attr("tread");
    var thread = $(this).attr("thread");
    var response = window.location.hash;
    var hash = response.replace("#", "");
    dir=  hashurl(hash);
    $.ajax({
        method: "POST",
        url: dir,
        cache : "false",
        data: {tread:tread,thread:thread},
        success : function(response) {
            $(".br-mainpanel").html(response);
        },
        error : function(xhr, status) {
            $(".br-mainpanel").html("error en la consulta");
        },
        beforeSend: function(){
            $(".br-mainpanel").html(loading());
        }
        })
        
    });

function hashurl(hash){

    defined ={
        0:"",
        1:"?action=",
        2:"&data=",
        3:"&s=",
        4:"$t"
    }
    var url = hash.split("/");
    dir = "";
    for (var i=0; i < url.length; i++) {
     dir += defined[i]+""+url[i];
    }
    return dir;
}


function loading(){
     var loading = "<div class='d-flex bg-gray-200 ht-300 pos-relative align-items-center'>";
              loading += "<div class='sk-circle'> ";
                loading +="<div class='sk-circle1 sk-child'></div>";                  
                loading +="<div class='sk-circle2 sk-child'></div>";
                loading +="<div class='sk-circle3 sk-child'></div>";
                loading +="<div class='sk-circle4 sk-child'></div>";
                loading +="<div class='sk-circle5 sk-child'></div>";
                loading +="<div class='sk-circle6 sk-child'></div>";
                loading +="<div class='sk-circle7 sk-child'></div>";
                loading +="<div class='sk-circle8 sk-child'></div>";
                loading +="<div class='sk-circle9 sk-child'></div>";
                loading +="<div class='sk-circle10 sk-child'></div>";
                loading +="<div class='sk-circle11 sk-child'></div>";
                loading +="<div class='sk-circle12 sk-child'></div>";
                loading +="</div>";
            loading +="</div>";
            return loading;
}

function linearLoading(){
    
        var loading = '<div class="loading">';
        loading += '<div class="loading-bar">';
        loading +='<span class="bar-animation"></span>';
        loading +='</div>';
        loading +='</div>';
        return loading;
        
}
 

function sendForm(idform){
    var data = $("#"+idform).serialize();
    var finish = $("#"+idform).attr("finish");
    var dirty = finish.replace("/", "&action=");
    var url= "index.php?controller="+dirty;
    $.ajax({
        method: "POST",
        url: url,
        cache : "false",
        data: data,
        success : function(response) {
            try {
                response = JSON.parse(response)
                swal(response.title,response.message,response.alert);
            } catch (e) {
                swal(response,"","success");
            }
            // if(JSON.parse(response)){
            //     response = JSON.parse(response)
            //     swal(response.title,response.message,response.alert);
            // }else{
            //     swal("Mensaje del sistema",response,"success");
            // }
        },
        error : function(xhr, status) {
            
        },
        beforeSend: function(){
            
        }
        });

}

function sendTableForm(idform){
    var data = $("#"+idform).serialize();
    var dataArray = $("#"+idform).serializeArray();
    var finish = $("#"+idform).attr("finish");
    var dirty = finish.replace("/", "&action=");
    var url= "index.php?controller="+dirty;
    var i =0;
    var txt ="<tr>";

    txt +="<td>ID</td>";
    for(d in dataArray){
        txt += "<td>"+dataArray[i].value+"</td>";
       i++;
    }
    txt +="<td></td>";
    txt += "</tr>";
    $("table ."+idform).append(txt);
    $("#"+idform).trigger("reset");

    $.ajax({
        method: "POST",
        url: url,
        cache : "false",
        data: data,
        success : function(response) {
            swal("Mensaje del sistema",response,"success");
        },
        error : function(xhr, status) {
            
        },
        beforeSend: function(){
            
        }
        });
}

function sendIdModal(controller){
    $("#sendIdModal").attr("controller",controller);
    console.log(controller);
}

function sendModal(){
 controller = $("#sendIdModal").attr("controller");
 idModal = $("#sendIdModal").attr("tread");
 dir=  hashurl(controller);
 $.ajax({
    method: "POST",
    url: dir,
    cache : "false",
    success : function(response) {
        $("#"+idModal).html(response);
    },
    error : function(xhr, status) {
        $("#"+idModal).html("error al eliminar");
    },
    beforeSend: function(){
        $("#"+idModal).html(loading());
    }
    });
}

function resetModal(idModal){
    placeholder = $("#"+idModal).attr("placeholder");
    $("#"+idModal).html(placeholder);
}






