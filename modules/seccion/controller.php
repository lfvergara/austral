<?php
require_once "modules/seccion/model.php";
require_once "modules/seccion/view.php";


class SeccionController {

	function __construct() {
		$this->model = new Seccion();
		$this->view = new SeccionView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$seccion_collection = Collector()->get('Seccion');
		$this->view->panel($seccion_collection);
	}

	function agregar() {
    	SessionHandler()->check_session();		
    	SessionHandler()->checkPerfil('3,9');
		$this->view->agregar();
	}

	function consultar($arg) {
		SessionHandler()->check_session();		
    	SessionHandler()->checkPerfil('3,9');
		$this->model->seccion_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
		require_once "core/helpers/file.php";	
		$this->model->seccion_id = $arg;
		$this->model->get();
		$this->view->editar($this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
		$this->model->denominacion = strtoupper(filter_input(INPUT_POST, 'denominacion'));
		$this->model->contenido = filter_input(INPUT_POST, 'descr');	
		$this->model->imagen = filter_input(INPUT_POST, 'imagen');
		$this->model->save();
		header("Location: " . URL_APP . "/seccion/panel");
	}

	function actualizar() {
		SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
		$seccion_id = filter_input(INPUT_POST, 'seccion_id');
		$this->model->seccion_id = $seccion_id;
		$this->model->get();
		$this->model->denominacion = strtoupper(filter_input(INPUT_POST, 'denominacion'));	
		$this->model->contenido = filter_input(INPUT_POST, 'descr');
		$this->model->save();
		header("Location: " . URL_APP . "/seccion/panel");
	}

	function imagen() {
		SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');

		$directorio = URL_PRIVATE . "seccion/archivos/";
		
		$archivo = $_FILES["archivo"]["tmp_name"];
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($archivo);
		$formato = explode("/", $mime);
		$mimes_permitidos = array("image/jpeg");
		
		$name = filter_input(INPUT_POST, 'imagen');
		if(in_array($mime, $mimes_permitidos)) move_uploaded_file($archivo, "{$directorio}/{$name}"); 
		header("Location: " . URL_APP . "/seccion/panel");
	}
}
?>