<script type="text/javascript">
    var ventas   = [];
	var configTable = [];
	$(document).ready(function() {
		configTable.buttons = [];
		configTable.columns = [];
		configTable.filtros = {};

        var btnNuevoTipoDocumento = {
			titleAttr : 'Nuevo tipo de documento',
			text      : '<i class="fas fa-file-invoice-dollar"></i> <i class="fas fa-plus-circle"></i>',
			action    : () => {
				$('#titleModalTipoDocumento').html('Nuevo Tipo de Documento');
				$('#idtipo_documento').val('');
				resetModalForm('modalTipoDocumento', 'btnformTipoDocumento', false, false);
				$('#modalTipoDocumento').modal('toggle');
			},
			className: 'btn btn-success btn-sm text-white'
		}

		var btnEditarTipoDocumento = {
			titleAttr 	: 'Editar tipo de documento',
			text      	: '<i class="fas fa-file-invoice-dollar"></i> <i class="fas fa-plus-circle"></i>',
			extend		: 'selected',
			action    	: () => {
				var datos  = table.row({selected: true}).data();
				if (datos[6]=='Activo') {
					$('#titleModalTipoDocumento').html('Editar Tipo de Documento #'+datos[0]);
					$('#idtipo_documento').val(datos[0]);
					$('#nombre').val(datos[1]);
					$('#prefijo').val(datos[2]);
					$('#operacion').val(datos[3]);
					$('#proceso').val(datos[4]);
					$('#type').val(datos[5]);
					$('.selectpicker').selectpicker('refresh')
					$('#modalTipoDocumento').modal('toggle');
				}else{
					toastMessage('error','Tipo de documento inactivo');
				}
			},
			className: 'btn btn-warning btn-sm text-white'
		}

        var btnExportarExcel ={
			titleAttr : 'Exportar a Excel',
			text      : '<i class="fas fa-file-excel"></i>',
			extend    : 'excelHtml5',
			className : 'btn btn-outline-success btn-sm',
			filename  : 'Listado de Articulos'
		}

        configTable.buttons.push(btnNuevoTipoDocumento);
        configTable.buttons.push(btnEditarTipoDocumento);
        configTable.buttons.push(btnExportarExcel);

		function ajaxTipoDocumento() {
			$('#tblTiposDocumentosSucursal').DataTable().ajax.reload(null, false);
		}
        var table = $('#tblTiposDocumentosSucursal').DataTable({
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
					{ 'data': 6 }
                ],
				pagingType     : "full_numbers",
				lengthMenu     : [
					[10, 25, 50, -1],
					[10, 25, 50, "Todos"]
				],
				select         : {
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
					url : base_url('lib/languaje/spanishVentas.json')
				},
				dom     : 'BftipP',
                ajax: function(){
                    $.ajax({
						type     : 'POST',
						url      : base_url('TiposDocumentos?action=obtenerTiposDocumentosAjax'),
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
						targets: 6,
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