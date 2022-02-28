<?php
require_once "modules/informeindustria/model.php";
require_once "modules/informeindustria/view.php";
require_once "modules/equipo/model.php";
require_once "modules/competencia/model.php";


class InformeIndustriaController {

	function __construct() {
		$this->model = new InformeIndustria();
		$this->view = new InformeIndustriaView();
	}

	function panel() {
    	SessionHandler()->check_session();

    	$select = "CONCAT('ZONA ', e.zona) AS ZONA, COUNT(e.zona) AS CANT, e.zona AS ARG";
    	$from = "equipo e";
    	$where = "e.usuario_id != 0 AND e.usuario_id IS NOT NULL";
    	$group_by = "e.zona";
    	$zona_collection = CollectorCondition()->get('Equipo', $where, 4, $from, $select, $group_by);
    	$this->view->panel($zona_collection);
	}

	function cargar_archivos_ajax($arg) {
    	SessionHandler()->check_session();
    	$select = "e.denominacion AS DENOMINACION, es.denominacion AS ESCUELA, e.equipo_id AS ID, e.numero AS EQUIPO";
    	$from = "equipo e INNER JOIN escuela es ON e.escuela = es.escuela_id";
    	$where = "e.usuario_id != 0 AND e.usuario_id IS NOT NULL AND e.zona = {$arg}";
    	$equipo_collection = CollectorCondition()->get('Equipo', $where, 4, $from, $select);
    	$this->view->ver_equipos_ajax($equipo_collection);
	}


	function guardar_archivos() {
		$zona = filter_input(INPUT_POST, 'zona');
		$zona = str_pad($zona, 2, "0", STR_PAD_LEFT);
		
		$cm = new Competencia();
		$cm->competencia_id = 1;
        $cm->get();
        $periodo = $cm->periodo;

        $directorio_periodo = URL_PRIVATE . "informeindustria/periodo{$periodo}/";
        if(!file_exists($directorio_periodo)) {
			mkdir($directorio_periodo);
			chmod($directorio_periodo, 0777);
		}

		$directorio = "{$directorio_periodo}zona{$zona}/";
		if(!file_exists($directorio)) {
			mkdir($directorio);
			chmod($directorio, 0777);
		}

		$directorio = $directorio . basename($_FILES['file']['name']);
		$archivo = $_FILES["file"]["tmp_name"];
		
		move_uploaded_file($archivo, $directorio); 
	}
}
?>