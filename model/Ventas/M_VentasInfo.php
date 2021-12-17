<?php
class M_VentasInfo extends ModeloBase
{
    private $table;
    public function __construct($adapter)
    {
        $table = "venta";
        parent::__construct($table, $adapter);
    }

    public function obtenerVenta($filter = [])
    {
        $query = $this->fluent()->from('venta V')
                                ->leftJoin('usuario U on V.idusuario = U.idusuario')
                                ->leftJoin('empleado EM on U.idempleado = EM.idempleado')
                                ->leftJoin('sucursal SU on V.idsucursal = SU.idsucursal')
                                ->leftJoin('persona PE on V.idCliente = PE.idpersona')
                                ->leftJoin('tipo_documento TD on V.tipo_comprobante = TD.nombre')
                                ->leftJoin('detalle_documento_sucursal DDS on V.serie_comprobante = DDS.ultima_serie')
                                ->leftJoin('tb_conf_print CP on DDS.dds_pri_id = CP.pri_id')
                                ->leftJoin('tb_forma_pago FP on V.tipo_pago = FP.fp_nombre and FP.fp_proceso = "Venta"')
                                ->select('V.*, U.*, EM.*, SU.*, PE.*, TD.*, DDS.*, CP.*, FP.*, V.estado as estadoVenta, PE.nombre as nombre_cliente, CONCAT(EM.nombre, " ",EM.apellidos) as nombreEmpleado, PE.telefono as telefonoCliente, SU.razon_social as razonSocial')
                                ->where('V.idventa = '.$filter['idventa']);
        return $query->fetch();
    }
}