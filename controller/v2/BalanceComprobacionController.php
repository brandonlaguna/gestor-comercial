<?php
use Carbon\Carbon;
class BalanceComprobacionController extends Controladorbase{

    private $adapter;
    private $conectar;

    public function __construct() {
        parent::__construct();
        $this->conectar=new Conectar();
        $this->adapter=$this->conectar->conexion();

        $this->loadModel([
            'Categorias/M_Categorias',
            'Informes/M_BalanceComprobacion'
        ],$this->adapter);

    }

    public function index()
    {
        if(isset($_SESSION["idsucursal"]) && !empty($_SESSION["idsucursal"]) && $_SESSION["permission"] > 3){
            $this->frameview('v2/balanceComprobacion/balanceComprobacion',[]);
            $this->load('v2/balanceComprobacion/balanceComprobacionTable',[]);
        }
    }

    public function balanceComprobacionAjax()
    {
        $comprobantecontable = new ComprobanteContable($this->adapter);
        $detallecomprobantecontable = new DetalleComprobanteContable($this->adapter);
        $reportecontable = new ReporteContable($this->adapter);
        $articulo = new Articulo($this->adapter);
        $compra = new Compras($this->adapter);
        $detallecompra = new DetalleIngreso($this->adapter);
        $venta = new Ventas($this->adapter);
        $detalleventa = new DetalleVenta($this->adapter);
        $puc = new PUC($this->adapter);
        $sucursales = new Sucursal($this->adapter);

        $clases = $puc->getClase();
        $t_saldo_anterior = 0;
        $t_debito = 0;
        $t_credito = 0;
        $t_nuevo_saldo = 0;
        $d_credito = 0;
        $fecha_filtro = date('2021-01-01 00:00:00');
        $fecha_fin_filtro = date('2021-12-31 23:59:59');
        $reg_list=[];
        foreach ($clases as $clase) {
            $grupos = $puc->getGrupo($clase->idcodigo);
            foreach ($grupos as $grupo) {
                //recuperar datos
                $debitogrupo = 0;
                $creditogrupo = 0;
                $ultimo_movimiento = "";
                $saldoanteriorgrupo = 0;
                $detallegrupos = $detallecomprobantecontable->getTotalByCuenta($grupo->idcodigo);
                foreach ($detallegrupos as $detallegrupo) {
                    //saldo anterior
                    if ($detallegrupo->ddc_fecha_creacion <= $fecha_filtro) {
                        if ($detallegrupo->dcc_d_c_item_det == "D") {
                            $saldoanteriorgrupo += $detallegrupo->dcc_valor_item;
                            $t_saldo_anterior += $detallegrupo->dcc_valor_item;
                        } else {
                            $saldoanteriorgrupo -= $detallegrupo->dcc_valor_item;
                            $t_saldo_anterior -= $detallegrupo->dcc_valor_item;
                        }

                    }
                    if ($detallegrupo->ddc_fecha_creacion >= $fecha_filtro && $detallegrupo->ddc_fecha_creacion <= $fecha_fin_filtro) {
                        if ($detallegrupo->dcc_d_c_item_det == "D") {
                            $debitogrupo += $detallegrupo->dcc_valor_item;
                            $t_debito += $detallegrupo->dcc_valor_item;
                        } else if ($detallegrupo->dcc_d_c_item_det == "C") {
                            $creditogrupo += $detallegrupo->dcc_valor_item;
                            $t_credito += $detallegrupo->dcc_valor_item;
                        }
                        $ultimo_movimiento = $detallegrupo->ddc_fecha_creacion;
                    }
                }

                if ($debitogrupo > 0 || $creditogrupo > 0) {
                    $code = str_split($grupo->idcodigo, 2);
                    $reg_list[] = [
                        0   => isset($code[0])?$code[0] : "",
                        1   => isset($code[1])?$code[1] : "",
                        2   => isset($code[2])?$code[2] : "",
                        3   => isset($code[3])?$code[3] : "",
                        4   => $grupo->tipo_codigo,
                        5   => Carbon::parse($ultimo_movimiento)->format('M d, Y h:i a'),
                        6   => number_format($saldoanteriorgrupo,2),
                        7   => number_format($debitogrupo,2),
                        8   => number_format($creditogrupo,2)
                    ];
                }

                $cuentas = $puc->getCuenta($grupo->idcodigo);
                foreach ($cuentas as $cuenta) {
                    $debitocuenta = 0;
                    $creditocuenta = 0;
                    $saldoanteriorcuenta = 0;
                    $ultimo_movimiento = "";
                    $detallecuentas = $detallecomprobantecontable->getTotalByCuenta($cuenta->idcodigo);

                    foreach ($detallecuentas as $detallecuenta) {
                        if ($detallecuenta->ddc_fecha_creacion <= $fecha_filtro) {
                            if ($detallecuenta->dcc_d_c_item_det == "D") {
                                $saldoanteriorcuenta += $detallecuenta->dcc_valor_item;
                                $t_saldo_anterior += $detallecuenta->dcc_valor_item;
                            } else {
                                $saldoanteriorcuenta -= $detallecuenta->dcc_valor_item;
                                $t_saldo_anterior -= $detallecuenta->dcc_valor_item;
                            }

                        }
                        if ($detallecuenta->ddc_fecha_creacion >= $fecha_filtro && $detallecuenta->ddc_fecha_creacion <= $fecha_fin_filtro) {
                            if ($detallecuenta->dcc_d_c_item_det == "D") {
                                $debitocuenta += $detallecuenta->dcc_valor_item;
                                $t_debito += $detallecuenta->dcc_valor_item;
                            } else if ($detallecuenta->dcc_d_c_item_det == "C") {
                                $creditocuenta += $detallecuenta->dcc_valor_item;
                                $d_credito += $detallecuenta->dcc_valor_item;
                            }
                            $ultimo_movimiento = $detallecuenta->ddc_fecha_creacion;
                        }
                    }
                    if ($debitocuenta > 0 || $creditocuenta > 0) {
                        $code = str_split($cuenta->idcodigo, 2);
                        $reg_list[] = [
                            0   => isset($code[0])?$code[0] : "",
                            1   => isset($code[1])?$code[1] : "",
                            2   => isset($code[2])?$code[2] : "",
                            3   => isset($code[3])?$code[3] : "",
                            4   => $cuenta->tipo_codigo,
                            5   => Carbon::parse($ultimo_movimiento)->format('M d, Y h:i a'),
                            6   => number_format($saldoanteriorcuenta,2),
                            7   => number_format($debitocuenta,2),
                            8   => number_format($creditocuenta,2)
                        ];
                    }
                    $subcuentas = $puc->getSubCuenta($cuenta->idcodigo);
                    foreach ($subcuentas as $subcuenta) {
                        $debitosubcuenta = 0;
                        $creditosubcuenta = 0;
                        $saldoanteriorsubcuenta = 0;
                        $ultimo_movimiento = "";
                        $detallesubcuentas = $detallecomprobantecontable->getTotalByCuenta($subcuenta->idcodigo);

                        foreach ($detallesubcuentas as $detallesubcuenta) {
                            if ($detallesubcuenta->ddc_fecha_creacion <= $fecha_filtro) {
                                if ($detallesubcuenta->dcc_d_c_item_det == "D") {
                                    $saldoanteriorsubcuenta += $detallesubcuenta->dcc_valor_item;
                                    $t_saldo_anterior += $detallesubcuenta->dcc_valor_item;
                                } else {
                                    $saldoanteriorsubcuenta -= $detallesubcuenta->dcc_valor_item;
                                    $t_saldo_anterior -= $detallesubcuenta->dcc_valor_item;
                                }
                            }
                            if ($detallesubcuenta->ddc_fecha_creacion >= $fecha_filtro && $detallesubcuenta->ddc_fecha_creacion <= $fecha_fin_filtro) {
                                if ($detallesubcuenta->dcc_d_c_item_det == "D") {
                                    $debitosubcuenta += $detallesubcuenta->dcc_valor_item;
                                    $t_debito += $detallesubcuenta->dcc_valor_item;
                                } else if ($detallesubcuenta->dcc_d_c_item_det == "C") {
                                    $creditosubcuenta += $detallesubcuenta->dcc_valor_item;
                                    $t_credito += $detallesubcuenta->dcc_valor_item;
                                }
                                $ultimo_movimiento = $detallesubcuenta->ddc_fecha_creacion;
                            }
                        }

                        if ($debitosubcuenta > 0 || $creditosubcuenta > 0) {
                            $code = str_split($subcuenta->idcodigo, 2);
                            $reg_list[] = [
                                0   => isset($code[0])?$code[0] : "",
                                1   => isset($code[1])?$code[1] : "",
                                2   => isset($code[2])?$code[2] : "",
                                3   => isset($code[3])?$code[3] : "",
                                4   => $subcuenta->tipo_codigo,
                                5   => Carbon::parse($ultimo_movimiento)->format('M d, Y h:i a'),
                                6   => number_format($saldoanteriorsubcuenta,2),
                                7   => number_format($debitosubcuenta,2),
                                8   => number_format($creditosubcuenta,2)
                            ];
                        }
                        $auxiliarsubcuentas = $puc->getAuxSubCuenta($subcuenta->idcodigo);
                        foreach ($auxiliarsubcuentas as $auxiliarsubcuenta) {
                            $debitoauxiliarsubcuenta = 0;
                            $creditoauxiliarsubcuenta = 0;
                            $saldoanteriorauxiliarsubcuenta = 0;
                            $ultimo_movimiento = "";
                            $detalleauxiliarsubcuentas = $detallecomprobantecontable->getTotalByCuenta($auxiliarsubcuenta->idcodigo);
                            foreach ($detalleauxiliarsubcuentas as $detalleauxiliarsubcuenta) {
                                if ($detalleauxiliarsubcuenta->ddc_fecha_creacion <= $fecha_filtro) {
                                    if ($detalleauxiliarsubcuenta->dcc_d_c_item_det) {
                                        $saldoanteriorauxiliarsubcuenta += $detalleauxiliarsubcuenta->dcc_valor_item;
                                        $t_saldo_anterior += $detalleauxiliarsubcuenta->dcc_valor_item;
                                    } else {
                                        $saldoanteriorauxiliarsubcuenta -= $detalleauxiliarsubcuenta->dcc_valor_item;
                                        $t_saldo_anterior -= $detalleauxiliarsubcuenta->dcc_valor_item;
                                    }

                                }
                                if ($detalleauxiliarsubcuenta->ddc_fecha_creacion >= $fecha_filtro && $detalleauxiliarsubcuenta->ddc_fecha_creacion <= $fecha_fin_filtro) {
                                    if ($detalleauxiliarsubcuenta->dcc_d_c_item_det == "D") {
                                        $debitoauxiliarsubcuenta += $detalleauxiliarsubcuenta->dcc_valor_item;
                                        $t_debito += $detalleauxiliarsubcuenta->dcc_valor_item;
                                    } else if ($detalleauxiliarsubcuenta->dcc_d_c_item_det == "C") {
                                        $creditoauxiliarsubcuenta += $detalleauxiliarsubcuenta->dcc_valor_item;
                                        $t_credito += $detalleauxiliarsubcuenta->dcc_valor_item;
                                    }
                                    $ultimo_movimiento = $detalleauxiliarsubcuenta->ddc_fecha_creacion;
                                }
                            }
                            if ($debitoauxiliarsubcuenta > 0 || $creditoauxiliarsubcuenta > 0) {
                                $code = str_split($auxiliarsubcuenta->idcodigo, 2);
                                $reg_list[] = [
                                    0   => isset($code[0])?$code[0] : "",
                                    1   => isset($code[1])?$code[1] : "",
                                    2   => isset($code[2])?$code[2] : "",
                                    3   => isset($code[3])?$code[3] : "",
                                    4   => $subcuenta->tipo_codigo,
                                    5   => Carbon::parse($ultimo_movimiento)->format('M d, Y h:i a'),
                                    6   => number_format($saldoanteriorauxiliarsubcuenta,2),
                                    7   => number_format($debitoauxiliarsubcuenta,2),
                                    8   => number_format($creditoauxiliarsubcuenta,2)
                                ];
                            }
                        }
                    }
                }
            }
        }
        $response = [
            "result"=> true,
            "data"=> [
                "contents"=> $reg_list,
                "pagination"=> [
                    "page"=> 1,
                    "totalCount"=> 100
                ]
            ]
        ];
        echo json_encode($reg_list);
    }

}