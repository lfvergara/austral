<?php


class PreguntaFrecuenteView extends View {
	function panel($preguntafrecuente_collection) {
		$gui = file_get_contents("static/modules/preguntafrecuente/panel.html");
		$gui_tbl_preguntafrecuente = file_get_contents("static/modules/preguntafrecuente/tbl_preguntafrecuente.html");
		foreach ($preguntafrecuente_collection as $clave=>$valor) {
			$contenido = substr($valor->contenido, 0, 150);
			$preguntafrecuente_collection[$clave]->contenido = "{$contenido}...";
		}

		$gui_tbl_preguntafrecuente = $this->render_regex('TBL_PREGUNTAFRECUENTE', $gui_tbl_preguntafrecuente, $preguntafrecuente_collection);
		$render = str_replace('{tbl_preguntafrecuente}', $gui_tbl_preguntafrecuente, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar($preguntafrecuente_collection, $obj_preguntafrecuente) {
		$gui = file_get_contents("static/modules/preguntafrecuente/editar.html");
		$obj_preguntafrecuente = $this->set_dict($obj_preguntafrecuente);
		$gui_tbl_preguntafrecuente = file_get_contents("static/modules/preguntafrecuente/tbl_preguntafrecuente.html");
		foreach ($preguntafrecuente_collection as $clave=>$valor) {
			$contenido = substr($valor->contenido, 0, 150);
			$preguntafrecuente_collection[$clave]->contenido = "{$contenido}...";
		}
		
		$gui_tbl_preguntafrecuente = $this->render_regex('TBL_PREGUNTAFRECUENTE', $gui_tbl_preguntafrecuente, $preguntafrecuente_collection);
		$render = str_replace('{tbl_preguntafrecuente}', $gui_tbl_preguntafrecuente, $gui);
		$render = $this->render($obj_preguntafrecuente, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_preguntafrecuente) {
		$gui = file_get_contents("static/modules/preguntafrecuente/consultar.html");
		$obj_preguntafrecuente = $this->set_dict($obj_preguntafrecuente);
		$render = $this->render($obj_preguntafrecuente, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>