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
	filtrarStockDatatables(obj);
});

function filtrarStockDatatables(filtrosCuentas = null) {
	configTable.filtros = filtrosCuentas
    console.log(filtrosCuentas);
	$.ajax({
		type     : 'POST',
		url      : base_url('reporteStock?action=reporteStockAjax'),
		data     : {
			filtrosCuentas : JSON.stringify(configTable.filtros),
		},
		dataType : 'JSON',
		success  : (cuentas) => {
			$('#tblReporteStock').DataTable().clear();
			if ( cuentas.length > 0 ) {
				$('#tblReporteStock').DataTable().rows.add(cuentas);
				$('#filtroAvanzado').modal('hide');
			}
			$('#tblReporteStock').DataTable().draw();
			$('#tblReporteStock').DataTable().searchPanes.rebuildPane();
		}
	});
}
</script>