<?php
require_once "modules/mesetime/model.php";
require_once "modules/mesetime/view.php";


class MeseTimeController {

	function __construct() {
		$this->model = new MeseTime();
		$this->view = new MeseTimeView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	$mesetime_collection = Collector()->get('MeseTime');
		$this->view->panel($mesetime_collection);
	}

	function consultar($arg) {
		SessionHandler()->check_session();	
		SessionHandler()->checkPerfil('3,9');	
		$this->model->mesetime_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
    	$mesetime_collection = Collector()->get('MeseTime');
		$this->model->mesetime_id = $arg;
		$this->model->get();
		$this->view->editar($mesetime_collection, $this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
		$this->model->fecha = date('Y-m-d');
		$this->model->hora = date('H:i:s');
		$this->model->save();
		$mesetime_id = $this->model->mesetime_id;

		$directorio = URL_PRIVATE . "mesetime/archivos/";
		
		$archivo = $_FILES["archivo"]["tmp_name"];
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($archivo);
		$formato = explode("/", $mime);
		$mimes_permitidos = array("application/pdf");
		
		if(in_array($mime, $mimes_permitidos)) move_uploaded_file($archivo, "{$directorio}/{$mesetime_id}"); 
		header("Location: " . URL_APP . "/mesetime/panel");
	}

	function actualizar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$mesetime_id = filter_input(INPUT_POST, 'mesetime_id');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
		$this->model->fecha = date('Y-m-d');
		$this->model->hora = date('H:i:s');
		$this->model->save();

		$directorio = URL_PRIVATE . "mesetime/archivos/";
		
		$archivo = $_FILES["archivo"]["tmp_name"];
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($archivo);
		$formato = explode("/", $mime);
		$mimes_permitidos = array("application/pdf");
		
		if(in_array($mime, $mimes_permitidos)) move_uploaded_file($archivo, "{$directorio}/{$mesetime_id}"); 
		header("Location: " . URL_APP . "/mesetime/panel");
	}

	function descargar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		require_once "core/helpers/file.php";
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$mesetime_id = $arg;
		$this->model->mesetime_id = $mesetime_id;
		$this->model->delete();	

		$archivo = URL_PRIVATE . "mesetime/archivos/{$mesetime_id}";
		chmod($archivo, 0777);
		unlink($archivo);


		header("Location: " . URL_APP . "/mesetime/panel");
	}
}
?>