<?php
require_once "modules/estadocompetencia/model.php";
require_once "modules/estadocompetencia/view.php";


class EstadoCompetenciaController {

	function __construct() {
		$this->model = new EstadoCompetencia();
		$this->view = new EstadoCompetenciaView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$estadocompetencia_collection = Collector()->get('EstadoCompetencia');
    	$this->view->panel($estadocompetencia_collection);
	}

	function consultar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');		
		$this->model->estadocompetencia_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
    	$estadocompetencia_collection = Collector()->get('EstadoCompetencia');
		$this->model->estadocompetencia_id = $arg;
		$this->model->get();
		$this->view->editar($estadocompetencia_collection, $this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
		$this->model->save();
		header("Location: " . URL_APP . "/estadocompetencia/panel");
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->estadocompetencia_id = $arg;
		$this->model->delete();		
		header("Location: " . URL_APP . "/estadocompetencia/panel");
	}
}
?>