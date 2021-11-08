<?php
class Verificar extends ControladorBase{

	function sesionActiva() {
		if(empty($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
			$this->redirect('Index',);
			exit();
		}
	}

	function verificarPermisoAccion($index){
		//Esta clase verifica que el usuario tenga el permiso para hacer determinadas acciones
		//Recibe la llave primaria de los permisos en la tabla, y verifica el permiso contra la variable de sesión.
		$permisos = $_SESSION['permisos'];
		if ($permisos[$index-1] == '1') {
			return true;
		} else {
			return false;
		}
	}
}
/* End of file Verificar.php */
/* Location: ./application/libraries/Verificar.php */