<script type="text/javascript">
$('.selectpicker').selectpicker();
$(".formTipoDocumento").validate({
        submit : false,
		ignore : '.note-editor *',
		errorPlacement: function(error, element){
			if ( element.hasClass('selectpicker') ) {
				element.attr('data-toggle', 'tooltip').attr('data-html', true).tooltip('show');
			} else {
				element.attr('data-toggle', 'tooltip').attr('data-html', true).tooltip('show');
			}
			return false;
		},
		rules: {
            nombre              : {required: true, rangelength: [6, 120]},
            prefijo             : {required: true, rangelength: [3, 10]},
            operacion           : {required: true},
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
				$(element).selectpicker('setStyle', 'btn-outline-invalid', 'remove').selectpicker('setStyle', 'btn-outline-valid').tooltip('dispose');
			} else {
				$(element).removeClass('is-invalid').addClass('is-valid').tooltip('dispose');
			}
		}
	});

    function guardarActualizarTipoDocumento() {
        var validateForm = $("#formTipoDocumento").valid();
        if (validateForm == true) {
            var dataTipoDocumento = new FormData();
            dataTipoDocumento.append('idtipo_documento', $('#idtipo_documento').val())
            dataTipoDocumento.append('nombre', $('#nombre').val());
            dataTipoDocumento.append('prefijo', $('#prefijo').val());
            dataTipoDocumento.append('type', $('#type').val());
            dataTipoDocumento.append('operacion', $('#operacion').val());
            dataTipoDocumento.append('proceso', $('#proceso').val());
            $('#btnformTipoDocumento').prop('disabled', true);
				$.ajax({
					type        : 'POST',
					method      : 'POST',
					cache       : false,
					contentType : false,
					processData : false,
					url         : base_url('TiposDocumentos?action=guardarActualizarTipoDocumento'),
					data        : dataTipoDocumento,
					dataType    : 'JSON',
					success: function(data) {
                        if(data.estadoTipoDocumento == true){
                            toastMessage('success', data.mensajeTipoDocumento);
                            $('#btnformTipoDocumento').prop('disabled', false);
                            $('#formTipoDocumento').trigger("reset");
                            $('#modalTipoDocumento').modal('hide');
                            $('#tblTiposDocumentosSucursal').DataTable().ajax.reload(null, false);
                        }else{
                            toastMessage('error', data.mensajeTipoDocumento);
                            $('#btnformTipoDocumento').prop('disabled', false);
                        }
					}
            });
        }
    }
</script>