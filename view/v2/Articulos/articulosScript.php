<script type="text/javascript">
    $('.selectpicker').selectpicker();

    $(".formArticulo").validate({
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
            nombre               : {required: true, lettersonly: true, maxlength: 120},
            idcategoria          : {required: true},
            idunidad_medida      : {required: true},
            descripcion          : {required: true, rangelength: [60, 2100]},
            costo_producto       : {required: true},
            precio_venta         : {required: true},
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
	function ajaxArticulo() {
		$('#tblArticulos').DataTable().ajax.reload(null, false);
	}

    function resetModalForm(idModal = null, idButton = null, loadTable = false, loadAlerts = false) {
	$('#'+idModal+' .form-control').removeClass('is-valid is-invalid ').val(null).tooltip('dispose');
	$('#'+idModal+' .selectpicker').selectpicker('setStyle', 'btn-outline-valid btn-outline-invalid', 'remove').selectpicker('val', null).tooltip('dispose');
	$('#'+idModal+' :file').filestyle('clear');
	$('#'+idModal+' .selectpicker').selectpicker('refresh');
    $('#'+idModal+' input[type=hidden]').val(0);
	$('#imgArea').filestyle('clear');
	$('#'+idModal+' .input-check-radio').iCheck('uncheck');
	$('#'+idModal+' input[type=checkbox]').prop('checked', false)
	idModal    != null ? $('#'+idModal).modal('hide'):'';
	idButton   != null ? $('#'+idButton).removeAttr('disabled'):'';
	loadTable  == true ? ajaxArticulo():'';
	//loadAlerts == true ? alertasUsuarios():'';
}

function guardarActualizarProducto() {
    var validateForm = $(".formArticulo").valid();
    console.log(validateForm);
	if (validateForm == true) {
		var dataArticulo = new FormData();
		dataArticulo.append('idarticulo', $('#idarticulo').val());
		dataArticulo.append('nombre', $('#nombre').val());
		dataArticulo.append('idcategoria', $('#idcategoria').val());
		dataArticulo.append('idunidad_medida', $('#idunidad_medida').val());
		dataArticulo.append('descripcion', $('#descripcion').val());
		dataArticulo.append('costo_producto', $('#costo_producto').val());
		dataArticulo.append('precio_venta', $('#precio_venta').val());
		$('#btnformGuardarActualizarProducto').prop('disabled', true);
		$.ajax({
			type        : 'POST',
			method      : 'POST',
			cache       : false,
			contentType : false,
			processData : false,
			url  : base_url('Articulos?action=guardarActualizar'),
			data : dataArticulo,
			dataType : 'JSON',
			success: function(json) {
				try {
					toastMessage(json.alert, json.message);
				} catch (e) {
					toastMessage('error', e);
				}
			}
		});
    }
}
</script>