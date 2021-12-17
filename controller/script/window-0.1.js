$( document ).ready(init);
function init() {
    $(".openWindow").click(openWindow);
}

function openWindow(){
    file = $(this).attr("file");
    $.ajax({
        async: true,
        method: "POST",
        url: "index.php?controller=Windows&action=openWindow",
        cache : "false",
        data:{file},
        success : function(r) {
            $(".wall").append(r);
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
        }
        });
}

function closeWindow(){
    id = $(this).attr("idwindow");
    $("#"+id).remove();
}

function loadWindow(hashurl,idwindow){
    console.log(hashurl);
    $.ajax({
        async: true,
        method: "POST",
        url: hashurl,
        cache : "false",
        data:{file},
        success : function(r) {
            $(".window-body-"+idwindow).html(r);
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
        }
        });
}