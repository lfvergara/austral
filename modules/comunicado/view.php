<?php


class ComunicadoView extends View {
	function panel($comunicado_collection) {
		$gui = file_get_contents("static/modules/comunicado/panel.html");
		$gui_tbl_comunicado = file_get_contents("static/modules/comunicado/tbl_comunicado.html");

		foreach ($comunicado_collection as $clave=>$valor) {
			$contenido = substr($valor->contenido, 0, 170);
			$comunicado_collection[$clave]->contenido = "{$contenido}...";
		}

		$gui_tbl_comunicado = $this->render_regex('TBL_COMUNICADO', $gui_tbl_comunicado, $comunicado_collection);
		$render = str_replace('{tbl_comunicado}', $gui_tbl_comunicado, $gui);
		$render = str_replace('{url_private}', URL_PRIVATE, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar($comunicado_collection, $obj_comunicado) {
		$gui = file_get_contents("static/modules/comunicado/editar.html");
		$obj_comunicado = $this->set_dict($obj_comunicado);
		$gui_tbl_comunicado = file_get_contents("static/modules/comunicado/tbl_comunicado.html");

		foreach ($comunicado_collection as $clave=>$valor) {
			$contenido = substr($valor->contenido, 0, 170);
			$comunicado_collection[$clave]->contenido = "{$contenido}...";
		}
		
		$gui_tbl_comunicado = $this->render_regex('TBL_COMUNICADO', $gui_tbl_comunicado, $comunicado_collection);
		$render = str_replace('{tbl_comunicado}', $gui_tbl_comunicado, $gui);
		$render = str_replace('{url_private}', URL_PRIVATE, $render);
		$render = $this->render($obj_comunicado, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_comunicado) {
		$gui = file_get_contents("static/modules/comunicado/consultar.html");
		$obj_comunicado = $this->set_dict($obj_comunicado);
		$render = $this->render($obj_comunicado, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>