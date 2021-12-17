<?php
class M_Cart extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "cola_ingreso";
        parent::__construct($table, $adapter);
    }

    public function sendItem($items){
        return $this->fluent()->insertInto('tb_cola_detalle_ingreso', $items)->execute();
    }

    public function obtenerArticulos($filter){
        $query = $this->fluent()->from('tb_cola_detalle_ingreso')
                        ->where('cdi_idsucursal = '.$filter['idsucursal'])
                        ->where('cdi_idusuraio  = '.$filter['idusuario'])
                        ->where('cdi_ci_id      = '.$filter['ci_id']);
        $result = $query->fetchAll();
        return $result;
    }

    public function obtenerUltimoCarrito($filter){
        $query = $this->fluent()->from('tb_cola_ingreso')
                        ->where('ci_idsucursal  = '.$filter['idsucursal'])
                        ->where('ci_usuario     = '.$filter['idusuario'])
                        ->orderBy('ci_id DESC')
                        ->limit(1);
        $result = $query->fetch();
        return $result;
    }

    public function getSubTotal($ci_id)
    {
        $query = $this->fluent()->from('tb_cola_detalle_ingreso')
                        ->select('SUM(cdi_credito) AS cdi_credito, SUM(cdi_debito) AS cdi_debito, SUM(cdi_precio_unitario * cdi_stock_ingreso) AS subtotal, SUM(cdi_precio_total_lote) AS totalCart')
                        ->where("cdi_idsucursal = '".$_SESSION['idsucursal']."'")
                        ->where("cdi_ci_id = '".$ci_id."'")
                        ->where("cdi_type = 'AR'")
                        ->where("cdi_idusuraio = '".$_SESSION['usr_uid']."'");
        return $query->fetch();
    }

    public function deleteCart()
    {
        $deleteCart = $this->fluent()->deleteFrom('tb_cola_ingreso')->where([
            'ci_idsucursal' => $_SESSION['idsucursal'],
            'ci_usuario'    => $_SESSION["usr_uid"]
        ])->execute();

        if($deleteCart){
            $this->fluent()->deleteFrom('tb_cola_detalle_ingreso')->where([
                'ci_idsucursal' => $_SESSION['idsucursal'],
                'ci_usuario'    => $_SESSION["usr_uid"]
            ])->execute();
        }

        return $deleteCart;
    }

    public function crearCarrito($dataCarrito)
    {
        return $this->fluent()->insertInto('tb_cola_ingreso', $dataCarrito)->execute();
    }
}