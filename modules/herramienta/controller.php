<?php
require_once "modules/herramienta/model.php";
require_once "modules/herramienta/view.php";


class HerramientaController {

	function __construct() {
		$this->model = new Herramienta();
		$this->view = new HerramientaView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$herramienta_collection = Collector()->get('Herramienta');
		$this->view->panel($herramienta_collection);
	}

	function agregar() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');		
		$this->view->agregar();
	}

	function consultar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');		
		$this->model->herramienta_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->herramienta_id = $arg;
		$this->model->get();
		$this->view->editar($this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->denominacion = strtoupper(filter_input(INPUT_POST, 'denominacion'));
		$this->model->contenido = filter_input(INPUT_POST, 'descr');	
		$this->model->ubicacion = filter_input(INPUT_POST, 'ubicacion');	
		$this->model->fecha = date('Y-m-d');
		$this->model->hora = date('H:i:s');
		$this->model->save();
		header("Location: " . URL_APP . "/herramienta/panel");
	}

	function actualizar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$herramienta_id = filter_input(INPUT_POST, 'herramienta_id');
		$this->model->herramienta_id = $herramienta_id;
		$this->model->get();
		$this->model->denominacion = strtoupper(filter_input(INPUT_POST, 'denominacion'));	
		$this->model->contenido = filter_input(INPUT_POST, 'descr');
		$this->model->ubicacion = filter_input(INPUT_POST, 'ubicacion');	
		$this->model->fecha = date('Y-m-d');
		$this->model->hora = date('H:i:s');
		$this->model->save();
		header("Location: " . URL_APP . "/herramienta/panel");
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->herramienta_id = $arg;
		$this->model->delete();		
		header("Location: " . URL_APP . "/herramienta/panel");
	}
}
?>