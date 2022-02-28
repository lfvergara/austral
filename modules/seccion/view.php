<?php


class SeccionView extends View {
	function panel($seccion_collection) {
		$gui = file_get_contents("static/modules/seccion/panel.html");
		$gui_tbl_seccion = file_get_contents("static/modules/seccion/tbl_seccion.html");
		$gui_tbl_seccion = $this->render_regex('TBL_SECCION', $gui_tbl_seccion, $seccion_collection);
		$render = str_replace('{tbl_seccion}', $gui_tbl_seccion, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function agregar() {
		$gui = file_get_contents("static/modules/seccion/agregar.html");
		$template = $this->render_template($gui);
		print $template;
	}
	
	function editar($obj_seccion) {
		$gui = file_get_contents("static/modules/seccion/editar.html");
		$obj_seccion = $this->set_dict($obj_seccion);
		$render = $this->render($obj_seccion, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_seccion) {
		$gui = file_get_contents("static/modules/seccion/consultar.html");
		$obj_seccion = $this->set_dict($obj_seccion);
		$render = $this->render($obj_seccion, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>