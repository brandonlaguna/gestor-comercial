<div class="br-pagetitle"></div>
<div class="br-pagebody">
    <div class="br-section-wrapper">
    <table id="datatable1" class="table display responsive nowrap">
              <thead>
                <tr>
                  <th class="wd-5p">ID</th>
                  <th class="wd-30p">Usuario</th>
                  <th class="wd-30p">Descripcion</th>
                  <th class="wd-20p">Fecha</th>
                  <th class="wd-10p">Accion</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php 
              $i=1;
              foreach ($reportes as $reporte) {?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$reporte->ju_name?></td>
                    <td><?=$reporte->rc_descripcion?></td>
                    <td><?=$reporte->rc_fecha?></td>
                    <td><a href="#file/caja/<?=$reporte->idreporte?>"><i class="fas fa-print text-info"></i></a></td>
                </tr>
              <?php $i++;} ?>
              </tbody>
    </div>
</div>