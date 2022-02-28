<?php


class EstadoCompetenciaView extends View {
	function panel($estadocompetencia_collection) {
		$gui = file_get_contents("static/modules/estadocompetencia/panel.html");
		$gui_tbl_estadocompetencia = file_get_contents("static/modules/estadocompetencia/tbl_estadocompetencia.html");
		$gui_tbl_estadocompetencia = $this->render_regex('TBL_ESTADOCOMPETENCIA', $gui_tbl_estadocompetencia, $estadocompetencia_collection);
		$render = str_replace('{tbl_estadocompetencia}', $gui_tbl_estadocompetencia, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar($estadocompetencia_collection, $obj_estadocompetencia) {
		$gui = file_get_contents("static/modules/estadocompetencia/editar.html");
		$obj_estadocompetencia = $this->set_dict($obj_estadocompetencia);
		$gui_tbl_estadocompetencia = file_get_contents("static/modules/estadocompetencia/tbl_estadocompetencia.html");
		$gui_tbl_estadocompetencia = $this->render_regex('TBL_ESTADOCOMPETENCIA', $gui_tbl_estadocompetencia, $estadocompetencia_collection);
		$render = str_replace('{tbl_estadocompetencia}', $gui_tbl_estadocompetencia, $gui);
		$render = $this->render($obj_estadocompetencia, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_estadocompetencia) {
		$gui = file_get_contents("static/modules/estadocompetencia/consultar.html");
		$obj_estadocompetencia = $this->set_dict($obj_estadocompetencia);
		$render = $this->render($obj_estadocompetencia, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>