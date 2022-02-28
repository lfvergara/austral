<?php


class ContactoView extends View {
	function panel($contacto_collection) {
		$gui = file_get_contents("static/modules/contacto/panel.html");
		$gui_tbl_contacto = file_get_contents("static/modules/contacto/tbl_contacto.html");
		$gui_tbl_contacto = $this->render_regex('TBL_CONTACTO', $gui_tbl_contacto, $contacto_collection);
		$render = str_replace('{tbl_contacto}', $gui_tbl_contacto, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function ver_contacto($obj_contacto) {
		$gui = file_get_contents("static/modules/contacto/ver_contacto.html");
		$obj_contacto->fecha = $this->reacomodar_fecha($obj_contacto->fecha);
		$obj_contacto = $this->set_dict($obj_contacto);
		$render = $this->render($obj_contacto, $gui);
		print $render;
	}
}
?>