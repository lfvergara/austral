<?php
require_once "modules/contacto/model.php";
require_once "modules/contacto/view.php";


class ContactoController {

	function __construct() {
		$this->model = new Contacto();
		$this->view = new ContactoView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$contacto_collection = Collector()->get('Contacto');
		$this->view->panel($contacto_collection);
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$contacto_id = $arg;
		$this->model->contacto_id = $contacto_id;
		$this->model->delete();	
		header("Location: " . URL_APP . "/contacto/panel");
	}

	function ver_contacto($arg) {
		$this->model->contacto_id = $arg;
    	$this->model->get();
		$this->view->ver_contacto($this->model);
	}
}
?>