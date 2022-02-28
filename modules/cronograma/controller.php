<?php
require_once "modules/cronograma/model.php";
require_once "modules/cronograma/view.php";


class CronogramaController {

	function __construct() {
		$this->model = new Cronograma();
		$this->view = new CronogramaView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$cronograma_collection = Collector()->get('Cronograma');
		$this->view->panel($cronograma_collection);
	}

	function consultar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');		
		$this->model->cronograma_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
    	$cronograma_collection = Collector()->get('Cronograma');
		$this->model->cronograma_id = $arg;
		$this->model->get();
		$this->view->editar($cronograma_collection, $this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
		$this->model->fecha = date('Y-m-d');
		$this->model->hora = date('H:i:s');
		$this->model->save();
		header("Location: " . URL_APP . "/cronograma/panel");
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->cronograma_id = $arg;
		$this->model->delete();		
		header("Location: " . URL_APP . "/cronograma/panel");
	}
}
?>