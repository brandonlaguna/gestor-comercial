<script type="text/javascript">
$(function () {
    $(".filtro_rango_fecha").flatpickr(
        {
            mode: "range",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    // disable every multiple of 8
                    
                }
            ]
        }
    );

    $('.select_multiple_sucursal').selectpicker();
});
$("#btnFiltrarRegistro").click(function(){
    var form = document.getElementById('formFiltro');
    var data = new FormData(form);
    var obj = {};
    for (var key of data.keys()) {
		obj[key] = data.get(key);
	}
	filtrarEmpleadosDatatables(obj);
});

function filtrarEmpleadosDatatables(filtrosCuentas = null) {
	configTable.filtros = filtrosCuentas
    console.log(filtrosCuentas);
	$.ajax({
		type     : 'POST',
		url      : base_url('movimientoCuentas?action=getMovimientoCuentasAjax'),
		data     : {
			filtrosCuentas : JSON.stringify(configTable.filtros),
		},
		dataType : 'JSON',
		success  : (cuentas) => {
			$('#tblMovimientoCuentas').DataTable().clear();
			if ( cuentas.length > 0 ) {
				$('#tblMovimientoCuentas').DataTable().rows.add(cuentas);
				$('#filtroAvanzado').modal('hide');
			}
			$('#tblMovimientoCuentas').DataTable().draw();
			$('#tblMovimientoCuentas').DataTable().searchPanes.rebuildPane();
		}
	});
}
</script>