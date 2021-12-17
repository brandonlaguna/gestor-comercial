
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
            
            try {
                r = JSON.parse(response);
                console.log(r.alert);
                if(r.alert != null){
                    toastMessage(r.alert,r.message);
                }else{
                    $(".br-mainpanel").html(response);
                }
                
            }catch(err){

                $(".br-mainpanel").html(response);
                
            }
            
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
                response = JSON.parse(response);
                if(typeof(response.redirect) != "undefined" && response.redirect !== null){
                    $(location).attr('href',response.redirect);
                }else if(typeof(response.alert) != "undefined" && response.alert !== null){
                        swal(response.title,response.message,response.alert);
                }

            } catch (e) {
                swal(response,"","success");
            }
        },
        error : function(xhr, status) {
            
        },
        beforeSend: function(){
            
        }
        });

}

function actionToReaction(object,launcher,data){
    var finish = $("#"+object).attr("finish");
    var dirty = finish.replace("/", "&action=");
    var url= "index.php?controller="+dirty;
    console.log(url);
    $("#actionToReaction").modal("hide");
    $.ajax({
        method: "POST",
        url: url,
        cache : "false",
        data: data,
        success : function(r) {
            try {
                r =JSON.parse(r);
                if(r.type == "message"){
                    toastMessage(r.alertType,r.response,r.success);
                    $(".linearLoading").html("");
                }else if(r.type == "redirect"){
                    window.location = r.success;
                    //$(location).attr('href',r.success);
                }else{
                   $("#"+launcher).html(r);
                }
            } catch (e) {
                $("#"+launcher).html(r);
                $("#actionToReaction").modal("show");
            }
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


function toastMessage(heading,message, redirect = false, alertType = 'error'){
    $.toast({
    text: message, // Text that is to be shown in the toast
    heading: heading, // Optional heading to be shown on the toast
    icon: heading, // Type of toast icon
    showHideTransition: 'fade', // fade, slide or plain
    allowToastClose: true, // Boolean value true or false
    hideAfter: 5000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
    stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
    position: 'mid-center', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
    
    textAlign: 'left',  // Text alignment i.e. left, right or center
    loader: true,  // Whether to show loader or not. True by default
    loaderBg: '#ffffff80',  // Background color of the toast loader
    beforeShow: function () {}, // will be triggered before the toast is shown
    afterShown: function () {}, // will be triggered after the toat has been shown
    beforeHide: function () {}, // will be triggered before the toast gets hidden
    afterHidden: function () {
        if(redirect){
            window.location.href = redirect;
        }
    }  // will be triggered after the toast has been hidden
    })
}



