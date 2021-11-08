var Comprobante = function() {
    return {
        ajaxComprobante: function(url, sucursal, select, area = null) {
            $.ajax({
                type: 'POST',
                url: url,
                data: { idSucursal: sucursal },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    var $selectComprobante = $('#' + select);
                    $selectComprobante.empty();
                    for (row in data) {
                        var dataContent = "<div>\
                                            <strong>" + data[row].nombreTipoDocumento + ' ' + data[row].ultima_serie + "</strong> \
                                            <small> Consecutivo: " + data[row].ultimo_numero + "</small>\
                                            </div>";
                        $selectComprobante.append($('<option value="' + data[row].ultima_serie + '" title="' + data[row].ultima_serie + '"\
                                                    data-content="' + dataContent + '"></option>'));
                    }
                    $selectComprobante.selectpicker('refresh');
                },
                error: function(obj, txt, c) {
                    console.log(txt);
                }
            });
        }
    }
}();