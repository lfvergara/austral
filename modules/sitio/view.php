<?php


class SitioView extends View {
	function inscripcion($provincia_collection, $obj_competencia, $flagAlerta) {
		$gui = file_get_contents("static/modules/sitio/inscripcion.html");
		$gui_slt_provincia = file_get_contents("static/common/slt_provincia.html");
		$gui_slt_provincia = $this->render_regex('SLT_PROVINCIA', $gui_slt_provincia, $provincia_collection);
		
		$fecha_ini = $obj_competencia->fecha_inicio_inscripcion;
		$fecha_fin = $obj_competencia->fecha_fin_inscripcion;
		$fecha_actual = date('Y-m-d');

		if ($fecha_actual >= $fecha_ini AND $fecha_actual <= $fecha_fin) {
			$gui_inscripcion = file_get_contents("static/modules/sitio/formulario_inscripcion.html");
			$gui_inscripcion = str_replace('{slt_provincia}', $gui_slt_provincia, $gui_inscripcion);
			if ($flagAlerta == 1) {
				$gui_inscripto = file_get_contents("static/modules/sitio/alerta_inscripto.html");
				$gui = str_replace('{alerta_inscripto}', $gui_inscripto, $gui);
			} else {
				$gui = str_replace('{alerta_inscripto}', '', $gui);
			}
		} else {
			$gui_inscripcion = file_get_contents("static/modules/sitio/gui_inscripcion_cerrada.html");
		}
		
		$render = str_replace('{formulario_inscripcion}', $gui_inscripcion, $gui);
		$template = $this->render_template_sitio($render);
		print $template;
	}	

	function continuar_inscripcion($datos_array, $obj_competencia) {
		$gui = file_get_contents("static/modules/sitio/continuar_inscripcion.html");
		
		$fecha_ini = $obj_competencia->fecha_inicio_inscripcion;
		$fecha_fin = $obj_competencia->fecha_fin_inscripcion;
		$fecha_actual = date('Y-m-d');

		if ($fecha_actual >= $fecha_ini AND $fecha_actual <= $fecha_fin) {
			$gui_inscripcion = file_get_contents("static/modules/sitio/formulario_continuar_inscripcion.html");
			$gui_inscripcion = $this->render($datos_array, $gui_inscripcion);

		} else {
			$gui_inscripcion = file_get_contents("static/modules/sitio/gui_inscripcion_cerrada.html");
		}
		
		$render = str_replace('{formulario_inscripcion}', $gui_inscripcion, $gui);
		$template = $this->render_template_sitio($render);
		print $template;
	}	
}
?>