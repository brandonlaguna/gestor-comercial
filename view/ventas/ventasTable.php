<script type="text/javascript">
    var ventas   = [];
	var configTable = [];
	$(document).ready(function() {
		configTable.buttons = [];
		configTable.columns = [];
		configTable.filtros = {};
        var btnFiltrar = {
			titleAttr : 'Filtrar',
			text      : '<i class="fas fa-filter"></i><sup><i class="fas fa-users"></i></sup>',
			action    : function () {
				//$('#filtrosAvanzadosEmpleados').modal('toggle');
			},
			className: 'btn btn-outline-success'
		};

        var btnNuevoExamen = {
						titleAttr : 'Nueva Venta',
						text      : '<i class="fas fa-file-invoice-dollar"></i> <i class="fas fa-plus-circle"></i>',
						action    : () => {
                            window.location.href = "#ventas/nueva_venta";
						},
						className: 'btn btn-success btn-sm text-white'
		}
        var btnEditar = {
			titleAttr : 'Editar Venta',
        	extend    : 'selected',
			text      : '<i class="fas fa-file-invoice-dollar"></i> <i class="fas fa-marker"></i>',
			action    : () => {
				var datos  = table.row({selected: true}).data();
				$.ajax({
					type     	: "POST",
					url      	: base_url('VentasAjax?action=ver_editar_venta'),
					dataType 	: 'JSON',
					data		: {idventa:datos[0]},
					success  	: function(response) {
						try {
							response.forEach(element => {
								toastMessage(element[0],element[1], element[2]);
							});
        				} catch (error) {
        			    	toastMessage('error',error);
        				}
				}
			});

			},
			className: 'btn btn-outline-warning btn-sm'
		};
		var btnAnular = {
			titleAttr : 'Anular Venta',
            extend    : 'selected',
			text      : '<i class="fas fa-file-invoice-dollar"></i> <i class="fas fa-undo-alt "></i>',
			action    : () => {
				var datos  = table.row({selected: true}).data();
				$.ajax({
					type     	: "POST",
					url      	: base_url('VentasAjax?action=ver_anular_venta'),
					dataType 	: 'JSON',
					data		: {idventa:datos[0]},
					success  	: function(response) {
						try {
							if(response.status == true){
								actionToReaction('anulacionVenta','modalSystem',datos[0])
							}else{
								toastMessage('error','No se puede anular esta venta');
							}
            		} catch (error) {
            		    toastMessage('error',error);
            		}
				}
			});
			
			},
			className: 'btn btn-outline-danger btn-sm'
		};

        var btnExportarExcel ={
			titleAttr : 'Exportar a Excel',
			text      : '<i class="fas fa-file-excel"></i>',
			extend    : 'excelHtml5',
			className : 'btn btn-outline-success btn-sm',
			filename  : 'Listado Proveedores'
		}
        var btnExportarPDF = {
			titleAttr     : 'Exportar a PDF',
			text          : '<i class="fas fa-file-pdf"></i>',
			extend        : 'pdfHtml5',
			alignment     : 'center',
			messageTop    : 'Listado Proveedores',
			margin        : [ 20, 10, 10, 20 ],
			orientation   : 'landscape',
			pageSize      : 'letter',
			image         : 'data:image/png;base64,',
			filename      : 'Listado Proveedores',
			className     : 'btn btn-outline-danger btn-sm',
			messageBottom : 'PDF Creado por Centro de Apoyo WebSoftware'
		}
        var btnImprimir = {
			titleAttr : 'Imprimir',
			extend    : 'selected',
			text      : '<i class="fa fa-lg fa-print"></i>',
			action    : () => {
                var datos = table.row({selected: true}).data();
                window.location.href = "#file/venta/"+datos[0];
				//$('#crearProveedores').modal('toggle');
			},
			className: 'btn btn-outline-info btn-sm'
		}
        configTable.buttons.push(btnNuevoExamen);
        configTable.buttons.push(btnEditar);
        configTable.buttons.push(btnAnular);
        configTable.buttons.push(btnExportarExcel);
        configTable.buttons.push(btnExportarPDF);
        configTable.buttons.push(btnImprimir);

        var table = $('#tblVentas').DataTable({
				scrollY        : '70vh',
				scrollX        : true,
				scrollCollapse : true,
				fixedHeader    : true,
				order          : [[ 0, "desc" ]],
                columns :[
                    { 'data': 0 },
					{ 'data': 1 },
					{ 'data': 2 },
					{ 'data': 3 },
					{ 'data': 4 },
					{ 'data': 5 },
					{ 'data': 6 },
					{ 'data': 7 },
                    { 'data': 8 }
                ],
				pagingType     : "full_numbers",
				lengthMenu     : [
					[10, 25, 50, -1],
					[10, 25, 50, "Todos"]
				],
				select         : {
					style    : 'os',
					blurable : true
				},
                responsive : {
					details: {
						type: 'column',
						target: 0
					}
				},
				searchPanes:{
					columns: [2]
				},
                language : {
					url : base_url('lib/languaje/spanishVentas.json')
				},
				dom     : 'BftipP',
                ajax: function(){
                    $.ajax({
						type     : 'POST',
						url      : 'VentasAjax?action=getAll',
						data     : {},
						dataType : 'JSON',
						success  : (examenes) => {
							//Se guarda la pagina actual
							var paginaActual = table.page();
							//Se guarda la posición del scroll actual
							var scrollActual = {
								'top': $(table.settings()[0].nScrollBody).scrollTop(),
								'left': $(table.settings()[0].nScrollBody).scrollLeft()
							};
							table.clear();
							if (examenes.length>0) {
								table.rows.add(examenes);
							}
							table.draw();
							table.searchPanes.rebuildPane();
							//Se verifica la pagina actual y si no es la inicial se restaura
							if (paginaActual > 0) {
								table.page(paginaActual).draw('page');
							}
							//Se verifica el scroll actual y si no es el inicial se restaura
							if ( scrollActual.top > 0 || scrollActual.left > 0) {
								$(table.settings()[0].nScrollBody).scrollTop( scrollActual.top );
								$(table.settings()[0].nScrollBody).scrollLeft( scrollActual.left );
							}
						},
                        error   :  function(error){
                            console.log(error);
                        }
					});
                },
                fnDrawCallback: function(){
					tippy('.btnFuncionVenta', {
						theme      : "light-border",
						maxWidth   : "600",
						interactive: true,
						arrow      : false,
						allowHTML  : true,
						appendTo   : () => document.body
					});
				},
                buttons: [
					configTable.buttons,
                ],
                columnDefs : [
					{
						targets: 8,
						createdCell: function (td, cellData, rowData, row, col) {
							if (cellData =='Aceptado') {
								$(td).addClass('text-success font-weight-bold text-center');
							} else if (cellData =='Cancelado') {
								$(td).addClass('text-warning font-weight-bold text-center');
							}
						}
					},
					{className: "text-right", "targets": [5, 6]},
				],
            });
    });
</script>
