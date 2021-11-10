<script type="text/javascript">
    $('.selectpicker').selectpicker();
    // Validacion de formulario
$("#formNuevoUsuario").validate({
	errorPlacement: function(error, element){
		return false;
	},
	rules: {
        empleado    : {required:true},
        sucursal    : {required:true},
        permisos    : {required:true},
        password    : {required:true, minlength:5},
	},
	highlight: function(element) {
		if (element.options!=undefined) {
			$(element).selectpicker('setStyle', 'btn-outline-valid', 'remove').selectpicker('setStyle', 'btn-outline-invalid');
		} else {
			$(element).removeClass('is-valid').addClass('is-invalid');
		}
	},
	unhighlight: function(element) {
		if (element.options!=undefined) {
			$(element).selectpicker('setStyle', 'btn-outline-invalid', 'remove').selectpicker('setStyle', 'btn-outline-valid');
		} else {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	}
});
$("#formEditarUsuario").validate({
	errorPlacement: function(error, element){
		return false;
	},
	rules: {
        empleadoE    : {required:true},
        sucursalE    : {required:true},
        permisosE    : {required:true},
	},
	highlight: function(element) {
		if (element.options!=undefined) {
			$(element).selectpicker('setStyle', 'btn-outline-valid', 'remove').selectpicker('setStyle', 'btn-outline-invalid');
		} else {
			$(element).removeClass('is-valid').addClass('is-invalid');
		}
	},
	unhighlight: function(element) {
		if (element.options!=undefined) {
			$(element).selectpicker('setStyle', 'btn-outline-invalid', 'remove').selectpicker('setStyle', 'btn-outline-valid');
		} else {
			$(element).removeClass('is-invalid').addClass('is-valid');
		}
	}
});
function guardarActualizarUsuario(formulario,modal = false){
    var validateForm = $("#"+formulario).valid();
    console.log(validateForm);
    if (validateForm == true) {
        var dataEmpleado = new FormData();
        var identAcion = formulario == 'formEditarUsuario'?'E':'';
        dataEmpleado.append('sucursal',$('#sucursal'+identAcion).val());
        dataEmpleado.append('permisos',$('#permisos'+identAcion).val());
        dataEmpleado.append('password',$('#password'+identAcion).val());
        dataEmpleado.append('empleado', identAcion =='E'?$('#empleado'+identAcion).val():0);
        //$('#btn'+formulario).prop('disabled', true);
        $.ajax({
            type        : 'POST',
            method      : 'POST',
            cache       : false,
            contentType : false,
            processData : false,
            url         : base_url('Usuario?action=guardarActualizarUsuario'),
            data        : dataEmpleado,
            dataType    : 'JSON',
            success: function(data) {
                console.log(data);
                if(data.estado == true){
                    toastMessage('success', data.mensaje);
                    $('#btn'+formulario).prop('disabled', false);
                    $('#'+formulario).trigger("reset");
                    $('#'+modal).modal('hide');
                    $('#tblUsuarios').DataTable().ajax.reload(null, false);
                }else{
                        toastMessage('error', data.mensaje);
                        $('#btn'+formulario).prop('disabled', false);
                }
        }
    });
    }
}

$('.guardarActualizarPermiso input').click(function(event){
    var value = event.target.checked;
    var permiso = $(this).val();
    var estado = value==true?1:0;
    var dataPermiso = new FormData();
    dataPermiso.append('per_estado',estado);
    dataPermiso.append('per_pr_id',permiso);
    dataPermiso.append('per_u_id',$('#idUsuarioPermiso').val());
    $.ajax({
            type        : 'POST',
            method      : 'POST',
            cache       : false,
            contentType : false,
            processData : false,
            url         : base_url('Permisos?action=guardarActualizarPermiso'),
            data        : dataPermiso,
            dataType    : 'JSON',
            success: function(data) {
                if(data.estado == true){
                    toastMessage('success', data.mensaje);
                }else{
                    toastMessage('error', data.mensaje);
                }
        }
    });
    //$('#permiso_1 input').attr('checked',true)
    console.log(estado);
});

function guardarActualizarPermiso(idpermiso){
    console.log(idpermiso);
    // var value = $('#permiso_'+idpermiso+' input:checked').val();
    // var estado = value!=undefined?1:0;
    // var dataPermiso = new FormData();
    // dataPermiso.append('per_estado',estado);
    // dataPermiso.append('per_pr_id',idpermiso);
    // dataPermiso.append('per_u_id',$('#idUsuarioPermiso').val());
    // $.ajax({
    //         type        : 'POST',
    //         method      : 'POST',
    //         cache       : false,
    //         contentType : false,
    //         processData : false,
    //         url         : base_url('Permisos?action=guardarActualizarPermiso'),
    //         data        : dataPermiso,
    //         dataType    : 'JSON',
    //         success: function(data) {
    //             console.log(data);
    //             if(data.estado == true){
    //                 toastMessage('success', data.mensaje);
    //             }else{
    //                 toastMessage('error', data.mensaje);
    //             }
    //     }
    // });
    // //$('#permiso_1 input').attr('checked',true)
    // console.log(estado);
}
function usuarioActivo(){
    toastMessage('success', 'Usuario Activo');
}
function usuarioInactivo(){
    toastMessage('warning', 'Usuario Activo');
}
</script>