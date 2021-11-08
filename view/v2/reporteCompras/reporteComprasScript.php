<script type="text/javascript">
$('.selectSucursal').selectpicker();
$('.selectComprobante').selectpicker();
$('.selectTipoPago').selectpicker();
$('.selectEstado').selectpicker();
$('.selectProveedor').selectpicker();
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
//Combobox Comprobantes
$(document).on('change', '#filtroSucursal', function(event) {
        console.log('cambiado');
		if($(this).val()!=undefined){
			Comprobante.ajaxComprobante(base_url('DocumentoSucursal?action=obtenerDocumentosAJax'), $(this).val(), 'filtroComprobante', 0);
		}
	});

$(document).ready(function() {
    $(".calendar").flatpickr({
        mode: "range",
        dateFormat: "Y-m-d"
    });
    //filtros de onchange
    $('#filtroFecha').change(function() {
        var dataFechas = new FormData();
        dataFechas.append('filtroFecha', $('#filtroFecha').val());
        $.ajax({
            type: 'POST',
            method: "POST",
            cache: false,
            contentType: false,
            processData: false,
            url: "FiltroReporte?action=filtroFecha",
            cache: "false",
            data: dataFechas,
            dataType: 'JSON',
            success: function(r) {
                if (r.estado) {
                    reporteTable.reloadData();
                }
            },
            error: function(xhr, status) {},
            beforeSend: function() {}
        })
    });
});

//* MODALS *//
function filtroPersonalizado() {
    $('#modalFiltroReporte').modal('toggle');
}

/* DIRECT FUNCTIONS */
function limpiarFiltro(){
    $.ajax({
        type: 'POST',
        method: "POST",
        cache: false,
        contentType: false,
        processData: false,
        url: "FiltroReporte?action=limpiarFiltroReporte",
        cache: "false",
        dataType: 'JSON',
        success: function(r) {
            if (r.estado) {
                reporteTable.reloadData();
            }else{
                toastMessage('error','No fué posible limpiar el filtro');
            }
        },
        error: function(xhr, status) {
            toastMessage('error','No fué posible limpiar el filtro');
        },
        beforeSend: function() {}
    })
}
function filtrarReportePersonalizado() {
    console.log('iniciado');
    var dataFiltro = new FormData();
    dataFiltro.append('filtroSucursal', $('#filtroSucursal').val());
    dataFiltro.append('filtroComprobante', $('#filtroComprobante').val());
    dataFiltro.append('filtroTipoPago', $('#filtroTipoPago').val());
    dataFiltro.append('filtroEstado', $('#filtroEstado').val());
    dataFiltro.append('filtroProveedor', $('#filtroProveedor').val());
    $.ajax({
        type: 'POST',
        method: "POST",
        cache: false,
        contentType: false,
        processData: false,
        url: "FiltroReporte?action=filtroReporte",
        cache: "false",
        data: dataFiltro,
        dataType: 'JSON',
        success: function(r) {
            if (r.estado) {
                $('#modalReporteSucursal').modal('hide');
                reporteTable.reloadData();
            }
        },
        error: function(xhr, status) {},
        beforeSend: function() {}
    })
}
</script>