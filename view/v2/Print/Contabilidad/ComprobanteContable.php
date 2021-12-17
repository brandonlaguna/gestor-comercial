<?php use Carbon\Carbon;?>
<link rel="stylesheet" href="css/comprobanteContable.css">
        <div class="invoice">
            <!-- begin invoice-company -->
            <div class="invoice-company text-inverse f-w-600">
                <?=$comprobante->razon_social?>
            </div>
            <table width="100%">
                <tr width="40%">
                    <td>
                    <div class="invoice-from">
                    <small>from</small>
                    <address class="m-t-5 m-b-5">
                        <strong class="text-inverse">T<?=$comprobante->razon_social?></strong><br>
                        <?=$comprobante->documentoSucursal?><br>
                        <?=$comprobante->direccion?><br>
                        Telefono: <?=$comprobante->telefonoSucursal?><br>
                    </address>
                    </div>
                    </td>
                    <td width="40%">
                    <div class="invoice-to">
                    <small>to</small>
                    <address class="m-t-5 m-b-5">
                        <strong class="text-inverse"><?=$comprobante->nombrePersona?></strong><br>
                        <?=$comprobante->documentoPersona?><br>
                        <?=$comprobante->direccion_calle?><br>
                        <?=$comprobante->direccion_provincia?><br>
                        <?=$comprobante->telefonoPersona?>
                    </address>
                </div>
                    </td>
                    <td width="20%">
                    <div class="invoice-date">
                    <img src="<?=$comprobante->logo?>" alt="" width="100px">
                    <div class="date text-inverse m-t-5"><?= Carbon::parse($comprobante->cc_fecha_cpte)->format('F d, Y')?></div>
                    <div class="invoice-detail">
                    <?=$comprobante->cc_num_cpte?>-<?=$comprobante->cc_cons_cpte?><br>
                    </div>
                </div>
                    </td>
                </tr>
            </table>
            <!-- end invoice-company -->
            <!-- begin invoice-content -->
            <div class="invoice-content">
                <!-- begin table-responsive -->
                <div class="table-responsive">
                    <table class="table table-invoice">
                        <tr>
                            <th>Cuenta</th>
                            <th>Detalle</th>
                            <th>Cant.</th>
                            <th>C. Costos</th>
                            <th>Tercero</th>
                            <th>Doc/Detalle</th>
                            <th>Fecha Venc.</th>
                            <th>Base Retencion</th>
                            <th>Debito</th>
                            <th>Credito</th>
                        </tr>
                            <?php foreach ($detalleComprobante as $detalle):?>
                                <tr>
                                    <td class="black-text"><?=$detalle['dcc_cta_item_det']?></td>
                                    <td class="black-text" colspan="8"><?=$detalle['dcc_det_item_det']?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><?=$detalle['dcc_cant_item_det']?></td>
                                    <td class="text-center"><?=$detalle['dcc_ccos_item_det']?></td>
                                    <td class="text-right"><?=$detalle['dcc_ter_item_det']?></td>
                                    <td class="text-right"><?=$detalle['dcc_dato_fact_prove']?></td>
                                    <td class="text-right"><?=$detalle['dcc_fecha_vcto_item']?></td>
                                    <td class="text-right"><?=precio($detalle['dcc_base_ret_item'],0,'$')?></td>
                                    <td class="text-right black-text"><?=$detalle['dcc_d_c_item_det'] == 'D'?precio($detalle['dcc_valor_item'],0,'$'):0?></td>
                                    <td class="text-right black-text"><?=$detalle['dcc_d_c_item_det'] == 'C'?precio($detalle['dcc_valor_item'],0,'$'):0?></td>
                                </tr>
                            <?php endforeach;?>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><?=isset($detalle)?precio($detalle['totalDebitoComprobante'],0,'$'):0?></th>
                                <th><?=isset($detalle)?precio($detalle['totalCreditoComprobante'],0,'$'):0?></th>
                            </tr>
                    </table>
                </div>
                <!-- end table-responsive -->
            </div>
            <!-- end invoice-content -->
        </div>
        <footer class="main-footer">
            <!-- begin invoice-note -->
            <div class="invoice-note">
            </div>
            <!-- end invoice-note -->
            <!-- begin invoice-footer -->
            <div class="invoice-footer">
                <p class="text-center m-b-5 f-w-600">
                    GRACIAS POR SU COMPRA
                </p>
                <p class="text-center">
                    <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> ecounts.com.co</span>
                    <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:016-18192302</span>
                    <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> info@ecounts.com</span>
                </p>
            </div>
            <!-- end invoice-footer -->
            </footer>