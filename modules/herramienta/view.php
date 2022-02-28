<?php


class HerramientaView extends View {
	function panel($herramienta_collection) {
		$gui = file_get_contents("static/modules/herramienta/panel.html");
		$gui_tbl_herramienta = file_get_contents("static/modules/herramienta/tbl_herramienta.html");

		foreach ($herramienta_collection as $clave=>$valor) {
			$contenido = substr($valor->contenido, 0, 170);
			$herramienta_collection[$clave]->contenido = "{$contenido}...";
		}

		$gui_tbl_herramienta = $this->render_regex('TBL_HERRAMIENTA', $gui_tbl_herramienta, $herramienta_collection);
		$render = str_replace('{tbl_herramienta}', $gui_tbl_herramienta, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function agregar() {
		$gui = file_get_contents("static/modules/herramienta/agregar.html");
		$template = $this->render_template($gui);
		print $template;
	}
	
	function editar($obj_herramienta) {
		$gui = file_get_contents("static/modules/herramienta/editar.html");
		$obj_herramienta = $this->set_dict($obj_herramienta);
		$render = $this->render($obj_herramienta, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_herramienta) {
		$gui = file_get_contents("static/modules/herramienta/consultar.html");
		$obj_herramienta = $this->set_dict($obj_herramienta);
		$render = $this->render($obj_herramienta, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>