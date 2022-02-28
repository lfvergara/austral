<?php


class MeseTimeView extends View {
	function panel($mesetime_collection) {
		$gui = file_get_contents("static/modules/mesetime/panel.html");
		$gui_tbl_mesetime = file_get_contents("static/modules/mesetime/tbl_mesetime.html");
		$gui_tbl_mesetime = $this->render_regex('TBL_MESETIME', $gui_tbl_mesetime, $mesetime_collection);
		$render = str_replace('{tbl_mesetime}', $gui_tbl_mesetime, $gui);
		$render = str_replace('{url_private}', URL_PRIVATE, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar($mesetime_collection, $obj_mesetime) {
		$gui = file_get_contents("static/modules/mesetime/editar.html");
		$obj_mesetime = $this->set_dict($obj_mesetime);
		$gui_tbl_mesetime = file_get_contents("static/modules/mesetime/tbl_mesetime.html");
		$gui_tbl_mesetime = $this->render_regex('TBL_MESETIME', $gui_tbl_mesetime, $mesetime_collection);
		$render = str_replace('{tbl_mesetime}', $gui_tbl_mesetime, $gui);
		$render = str_replace('{url_private}', URL_PRIVATE, $render);
		$render = $this->render($obj_mesetime, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_mesetime) {
		$gui = file_get_contents("static/modules/mesetime/consultar.html");
		$obj_mesetime = $this->set_dict($obj_mesetime);
		$render = $this->render($obj_mesetime, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>