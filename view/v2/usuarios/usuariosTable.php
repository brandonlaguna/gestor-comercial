<script type="text/javascript">
var configTable = [];
//tblUsuarios
$(document).ready(function() {
configTable.buttons = [];
configTable.columns = [];
configTable.filtros = {}

var btnNuevoUsuario = {
	titleAttr : 'Nuevo Usuario',
	text      : '<i class="fas fa-user-tie"></i> <small><i class="fas fa-plus-circle"></i></small>',
	action    : () => {
		$('#modalNuevoUsuario').modal('toggle')
	},
	className: 'btn btn-success btn-sm text-white'
}

var btnEditarUsuario = {
	titleAttr : 'Editar Usuario',
	text      : '<i class="fas fa-user-tie"></i> <small><i class="fas fa-pen-fancy"></i></small>',
	extend    : 'selected',
	action    : () => {
		var datos  = table.row({selected: true}).data();
		if (datos[6]=='Activo') {
			$.ajax({
				type: "POST",
				url: base_url('Usuario?action=infoUsuario'),
				data:{idUsuario:datos[0],
				dataType    : 'JSON'
				},
				success: function(dataUsuario) {
					dataUsuario = JSON.parse(dataUsuario);
					$('#modalEditarUsuario').find('input:text').val('');
					$('#empleadoE').val(dataUsuario.data[0].idusuario);
					$('#sucursalE').val(dataUsuario.data[0].sc_id);
					$('#permisosE').val(dataUsuario.data[0].ju_type);
					$('.selectpicker').selectpicker('refresh')
					$('#modalEditarUsuario').modal('toggle');
				}
				});
		}else{
			usuarioInactivo();
		}
	},
	className: 'btn btn-warning btn-sm text-white'
}

var btnInactivarUsuario = {
	titleAttr : 'Inactivar Usuario',
    extend    : 'selected',
	text      : '<i class="fas fa-user-tie"></i> <small><i class="fas fa-ban""></i></small>',
	action    : () => {
		var datos  = table.row({selected: true}).data();
		if (datos[6]=='Activo') {
			cambiarEstadoUsuario(datos[0],0);
		}else{
			usuarioInactivo();
		}
	},
	className: 'btn btn-outline-danger btn-sm'
};

var btnActivarUsuario = {
	titleAttr : 'Activar Usuario',
    extend    : 'selected',
	text      : '<i class="fas fa-user-tie"></i> <small><i class="fas fa-undo-alt "></i></small>',
	action    : () => {
		var datos  = table.row({selected: true}).data();
		if (datos[6]=='Inactivo') {
			cambiarEstadoUsuario(datos[0],1);
		}else{
			usuarioActivo();
		}
	},
	className: 'btn btn-outline-info btn-sm'
};

var btnPermisosUsuario = {
	titleAttr : 'Permisos Usuario',
    extend    : 'selected',
	text      : '<i class="fas fa-user-tie"></i> <small><i class="fas fa-key"></i></small>',
	action    : () => {
		var datos  = table.row({selected: true}).data();
		$('#idUsuarioPermiso').val(datos[0]);
		if (datos[6]=='Activo') {
			$.ajax({
            method      : 'POST',
            url         : base_url('Permisos?action=obtenerPermisosUsuario'),
            data        : {idUsuario:datos[0]},
            dataType    : 'JSON',
            success: function(data) {
                if(data.estado == true){
					$('#modalPermisosUsuario').modal('toggle');
					$('.switch-light input').attr('checked',false);
					setInterval(() => {
						data.data.forEach(element => {
							var status = element.per_estado ==1?true:false;
								$('#permiso_'+element.per_pr_id+' input').attr('checked',status);
						});
					}, 500);
                }else{
					$('#modalPermisosUsuario').modal('toggle');
                    toastMessage('error', data.mensaje);
                }
        }
    });
		}else{
			usuarioActivo();
		}
	},
	className: 'btn btn-outline-warning btn-sm'
}

var btnExportarExcel ={
	titleAttr : 'Exportar a Excel',
	text      : '<i class="fas fa-file-excel"></i>',
	extend    : 'excelHtml5',
	className : 'btn btn-outline-success btn-sm',
	filename  : 'Listado de categorias'
}

configTable.buttons.push(btnNuevoUsuario);
configTable.buttons.push(btnEditarUsuario);
configTable.buttons.push(btnInactivarUsuario);
configTable.buttons.push(btnActivarUsuario);
configTable.buttons.push(btnPermisosUsuario);
configTable.buttons.push(btnExportarExcel);

var table = $('#tblUsuarios').DataTable({
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
			url : base_url('lib/languaje/spanishUsuarios.json')
		},
		dom     : 'BftipP',
        ajax: function(){
            $.ajax({
				type     : 'POST',
				url      : base_url('Usuario?action=usuariosAjax'),
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