<?php
use Carbon\Carbon;
class Verificar extends ControladorBase{

	function sesionActiva() {
		if(empty($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
			$this->redirect('Index',);
			exit();
		}
	}

	function verificarPermisoAccion($permiso, $alerta = false){
		//Esta clase verifica que el usuario tenga el permiso para hacer determinadas acciones
		$fechaActual = Carbon::now()->format('Y-m-d H:i:s');

		//verificar estado del permiso
		$estadoPermiso = false;
		$mensaje = 'No tienes permisos';
		try {
			if(isset($permiso['per_estado']) && $permiso['per_estado'] == 1){
				$estadoPermiso = true;
			}
		} catch (\Throwable $e) {
			$mensaje = $e->getMessage();
		}
		if(!$estadoPermiso && !$alerta){
			$this->redirect('Index',);
			exit();
		}elseif(!$estadoPermiso && $alerta){
			return [
				'estado'	=> $estadoPermiso,
				'mensaje'	=> $mensaje
			];
		}else{
			return $estadoPermiso;
		}
	}
}
/* End of file Verificar.php */
/* Location: ./application/libraries/Verificar.php */