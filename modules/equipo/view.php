<?php


class EquipoView extends View {
	function panel($equipo_collection) {
		$gui = file_get_contents("static/modules/equipo/panel.html");
		$gui_tbl_equipo = file_get_contents("static/modules/equipo/tbl_equipo.html");
		$gui_tbl_equipo = $this->render_regex_dict('TBL_EQUIPO', $gui_tbl_equipo, $equipo_collection);
		$render = str_replace('{tbl_equipo}', $gui_tbl_equipo, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function configurar_zonas($equipo_collection) {
		$gui = file_get_contents("static/modules/equipo/configurar_zonas.html");
		$gui_tbl_equipo = file_get_contents("static/modules/equipo/tbl_short_equipo.html");
		$gui_tbl_equipo = $this->render_regex_dict('TBL_EQUIPO', $gui_tbl_equipo, $equipo_collection);
		$render = str_replace('{tbl_equipo}', $gui_tbl_equipo, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function agregar($provincia_collection) {
		$gui = file_get_contents("static/modules/equipo/agregar.html");
		$gui_slt_provincia = file_get_contents("static/common/slt_provincia.html");
		$gui_slt_provincia = $this->render_regex('SLT_PROVINCIA', $gui_slt_provincia, $provincia_collection);
		
		$render = str_replace('{slt_provincia}', $gui_slt_provincia, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar($provincia_collection, $obj_equipo) {
		$gui = file_get_contents("static/modules/equipo/editar.html");
		$gui_infocontacto = file_get_contents("static/modules/equipo/lst_infocontacto.html");
		$gui_tbl_integrante = file_get_contents("static/modules/equipo/tbl_integrante.html");
		$gui_slt_provincia = file_get_contents("static/common/slt_provincia.html");
		$integrante_collection = $obj_equipo->integrante_collection;
		$equipo_infocontacto_collection = $obj_equipo->infocontacto_collection;
		$escuela_infocontacto_collection = $obj_equipo->escuela->infocontacto_collection;
		unset($obj_equipo->integrante_collection, $obj_equipo->infocontacto_collection,$obj_equipo->escuela->infocontacto_collection);
		$obj_equipo = $this->set_dict($obj_equipo);
		foreach ($integrante_collection as $clave=>$valor) unset($integrante_collection[$clave]->infocontacto_collection);
		
		$gui_infocontacto_equipo = $this->render_regex('LST_INFOCONTACTO', $gui_infocontacto, $equipo_infocontacto_collection);
		$gui_infocontacto_escuela = $this->render_regex('LST_INFOCONTACTO', $gui_infocontacto, $escuela_infocontacto_collection);
		$gui_tbl_integrante = $this->render_regex('TBL_INTEGRANTE', $gui_tbl_integrante, $integrante_collection);
		$gui_slt_provincia = $this->render_regex('SLT_PROVINCIA', $gui_slt_provincia, $provincia_collection);

		$render = $this->render($obj_equipo, $gui);
		$render = str_replace('{lst_infocontacto_equipo}', $gui_infocontacto_equipo, $render);
		$render = str_replace('{lst_infocontacto_escuela}', $gui_infocontacto_escuela, $render);
		$render = str_replace('{tbl_integrante}', $gui_tbl_integrante, $render);
		$render = str_replace('{slt_provincia}', $gui_slt_provincia, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function editar_zona($obj_equipo) {
		$gui = file_get_contents("static/modules/equipo/editar_zona.html");
		unset($obj_equipo->integrante_collection, $obj_equipo->infocontacto_collection);
		$obj_equipo = $this->set_dict($obj_equipo);
		$render = $this->render($obj_equipo, $gui);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;
	}

	function agregar_integrante_ajax($equipo_id) {
		$gui = file_get_contents("static/modules/equipo/agregar_integrante.html");
		$render = str_replace('{equipo-equipo_id}', $equipo_id, $gui);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;
	}

	function editar_integrante_ajax($obj_integrante) {
		$gui = file_get_contents("static/modules/equipo/editar_integrante.html");
		$gui_infocontacto = file_get_contents("static/modules/equipo/lst_infocontacto.html");
		$infocontacto_collection = $obj_integrante->infocontacto_collection;
		unset($obj_integrante->infocontacto_collection);
		$gui_infocontacto = $this->render_regex('LST_INFOCONTACTO', $gui_infocontacto, $infocontacto_collection);
		$obj_integrante = $this->set_dict($obj_integrante);
		$render = $this->render($obj_integrante, $gui);
		$render = str_replace('{lst_infocontacto}', $gui_infocontacto, $render);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;
	}
}
?>