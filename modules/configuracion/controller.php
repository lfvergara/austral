<?php
require_once "modules/configuracion/model.php";
require_once "modules/configuracion/view.php";
require_once "modules/competencia/model.php";
require_once "modules/configuraciondecision/model.php";


class ConfiguracionController {

	function __construct() {
		$this->model = new Configuracion();
		$this->view = new ConfiguracionView();
	}

	function panel() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	require_once "core/helpers/file.php";
    	$this->model->configuracion_id = 1;
    	$this->model->get();    	
    	$cm = new Competencia();
    	$cm->competencia_id = 1;
    	$cm->get();		
    	$ronda_collection = Collector()->get('RondaCompetencia');
    	$estado_collection = Collector()->get('EstadoCompetencia');
    	$this->view->panel($ronda_collection, $estado_collection, $this->model, $cm);
	}

	function ver_archivo() {
    	SessionHandler()->check_session();
    	SessionHandler()->checkPerfil('3,9');
    	require_once "core/helpers/file.php";
	}

	function definir_entidad() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->configuracion_id = filter_input(INPUT_POST, "configuracion_id");
		$this->model->get();
		$this->model->entidad = filter_input(INPUT_POST, "entidad");
		$this->model->save();
		header("Location: " . URL_APP . "/configuracion/panel");
	}

	function definir_logo() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$directorio = URL_PRIVATE . "configuracion/logo/";		
		$archivo = $_FILES["logo"]["tmp_name"];
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($archivo);
		$formato = explode("/", $mime);
		$mimes_permitidos = array("image/jpeg");		
		$name = "logo";
		if(in_array($mime, $mimes_permitidos)) move_uploaded_file($archivo, "{$directorio}/{$name}"); 
		header("Location: " . URL_APP . "/configuracion/panel");
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		foreach ($_POST as $clave=>$valor) $this->model->$clave = $valor;
        $this->model->save();
		header("Location: " . URL_APP . "/configuracion/panel");
	}

	function configurar_competencia() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$competencia_id = filter_input(INPUT_POST, 'competencia_id');
		$cm = new Competencia();
		$cm->competencia_id = $competencia_id;
		$cm->get();

		$activo_equipos = filter_input(INPUT_POST, 'activo_equipos');
		$activo_equipos = (is_null($activo_equipos)) ? 0 : $activo_equipos;
		$activo_reportes = filter_input(INPUT_POST, 'activo_reportes');
		$activo_reportes = (is_null($activo_reportes)) ? 0 : $activo_reportes;

		$cm->denominacion = filter_input(INPUT_POST, 'denominacion');
		$cm->administrador = filter_input(INPUT_POST, 'administrador');
		$cm->periodo = filter_input(INPUT_POST, 'periodo');
		$cm->maximo_equipos = filter_input(INPUT_POST, 'maximo_equipos');
		$cm->rondacompetencia = filter_input(INPUT_POST, 'rondacompetencia');
		$cm->estadocompetencia = filter_input(INPUT_POST, 'estadocompetencia');
		$cm->activo_equipos = $activo_equipos;
		$cm->activo_reportes = $activo_reportes;
		$cm->fecha_inicio_inscripcion = filter_input(INPUT_POST, 'fecha_inicio_inscripcion');
		$cm->fecha_fin_inscripcion = filter_input(INPUT_POST, 'fecha_fin_inscripcion');
		$cm->correoelectronico = filter_input(INPUT_POST, 'correoelectronico');
		$cm->save();
		header("Location: " . URL_APP . "/configuracion/panel");
	}

	function configurar_decision() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$competencia_id = filter_input(INPUT_POST, 'competencia_id');
		
		$cm = new Competencia();
		$cm->competencia_id = $competencia_id;
		$cm->get();
		$configuraciondecision_id = $cm->configuraciondecision->configuraciondecision_id;
		
		$cdm = new ConfiguracionDecision();
		$cdm->configuraciondecision_id = $configuraciondecision_id;
		$cdm->get();

		$activo = filter_input(INPUT_POST, 'activo');
		$activo = (is_null($activo)) ? 0 : $activo;

		$cdm->activo = $activo;
		$cdm->texto = filter_input(INPUT_POST, 'texto');
		$cdm->precio_minimo = filter_input(INPUT_POST, 'precio_minimo');
		$cdm->precio_maximo = filter_input(INPUT_POST, 'precio_maximo');
		$cdm->produccion_minimo = filter_input(INPUT_POST, 'produccion_minimo');
		$cdm->produccion_maximo = filter_input(INPUT_POST, 'produccion_maximo');
		$cdm->marketing_minimo = filter_input(INPUT_POST, 'marketing_minimo');
		$cdm->marketing_maximo = filter_input(INPUT_POST, 'marketing_maximo');
		$cdm->inversion_minimo = filter_input(INPUT_POST, 'inversion_minimo');
		$cdm->inversion_maximo = filter_input(INPUT_POST, 'inversion_maximo');
		$cdm->iandd_minimo = filter_input(INPUT_POST, 'iandd_minimo');
		$cdm->iandd_maximo = filter_input(INPUT_POST, 'iandd_maximo');
		$cdm->save();
		header("Location: " . URL_APP . "/configuracion/panel");
	}
}
?>