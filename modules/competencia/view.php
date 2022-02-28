<?php


class CompetenciaView extends View {
	function home($obj_seccion_bienvenida, $obj_competencia) {
		$gui = file_get_contents("static/modules/competencia/home.html");		
		$obj_seccion_bienvenida = $this->set_dict($obj_seccion_bienvenida);
		$obj_competencia = $this->set_dict($obj_competencia);
		$fecha_actual = $this->descomponer_fecha();
		$render = $this->render($obj_seccion_bienvenida, $gui);
		$render = $this->render($obj_competencia, $render);
		$render = $this->render($fecha_actual, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function cronograma($obj_cronograma, $obj_competencia) {
		$gui = file_get_contents("static/modules/competencia/cronograma.html");		
		$obj_cronograma = $this->set_dict($obj_cronograma);
		$obj_competencia = $this->set_dict($obj_competencia);
		$fecha_actual = $this->descomponer_fecha();
		$render = $this->render($obj_cronograma, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function manual($obj_seccion_manual, $obj_competencia) {
		$gui = file_get_contents("static/modules/competencia/manual.html");
		$obj_seccion_manual = $this->set_dict($obj_seccion_manual);
		$obj_competencia = $this->set_dict($obj_competencia);
		$fecha_actual = $this->descomponer_fecha();
		$render = $this->render($obj_seccion_manual, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function preguntas_frecuentes($preguntafrecuente_collection, $obj_competencia) {
		$gui = file_get_contents("static/modules/competencia/preguntas_frecuentes.html");
		$gui_tbl_preguntafrecuente = file_get_contents("static/modules/competencia/tbl_preguntafrecuente.html");
		$gui_tbl_preguntafrecuente = $this->render_regex('TBL_PREGUNTAFRECUENTE', $gui_tbl_preguntafrecuente, $preguntafrecuente_collection);		
		$fecha_actual = $this->descomponer_fecha();
		$obj_competencia = $this->set_dict($obj_competencia);
		$render = str_replace('{tbl_preguntafrecuente}', $gui_tbl_preguntafrecuente, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function ver_preguntafrecuente($obj_preguntafrecuente) {
		$gui = file_get_contents("static/modules/competencia/ver_preguntafrecuente.html");
		$obj_preguntafrecuente->fecha = $this->reacomodar_fecha($obj_preguntafrecuente->fecha);
		$obj_preguntafrecuente = $this->set_dict($obj_preguntafrecuente);
		$render = $this->render($obj_preguntafrecuente, $gui);
		print $render;
	}

	function herramientas($herramienta_collection, $obj_competencia) {
		$gui = file_get_contents("static/modules/competencia/herramientas.html");
		$gui_tbl_herramienta = file_get_contents("static/modules/competencia/tbl_herramienta.html");
		$gui_tbl_herramienta = $this->render_regex('TBL_HERRAMIENTA', $gui_tbl_herramienta, $herramienta_collection);		
		$fecha_actual = $this->descomponer_fecha();
		$obj_competencia = $this->set_dict($obj_competencia);
		$render = str_replace('{tbl_herramienta}', $gui_tbl_herramienta, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function equipos($equipo_collection, $obj_competencia) {
		$gui = file_get_contents("static/modules/competencia/equipos.html");

		if ($obj_competencia->activo_equipos == 1) {
			$gui_cuerpo = file_get_contents("static/modules/competencia/contenido_equipos.html");
			$gui_tbl_equipo = file_get_contents("static/modules/competencia/tbl_equipo.html");
			$gui_tbl_equipo = $this->render_regex_dict('TBL_EQUIPO', $gui_tbl_equipo, $equipo_collection);		
			$gui_cuerpo = str_replace('{tbl_equipo}', $gui_tbl_equipo, $gui_cuerpo);
		} else {
			$gui_cuerpo = file_get_contents("static/modules/competencia/alert_equipos_inactivo.html");
			$gui_cuerpo = str_replace('{info_txt}', $info_txt, $gui_cuerpo);
			
		}
		
		$fecha_actual = $this->descomponer_fecha();
		$obj_competencia = $this->set_dict($obj_competencia);

		$render = str_replace('{gui_cuerpo}', $gui_cuerpo, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function ver_herramienta($obj_herramienta) {
		$gui = file_get_contents("static/modules/competencia/ver_herramienta.html");
		$obj_herramienta->fecha = $this->reacomodar_fecha($obj_herramienta->fecha);
		$obj_herramienta = $this->set_dict($obj_herramienta);
		$render = $this->render($obj_herramienta, $gui);
		print $render;
	}

	function comunicados($comunicado_collection) {
		$gui = file_get_contents("static/modules/competencia/comunicados.html");
		$gui_tbl_comunicado = file_get_contents("static/modules/competencia/tbl_comunicado.html");
		$gui_tbl_comunicado = $this->render_regex('TBL_COMUNICADO', $gui_tbl_comunicado, $comunicado_collection);		
		$fecha_actual = $this->descomponer_fecha();
		$render = str_replace('{tbl_comunicado}', $gui_tbl_comunicado, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function ver_comunicado($obj_comunicado) {
		$gui = file_get_contents("static/modules/competencia/ver_comunicado.html");
		$obj_comunicado->fecha = $this->reacomodar_fecha($obj_comunicado->fecha);
		$obj_comunicado = $this->set_dict($obj_comunicado);
		$render = $this->render($obj_comunicado, $gui);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;
	}

	function mesetimes($mesetime_collection) {
		$gui = file_get_contents("static/modules/competencia/mesetimes.html");
		$gui_tbl_mesetime = file_get_contents("static/modules/competencia/tbl_mesetime.html");
		$gui_tbl_mesetime = $this->render_regex('TBL_MESETIME', $gui_tbl_mesetime, $mesetime_collection);		
		$fecha_actual = $this->descomponer_fecha();
		$render = str_replace('{tbl_mesetime}', $gui_tbl_mesetime, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function ver_mesetime($obj_mesetime) {
		$gui = file_get_contents("static/modules/competencia/ver_mesetime.html");
		$obj_mesetime->fecha = $this->reacomodar_fecha($obj_mesetime->fecha);
		$obj_mesetime = $this->set_dict($obj_mesetime);
		$render = $this->render($obj_mesetime, $gui);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;
	}

	function contacto($array_alert, $obj_competencia) {
		$gui = file_get_contents("static/modules/competencia/contacto.html");		
		$fecha_actual = $this->descomponer_fecha();
		$render = $this->render($fecha_actual, $gui);
		$obj_competencia = $this->set_dict($obj_competencia);
		$render = str_replace('{equipo-denominacion}', $_SESSION["data-login-" . APP_ABREV]["equipo-denominacion"], $render);
		$render = $this->render($array_alert, $render);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function foro($foro_collection, $array_alert, $obj_competencia) {
		$gui = file_get_contents("static/modules/competencia/foro.html");
		$gui_lst_foro = file_get_contents("static/modules/competencia/lst_foro.html");
		$gui_lst_foro = $this->render_regex_dict('LST_FORO', $gui_lst_foro, $foro_collection);
		$fecha_actual = $this->descomponer_fecha();
		$obj_competencia = $this->set_dict($obj_competencia);
		$render = str_replace('{lst_foro}', $gui_lst_foro, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = str_replace('{equipo-denominacion}', $_SESSION["data-login-" . APP_ABREV]["equipo-denominacion"], $render);
		$render = $this->render($array_alert, $render);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function agregar_comentario_foro($foro_id) {
		$gui = file_get_contents("static/modules/competencia/agregar_comentario_foro.html");
		$render = str_replace('{foro-foro_id}', $foro_id, $gui);
		$render = str_replace('{equipo-denominacion}', $_SESSION["data-login-" . APP_ABREV]["equipo-denominacion"], $render);
		$render = str_replace('{url_app}', URL_APP, $render);
		print $render;
	}

	function ver_comentarios($obj_foro) {
		$gui_lst_comentario = file_get_contents("static/modules/competencia/lst_comentario.html");
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
		$render = str_replace('{url_app}', URL_APP, $gui_lst_comentario);
		print $render;	
	}

	function decisiones($array_alert, $obj_competencia, $equipodecision_id, $obj_equipodecision, $rondacompetencia_id) {
		$gui = file_get_contents("static/modules/competencia/decisiones.html");		

		$activo = $obj_competencia->configuraciondecision->activo;
		if ($activo == 0) {
			$gui_cuerpo = file_get_contents("static/modules/competencia/alert_decision_inactivo.html");
			$info_txt = "El formulario ha sido desactivado. Muchas gracias!";
			$gui_cuerpo = str_replace('{info_txt}', $info_txt, $gui_cuerpo);
		} else {
			if ($equipodecision_id != 0 AND !is_null($obj_equipodecision)) {
				//$gui_cuerpo = file_get_contents("static/modules/competencia/alert_decision_inactivo.html");
				//$info_txt = "Ya posee una decisión cargada para el período. Muchas gracias!";
				//$gui_cuerpo = str_replace('{info_txt}', $info_txt, $gui_cuerpo);
				$gui_cuerpo = file_get_contents("static/modules/competencia/form_editar_decision.html");
				$obj_equipodecision = $this->set_dict($obj_equipodecision);
				$gui_cuerpo = $this->render($obj_equipodecision, $gui_cuerpo);
			} else {
				$gui_cuerpo = file_get_contents("static/modules/competencia/form_decision.html");				
				$gui_cuerpo = str_replace('{rondacompetencia-rondacompetencia_id}', $rondacompetencia_id, $gui_cuerpo);
			}
		}
		
		$obj_competencia = $this->set_dict($obj_competencia);
		$fecha_actual = $this->descomponer_fecha();
		$render = str_replace('{gui_cuerpo}', $gui_cuerpo, $gui);
		$render = $this->render($fecha_actual, $render);
		$render = str_replace('{equipo-denominacion}', $_SESSION["data-login-" . APP_ABREV]["equipo-denominacion"], $render);
		$render = $this->render($array_alert, $render);
		$render = $this->render($obj_competencia, $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = str_replace('{equipo-equipo_id}', $_SESSION["data-login-" . APP_ABREV]["equipo-equipo_id"], $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function reportes_compania($obj_competencia, $array_periodos) {
		$gui = file_get_contents("static/modules/competencia/reportes_compania.html");

		if ($obj_competencia->activo_reportes == 1) {
			$gui_cuerpo = file_get_contents("static/modules/competencia/contenido_reportes_compania.html");
			$slt_periodo_informecompania = file_get_contents("static/modules/competencia/slt_periodo_informecompania.html");
			$slt_periodo_informecompania = $this->render_regex_dict('SLT_PERIODO_INFORMECOMPANIA', $slt_periodo_informecompania, $array_periodos);
			$gui_cuerpo = str_replace('{slt_periodo_informecompania}', $slt_periodo_informecompania, $gui_cuerpo);
		} else {
			$gui_cuerpo = file_get_contents("static/modules/competencia/alert_reportes_inactivo.html");
		}
	
		$fecha_actual = $this->descomponer_fecha();
		$obj_competencia = $this->set_dict($obj_competencia);
		$render = $this->render($fecha_actual, $gui);
		$render = str_replace('{gui_cuerpo}', $gui_cuerpo, $render);
		$render = str_replace('{equipo-denominacion}', $_SESSION["data-login-" . APP_ABREV]["equipo-denominacion"], $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = str_replace('{equipo-equipo_id}', $_SESSION["data-login-" . APP_ABREV]["equipo-equipo_id"], $render);
		$render = $this->render($obj_competencia, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function reportes_industria($obj_competencia, $array_periodos) {
		$gui = file_get_contents("static/modules/competencia/reportes_industria.html");

		if ($obj_competencia->activo_reportes == 1) {
			$gui_cuerpo = file_get_contents("static/modules/competencia/contenido_reportes_industria.html");
			$slt_periodo_informeindustria = file_get_contents("static/modules/competencia/slt_periodo_informeindustria.html");
			$slt_periodo_informeindustria = $this->render_regex_dict('SLT_PERIODO_INFORMEINDUSTRIA', $slt_periodo_informeindustria, $array_periodos);
			$gui_cuerpo = str_replace('{slt_periodo_informeindustria}', $slt_periodo_informeindustria, $gui_cuerpo);
		} else {
			$gui_cuerpo = file_get_contents("static/modules/competencia/alert_reportes_inactivo.html");
		}
	
		$fecha_actual = $this->descomponer_fecha();
		$obj_competencia = $this->set_dict($obj_competencia);
		$render = $this->render($fecha_actual, $gui);
		$render = str_replace('{gui_cuerpo}', $gui_cuerpo, $render);
		$render = str_replace('{equipo-denominacion}', $_SESSION["data-login-" . APP_ABREV]["equipo-denominacion"], $render);
		$render = str_replace('{equipo-zona}', $_SESSION["data-login-" . APP_ABREV]["equipo-zona"], $render);
		$render = str_replace('{equipo-equipo_id}', $_SESSION["data-login-" . APP_ABREV]["equipo-equipo_id"], $render);
		$render = $this->render($obj_competencia, $render);
		$render = $this->render_breadcrumb($render);
		$template = $this->render_template($render);
		print $template;
	}

	function mostrar_reporte_compania_ajax($array_datos, $estado_ingreso_array, $reporte_operacion_array, $reporte_produccion_array,
							   	  		   $reporte_marketing_array, $reporte_inversion_array, $reporte_flujocaja_array) {
		$gui = file_get_contents("static/modules/competencia/mostrar_reportes_compania.html");
		$gui = $this->render($estado_ingreso_array, $gui);
		$gui = $this->render($reporte_operacion_array, $gui);
		$gui = $this->render($reporte_produccion_array, $gui);
		$gui = $this->render($reporte_marketing_array, $gui);
		$gui = $this->render($reporte_inversion_array, $gui);
		$gui = $this->render($reporte_flujocaja_array, $gui);
		$gui = $this->render($array_datos, $gui);
		print $gui;
	}

	function sin_reporte_cargado_ajax($array_datos) {
		$gui = file_get_contents("static/modules/competencia/sin_reporte_cargado.html");
		print $gui;
	}

	function mostrar_reporte_industria_ajax($array_datos, $unidades, $dolares, $productividad, $economia, $array_zona, $cant_equipos) {
		$gui = file_get_contents("static/modules/competencia/mostrar_reportes_industria.html");
		$gui_td = file_get_contents("static/modules/competencia/td_reporte_industria.html");
		$gui_tr = file_get_contents("static/modules/competencia/tr_reporte_industria.html");
		$render_td = '';
        $codigo_td = $this->get_regex('EQUIPO_INFORME_INDUSTRIA', $gui_td);
        
        for ($i=0; $i < $cant_equipos ; $i++) { 
            $render_td .= str_replace('{indice}', $i, $codigo_td);	
        }
        
        $render_td_final = str_replace($codigo_td, $render_td, $gui_td);
        $render_td_final = str_replace('<!--EQUIPO_INFORME_INDUSTRIA-->', '', $render_td_final);
        $render_tr = str_replace('{td_informe_industria}', $render_td_final, $gui_tr);    

        $array_tabla = array();
        foreach($array_zona as $fila) {
        	$new_dict = array();
        	foreach ($fila as $clave=>$valor) $new_dict["{VALOR{$clave}}"] = $valor;        	
        	$array_tabla[] = $new_dict;
        }
	
        $render_tr_final = $this->render_regex_dict('EQUIPO_INFORME_INDUSTRIA', $render_tr, $array_tabla);
    	$gui = $this->render($unidades, $gui);
		$gui = $this->render($dolares, $gui);
		$gui = $this->render($productividad, $gui);
		$gui = $this->render($economia, $gui);
		$gui = $this->render($array_datos, $gui);
        $gui = str_replace('{tbl_informe_industria_equipos}', $render_tr_final, $gui);    
		print $gui;
	}
}
?>