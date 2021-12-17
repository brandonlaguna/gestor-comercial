<script type="text/javascript">
    var ventas   = [];
	var configTable = [];
	$(document).ready(function() {
		configTable.buttons = [];
		configTable.columns = [];
		configTable.filtros = {};

        var btnNuevoDocumentoSucursal = {
			titleAttr : 'Nuevo documento',
			text      : '<i class="fas fa-file-invoice-dollar"></i> <i class="fas fa-plus-circle"></i>',
			action    : () => {
				$('#titleModalDocumento').html('Nuevo Documento');
				resetModalForm('modalDocumento', 'btnformDocumento', false, false);
				$('#modalDocumento').modal('toggle');
			},
			className: 'btn btn-success btn-sm text-white'
		}

		var btnEditarDocumentoSucursal = {
			titleAttr 	: 'Editar documento',
			text      	: '<i class="fas fa-file-invoice-dollar"></i> <i class="fas fa-plus-circle"></i>',
			extend		: 'selected',
			action    	: () => {
				var datos  = table.row({selected: true}).data();
				if (datos[7]=='Activo') {
                    $.ajax({
						type: "POST",
						url: base_url('DocumentoSucursal?action=infoDocumento'),
						data:{idDocumento:datos[0]},
						dataType    : 'JSON',
						success: function(dataDocumento){
							console.log(dataDocumento);
                            $('#titleModalDocumento').html('Editar Documento #'+dataDocumento.data.iddetalle_documento_sucursal);
                            $('#iddetalle_documento_sucursal').val(dataDocumento.data.iddetalle_documento_sucursal);
                            $('#ultima_serie').val(dataDocumento.data.ultima_serie);
                            $('#ultimo_numero').val(dataDocumento.data.ultimo_numero);
                            $('#dds_pri_id').val(dataDocumento.data.dds_pri_id);
                            $('#contabilidad').val(parseInt(dataDocumento.data.contabilidad));
                            $('#dds_afecta_inventario').val(dataDocumento.data.dds_afecta_inventario);
							$('#idtipo_documento').val(dataDocumento.data.idtipo_documento);
							$('#dds_propertie').val(dataDocumento.data.dds_propertie);
							$('#pf_text').val(dataDocumento.data.pf_text);
                            $('.selectpicker').selectpicker('refresh');
                            $('#modalDocumento').modal('toggle');
                        }
                    });
				}else{
					toastMessage('error','Documento inactivo');
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

        configTable.buttons.push(btnNuevoDocumentoSucursal);
        configTable.buttons.push(btnEditarDocumentoSucursal);
        configTable.buttons.push(btnExportarExcel);

		function ajaxTipoDocumento() {
			$('#tblDocumentoSucursal').DataTable().ajax.reload(null, false);
		}
        var table = $('#tblDocumentoSucursal').DataTable({
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
					{ 'data': 7 }
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
					columns: [4,5,6]
				},
                language : {
					url : base_url('lib/languaje/spanishVentas.json')
				},
				dom     : 'BftipP',
                ajax: function(){
                    $.ajax({
						type     : 'POST',
						url      : base_url('DocumentoSucursal?action=obtenerDocumentosAJax'),
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
						targets: 7,
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