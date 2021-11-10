<script type="text/javascript">
    $('.selectpicker').selectpicker();
    // Validacion de formulario
    $("#formNuevaCategoria").validate({
			errorPlacement: function(error, element){
				return false;
			},
			rules: {
                nombre              : {required: true},
                cod_ventaE          :{required: true},
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
    // Formulario de actualizar
    $("#formEditarCategoria").validate({
			errorPlacement: function(error, element){
				return false;
			},
			rules: {
                nombreE              : {required: true},
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

    function guardarActualizarCategoria(formulario,modal = false){
        var validateForm = $("#"+formulario).valid();
        if (validateForm == true) {
			var dataCategoria = new FormData();
            var identAcion = formulario == 'formEditarCategoria'?'E':'';
            dataCategoria.append('nombre', $('#nombre'+identAcion).val());
            dataCategoria.append('cod_venta', $('#cod_venta'+identAcion).val());
            dataCategoria.append('cod_costos', $('#cod_costos'+identAcion).val());
            dataCategoria.append('cod_devoluciones', $('#cod_devoluciones'+identAcion).val());
            dataCategoria.append('cod_inventario', $('#cod_inventario'+identAcion).val());
            dataCategoria.append('imp_compra', $('#imp_compra'+identAcion).val());
            dataCategoria.append('imp_venta', $('#imp_venta'+identAcion).val());
            dataCategoria.append('idcategoria', identAcion =='E'?$('#idcategoria'+identAcion).val():0);
            $('#btn'+formulario).prop('disabled', true);
				$.ajax({
					type        : 'POST',
					method      : 'POST',
					cache       : false,
					contentType : false,
					processData : false,
					url         : base_url('Categorias?action=guardarActualizarCategoria'),
					data        : dataCategoria,
					dataType    : 'JSON',
					success: function(data) {
						console.log(data);
                        if(data.estado == true){
                            toastMessage('success', data.mensaje);
                            $('#btn'+formulario).prop('disabled', false);
                            $('#'+formulario).trigger("reset");
                            $('#'+modal).modal('hide');
                            $('#tblCategorias').DataTable().ajax.reload(null, false);
                        }else{
                            toastMessage('error', data.mensaje);
                            $('#btn'+formulario).prop('disabled', false);
                        }
					}
				});
        }
    }

    function cambiarEstadoCategoria(idcategoria,estado){
        $.ajax({
            type: "POST",
            url: base_url('Categorias?action=cambiarEstado'),
            data:{idcategoria:idcategoria,estado:estado},
            dataType    : 'JSON',
            success: function(estadoCategoria) {
                toastMessage(estadoCategoria.tipoAlerta, estadoCategoria.mensaje);
                $('#tblCategorias').DataTable().ajax.reload(null, false);
            }
    });
    }

    function categoriaActiva(){
        toastMessage('success', 'Categoria Activa');
    }
    function categoriaInactiva(){
        toastMessage('warning', 'Categoria inactiva');
    }
</script>