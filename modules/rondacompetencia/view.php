<?php


class RondaCompetenciaView extends View {
	function panel($rondacompetencia_collection) {
		$gui = file_get_contents("static/modules/rondacompetencia/panel.html");
		$gui_tbl_rondacompetencia = file_get_contents("static/modules/rondacompetencia/tbl_rondacompetencia.html");
		$gui_tbl_rondacompetencia = $this->render_regex('TBL_RONDACOMPETENCIA', $gui_tbl_rondacompetencia, $rondacompetencia_collection);
		$render = str_replace('{tbl_rondacompetencia}', $gui_tbl_rondacompetencia, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar($rondacompetencia_collection, $obj_rondacompetencia) {
		$gui = file_get_contents("static/modules/rondacompetencia/editar.html");
		$obj_rondacompetencia = $this->set_dict($obj_rondacompetencia);
		$gui_tbl_rondacompetencia = file_get_contents("static/modules/rondacompetencia/tbl_rondacompetencia.html");
		$gui_tbl_rondacompetencia = $this->render_regex('TBL_RONDACOMPETENCIA', $gui_tbl_rondacompetencia, $rondacompetencia_collection);
		$render = str_replace('{tbl_rondacompetencia}', $gui_tbl_rondacompetencia, $gui);
		$render = $this->render($obj_rondacompetencia, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_rondacompetencia) {
		$gui = file_get_contents("static/modules/rondacompetencia/consultar.html");
		$obj_rondacompetencia = $this->set_dict($obj_rondacompetencia);
		$render = $this->render($obj_rondacompetencia, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>