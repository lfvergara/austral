<?php


class CronogramaView extends View {
	function panel($cronograma_collection) {
		$gui = file_get_contents("static/modules/cronograma/panel.html");
		$gui_tbl_cronograma = file_get_contents("static/modules/cronograma/tbl_cronograma.html");
		$gui_tbl_cronograma = $this->render_regex('TBL_CRONOGRAMA', $gui_tbl_cronograma, $cronograma_collection);
		$render = str_replace('{tbl_cronograma}', $gui_tbl_cronograma, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar($cronograma_collection, $obj_cronograma) {
		$gui = file_get_contents("static/modules/cronograma/editar.html");
		$obj_cronograma = $this->set_dict($obj_cronograma);
		$gui_tbl_cronograma = file_get_contents("static/modules/cronograma/tbl_cronograma.html");
		$gui_tbl_cronograma = $this->render_regex('TBL_CRONOGRAMA', $gui_tbl_cronograma, $cronograma_collection);
		$render = str_replace('{tbl_cronograma}', $gui_tbl_cronograma, $gui);
		$render = $this->render($obj_cronograma, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_cronograma) {
		$gui = file_get_contents("static/modules/cronograma/consultar.html");
		$obj_cronograma = $this->set_dict($obj_cronograma);
		$render = $this->render($obj_cronograma, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>