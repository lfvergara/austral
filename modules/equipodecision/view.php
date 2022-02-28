<?php


class EquipoDecisionView extends View {
	function panel($decision_collection) {
		$gui = file_get_contents("static/modules/equipodecision/panel.html");
		$gui_tbl_decision = file_get_contents("static/modules/equipodecision/tbl_equipodecision.html");
		$gui_tbl_decision = $this->render_regex_dict('TBL_EQUIPODECISION', $gui_tbl_decision, $decision_collection);
		$render = str_replace('{tbl_equipodecision}', $gui_tbl_decision, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar($obj_equipodecision) {
		$gui = file_get_contents("static/modules/equipodecision/editar.html");
		$obj_equipodecision = $this->set_dict($obj_equipodecision);
		$render = $this->render($obj_equipodecision, $gui);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;
	}
}
?>