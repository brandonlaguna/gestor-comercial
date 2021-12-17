<script type="text/javascript">
$('.selectpicker').selectpicker();

$("#formDocumento").validate({
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
            ultima_serie            : {required: true, rangelength: [6, 20]},
            ultimo_numero           : {required: true},
            idtipo_documento        : {required: true},
            dds_pri_id              : {required: true},
            contabilidad            : {required: true},
            dds_afecta_inventario   : {required: true},
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

function guardarActualizarDocumento(){
    var validateForm = $("#formDocumento").valid();
        if (validateForm == true) {
            var dataDocumento = new FormData();
            dataDocumento.append('iddetalle_documento_sucursal',$('#iddetalle_documento_sucursal').val());
            dataDocumento.append('ultima_serie',$('#ultima_serie').val());
            dataDocumento.append('ultimo_numero',$('#ultimo_numero').val());
            dataDocumento.append('dds_pri_id',$('#dds_pri_id').val());
            dataDocumento.append('contabilidad',$('#contabilidad').val());
            dataDocumento.append('dds_afecta_inventario',$('#dds_afecta_inventario').val());
            dataDocumento.append('idtipo_documento',$('#idtipo_documento').val());
            dataDocumento.append('dds_propertie',$('#dds_propertie').val());
            $('#btnformDocumento').prop('disabled', true);
				$.ajax({
					type        : 'POST',
					method      : 'POST',
					cache       : false,
					contentType : false,
					processData : false,
					url         : base_url('DocumentoSucursal?action=guardarActualizar'),
					data        : dataDocumento,
					dataType    : 'JSON',
					success: function(data) {
                        if(data.estadoDocumento == true){
                            toastMessage('success', data.mensajeDocumento);
                            $('#btnformDocumento').prop('disabled', false);
                            $('#formDocumento').trigger("reset");
                            $('#modalDocumento').modal('hide');
                            $('#tblDocumentoSucursal').DataTable().ajax.reload(null, false);
                        }else{
                            toastMessage('error', data.mensajeDocumento);
                            $('#btnformDocumento').prop('disabled', false);
                        }
					}
            });
        }
}

</script>