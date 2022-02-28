<?php
require_once "modules/foro/model.php";
require_once "modules/foro/view.php";


class ForoController {

	function __construct() {
		$this->model = new Foro();
		$this->view = new ForoView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$select = "f.denominacion AS TITULO, f.equipo AS EQUIPO, f.contenido AS CONTENIDO, date_format(f.fecha, '%d/%m/%Y') AS FECHA, 
    			   f.hora AS HORA, f.correoelectronico AS EMAIL, CASE f.activo WHEN 0 THEN 'danger' WHEN 1 THEN 'success' END AS CLASS,
    			   CASE f.activo WHEN 0 THEN 'close' WHEN 1 THEN 'check' END AS ICON, f.foro_id AS FORID";
    	$from = "foro f ORDER BY f.fecha DESC";
    	$foro_collection = CollectorCondition()->get('Foro', NULL, 4, $from, $select);
		$this->view->panel($foro_collection);
	}

	function consultar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$foro_id = $arg;
		$this->model->foro_id = $foro_id;
		$this->model->get();	
		$this->view->consultar($this->model);
	}

	function ver_comentarios($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$foro_id = $arg;
		$this->model->foro_id = $foro_id;
		$this->model->get();	
		$this->view->ver_comentarios($this->model);		
	}

	function activar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$foro_id = $arg;
		$this->model->foro_id = $foro_id;
		$this->model->get();	
		$this->model->activo = 1;
		$this->model->save();	
		header("Location: " . URL_APP . "/foro/panel");
	}

	function desactivar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$foro_id = $arg;
		$this->model->foro_id = $foro_id;
		$this->model->get();	
		$this->model->activo = 0;
		$this->model->save();	
		header("Location: " . URL_APP . "/foro/panel");
	}

	function activar_comentario($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$comentario_id = $arg;
		$cm = new Comentario();
		$cm->comentario_id = $comentario_id;
		$cm->get();	
		$cm->activo = 1;
		$cm->save();	
		header("Location: " . URL_APP . "/foro/panel");
	}

	function desactivar_comentario($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$comentario_id = $arg;
		$cm = new Comentario();
		$cm->comentario_id = $comentario_id;
		$cm->get();	
		$cm->activo = 0;
		$cm->save();	
		header("Location: " . URL_APP . "/foro/panel");
	}	

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$foro_id = $arg;
		$this->model->foro_id = $foro_id;
		$this->model->delete();	
		header("Location: " . URL_APP . "/foro/panel");
	}
}
?>