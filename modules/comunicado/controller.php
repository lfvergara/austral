<?php
require_once "modules/comunicado/model.php";
require_once "modules/comunicado/view.php";


class ComunicadoController {

	function __construct() {
		$this->model = new Comunicado();
		$this->view = new ComunicadoView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$comunicado_collection = Collector()->get('Comunicado');
		$this->view->panel($comunicado_collection);
	}

	function consultar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');		
		$this->model->comunicado_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
    	$comunicado_collection = Collector()->get('Comunicado');
		$this->model->comunicado_id = $arg;
		$this->model->get();
		$this->view->editar($comunicado_collection, $this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
		$this->model->fecha = date('Y-m-d');
		$this->model->hora = date('H:i:s');
		$this->model->save();
		$comunicado_id = $this->model->comunicado_id;

		$directorio = URL_PRIVATE . "comunicado/archivos/";
		
		$archivo = $_FILES["archivo"]["tmp_name"];
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($archivo);
		$formato = explode("/", $mime);
		$mimes_permitidos = array("application/pdf");
		
		if(in_array($mime, $mimes_permitidos)) move_uploaded_file($archivo, "{$directorio}/{$comunicado_id}"); 
		header("Location: " . URL_APP . "/comunicado/panel");
	}

	function actualizar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$comunicado_id = filter_input(INPUT_POST, 'comunicado_id');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
		$this->model->fecha = date('Y-m-d');
		$this->model->hora = date('H:i:s');
		$this->model->save();

		$directorio = URL_PRIVATE . "comunicado/archivos/";
		
		$archivo = $_FILES["archivo"]["tmp_name"];
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($archivo);
		$formato = explode("/", $mime);
		$mimes_permitidos = array("application/pdf");
		
		if(in_array($mime, $mimes_permitidos)) move_uploaded_file($archivo, "{$directorio}/{$comunicado_id}"); 
		header("Location: " . URL_APP . "/comunicado/panel");
	}

	function descargar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		require_once "core/helpers/file.php";
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$comunicado_id = $arg;
		$this->model->comunicado_id = $comunicado_id;
		$this->model->delete();	

		$archivo = URL_PRIVATE . "comunicado/archivos/{$comunicado_id}";
		chmod($archivo, 0777);
		unlink($archivo);


		header("Location: " . URL_APP . "/comunicado/panel");
	}
}
?>