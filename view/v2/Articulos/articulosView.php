<link rel="stylesheet" type="text/css" href="lib/datatablesV1.0.0/datatables.css">
<div class="br-pagetitle"></div>
<div class="br-pagebody">
<div id="modaldemo2" class="modal fade">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document" style="position: relative; top: -200;">
              <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-x-20">
                  <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Desea eliminar este articulo?</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body pd-20">
                  <p class="mg-b-5" id="messageFinalModal" placeholder="Este articulo está relacionado a registros, por lo tanto dejara de ser visible solo para ingresos y ventas">Este articulo está relacionado a registros, por lo tanto dejara de ser visible solo para ingresos y ventas</p>
                </div>
                <div class="modal-footer justify-content-center">
                  <button type="button" id="sendIdModal" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" tread="messageFinalModal" controller="" onclick="sendModal()" >Si eliminar</a>
                  <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal" onclick="resetModal('messageFinalModal')">Cerrar</button>
                </div>
              </div>
            </div><!-- modal-dialog -->
  </div>
    <div class="br-section-wrapper">
    <div class="table-wrapper">
            <table id="tblArticulos" class="table display responsive nowrap" width="100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Categoria</th>
                  <th>U. Medida</th>
                  <th>Stock</th>
                  <th>Descripcion</th>
                  <th>Costo</th>
                  <th>Precio Venta</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
              
              </tbody>

    </div>
</div>

