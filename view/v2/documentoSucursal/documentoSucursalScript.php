<script type="text/javascript">
    $("#btnGuardarActualizar").click(function(){
        $.ajax({
        method: "POST",
        url: base_url('documentoSucursal?action=guardarActualizar'),
        cache : "false",
        data: data,
        success : function(response) {
            toastMessage("success","Documento creado",false);
        },
        error : function(xhr, status) {
            
        },
        beforeSend: function(){
            $("."+modalPos).html(loading());
        }
        });
    });
</script>