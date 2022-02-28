<?php
require_once "modules/competencia/model.php";
require_once "modules/competencia/view.php";
require_once "modules/seccion/model.php";
require_once "modules/cronograma/model.php";
require_once "modules/preguntafrecuente/model.php";
require_once "modules/herramienta/model.php";
require_once "modules/comunicado/model.php";
require_once "modules/mesetime/model.php";
require_once "modules/contacto/model.php";
require_once "modules/foro/model.php";
require_once "modules/comentario/model.php";
require_once "modules/equipodecision/model.php";
require_once "modules/equipo/model.php";


class CompetenciaController {

    function __construct() {
        $this->model = new Competencia();
        $this->view = new CompetenciaView();
    }

    function home() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        require_once "core/helpers/file.php";
        $sm = new Seccion();
        $sm->seccion_id = 1;
        $sm->get();

        $this->model->competencia_id = 1;
        $this->model->get();

        $this->view->home($sm, $this->model);
    }

    function cronograma() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $cm = new Cronograma();
        $cm->cronograma_id = 1;
        $cm->get();

        $this->model->competencia_id = 1;
        $this->model->get();

        $this->view->cronograma($cm, $this->model);
    }

    function manual() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        require_once "core/helpers/file.php";
        $sm = new Seccion();
        $sm->seccion_id = 3;
        $sm->get();

        $this->model->competencia_id = 1;
        $this->model->get();

        $this->view->manual($sm, $this->model);
    }

    function preguntas_frecuentes() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $preguntafrecuente_collection = Collector()->get('PreguntaFrecuente');
        $this->model->competencia_id = 1;
        $this->model->get();
        $this->view->preguntas_frecuentes($preguntafrecuente_collection, $this->model);
    }

    function ver_preguntafrecuente($arg) {
        SessionHandler()->check_actualiza_contrasena();
        $pfm = new PreguntaFrecuente();
        $pfm->preguntafrecuente_id = $arg;
        $pfm->get();
        $this->view->ver_preguntafrecuente($pfm);
    }

    function herramientas() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $herramienta_collection = Collector()->get('Herramienta');
        $this->model->competencia_id = 1;
        $this->model->get();
        $this->view->herramientas($herramienta_collection, $this->model);
    }

    function ver_herramienta($arg) {
        SessionHandler()->check_actualiza_contrasena();
        $hm = new Herramienta();
        $hm->herramienta_id = $arg;
        $hm->get();
        $this->view->ver_herramienta($hm);
    }

    function equipos() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $zona = $_SESSION["data-login-" . APP_ABREV]["equipo-zona"];
        $select = "e.equipo_id AS EQUID, e.denominacion AS NOMBRE, es.denominacion AS INSTITUCION, es.ciudad AS LOCALIDAD, e.zona";
        $from = "equipo e INNER JOIN escuela es ON e.escuela = es.escuela_id";
        $where = "e.zona = {$zona}";
        $equipo_collection = CollectorCondition()->get('Equipo', $where, 4, $from, $select);
        
        $this->model->competencia_id = 1;
        $this->model->get();

        $this->view->equipos($equipo_collection, $this->model);
    }

    function comunicados() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $comunicado_collection = Collector()->get('Comunicado');
        $this->view->comunicados($comunicado_collection);
    }

    function ver_comunicado($arg) {
        SessionHandler()->check_actualiza_contrasena();
        require_once "core/helpers/file.php";
        $cm = new Comunicado();
        $cm->comunicado_id = $arg;
        $cm->get();
        $this->view->ver_comunicado($cm);
    }

    function mesetimes() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $mesetime_collection = Collector()->get('MeseTime');
        $this->view->mesetimes($mesetime_collection);
    }

    function ver_mesetime($arg) {
        SessionHandler()->check_actualiza_contrasena();
        require_once "core/helpers/file.php";
        $mtm = new MeseTime();
        $mtm->mesetime_id = $arg;
        $mtm->get();
        $this->view->ver_mesetime($mtm);
    }

    function contacto($arg) {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        switch ($arg) {
            case 1:
                $array_alert = array('{alert-msj}'=>'Su mensaje ha sido enviado con éxito. Nuestro equipo se comunicará con Ud. a la brevedad.<br><br>Muchas gracias por contactarse con nosotros!',
                                     '{alert-display}'=>'show');
                break;
            default:
                $array_alert = array('{alert-msj}'=>'',
                                     '{alert-display}'=>'none');
                break;
        }

        $this->model->competencia_id = 1;
        $this->model->get();

        $this->view->contacto($array_alert, $this->model);
    }

    function guardar_mensaje() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $cm = new Contacto();
        foreach ($_POST as $clave=>$valor) $cm->$clave = $valor;
        $cm->fecha = date('Y-m-d');
        $cm->hora = date('H:i:s');
        $cm->save();
        header("Location: " . URL_APP . "/competencia/contacto/1");
    }

    function foro($arg) {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $this->model->competencia_id = 1;
        $this->model->get();

        $select = "f.denominacion AS TITULO, f.equipo AS EQUIPO, f.contenido AS CONTENIDO, date_format(f.fecha, '%d/%m/%Y') AS FECHA, f.hora AS HORA, 
                   f.correoelectronico AS CORREOELECTRONICO, f.foro_id AS FORID ";
        $from = "foro f";
        $where = "f.activo = 1 ORDER BY f.fecha DESC";
        $foro_collection = CollectorCondition()->get('Foro', $where, 4, $from, $select);

        $select = "COUNT(cf.compuesto) AS CANTIDAD";
        $from = "foro f INNER JOIN comentarioforo cf ON f.foro_id = cf.compuesto INNER JOIN comentario c ON cf.compositor = c.comentario_id";
        
        if (is_array($foro_collection) AND !empty($foro_collection)) {
            foreach ($foro_collection as $clave=>$valor) {
                $foro_id = $valor['FORID'];             
                $where = "c.activo = 1 AND f.foro_id = {$foro_id} ORDER BY f.fecha DESC";
                $comentario_collection = CollectorCondition()->get('Foro', $where, 4, $from, $select);

                $cantidad = (is_array($foro_collection) AND !empty($comentario_collection)) ? $comentario_collection[0]['CANTIDAD'] : 0;
                $foro_collection[$clave]['CANTIDAD'] = $cantidad;
            }
        }

        switch ($arg) {
            case 1:
                $array_alert = array('{alert-msj}'=>'Su blog se ha cargado con éxito. Nuestro equipo evaluará su contenido para ser aprobado.<br><br>Muchas gracias por participar!',
                                     '{alert-display}'=>'show');
                break;
            default:
                $array_alert = array('{alert-msj}'=>'',
                                     '{alert-display}'=>'none');
                break;
        }

        $this->view->foro($foro_collection, $array_alert, $this->model);
    }

    function agregar_comentario_foro($arg) {
        SessionHandler()->check_actualiza_contrasena();
        $foro_id = $arg; 
        $this->view->agregar_comentario_foro($foro_id);
    }

    function guardar_comentario_foro() {
        SessionHandler()->check_actualiza_contrasena();
        $foro_id = filter_input(INPUT_POST, 'foro_id');
        
        $cm = new Comentario();
        $cm->denominacion = filter_input(INPUT_POST, 'denominacion');
        $cm->correoelectronico = filter_input(INPUT_POST, 'correoelectronico');
        $cm->contenido = filter_input(INPUT_POST, 'contenido');
        $cm->equipo = filter_input(INPUT_POST, 'equipo');
        $cm->fecha = date('Y-m-d');
        $cm->hora = date('H:i:s');
        $cm->activo = 0;
        $cm->save();
        $comentario_id = $cm->comentario_id;

        $cm = new Comentario();
        $cm->comentario_id = $comentario_id;
        $cm->get();

        $fm = new Foro();
        $fm->foro_id = $foro_id;
        $fm->get();
        $fm->add_comentario($cm);

        $cfm = new ComentarioForo($fm);
        $cfm->save();
        header("Location: " . URL_APP . "/competencia/foro/1");
    }

    function ver_comentarios($arg) {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $foro_id = $arg;
        $fm = new Foro();
        $fm->foro_id = $foro_id;
        $fm->get();    
        $this->view->ver_comentarios($fm);     
    }

    function decisiones($arg) {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        switch ($arg) {
            case 1:
                $array_alert = array('{alert-msj}'=>'Su decisión ha sido cargada con éxito.',
                                     '{alert-display}'=>'show');
                break;
            case 2:
                $array_alert = array('{alert-msj}'=>'Su decisión ha sido modificada con éxito.',
                                     '{alert-display}'=>'show');
                break;
            default:
                $array_alert = array('{alert-msj}'=>'',
                                     '{alert-display}'=>'none');
                break;
        }

        $this->model->competencia_id = 1;
        $this->model->get();
        $periodo = $this->model->periodo;
        $rondacompetencia = $this->model->rondacompetencia->rondacompetencia_id;

        $equipo_id = $_SESSION["data-login-" . APP_ABREV]["equipo-equipo_id"];
        $periodo = $periodo;
        $select = "ed.equipodecision_id AS ID";
        $from = "equipodecision ed";
        $where = "ed.equipo_id = {$equipo_id} AND ed.periodo = {$periodo} AND ed.rondacompetencia_id = {$rondacompetencia}";
        $equipodecision_collection = CollectorCondition()->get('EquipoDecision', $where, 4, $from, $select);
        $equipodecision_id = (is_array($equipodecision_collection) AND !empty($equipodecision_collection)) ? $equipodecision_collection[0]["ID"] : 0;

        if ($equipodecision_id != 0) {
            $edm = new EquipoDecision();
            $edm->equipodecision_id = $equipodecision_id;
            $edm->get();
        } else {
            $edm = NULL;
        }

        $this->view->decisiones($array_alert, $this->model, $equipodecision_id, $edm, $rondacompetencia);
    }

    function guardar_decision() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $edm = new EquipoDecision();
        $edm->precio = filter_input(INPUT_POST, "precio");
        $edm->produccion = filter_input(INPUT_POST, "produccion");
        $edm->marketing = filter_input(INPUT_POST, "marketing");
        $edm->inversion = filter_input(INPUT_POST, "inversion");
        $edm->iandd = filter_input(INPUT_POST, "iandd");
        $edm->fecha = date('Y-m-d');
        $edm->hora = date('H:i:s');
        $edm->periodo = filter_input(INPUT_POST, "periodo");
        $edm->rondacompetencia_id = filter_input(INPUT_POST, "rondacompetencia_id");
        $edm->equipo_id = filter_input(INPUT_POST, "equipo_id");
        $edm->save();
        header("Location: " . URL_APP . "/competencia/decisiones/1");
    }

    function actualizar_decision() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $edm = new EquipoDecision();
        $equipodecision_id = filter_input(INPUT_POST, "equipodecision_id");
        $edm->equipodecision_id = $equipodecision_id;
        $edm->get();
        $edm->precio = filter_input(INPUT_POST, "precio");
        $edm->produccion = filter_input(INPUT_POST, "produccion");
        $edm->marketing = filter_input(INPUT_POST, "marketing");
        $edm->inversion = filter_input(INPUT_POST, "inversion");
        $edm->iandd = filter_input(INPUT_POST, "iandd");
        $edm->fecha = date('Y-m-d');
        $edm->hora = date('H:i:s');
        $edm->save();
        header("Location: " . URL_APP . "/competencia/decisiones/2");
    }

    function reportes_compania() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $this->model->competencia_id = 1;
        $this->model->get();
        $periodo = $this->model->periodo;

        $zona = $_SESSION["data-login-" . APP_ABREV]["equipo-zona"];
        $zona = str_pad($zona, 2, "0", STR_PAD_LEFT);

        $equipo_id = $_SESSION["data-login-" . APP_ABREV]["equipo-equipo_id"];
        $em = new Equipo();
        $em->equipo_id = $equipo_id;
        $em->get();
        $numero_equipo = $em->numero;

        $array_periodos = array();
        for ($i=0; $i <= $periodo; $i++) { 
            $array_temp = array('{periodo_id}'=>$i, '{periodo_text}'=>'Período ' . $i);
            $array_periodos[] = $array_temp;
        }
        
        $this->view->reportes_compania($this->model, $array_periodos);
    }

    function mostrar_reporte_compania_ajax($arg) {
        SessionHandler()->check_actualiza_contrasena();
        $periodo = $arg;
        $zona = $_SESSION["data-login-" . APP_ABREV]["equipo-zona"];
        $zona = str_pad($zona, 2, "0", STR_PAD_LEFT);

        $equipo_id = $_SESSION["data-login-" . APP_ABREV]["equipo-equipo_id"];
        $em = new Equipo();
        $em->equipo_id = $equipo_id;
        $em->get();
        $equipo_denominacion = $em->denominacion;
        $numero_equipo = $em->numero;
        $array_datos = array('{equipo-denominacion}'=>$equipo_denominacion, '{competencia-periodo}'=>$periodo);
        
        $archivo = URL_PRIVATE . "informecompania/periodo{$periodo}/zona{$zona}/co{$numero_equipo}.lst";
        
        if(!file_exists($archivo)) {
            $this->view->sin_reporte_cargado_ajax($array_datos);
        } else {
            $archivo = fopen($archivo, "r");
            $i = 0;
            $array = array();
            while(!feof($archivo)) {
                $array[] = fgets($archivo);
                $i = $i + 1;
            }
            
            fclose($archivo);

            $fila1 = array('Ventas'=>substr($array[4],0,18), 
                           'valor_venta'=>substr($array[4],18,13), 
                           'valor_venta_porcentaje'=>substr($array[4],28,8), 
                           'Decisiones:Precio'=>substr($array[4],37,22), 
                           'valor_decisiones_precio'=>substr($array[4],59,20));
            
            $fila2 = array('CBV'=>substr($array[5],0,18), 
                           'valor_cbv'=>substr($array[5],18,13), 
                           'valor_cbv_porcentaje'=>substr($array[5],28,8), 
                           'Producción'=>substr($array[5],37,22), 
                           'valor_produccion'=>substr($array[5],59,20));

            $fila3 = array(''=>substr($array[6],0,18), 
                           '-'=>substr($array[6],19,9), 
                           '--'=>substr($array[6],28,8), 
                           'Marketing'=>substr($array[6],36,22), 
                           'valor_marketing'=>substr($array[6],59,20));

            $fila4 = array('Margen Bruto'=>substr($array[7],0,18), 
                           'valor_margen_bruto'=>substr($array[7],18,13), 
                           'valor_margen_bruto_porcentaje'=>substr($array[7],28,8), 
                           'Inversión'=>substr($array[7],37,22), 
                           'valor_inversion'=>substr($array[7],59,20));

            $fila5 = array('Marketing'=>substr($array[8],0,18), 
                           'valor_marketing'=>substr($array[8],18,13), 
                           'valor_marketing_porcentaje'=>substr($array[8],28,8), 
                           'I&D'=>substr($array[8],37,22), 
                           'valor_iandd'=>substr($array[8],59,20));

            $fila6 = array('Depreciación'=>substr($array[9],0,18), 
                           'valor_depreciacion'=>substr($array[9],18,13), 
                           'valor_depreciacion_porcentaje'=>substr($array[9],28,8), 
                           ''=>substr($array[9],37,22), 
                           '-'=>substr($array[9],59,20));

            $fila7 = array('I&D'=>substr($array[10],0,18), 
                           'valor_iandd'=>substr($array[10],18,13), 
                           'valor_iandd_porcentaje'=>substr($array[10],28,8), 
                           ''=>substr($array[10],37,22), 
                           '-'=>substr($array[10],59,20));

            $fila8 = array('Carga Social'=>substr($array[11],0,18), 
                           'valor_carga_social'=>substr($array[11],18,13), 
                           'valor_carga_social_porcentaje'=>substr($array[11],28,8), 
                           'Reporte de Producción'=>substr($array[11],37,22), 
                           'valor_reporte_produccion'=>substr($array[11],59,20));

            $fila9 = array('Cargo Inventario'=>substr($array[12],0,18), 
                           'valor_cargo_inventario'=>substr($array[12],18,13), 
                           'valor_cargo_inventario_porcentaje'=>substr($array[12],28,8), 
                           'Producción'=>substr($array[12],37,22), 
                           'valor_produccion'=>substr($array[12],59,20));

            $fila10 = array('Interés'=>substr($array[13],0,18), 
                            'valor_interes'=>substr($array[13],18,13), 
                            'valor_interes_porcentaje'=>substr($array[13],28,8), 
                            'Capacidad de Fábrica'=>substr($array[13],37,22), 
                            'valor_capacidad_fabrica'=>substr($array[13],59,20));

            $fila11 = array(''=>substr($array[14],0,18), 
                            '-'=>substr($array[14],19,9), 
                            '--'=>substr($array[14],28,8), 
                            'Capacidad Utilizada'=>substr($array[14],36,22), 
                            'valor_capacidad_utilizada'=>substr($array[14],59,20));

            $fila12 = array('Utilidad antes de Impuesto'=>substr($array[15],0,18), 
                            'valor_utilidad_antes_impuesto'=>substr($array[15],18,13), 
                            'valor_utilidad_antes_impuesto_porcentaje'=>substr($array[15],28,8), 
                            'Produc Costo/Unidad'=>substr($array[15],37,22), 
                            'valor_produc_costo_unidad'=>substr($array[15],59,20));

            $fila13 = array('Impuesto'=>substr($array[16],0,18), 
                            'valor_impuesto'=>substr($array[16],18,13), 
                            'valor_impuesto_porcentaje'=>substr($array[16],28,8), 
                            'Inventario'=>substr($array[16],37,22), 
                            'valor_inventario'=>substr($array[16],59,20));

            $fila14 = array(''=>substr($array[17],0,18), 
                            '-'=>substr($array[17],19,9), 
                            '--'=>substr($array[17],28,8), 
                            'Empleados'=>substr($array[17],36,22), 
                            'valor_empleados'=>substr($array[17],59,20));

            $fila15 = array('Utilidad Neta'=>substr($array[18],0,18), 
                            'valor_utilidad_neta'=>substr($array[18],18,13), 
                            'valor_utilidad_neta_porcentaje'=>substr($array[18],28,8), 
                            ''=>substr($array[18],37,22), 
                            '-'=>substr($array[18],59,20));

            $fila16 = array('Hoja de Balance'=>substr($array[20],0,18), 
                            'valor_balance'=>substr($array[20],18,9), 
                            'valor_balance_porcentaje'=>substr($array[20],28,8), 
                            'Reporte de Markenting'=>substr($array[20],37,22), 
                            'valor_reporte_marketing'=>substr($array[20],59,20));

            $fila17 = array('-'=>substr($array[21],0,18), 
                            ''=>substr($array[21],18,10), 
                            '--'=>substr($array[21],28,7), 
                            'Órdenes Recibidas'=>substr($array[21],37,22), 
                            'valor_ordenes_recibidas'=>substr($array[21],59,20));

            $fila18 = array('Efectivo'=>substr($array[22],0,18), 
                            'valor_efectivo'=>substr($array[22],18,13), 
                            'valor_efectivo_porcentaje'=>substr($array[22],28,8), 
                            'Ventas Realizadas'=>substr($array[22],37,22), 
                            'valor_ventas_realizadas'=>substr($array[22],59,20));

            $fila19 = array('Inventario'=>substr($array[23],0,18), 
                            'valor_inventario'=>substr($array[23],18,13), 
                            'valor_inventario_porcentaje'=>substr($array[23],28,8), 
                            'Órdenes no Realizadas'=>substr($array[23],37,22), 
                            'valor_ordenes_no_realizadas'=>substr($array[23],59,20));

            $fila20 = array('Inver. de Capital'=>substr($array[24],0,18), 
                            'valor_inver_capital'=>substr($array[24],18,13), 
                            'valor_inver_capital_porcentaje'=>substr($array[24],28,8), 
                            'Precio/Unidad Vendida'=>substr($array[24],37,22), 
                            'valor_precio_unidad_vendida'=>substr($array[24],59,20));

            $fila21 = array('-'=>substr($array[25],0,18), 
                            ''=>substr($array[25],18,10), 
                            '--'=>substr($array[25],28,7), 
                            'Tot Costo/Unid Vendida'=>substr($array[25],37,22), 
                            'valor_total_costo_unidad_vendida'=>substr($array[25],59,20));

            $fila22 = array('Total Activo'=>substr($array[26],0,18), 
                            'valor_total_activo'=>substr($array[26],18,13), 
                            'valor_total_activo_porcentaje'=>substr($array[26],28,8), 
                            'Margen/Unidad Vendida'=>substr($array[26],37,22), 
                            'valor_margen_unidad_vendida'=>substr($array[26],59,20));

            $fila23 = array('Préstamos'=>substr($array[28],0,18), 
                            'valor_prestamos'=>substr($array[28],18,13), 
                            'valor_prestamos_porcentaje'=>substr($array[28],28,8), 
                            'Reporte de Inversión'=>substr($array[28],37,22), 
                            'valor_reporte_inversion'=>substr($array[28],59,20));

            $fila24 = array('Ganancias Reten.'=>substr($array[29],0,18), 
                            'valor_ganacias_retenciones'=>substr($array[29],18,13), 
                            'valor_ganacias_retenciones_porcentaje'=>substr($array[29],28,8), 
                            'Capacidad de Fábrica'=>substr($array[29],37,16), 
                            'valor_capacidad_fabrica'=>substr($array[29],54,10),
                            'valor_capacidad_fabrica1'=>substr($array[29],65,20));

            $fila25 = array('Capital'=>substr($array[30],0,18), 
                            'valor_capital'=>substr($array[30],18,13), 
                            'valor_capital_porcentaje'=>substr($array[30],28,8), 
                            'Inversión Neta'=>substr($array[30],37,16), 
                            'valor_inversion_neta'=>substr($array[30],54,10),
                            'valor_inversion_neta1'=>substr($array[30],65,20));

            $fila26 = array('Pasivo+Patrimonio'=>substr($array[32],0,18), 
                            'valor_pasivo_patrimonio'=>substr($array[32],18,13), 
                            'valor_pasivo_patrimonio_porcentaje'=>substr($array[32],28,8), 
                            'Próx Capacidad'=>substr($array[32],37,16), 
                            'valor_prox_capacidad'=>substr($array[32],54,10),
                            'valor_prox_capacidad1'=>substr($array[32],65,20));

            $fila27 = array('Caja Inicial'=>substr($array[36],0,18), 
                            'valor_caja_inicial'=>substr($array[36],19,21));

            $fila28 = array('Utilidad Neta'=>substr($array[37],0,18), 
                            'valor_utilidad_neta'=>substr($array[37],19,21));

            $fila29 = array('Depreciación'=>substr($array[38],0,18), 
                            'valor_depreciacion'=>substr($array[38],19,21));

            $fila30 = array('Inversión de Capital'=>substr($array[39],0,18), 
                            'valor_inversion_capital'=>substr($array[39],19,21));

            $fila31 = array('Cambio de Inventario'=>substr($array[40],0,18), 
                            'valor_cambio_inventario'=>substr($array[40],19,21));

            $fila32 = array('Préstamo Neto'=>substr($array[41],0,18), 
                            'valor_prestamo_neto'=>substr($array[41],19,21));

            $fila33 = array('Caja Final'=>substr($array[43],0,18), 
                            'valor_caja_final'=>substr($array[43],19,21));

            $array_reporte = array($fila1, $fila2, $fila3, $fila4, $fila5, $fila6, $fila7, $fila8, $fila9, $fila10, $fila11,
                                   $fila12, $fila13, $fila14, $fila15, $fila16, $fila17, $fila18, $fila19, $fila20, $fila21,
                                   $fila22, $fila23, $fila24, $fila25, $fila26, $fila27, $fila28, $fila29, $fila30, $fila31, 
                                   $fila32, $fila33);

            $estado_ingreso_array = array('{valor_venta}'=>substr($array[4],18,13), 
                                          '{valor_venta_porcentaje}'=>substr($array[4],28,8),
                                          '{valor_venta_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[4],28,8))),
                                          '{valor_cbv}'=>substr($array[5],18,13), 
                                          '{valor_cbv_porcentaje}'=>substr($array[5],28,8),
                                          '{valor_cbv_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[5],28,8))),
                                          '{valor_margen_bruto}'=>substr($array[7],18,13), 
                                          '{valor_margen_bruto_porcentaje}'=>substr($array[7],28,8),
                                          '{valor_margen_bruto_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[7],28,8))),
                                          '{valor_marketing}'=>substr($array[8],18,13), 
                                          '{valor_marketing_porcentaje}'=>substr($array[8],28,8), 
                                          '{valor_marketing_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[8],28,8))),
                                          '{valor_depreciacion}'=>substr($array[9],18,13), 
                                          '{valor_depreciacion_porcentaje}'=>substr($array[9],28,8), 
                                          '{valor_depreciacion_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[9],28,8))),
                                          '{valor_iandd}'=>substr($array[10],18,13), 
                                          '{valor_iandd_porcentaje}'=>substr($array[10],28,8), 
                                          '{valor_iandd_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[10],28,8))),
                                          '{valor_carga_social}'=>substr($array[11],18,13), 
                                          '{valor_carga_social_porcentaje}'=>substr($array[11],28,8), 
                                          '{valor_carga_social_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[11],28,8))), 
                                          '{valor_cargo_inventario}'=>substr($array[12],18,13), 
                                          '{valor_cargo_inventario_porcentaje}'=>substr($array[12],28,8), 
                                          '{valor_cargo_inventario_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[12],28,8))),
                                          '{valor_interes}'=>substr($array[13],18,13), 
                                          '{valor_interes_porcentaje}'=>substr($array[13],28,8), 
                                          '{valor_interes_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[13],28,8))),
                                          '{valor_utilidad_antes_impuesto}'=>substr($array[15],18,13), 
                                          '{valor_utilidad_antes_impuesto_porcentaje}'=>substr($array[15],28,8), 
                                          '{valor_utilidad_antes_impuesto_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[15],28,8))),
                                          '{valor_impuesto}'=>substr($array[16],18,13), 
                                          '{valor_impuesto_porcentaje}'=>substr($array[16],28,8), 
                                          '{valor_impuesto_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[16],28,8))), 
                                          '{valor_utilidad_neta}'=>substr($array[18],18,13), 
                                          '{valor_utilidad_neta_porcentaje}'=>substr($array[18],28,8), 
                                          '{valor_utilidad_neta_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[18],28,8))), 
                                          '{valor_balance}'=>substr($array[20],18,9), 
                                          '{valor_balance_porcentaje}'=>substr($array[20],28,8), 
                                          '{valor_balance_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[20],28,8))), 
                                          '{valor_efectivo}'=>substr($array[22],18,13), 
                                          '{valor_efectivo_porcentaje}'=>substr($array[22],28,8), 
                                          '{valor_efectivo_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[22],28,8))), 
                                          '{valor_inventario}'=>substr($array[23],18,13), 
                                          '{valor_inventario_porcentaje}'=>substr($array[23],28,8), 
                                          '{valor_inventario_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[23],28,8))), 
                                          '{valor_inver_capital}'=>substr($array[24],18,13), 
                                          '{valor_inver_capital_porcentaje}'=>substr($array[24],28,8),
                                          '{valor_inver_capital_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[24],28,8))),
                                          '{valor_total_activo}'=>substr($array[26],18,13), 
                                          '{valor_total_activo_porcentaje}'=>substr($array[26],28,8), 
                                          '{valor_total_activo_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[26],28,8))), 
                                          '{valor_prestamos}'=>substr($array[28],18,13), 
                                          '{valor_prestamos_porcentaje}'=>substr($array[28],28,8), 
                                          '{valor_prestamos_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[28],28,8))), 
                                          '{valor_ganacias_retenciones}'=>substr($array[29],18,13), 
                                          '{valor_ganacias_retenciones_porcentaje}'=>substr($array[29],28,8), 
                                          '{valor_ganacias_retenciones_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[29],28,8))), 
                                          '{valor_capital}'=>substr($array[30],18,13), 
                                          '{valor_capital_porcentaje}'=>substr($array[30],28,8),
                                          '{valor_capital_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[30],28,8))),
                                          '{valor_pasivo_patrimonio}'=>substr($array[32],18,13), 
                                          '{valor_pasivo_patrimonio_porcentaje}'=>substr($array[32],28,8),
                                          '{valor_pasivo_patrimonio_porcentaje_limpio}'=>trim(str_replace('%', '', substr($array[32],28,8))));
        
            $reporte_operacion_array = array('{reporte_operacion-valor_decisiones_precio}'=>substr($array[4],59,20),
                                             '{reporte_operacion-valor_produccion}'=>substr($array[5],59,20),
                                             '{reporte_operacion-valor_marketing}'=>substr($array[6],59,20),
                                             '{reporte_operacion-valor_inversion}'=>substr($array[7],59,20),
                                             '{reporte_operacion-valor_iandd}'=>substr($array[8],59,20));

            $reporte_produccion_array = array('{reporte_produccion-valor_produccion}'=>substr($array[12],59,20),
                                              '{reporte_produccion-valor_capacidad_fabrica}'=>substr($array[13],59,20),
                                              '{reporte_produccion-valor_capacidad_utilizada}'=>substr($array[14],59,20),
                                              '{reporte_produccion-valor_produc_costo_unidad}'=>substr($array[15],59,20),
                                              '{reporte_produccion-valor_inventario}'=>substr($array[16],59,20),
                                              '{reporte_produccion-valor_empleados}'=>substr($array[17],59,20));

            $reporte_marketing_array = array('{reporte_marketing-valor_ordenes_recibidas}'=>substr($array[21],59,20),
                                             '{reporte_marketing-valor_ventas_realizadas}'=>substr($array[22],59,20),
                                             '{reporte_marketing-valor_ordenes_no_realizadas}'=>substr($array[23],59,20),
                                             '{reporte_marketing-valor_precio_unidad_vendida}'=>substr($array[24],59,20),
                                             '{reporte_marketing-valor_total_costo_unidad_vendida}'=>substr($array[25],59,20),
                                             '{reporte_marketing-valor_margen_unidad_vendida}'=>substr($array[26],59,20));

            $reporte_inversion_array = array('{reporte_inversion-valor_capacidad_fabrica}'=>substr($array[29],54,10),
                                             '{reporte_inversion-valor_capacidad_fabrica1}'=>substr($array[29],65,20),
                                             '{reporte_inversion-valor_inversion_neta}'=>substr($array[30],54,10),
                                             '{reporte_inversion-valor_inversion_neta1}'=>substr($array[30],65,20),
                                             '{reporte_inversion-valor_prox_capacidad}'=>substr($array[32],54,10),
                                             '{reporte_inversion-valor_prox_capacidad1}'=>substr($array[32],65,20));

            $reporte_flujocaja_array = array('{reporte_flujocaja-valor_caja_inicial}'=>substr($array[36],19,21),
                                             '{reporte_flujocaja-valor_utilidad_neta}'=>substr($array[37],19,21),
                                             '{reporte_flujocaja-valor_depreciacion}'=>substr($array[38],19,21),
                                             '{reporte_flujocaja-valor_inversion_capital}'=>substr($array[39],19,21),
                                             '{reporte_flujocaja-valor_cambio_inventario}'=>substr($array[40],19,21),
                                             '{reporte_flujocaja-valor_prestamo_neto}'=>substr($array[41],19,21),
                                             '{reporte_flujocaja-valor_caja_final}'=>substr($array[43],19,21)); 

            $this->view->mostrar_reporte_compania_ajax($array_datos, $estado_ingreso_array, $reporte_operacion_array, 
                                                       $reporte_produccion_array, $reporte_marketing_array, $reporte_inversion_array, 
                                                       $reporte_flujocaja_array);
            
        }
    }

    function reportes_industria() {
        SessionHandler()->check_session();
        SessionHandler()->check_actualiza_contrasena();
        $this->model->competencia_id = 1;
        $this->model->get();
        $periodo = $this->model->periodo;

        $zona = $_SESSION["data-login-" . APP_ABREV]["equipo-zona"];
        $zona = str_pad($zona, 2, "0", STR_PAD_LEFT);

        $equipo_id = $_SESSION["data-login-" . APP_ABREV]["equipo-equipo_id"];
        $em = new Equipo();
        $em->equipo_id = $equipo_id;
        $em->get();
        $numero_equipo = $em->numero;

        $array_periodos = array();
        for ($i=0; $i <= $periodo; $i++) { 
            $array_temp = array('{periodo_id}'=>$i, '{periodo_text}'=>'Período ' . $i);
            $array_periodos[] = $array_temp;
        }
        
        $this->view->reportes_industria($this->model, $array_periodos);
    }

    function mostrar_reporte_industria_ajax($arg) {
        SessionHandler()->check_actualiza_contrasena();
        $periodo = $arg;
        $zona = $_SESSION["data-login-" . APP_ABREV]["equipo-zona"];
        $zona = str_pad($zona, 2, "0", STR_PAD_LEFT);

        $equipo_id = $_SESSION["data-login-" . APP_ABREV]["equipo-equipo_id"];
        $em = new Equipo();
        $em->equipo_id = $equipo_id;
        $em->get();
        $equipo_denominacion = $em->denominacion;
        $numero_equipo = $em->numero;
        $array_datos = array('{equipo-denominacion}'=>$equipo_denominacion, '{competencia-periodo}'=>$periodo);
        
        $archivo = URL_PRIVATE . "informeindustria/periodo{$periodo}/zona{$zona}/ind.lst";
        if(!file_exists($archivo)) {
            $this->view->sin_reporte_cargado_ajax($array_datos);
        } else {
            $archivo = fopen($archivo, "r");
            $i = 0;
            $array = array();
            while(!feof($archivo)) {
                $array[] = fgets($archivo);
                $i = $i + 1;
            }
            
            fclose($archivo);

            $unidades = array('{unidades-total_ordenes_valor}'=>substr($array[4],20,7), 
                              '{unidades-total_ordenes_cambio}'=>substr($array[4],27,7), 
                              '{unidades-total_produccion_valor}'=>substr($array[5],20,7), 
                              '{unidades-total_produccion_cambio}'=>substr($array[5],27,7), 
                              '{unidades-total_vendido_valor}'=>substr($array[6],20,7), 
                              '{unidades-total_vendido_cambio}'=>substr($array[6],27,7), 
                              '{unidades-capacidad_total_valor}'=>substr($array[7],20,7), 
                              '{unidades-capacidad_total_cambio}'=>substr($array[7],27,7), 
                              '{unidades-inventario_valor}'=>substr($array[8],20,7), 
                              '{unidades-inventario_cambio}'=>substr($array[8],27,7));

            $dolares = array('{dolares-ventas_industria_valor}'=>substr($array[4],62,7),
                             '{dolares-ventas_industria_cambio}'=>substr($array[4],69,7),
                             '{dolares-precio_promedio_valor}'=>substr($array[5],62,7),
                             '{dolares-precio_promedio_cambio}'=>substr($array[5],69,7),
                             '{dolares-total_produccion_valor}'=>substr($array[6],62,7),
                             '{dolares-total_produccion_cambio}'=>substr($array[6],69,7),
                             '{dolares-costo_promedio_pdun_valor}'=>substr($array[7],62,7),
                             '{dolares-costo_promedio_pdun_cambio}'=>substr($array[7],69,7),
                             '{dolares-total_costo_prom_valor}'=>substr($array[8],62,7),
                             '{dolares-total_costo_prom_cambio}'=>substr($array[8],69,7));
            
            $productividad = array('{productividad-empleo_valor}'=>substr($array[12],19,8),
                                   '{productividad-empleo_cambio}'=>substr($array[12],27,7),
                                   '{productividad-ventas_empleado_valor}'=>substr($array[13],19,8),
                                   '{productividad-ventas_empleado_cambio}'=>substr($array[13],27,7),
                                   '{productividad-unid_empleado_valor}'=>substr($array[14],19,8),
                                   '{productividad-unid_empleado_cambio}'=>substr($array[14],27,7),
                                   '{productividad-invers_capital_valor}'=>substr($array[15],19,8),
                                   '{productividad-invers_capital_cambio}'=>substr($array[15],27,7),
                                   '{productividad-capacidad_utiliz_valor}'=>substr($array[16],19,8),
                                   '{productividad-capacidad_utiliz_cambio}'=>substr($array[16],27,7));

            $economia = array('{economia-tasa_prima_valor}'=>substr($array[12],62,7),
                              '{economia-tasa_prima_cambio}'=>substr($array[12],69,7),
                              '{economia-prestamo_limite_valor}'=>substr($array[13],62,7),
                              '{economia-prestamo_limite_cambio}'=>substr($array[13],69,7),
                              '{economia-tasa_impuesto_valor}'=>substr($array[14],62,7),
                              '{economia-tasa_impuesto_cambio}'=>substr($array[14],69,7),
                              '{economia-imp_pag_periodo_pdun_valor}'=>substr($array[15],62,7),
                              '{economia-imp_pag_periodo_pdun_cambio}'=>substr($array[15],69,7),
                              '{economia-imp_pag_fecha_valor}'=>substr($array[16],62,7),
                              '{economia-imp_pag_fecha_cambio}'=>substr($array[16],69,7));

            //$tamanio = explode(trim($array[19]), "");

            $fila_equipos = $array[18];
            $fila_guiones = trim($array[19]);
            $fila_ventas = trim($array[20]);
            $fila_util = trim($array[21]);
            $fila_precio = trim($array[22]);
            $fila_ganret = trim($array[23]);
            $fila_unrepa = trim($array[24]);
            $fila_MPI = trim($array[25]);

            $array_guiones = explode(" ", $fila_guiones);
            $cant_equipos_zona = count($array_guiones);
            $array_size_celda_fila = array(); 
            for ($i=0; $i < $cant_equipos_zona; $i++) { 
                $array_size_celda_fila[] = strlen($array_guiones[$i]);
            }

            $pos = strpos($fila_guiones, '-');
            $texto_equipos = substr($fila_equipos, $pos, $array_size_celda_fila[0]);
            $texto_ventas = substr($fila_ventas, $pos, $array_size_celda_fila[0]);
            $texto_util = substr($fila_util, $pos, $array_size_celda_fila[0]);
            $texto_precio = substr($fila_precio, $pos, $array_size_celda_fila[0]);
            $texto_ganret = substr($fila_ganret, $pos, $array_size_celda_fila[0]);
            $texto_unrepa = substr($fila_unrepa, $pos, $array_size_celda_fila[0]);
            $texto_MPI = substr($fila_MPI, $pos, $array_size_celda_fila[0]);

            $array_zona = array();
            for ($i=0; $i < $cant_equipos_zona; $i++) { 
                $pos = strpos($fila_guiones, ' ', $pos);
                $pos = $pos + $array_size_celda_fila[$i];


                $array_equipos[] = trim(substr($fila_equipos, $pos, $array_size_celda_fila[$i]));
                $array_ventas[] = trim(substr($fila_ventas, $pos, $array_size_celda_fila[$i]));
                $array_util[] = trim(substr($fila_util, $pos, $array_size_celda_fila[$i]));
                $array_precio[] = trim(substr($fila_precio, $pos, $array_size_celda_fila[$i]));
                $array_ganret[] = trim(substr($fila_ganret, $pos, $array_size_celda_fila[$i]));
                $array_unrepa[] = trim(substr($fila_unrepa, $pos, $array_size_celda_fila[$i]));
                $array_MPI[] = trim(substr($fila_MPI, $pos, $array_size_celda_fila[$i]));
            }

            //$array_equipos = array_unshift($array_equipos, $texto_equipos);
            $array_equipos[] = array_unshift($array_equipos, ' ');
            $array_ventas[] = array_unshift($array_ventas, 'VENTAS');
            $array_util[] = array_unshift($array_util, 'UTIL');
            $array_precio[] = array_unshift($array_precio, 'PRECIO');
            $array_ganret[] = array_unshift($array_ganret, 'GANRET');
            $array_unrepa[] = array_unshift($array_unrepa, 'UNREPA');
            $array_MPI[] = array_unshift($array_MPI, 'MPI');
            $array_zona[] = $array_equipos;
            $array_zona[] = $array_ventas;
            $array_zona[] = $array_util;
            $array_zona[] = $array_precio;
            $array_zona[] = $array_ganret;
            $array_zona[] = $array_unrepa;
            $array_zona[] = $array_MPI;

            foreach ($array_zona as $clave=>$valor) array_pop($array_zona[$clave]); 
            $cant_equipos = count($array_zona[0]);
            $this->view->mostrar_reporte_industria_ajax($array_datos, $unidades, $dolares, $productividad, $economia, $array_zona, $cant_equipos);
        }
    }
}
?>