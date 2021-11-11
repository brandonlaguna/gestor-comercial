<?php
class M_Cart extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "cola_ingreso";
        parent::__construct($table, $adapter);
    }

    public function sendItem($items)
    {
        return $this->fluent()->insertInto('tb_cola_detalle_ingreso', $items)->execute();
    }

    public function obtenerArticulos($filter)
    {
        $query = $this->fluent()->from('tb_cola_detalle_ingreso')
                        ->where('cdi_idsucursal = '.$filter['idsucursal'])
                        ->where('cdi_idusuraio  = '.$filter['idusuario'])
                        ->where('cdi_ci_id      = '.$filter['ci_id']);
        $result = $query->fetchAll();
        return $result;
    }

    public function obtenerUltimoCarrito($filter)
    {
        $query = $this->fluent()->from('tb_cola_ingreso')
                        ->where('ci_idsucursal  = '.$filter['idsucursal'])
                        ->where('ci_usuario     = '.$filter['idusuario'])
                        ->orderBy('ci_id DESC')
                        ->limit(1);
        $result = $query->fetch();
        return $result;
    }

    public function obtenerTotales($filtro)
    {
        #SELECT sum(cdi_credito) as cdi_credito, sum(cdi_debito) as cdi_debito, sum(cdi_precio_unitario * cdi_stock_ingreso) as subtotal
        #FROM tb_cola_detalle_ingreso WHERE cdi_idsucursal = '".$_SESSION['idsucursal']."' AND cdi_ci_id = '$value' AND cdi_type = 'AR' AND cdi_idusuraio = '".$_SESSION['usr_uid']."'")
        $query = $this->fluent()->from('tb_cola_ingreso ci')
                        ->join('tb_cola_detalle_ingreso cdi ON cdi.cdi_ci_id = ci.ci_id')
                        ->select('(sum(cdi_credito)) as cdi_credito')
                        //->select('sum(cdi_debito) as cdi_debito')
                        ;

        if(isset($filtro['cdi_ci_id'])){
            $query->where('ci.ci_id = '.$filtro['cdi_ci_id']);
        }
        return $query->fetch();
    }
}