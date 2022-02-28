<?php


class InformeIndustriaView extends View {
	function panel($zona_collection) {
		$gui = file_get_contents("static/modules/informeindustria/panel.html");
		$gui_tbl_zona = file_get_contents("static/modules/informeindustria/tbl_zona_array.html");
		$gui_tbl_zona = $this->render_regex_dict('TBL_ZONA', $gui_tbl_zona, $zona_collection);
		$render = str_replace('{tbl_zona}', $gui_tbl_zona, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function ver_equipos_ajax($zona_collection) {
		$gui = file_get_contents("static/modules/informeindustria/lst_check_equipos_array.html");
		$gui = $this->render_regex_dict('LST_CHECK_EQUIPO', $gui, $zona_collection);
		print $gui;
	}
}
?>