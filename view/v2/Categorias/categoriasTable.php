<script type="text/javascript">
    var ventas   = [];
	var configTable = [];
	$(document).ready(function() {
		configTable.buttons = [];
		configTable.columns = [];
		configTable.filtros = {};

        var btnNuevaCategoria = {
			titleAttr : 'Nueva Categoria',
			text      : '<i class="fas fa-sitemap"></i> <small><i class="fas fa-plus-circle"></i></small>',
			action    : () => {
				$('#modalNuevaCategoria').modal('toggle')
			},
			className: 'btn btn-success btn-sm text-white'
		}

		var btnEditarCategoria = {
			titleAttr : 'Editar Categoria',
			text      : '<i class="fas fa-sitemap"></i> <small><i class="fas fa-pen-fancy"></i></small>',
			extend    : 'selected',
			action    : () => {
				var datos  = table.row({selected: true}).data();
				if (datos[8]=='Activo') {
					$.ajax({
						type: "POST",
						url: base_url('Categorias?action=infoCategoria'),
						data:{idCategoria:datos[0],
						dataType    : 'JSON'
						},
						success: function(dataCategoria) {
							dataCategoria = JSON.parse(dataCategoria);
							$('#modalEditarCategoria').find('input:text').val('');
							$('#nombreE').val(dataCategoria.data[0].nombre);
							$('#idcategoriaE').val(dataCategoria.data[0].idcategoria);
							$('#cod_ventaE').val(dataCategoria.data[0].cod_venta);
							$('#cod_costosE').val(dataCategoria.data[0].cod_costos);
							$('#cod_devolucionesE').val(dataCategoria.data[0].cod_devoluciones);
							$('#cod_inventarioE').val(dataCategoria.data[0].cod_inventario);
							$('#imp_compraE').val(dataCategoria.data[0].imp_compra);
							$('#imp_ventaE').val(dataCategoria.data[0].imp_venta);
							$('.selectpicker').selectpicker('refresh')
							$('#modalEditarCategoria').modal('toggle');
						}
						});
				}else{
					categoriaInactiva();
				}
			},
			className: 'btn btn-warning btn-sm text-white'
		}

		var btnInactivarCaregoria = {
			titleAttr : 'Inactivar Categoria',
            extend    : 'selected',
			text      : '<i class="fas fa-sitemap"></i> <small><i class="fas fa-ban""></i></small>',
			action    : () => {
				var datos  = table.row({selected: true}).data();
				if (datos[8]=='Activo') {
					cambiarEstadoCategoria(datos[0],'D');
				}else{
					categoriaInactiva();
				}
			},
			className: 'btn btn-outline-danger btn-sm'
		};

		var btnActivarCaregoria = {
			titleAttr : 'Activar Categoria',
            extend    : 'selected',
			text      : '<i class="fas fa-sitemap"></i> <small><i class="fas fa-undo-alt "></i></small>',
			action    : () => {
				var datos  = table.row({selected: true}).data();
				if (datos[8]=='Inactivo') {
					cambiarEstadoCategoria(datos[0],'A');
				}else{
					categoriaActiva();
				}
			},
			className: 'btn btn-outline-info btn-sm'
		};

        var btnExportarExcel ={
			titleAttr : 'Exportar a Excel',
			text      : '<i class="fas fa-file-excel"></i>',
			extend    : 'excelHtml5',
			className : 'btn btn-outline-success btn-sm',
			filename  : 'Listado de categorias'
		}

        configTable.buttons.push(btnNuevaCategoria);
		configTable.buttons.push(btnEditarCategoria);
        configTable.buttons.push(btnInactivarCaregoria);
		configTable.buttons.push(btnActivarCaregoria);
        configTable.buttons.push(btnExportarExcel);

        var table = $('#tblCategorias').DataTable({
				scrollY        : '70vh',
				scrollX        : true,
				scrollCollapse : true,
				fixedHeader    : true,
				order          : [[ 0, "desc" ]],
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
					columns: [3,4,5,6,8]
				},
                language : {
					url : base_url('lib/languaje/spanishCategorias.json')
				},
				dom     : 'BftipP',
                ajax: function(){
                    $.ajax({
						type     : 'POST',
						url      : base_url('Categorias?action=getCategoriasAjax'),
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
                columnDefs : [{
						data           : null,
						defaultContent : '',
						className      : 'control font-weight-bold text-center',
						orderable      : false,
						targets        : 0
					},
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