<?php
require_once "modules/equipodecision/model.php";
require_once "modules/equipodecision/view.php";
require_once "modules/equipo/model.php";
require_once "modules/competencia/model.php";


class EquipoDecisionController {

	function __construct() {
		$this->model = new EquipoDecision();
		$this->view = new EquipoDecisionView();
	}

	function panel() {
		SessionHandler()->check_session();
		$cm = new Competencia();
		$cm->competencia_id = 1;
		$cm->get();
		$periodo = $cm->periodo;
		$rondacompetencia_id = $cm->rondacompetencia->rondacompetencia_id;

		$select = "e.equipo_id AS EQUID, e.zona AS ZONA, e.numero AS EQUIPO, e.denominacion AS NOMBRE, ed.precio AS PRECIO, ed.produccion AS PRODUCCION,
				   ed.marketing AS MARKETING, ed.iandd AS IANDD, ed.inversion AS INVERSION, CONCAT(ed.fecha, ' ', ed.hora) AS FECHA, ed.equipodecision_id AS DID,
				   ed.periodo AS PER";
		$from = "equipo e INNER JOIN equipodecision ed ON e.equipo_id = ed.equipo_id";
		$where = "ed.periodo = {$periodo} AND ed.rondacompetencia_id = {$rondacompetencia_id}";
		$decision_collection = CollectorCondition()->get('Competencia', $where, 4, $from, $select);
		$this->view->panel($decision_collection);
	}

	function editar($arg) {
		SessionHandler()->check_session();

		$equipodecision_id = $arg;
		$this->model->equipodecision_id = $equipodecision_id;
		$this->model->get();
		$this->view->editar($this->model);
	}

	function actualizar() {
		SessionHandler()->check_session();

		$equipodecision_id = filter_input(INPUT_POST, "equipodecision_id");
		$this->model->equipodecision_id = $equipodecision_id;
		$this->model->get();
		$this->model->precio = filter_input(INPUT_POST, "precio");
		$this->model->produccion = filter_input(INPUT_POST, "produccion");
		$this->model->marketing = filter_input(INPUT_POST, "marketing");
		$this->model->inversion = filter_input(INPUT_POST, "inversion");
		$this->model->iandd = filter_input(INPUT_POST, "iandd");
		$this->model->equipo_id = filter_input(INPUT_POST, "equipo_id");
		$this->model->periodo = filter_input(INPUT_POST, "periodo");
		$this->model->save();
		header("Location: " . URL_APP . "/equipodecision/panel");
	}

	function eliminar($arg) {
		SessionHandler()->check_session();

		$equipodecision_id = $arg;
		$this->model->equipodecision_id = $equipodecision_id;
		$this->model->delete();
		header("Location: " . URL_APP . "/equipodecision/panel");
	}

	function procesar() {
		$cm = new Competencia();
		$cm->competencia_id = 1;
		$cm->get();
		$periodo = $cm->periodo;
		$rondacompetencia_id = $cm->rondacompetencia->rondacompetencia_id;

		$select = "e.equipo_id AS EQUID, e.zona AS ZONA, e.numero AS EQUIPO, e.denominacion AS NOMBRE, ed.precio AS PRECIO, ed.produccion AS PRODUCCION,
				   ed.marketing AS MARKETING, ed.iandd AS IANDD, ed.inversion AS INVERSION, CONCAT(ed.fecha, ' ', ed.hora) AS FECHA, ed.equipodecision_id AS DID";
		$from = "equipo e INNER JOIN equipodecision ed ON e.equipo_id = ed.equipo_id";
		$where = "ed.periodo = {$periodo} AND ed.rondacompetencia_id = {$rondacompetencia_id}";
		$decision_collection = CollectorCondition()->get('Competencia', $where, 4, $from, $select);

		$directorio = URL_PRIVATE . 'equipodecision';
		$this->eliminar_directorio($directorio);
		
		if (is_array($decision_collection) AND !empty($decision_collection)) {
			foreach ($decision_collection as $clave=>$valor) {
				$equipo = $valor["EQUIPO"];
				$zona = 'ZONA' . str_pad($valor["ZONA"], 2, "0", STR_PAD_LEFT);
				$valores_array = array();
				$valores_array[] = $valor["PRECIO"];
				$valores_array[] = $valor["PRODUCCION"];
				$valores_array[] = $valor["MARKETING"];
				$valores_array[] = $valor["INVERSION"];
				$valores_array[] = $valor["IANDD"];
				$valores_array[] = 0;
				$linea_decision = implode(',', $valores_array);
				$nuevo_directorio = URL_PRIVATE . 'equipodecision/' . $zona;
				if(!file_exists($nuevo_directorio)) {
					mkdir($nuevo_directorio);
					chmod($nuevo_directorio, 0777);
				}
				
				$archivo = $nuevo_directorio . "/Decisn" . $equipo . ".lst";
				$new_archivo = fopen($archivo, "w+b");
    		    if($new_archivo != false ) {
      				fwrite($new_archivo, $linea_decision);
			        fflush($new_archivo);
    		    } 

    			fclose($new_archivo);
			}
		}

		
		require_once "tools/decisionesZIP.php";
		decisionesZIP()->crearZIP();
		exit;
	}

	function eliminar_directorio($arg) {
		$directorio = $arg;
		$directorio_inicial = URL_PRIVATE . 'equipodecision';
		foreach(glob($directorio . "/*") as $archivos){             
        	if (is_dir($archivos)){
          		$this->eliminar_directorio($archivos);
        	} else {
        		unlink($archivos);
        	}	
      	}

      	if ($directorio != $directorio_inicial) rmdir($directorio);
    }

	function descargar() {
		SessionHandler()->check_session();
		require_once "tools/excelreport.php";
		$cm = new Competencia();
		$cm->competencia_id = 1;
		$cm->get();
		$periodo = $cm->periodo;
		$rondacompetencia_id = $cm->rondacompetencia->rondacompetencia_id;
		$rondacompetencia_denominacion = $cm->rondacompetencia->denominacion;

		$select = "e.equipo_id AS EQUID, e.zona AS ZONA, e.numero AS EQUIPO, e.denominacion AS NOMBRE, ed.precio AS PRECIO, ed.produccion AS PRODUCCION,
				   ed.marketing AS MARKETING, ed.iandd AS IANDD, ed.inversion AS INVERSION, CONCAT(ed.fecha, ' ', ed.hora) AS FECHA, ed.equipodecision_id AS DID";
		$from = "competencia c, equipo e INNER JOIN equipodecision ed ON e.equipo_id = ed.equipo_id";
		$where = "c.periodo = {$periodo} AND ed.rondacompetencia_id = {$rondacompetencia_id}";
		$decision_collection = CollectorCondition()->get('Competencia', $where, 4, $from, $select);

		$subtitulo = "RONDA: {$rondacompetencia_denominacion} - PERIODO: {$periodo}";
		$array_encabezados = array('ID', 'ZONA', 'EQUIPO', 'NOMBRE', 'PRECIO', 'PRODUCCION', 'MARKETING', 'INVERSION', 'INVESTIGACION', 'FECHA');
		$array_exportacion = array();
		$array_exportacion[] = $array_encabezados;
		foreach ($decision_collection as $clave=>$valor) {
			$array_temp = array();
			$array_temp = array($valor["EQUID"]
								, $valor["ZONA"]
								, $valor["EQUIPO"]
								, $valor["NOMBRE"]
								, $valor["PRECIO"]
								, $valor["PRODUCCION"]
								, $valor["MARKETING"]
								, $valor["INVERSION"]
								, $valor["IANDD"]
								, $valor["FECHA"]);
			$array_exportacion[] = $array_temp;
		}

		ExcelReport()->extraer_informe($array_exportacion, $subtitulo);
	}
}
?>