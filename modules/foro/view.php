<?php


class ForoView extends View {
	function panel($foro_collection) {
		$gui = file_get_contents("static/modules/foro/panel.html");
		$gui_tbl_foro = file_get_contents("static/modules/foro/tbl_foro.html");
		$gui_tbl_foro = $this->render_regex_dict('TBL_FORO', $gui_tbl_foro, $foro_collection);
		$render = str_replace('{tbl_foro}', $gui_tbl_foro, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function consultar($obj_foro) {
		$gui = file_get_contents("static/modules/foro/consultar.html");
		$obj_foro->estado_denominacion = ($obj_foro->activo == 1) ? 'Activo' : 'Inactivo';
		$obj_foro->url_estado = ($obj_foro->activo == 1) ? 'desactivar' : 'activar';
		$obj_foro->url_icon = ($obj_foro->activo == 1) ? 'close' : 'check';
		$obj_foro->url_label = ($obj_foro->activo == 1) ? 'Desactivar' : 'Activar';
		$obj_foro->class_estado = ($obj_foro->activo == 1) ? 'danger' : 'success';

		$comentario_collection = $obj_foro->comentario_collection;
		unset($obj_foro->comentario_collection);

		$obj_foro = $this->set_dict($obj_foro);
		$render = $this->render($obj_foro, $gui);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;	
	}

	function ver_comentarios($obj_foro) {
		$gui = file_get_contents("static/modules/foro/ver_comentarios.html");
		$gui_lst_comentario = file_get_contents("static/modules/foro/lst_comentarios.html");
		$comentario_collection = $obj_foro->comentario_collection;
		unset($obj_foro->comentario_collection);

		foreach ($comentario_collection as $clave=>$valor) {
			$comentario_collection[$clave]->estado_denominacion = ($valor->activo == 1) ? 'Activo' : 'Inactivo';
			$comentario_collection[$clave]->url_estado = ($valor->activo == 1) ? 'desactivar_comentario' : 'activar_comentario';
			$comentario_collection[$clave]->url_icon = ($valor->activo == 1) ? 'close' : 'check';
			$comentario_collection[$clave]->url_label = ($valor->activo == 1) ? 'Desactivar' : 'Activar';
			$comentario_collection[$clave]->class_estado = ($valor->activo == 1) ? 'danger' : 'success';			
		}

		$comentario_collection = $this->order_collection_objects($comentario_collection, 'fecha', SORT_DESC);
		$gui_lst_comentario = $this->render_regex('LST_COMENTARIO', $gui_lst_comentario, $comentario_collection);
		$render = str_replace('{lst_comentario}', $gui_lst_comentario, $gui);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;	
	}
}
?>