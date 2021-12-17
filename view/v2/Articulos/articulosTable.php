<script type="text/javascript">
    var ventas   = [];
	var configTable = [];
	$(document).ready(function() {
		configTable.buttons = [];
		configTable.columns = [];
		configTable.filtros = {};

        var btnNuevoArticulo = {
			titleAttr : 'Nueva Articulo',
			text      : '<i class="fas fa-box"></i> <i class="fas fa-plus-circle"></i>',
			action    : () => {
				$('#idarticulo').val(0);
                $('#modalGuardarActualizarProducto').modal('toggle');
			},
			className: 'btn btn-success btn-sm text-white'
		}

		var btnActualizarArticulo = {
			titleAttr : 'Actualizar Articulo',
			text      : '<i class="fas fa-box"></i> <i class="fas fa-plus-circle"></i>',
			extend    : 'selected',
			action    : () => {
				var datos  = table.row({selected: true}).data();
				$.ajax({
					type: "POST",
					url: base_url('Articulos?action=editarArticulo'),
					data:{idArticulo:datos[0]},
					dataType    : 'JSON',
					success: function(articulo) {
						console.log(articulo);
						Object.entries(articulo.data).forEach(element => {
							const [key, value] = element;
							$('#' + key).val(value);
						});
						$('.selectpicker').change();
						$('#modalGuardarActualizarProducto').modal('toggle');
					}
				});
                $('#modalGuardarActualizarProducto').modal('toggle');
			},
			className: 'btn btn-warning btn-sm text-white'
		}

		var btnAnular = {
			titleAttr : 'Inactivar Articulo',
            extend    : 'selected',
			text      : '<i class="fas fa-box"></i> <i class="fas fa-undo-alt "></i>',
			action    : () => {
				var datos  = table.row({selected: true}).data();
			},
			className: 'btn btn-outline-danger btn-sm'
		};

		var btnActivarArticulo = {
			titleAttr : 'Activar Articulo',
            extend    : 'selected',
			text      : '<i class="fas fa-box"></i> <i class="fas fa-undo-alt "></i>',
			action    : () => {
				var datos  = table.row({selected: true}).data();
			},
			className: 'btn btn-outline-info btn-sm'
		};

        var btnExportarExcel ={
			titleAttr : 'Exportar a Excel',
			text      : '<i class="fas fa-file-excel"></i>',
			extend    : 'excelHtml5',
			className : 'btn btn-outline-success btn-sm',
			filename  : 'Listado de Articulos'
		}

        configTable.buttons.push(btnNuevoArticulo);
		configTable.buttons.push(btnActualizarArticulo);
        configTable.buttons.push(btnAnular);
        configTable.buttons.push(btnExportarExcel);

		function ajaxArticulo() {
			$('#tblArticulos').DataTable().ajax.reload(null, false);
		}
        var table = $('#tblArticulos').DataTable({
				scrollY        : '70vh',
				scrollX        : true,
				scrollCollapse : true,
				fixedHeader    : true,
				order          : [[ 0, "asc" ]],
                columns :[
                    {   'data': 0,
                        "className": 'details-control',
                        "orderable":  false,
                        "defaultContent": '' 
                    },
					{ 'data': 1 },
					{ 'data': 2 },
					{ 'data': 3 },
					{ 'data': 4 },
					{ 'data': 5 },
					{ 'data': 6 },
					{ 'data': 7 },
                    { 'data': 8 },
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
					columns: [2,3]
				},
                language : {
					url : base_url('lib/languaje/spanishArticulos.json')
				},
				dom     : 'BftipP',
                ajax: function(){
                    $.ajax({
						type     : 'POST',
						url      : base_url('Articulos?action=getArticulosAjax'),
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
                buttons: [
					configTable.buttons,
                ],
                columnDefs : [
					{
						targets: 8,
						createdCell: function (td, cellData, rowData, row, col) {
							if (cellData =='Activo') {
								$(td).addClass('text-success font-weight-bold text-center');
							} else if (cellData =='Inactivo') {
								$(td).addClass('text-warning font-weight-bold text-center');
							}
						}
					},
				],
            });
    });
</script>