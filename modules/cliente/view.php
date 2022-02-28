<?php


class ClienteView extends View {
	function panel() {
		$gui = file_get_contents("static/modules/cliente/panel.html");
		$render = $this->render_breadcrumb($gui);
		$template = $this->render_template($render);
		print $template;
	}

	function listar($cliente_collection) {
		$gui = file_get_contents("static/modules/cliente/listar.html");
		$tbl_cliente_array = file_get_contents("static/modules/cliente/tbl_cliente_array.html");

		$tbl_cliente_array = $this->render_regex_dict('TBL_CLIENTE', $tbl_cliente_array, $cliente_collection);
		$render = str_replace('{tbl_cliente}', $tbl_cliente_array, $gui);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function agregar($provincia_collection, $documentotipo_collection) {
		$gui = file_get_contents("static/modules/cliente/agregar.html");
		$gui_slt_provincia = file_get_contents("static/common/slt_provincia.html");
		$gui_slt_documentotipo = file_get_contents("static/common/slt_documentotipo.html");
		
		$gui_slt_provincia = $this->render_regex('SLT_PROVINCIA', $gui_slt_provincia, $provincia_collection);
		$gui_slt_documentotipo = $this->render_regex('SLT_DOCUMENTOTIPO', $gui_slt_documentotipo, $documentotipo_collection);
		$render = str_replace('{slt_provincia}', $gui_slt_provincia, $gui);
		$render = str_replace('{slt_documentotipo}', $gui_slt_documentotipo, $render);
		$template = $this->render_template($render);
		print $template;
	}
	
	function editar($provincia_collection, $documentotipo_collection, $obj_cliente) {
		$gui = file_get_contents("static/modules/cliente/editar.html");
		$gui_slt_provincia = file_get_contents("static/common/slt_provincia.html");
		$gui_slt_documentotipo = file_get_contents("static/common/slt_documentotipo.html");
		$gui_lst_input_infocontacto = file_get_contents("static/modules/cliente/lst_input_infocontacto.html");
		
		$infocontacto_collection = $obj_cliente->infocontacto_collection;
		$gui_lst_input_infocontacto = $this->render_regex('LST_INPUT_INFOCONTACTO', $gui_lst_input_infocontacto, $infocontacto_collection);
		unset($obj_cliente->infocontacto_collection);
		$obj_cliente = $this->set_dict($obj_cliente);
		$gui_slt_provincia = $this->render_regex('SLT_PROVINCIA', $gui_slt_provincia, $provincia_collection);
		$gui_slt_documentotipo = $this->render_regex('SLT_DOCUMENTOTIPO', $gui_slt_documentotipo, $documentotipo_collection);
		$render = str_replace('{slt_provincia}', $gui_slt_provincia, $gui);
		$render = str_replace('{slt_documentotipo}', $gui_slt_documentotipo, $render);
		$render = str_replace('{lst_input_infocontacto}', $gui_lst_input_infocontacto, $render);
		$render = $this->render($obj_cliente, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}

	function consultar($obj_cliente) {
		$gui = file_get_contents("static/modules/cliente/consultar.html");
		$gui_lst_infocontacto = file_get_contents("static/common/lst_infocontacto.html");
		if ($obj_cliente->documentotipo->denominacion == 'CUIL' OR $obj_cliente->documentotipo->denominacion == 'CUIT') {
			$cuil1 = substr($obj_cliente->documento, 0, 2);
			$cuil2 = substr($obj_cliente->documento, 2, 8);
			$cuil3 = substr($obj_cliente->documento, 10);
			$obj_cliente->documento = "{$cuil1}-{$cuil2}-{$cuil3}";
		}

		$infocontacto_collection = $obj_cliente->infocontacto_collection;
		unset($obj_cliente->infocontacto_collection);	
		$obj_cliente = $this->set_dict($obj_cliente);

		$gui_lst_infocontacto = $this->render_regex('LST_INFOCONTACTO', $gui_lst_infocontacto, $infocontacto_collection);
		$render = str_replace('{lst_infocontacto}', $gui_lst_infocontacto, $gui);
		$render = $this->render($obj_cliente, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;	
	}
}
?>