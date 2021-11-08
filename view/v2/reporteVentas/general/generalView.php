<link rel="stylesheet" href="node_modules/tui-grid/dist/tui-grid.min.css">
<div class="br-pagetitle"></div>
<div class="br-pagebody">
<p>Reporte General</p>
    <div class="br-section-wrapper">
        <div class='container-fluid mb-2'>
            <div class="btn-group" role="group" aria-label="Basic example">
                <div class="btn-group" role="group" data-toggle="tooltip" data-placement="top" title="Lista de filtros">
                    <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter"></i> <small><i class="fas fa-sort"></i></small>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" onclick="filtroPersonalizado()">Filtro personalizado</a>
                    </div>
                </div>
                <input type="text" class="form-control calendar" id="filtroFecha" aria-label="Fecha de filtro" aria-describedby="inputGroup-sizing-sm" style="height: 28px;" readonly placeholder="a-m-d to a-m-d">
                <button type="button" class="btn btn-sm btn-success btn-disabled"data-toggle="tooltip" data-placement="top" title="Filtro por fechas" ><i class="fas fa-calendar"></i></button>
                <button type="button" class="btn btn-sm btn-danger" onclick="limpiarFiltro()" data-toggle="tooltip" data-placement="top" title="Limpiar filtro"><i class="fas fa-undo"></i></i></button>
            </div>
        </div>
    <div class="table-wrapper">
    <div id="gridReporteVentaGeneral" style="width: 100%;"></div>
    </div>
</div>

