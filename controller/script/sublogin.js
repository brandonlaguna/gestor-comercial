$( document ).ready(init);
function init() {
    $("#sendLogin").click(validationCode);
}

function validationCode(){
    code = $("#code").val();
    redirection = $("#redirect option:selected").val();
    pos = $("#pos").val()
    $.ajax({
        method: "POST",
        url: pos,
        cache : "false",
        data:{code:code,redirection:redirection},
        success : function(r) {
            r = JSON.parse(r);
            try {
                $(location).attr('href',r.redirect);
              }
              catch(err) {
                console.log(r.error);
              }
        },
        error : function(xhr, status) {
        },
        beforeSend: function(){
            $(".linear-loading").html(linearLoading());
        }
        });
}