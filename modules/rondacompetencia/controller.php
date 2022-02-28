<?php
require_once "modules/rondacompetencia/model.php";
require_once "modules/rondacompetencia/view.php";


class RondaCompetenciaController {

	function __construct() {
		$this->model = new RondaCompetencia();
		$this->view = new RondaCompetenciaView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$rondacompetencia_collection = Collector()->get('RondaCompetencia');
    	$this->view->panel($rondacompetencia_collection);
	}

	function consultar($arg) {
		SessionHandler()->check_session();	
		SessionHandler()->checkPerfil('3,9');	
		$this->model->rondacompetencia_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
    	$rondacompetencia_collection = Collector()->get('RondaCompetencia');
		$this->model->rondacompetencia_id = $arg;
		$this->model->get();
		$this->view->editar($rondacompetencia_collection, $this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
		$this->model->save();
		header("Location: " . URL_APP . "/rondacompetencia/panel");
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->rondacompetencia_id = $arg;
		$this->model->delete();		
		header("Location: " . URL_APP . "/rondacompetencia/panel");
	}
}
?>