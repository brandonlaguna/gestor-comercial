<script>
    tui.Grid({
        el: document.getElementById('gridReporteVentaDetallado'),
        data: {
        api: {
            readData: { url: 'reporteVentas?action=detalladoAjax', method: 'POST' }
        },
        initialRequest:true,
        },
        scrollX: false,
        scrollY: false,
        minBodyHeight: 50,
            header: {
            height: 50,
        },
        columns: [
        {
            header:'ID',
            name:'id',
            width: 40
        },
        {
            header:'Fecha',
            name:'fecha',
            minWidth: 40
        },
        {
            header:'Sucursal',
            name:'sucursal',
            minWidth: 70
        },
        {
            header:'Empleado',
            name:'empleado',
        },
        {
            header:'Cliente',
            name:'cliente',
        },
        {
            header:'Comprobante',
            name:'comprobante',
        },
        {
            header:'Impuesto',
            name:'impuesto',
        },
        {
            header:'Articulo',
            name:'articulo',
        },
        {
            header:'Código',
            name:'codigo',
        },
        {
            header:'Cantidad',
            name:'cantidad',
        },
        {
            header:'P. unitario',
            name:'p_unitario',
        },
        {
            header:'P. total',
            name:'p_total',
        },

    ],
    columnOptions: {
        resizable: true
    },
    summary: {
        height: 40,
        position: 'bottom', // or 'top'
        columnContent: {
            p_unitario: {
                template: function(valueMap) {
                    return `${valueMap.sum}`;
                }
            },
            p_total: {
            template: function(valueMap) {
                    return `${valueMap.sum}`;
                }
            }
        }
    }
    });


</script>

        
