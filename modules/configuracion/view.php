<?php


class ConfiguracionView extends View {
	
	function panel($ronda_collection, $estado_collection, $obj_configuracion, $obj_competencia) {
		$gui = file_get_contents("static/modules/configuracion/panel.html");
		$slt_ronda = file_get_contents("static/modules/competencia/slt_rondacompetencia.html");
		$slt_estado = file_get_contents("static/modules/competencia/slt_estadocompetencia.html");
		
		$slt_ronda = $this->render_regex('SLT_RONDACOMPETENCIA', $slt_ronda, $ronda_collection);
		$slt_estado = $this->render_regex('SLT_ESTADOCOMPETENCIA', $slt_estado, $estado_collection);

		$obj_competencia->chk_act_equipo = ($obj_competencia->activo_equipos == 1) ? 'checked' : '';
		$obj_competencia->chk_act_reporte = ($obj_competencia->activo_reportes == 1) ? 'checked' : '';
		$configuraciondecision_checked = $obj_competencia->configuraciondecision->activo;
		$obj_competencia->configuraciondecision->chk_activo = ($configuraciondecision_checked == 1) ? 'checked' : '';
		
		$obj_configuracion = $this->set_dict($obj_configuracion);
		$obj_competencia = $this->set_dict($obj_competencia);
		$render = $this->render($obj_configuracion, $gui);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{slt_rondacompetencia}', $slt_ronda, $render);
		$render = str_replace('{slt_estadocompetencia}', $slt_estado, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}
}
?>