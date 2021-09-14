<?php
class M_Cart extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "cart";
        parent::__construct($table, $adapter);
    }

    public function sendItem($cart)
    {
        $query = $this->fluent()->insertInto('tb_cola_detalle_ingreso', $cart)->execute();
            
        return $query;
    }


}