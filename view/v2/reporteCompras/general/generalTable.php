<script>
    var reporteTable = new tui.Grid({
        el: document.getElementById('gridReporteComprasGeneral'),
        data: {
        api: {
            readData: { url: 'reporteCompras?action=generalAjax', method: 'POST' }
        },
        initialRequest:true,
        },
        scrollX: true,
        scrollY: true,
        minBodyHeight: 50,
            header: {
            height: 50,
        },
        minWidth:60,
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
            header:'Proveedor',
            name:'proveedor',
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
            header:'Retencion',
            name:'retencion',
        },
        {
            header:'Subtotal',
            name:'subtotal',
        },
        {
            header:'Total',
            name:'total',
        }
    ],
    columnOptions: {
        resizable: true
    },
    summary: {
        height: 40,
        position: 'bottom', // or 'top'
        columnContent: {
            subtotal: {
                template: function(valueMap) {
                    return `${valueMap.sum}`;
                }
            },
            total: {
            template: function(valueMap) {
                    return `${valueMap.sum}`;
                }
            }
        }
    }
    });


</script>

