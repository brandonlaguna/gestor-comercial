<script type="text/javascript">
    var ventas   = [];
	var configTable = [];
	$(document).ready(function() {
		configTable.buttons = [];
		configTable.columns = [];
		configTable.filtros = {};

        var btnExportarExcel ={
			titleAttr : 'Exportar a Excel',
			text      : '<i class="fas fa-file-excel"></i>',
			extend    : 'excelHtml5',
			className : 'btn btn-outline-success btn-sm',
			filename  : 'Ventas Contables'
		}

        configTable.buttons.push(btnExportarExcel);

		function ajaxArticulo() {
			$('#tblCreditoProveedor').DataTable().ajax.reload(null, false);
		}
        var table = $('#tblCreditoProveedor').DataTable({
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
					{ 'data': 8 }
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
						url      : base_url('CarteraProveedor?action=carteraProveedoresAjax'),
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
                createdRow: function(row, data, dataIndex ) {
					//Comparar fecha de vencimiento con actual.
					var diffDates = moment().diff(moment(data[3]), 'days');
                    var amount = parseInt(data[6].replace('$',''));
                    console.log(amount+ ' - '+ diffDates);
					if (diffDates <= 30 && amount > 0) {
						$(row).addClass('bg-warning-ligh');
					} else if (diffDates > 30 && amount > 0) {
						$(row).addClass('bg-danger-light');
					}else if(jQuery.inArray(data[3], ['0001-01-01', '0000-00-00']) && amount > 0){
                        $(row).addClass('bg-purple-light');
                    }else{}
				},
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