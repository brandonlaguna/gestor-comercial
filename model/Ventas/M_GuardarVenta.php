<?php
class M_GuardarVenta extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "venta";
        parent::__construct($table, $adapter);
    }

    public function guardarActualizarVenta($venta)
    {
        if(isset($venta['ideventa']) && !empty($venta['idventa'])){
            $query = $this->fluent()->update('venta')->set($venta)->where('idventa', $venta['idventa'])->execute();
            if($query){
                return $venta['idventa'];
            }else{
                return $query;
            }
        }else{
            $query = $this->fluent()->insertInto('venta', $venta)->execute();
            return $this->informacionVenta($query);
        }
    }

    public function informacionVenta($idventa)
    {
        $query = $this->fluent()->from('venta')
                        ->where('idventa = '.$idventa);
        return $query->fetch();
    }
}