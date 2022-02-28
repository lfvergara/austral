<?php
require_once "modules/preguntafrecuente/model.php";
require_once "modules/preguntafrecuente/view.php";


class PreguntaFrecuenteController {

	function __construct() {
		$this->model = new PreguntaFrecuente();
		$this->view = new PreguntaFrecuenteView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$preguntafrecuente_collection = Collector()->get('PreguntaFrecuente');
		$this->view->panel($preguntafrecuente_collection);
	}

	function consultar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');		
		$this->model->preguntafrecuente_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
    	$preguntafrecuente_collection = Collector()->get('PreguntaFrecuente');
		$this->model->preguntafrecuente_id = $arg;
		$this->model->get();
		$this->view->editar($preguntafrecuente_collection, $this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
		$this->model->fecha = date('Y-m-d');
		$this->model->hora = date('H:i:s');
		$this->model->save();
		header("Location: " . URL_APP . "/preguntafrecuente/panel");
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->preguntafrecuente_id = $arg;
		$this->model->delete();		
		header("Location: " . URL_APP . "/preguntafrecuente/panel");
	}
}
?>