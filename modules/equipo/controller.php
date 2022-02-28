<?php
require_once "modules/equipo/model.php";
require_once "modules/equipo/view.php";
require_once "modules/escuela/model.php";
require_once "modules/provincia/model.php";
require_once "modules/integrante/model.php";
require_once "modules/usuario/model.php";
require_once "modules/usuariodetalle/model.php";
require_once "modules/infocontacto/model.php";


class EquipoController {

	function __construct() {
		$this->model = new Equipo();
		$this->view = new EquipoView();
	}

	function panel() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$select = "e.equipo_id AS EQUID, e.zona AS ZONA, e.numero AS EQUIPO, e.denominacion AS NOMBRE, e.asesor AS CONTACTO,
				   (SELECT ic.valor FROM infocontacto ic INNER JOIN infocontactoequipo ice ON ic.infocontacto_id = ice.compositor WHERE ice.compuesto = e.equipo_id AND ic.denominacion = 'Email Oficial') AS EMAIL,
				   (SELECT ic.valor FROM infocontacto ic INNER JOIN infocontactoescuela ice ON ic.infocontacto_id = ice.compositor WHERE ice.compuesto = es.escuela_id AND ic.denominacion = 'Teléfono') AS TELEFONO,
				   es.denominacion AS INSTITUCION, p.denominacion AS PROVINCIA";
		$from = "equipo e INNER JOIN escuela es ON e.escuela = es.escuela_id INNER JOIN provincia p ON es.provincia = p.provincia_id";
		$equipo_collection = CollectorCondition()->get('Equipo', NULL, 4, $from, $select);
		$this->view->panel($equipo_collection);
	}

	function configurar_zonas() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$select = "e.equipo_id AS EQUID, e.zona AS ZONA, e.numero AS EQUIPO, e.denominacion AS NOMBRE, e.asesor AS CONTACTO,
				   (SELECT ic.valor FROM infocontacto ic INNER JOIN infocontactoequipo ice ON ic.infocontacto_id = ice.compositor WHERE ice.compuesto = e.equipo_id AND ic.denominacion = 'Email Oficial') AS EMAIL,
				   (SELECT ic.valor FROM infocontacto ic INNER JOIN infocontactoescuela ice ON ic.infocontacto_id = ice.compositor WHERE ice.compuesto = es.escuela_id AND ic.denominacion = 'Teléfono') AS TELEFONO,
				   es.denominacion AS INSTITUCION, p.denominacion AS PROVINCIA";
		$from = "equipo e INNER JOIN escuela es ON e.escuela = es.escuela_id INNER JOIN provincia p ON es.provincia = p.provincia_id";
		$equipo_collection = CollectorCondition()->get('Equipo', NULL, 4, $from, $select);
		$this->view->configurar_zonas($equipo_collection);
	}

	function guardar_configuracion_zona_conjunta() {
		SessionHandler()->check_session();
		$zona = filter_input(INPUT_POST, 'zona');
		$array_equipo_numero = $_POST['equipo_numero'];

		foreach ($array_equipo_numero as $equipo_id=>$numero) {
			$this->model = new Equipo();
			$this->model->equipo_id = $equipo_id;
			$this->model->get();
			$this->model->zona = $zona;
			$this->model->numero = $numero;
			$this->model->save();
		}

		header("Location: " . URL_APP . "/equipo/panel");
	}

	function agregar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$provincia_collection = Collector()->get('Provincia');
		$this->view->agregar($provincia_collection);
	}

	function guardar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$em = new Escuela();
		$em->denominacion = filter_input(INPUT_POST, "escuela_denominacion");
		$em->rector = filter_input(INPUT_POST, "rector");
		$em->asesor = filter_input(INPUT_POST, "escuela_asesor");
		$em->direccion = filter_input(INPUT_POST, "direccion");
		$em->ciudad = filter_input(INPUT_POST, "ciudad");
		$em->codigopostal = filter_input(INPUT_POST, "codigopostal");
		$em->provincia = filter_input(INPUT_POST, "provincia");
		$em->save();
		$escuela_id = $em->escuela_id;

		$em = new Escuela();
		$em->escuela_id = $escuela_id;
		$em->get();

		$contacto_escuela = $_POST['infocontacto_escuela'];
		foreach ($contacto_escuela as $clave=>$valor) {
			$icm = new InfoContacto();
			$icm->denominacion = $clave;
			$icm->valor = $valor;
			$icm->save();
			$infocontacto_id = $icm->infocontacto_id;
			
			$icm = new InfoContacto();
			$icm->infocontacto_id = $infocontacto_id;
			$icm->get();

			$em->add_infocontacto($icm);
		}

		$icesm = new InfoContactoEscuela($em);
		$icesm->save();

		$equipo_denominacion = filter_input(INPUT_POST, 'equipo_denominacion');
		$this->model->denominacion = $equipo_denominacion;
		$this->model->asesor = filter_input(INPUT_POST, 'equipo_asesor');
		$this->model->numero = 0;
		$this->model->zona = 0;
		$this->model->escuela = $escuela_id;
		$this->model->save();
		$equipo_id = $this->model->equipo_id;

		$this->model = new Equipo();
		$this->model->equipo_id = $equipo_id;
		$this->model->get();

		$contacto_equipo = $_POST['infocontacto_equipo'];
		foreach ($contacto_equipo as $clave=>$valor) {
			if ($clave == 'Email Oficial') $correoelectronico_usuario = $valor;

			$icm = new InfoContacto();
			$icm->denominacion = $clave;
			$icm->valor = $valor;
			$icm->save();
			$infocontacto_id = $icm->infocontacto_id;
			
			$icm = new InfoContacto();
			$icm->infocontacto_id = $infocontacto_id;
			$icm->get();

			$this->model->add_infocontacto($icm);
		}

		$icem = new InfoContactoEquipo($this->model);
		$icem->save();

		$usuario = filter_input(INPUT_POST, "usuario");
		$pass = strtolower($usuario) . "$1";
		$user = hash(ALGORITMO_USER, $usuario);
		$pass = hash(ALGORITMO_PASS, $pass);
		$token = hash(ALGORITMO_FINAL, $user . $pass);
		
		$udm = new UsuarioDetalle();
		$udm->nombre = $equipo_denominacion;
		$udm->apellido = "EQUIPO";
		$udm->correoelectronico = $correoelectronico_usuario;
		$udm->token = $token;
		$udm->save();
		$usuariodetalle_id = $udm->usuariodetalle_id;

		$um = new Usuario();
		$um->denominacion = $usuario;
		$um->nivel = 1;
		$um->equipo = $equipo_id;
		$um->actualiza_contrasena = 1;
		$um->usuariodetalle = $usuariodetalle_id;
		$um->configuracionmenu = 3;
		$um->save();
		$usuario_id = $um->usuario_id;

		$this->model = new Equipo();
		$this->model->equipo_id = $equipo_id;
		$this->model->get();
		$this->model->usuario_id = $usuario_id;
		$this->model->save();

		header("Location: " . URL_APP . "/equipo/editar/{$equipo_id}");
	}

	function editar_zona($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->equipo_id = $arg;
		$this->model->get();
		$this->view->editar_zona($this->model);
	}

	function guardar_zona() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->equipo_id = filter_input(INPUT_POST, 'equipo_id');
		$this->model->get();
		$this->model->zona = filter_input(INPUT_POST, 'zona');
		$this->model->numero = filter_input(INPUT_POST, 'numero');
		$this->model->save();
		header("Location: " . URL_APP . "/equipo/panel");
	}

	function editar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$equipo_id = $arg;
		$provincia_collection = Collector()->get('Provincia');
		$this->model->equipo_id = $equipo_id;
		$this->model->get();

		$select = "u.denominacion AS USUARIO, u.usuario_id AS USUARIO_ID";
		$from = "usuario u";
		$where = "u.equipo = {$equipo_id}";
		$usuario = CollectorCondition()->get('Usuario', $where, 4, $from, $select);
		$usuario_id = (is_array($usuario) AND !empty($usuario)) ? $usuario[0]['USUARIO_ID'] : '';
		$usuario_denominacion = (is_array($usuario) AND !empty($usuario)) ? $usuario[0]['USUARIO'] : '';
		$this->model->usuario_id = $usuario_id;
		$this->model->usuario = $usuario_denominacion;
		$this->view->editar($provincia_collection, $this->model);
	}

	function agregar_integrante($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$equipo_id = $arg;
		$this->view->agregar_integrante_ajax($equipo_id);
	}

	function editar_integrante($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$im = new Integrante();
		$im->integrante_id = $arg;
		$im->get();
		$this->view->editar_integrante_ajax($im);
	}

	function traer_equipo($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$this->model->equipo_id = $arg;
		$this->model->get();
		print_r($this->model);exit;
	}

	function actualizar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$equipo_id = filter_input(INPUT_POST, 'equipo_id');
		$infocontacto = $_POST['infocontacto'];
		$this->model->equipo_id = $equipo_id;
		$this->model->get();
		$this->model->denominacion = filter_input(INPUT_POST, 'denominacion');
		$this->model->asesor = filter_input(INPUT_POST, 'asesor');
		$this->model->numero = filter_input(INPUT_POST, 'numero');
		$this->model->zona = filter_input(INPUT_POST, 'zona');
		$this->model->save();

		$this->model = new Equipo();
		$this->model->equipo_id = $equipo_id;
		$this->model->get();
		$infocontacto_collection = $this->model->infocontacto_collection;
		foreach ($infocontacto_collection as $clave=>$valor) {
			$icm = new InfoContacto();
			$icm->infocontacto_id = $valor->infocontacto_id;
			$icm->delete();
		}

		$this->model->infocontacto_collection = array();
		foreach ($infocontacto as $clave=>$valor) {
			$icm = new InfoContacto();
			$icm->denominacion = $clave;
			$icm->valor = $valor;
			$icm->save();

			$infocontacto_id = $icm->infocontacto_id;
			$icm = new InfoContacto();
			$icm->infocontacto_id = $infocontacto_id;
			$icm->get();
			$this->model->add_infocontacto($icm);
		}

		$icem = new InfoContactoEquipo($this->model);
		$icem->save();

		$usuario_id = filter_input(INPUT_POST, 'usuario_id');
		$usuario = filter_input(INPUT_POST, 'usuario');
		$um = new Usuario();
		$um->usuario_id = $usuario_id;
		$um->get();
		$vieja_denominacion = $um->denominacion;
		if ($vieja_denominacion != $usuario) {
			$usuariodetalle_id = $um->usuariodetalle->usuariodetalle_id;
			$um->denominacion = $usuario;
			$um->save();
			
			$new_denominacion = hash(ALGORITMO_USER, $usuario);
			$password = strtolower($usuario) . "$1";
			$password = hash(ALGORITMO_PASS, $password);
			$token = hash(ALGORITMO_FINAL, $new_denominacion . $password);

			$udm = new UsuarioDetalle();
			$udm->usuariodetalle_id = $usuariodetalle_id;
			$udm->get();
			$udm->token = $token;
			$udm->save();
		}

		header("Location: " . URL_APP . "/equipo/editar/{$equipo_id}");
	}

	function actualizar_escuela() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$equipo_id = filter_input(INPUT_POST, 'equipo_id');
		$escuela_id = filter_input(INPUT_POST, 'escuela_id');
		$infocontacto = $_POST['infocontacto'];

		$em = new Escuela();
		$em->escuela_id = $escuela_id;
		$em->get();
		$em->denominacion = filter_input(INPUT_POST, 'denominacion');
		$em->rector = filter_input(INPUT_POST, 'rector');
		$em->asesor = filter_input(INPUT_POST, 'asesor');
		$em->direccion = filter_input(INPUT_POST, 'direccion');
		$em->ciudad = filter_input(INPUT_POST, 'ciudad');
		$em->codigopostal = filter_input(INPUT_POST, 'codigopostal');
		$em->provincia = filter_input(INPUT_POST, 'provincia');
		$em->save();

		$em = new Escuela();
		$em->escuela_id = $escuela_id;
		$em->get();
		$infocontacto_collection = $em->infocontacto_collection;
		foreach ($infocontacto_collection as $clave=>$valor) {
			$icm = new InfoContacto();
			$icm->infocontacto_id = $valor->infocontacto_id;
			$icm->delete();
		}

		$em->infocontacto_collection = array();
		foreach ($infocontacto as $clave=>$valor) {
			$icm = new InfoContacto();
			$icm->denominacion = $clave;
			$icm->valor = $valor;
			$icm->save();

			$infocontacto_id = $icm->infocontacto_id;
			$icm = new InfoContacto();
			$icm->infocontacto_id = $infocontacto_id;
			$icm->get();
			$em->add_infocontacto($icm);
		}

		$icem = new InfoContactoEscuela($em);
		$icem->save();
		header("Location: " . URL_APP . "/equipo/editar/{$equipo_id}");
	}

	function guardar_integrante() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$equipo_id = filter_input(INPUT_POST, 'equipo_id');
		$infocontacto = $_POST['infocontacto'];

		$im = new Integrante();
		$im->denominacion = filter_input(INPUT_POST, 'denominacion');
		$im->documento = filter_input(INPUT_POST, 'documento');
		$im->curso = filter_input(INPUT_POST, 'curso');
		$im->save();
		$integrante_id = $im->integrante_id;

		$im = new Integrante();
		$im->integrante_id = $integrante_id;
		$im->get();
		
		foreach ($infocontacto as $clave=>$valor) {
			$icm = new InfoContacto();
			$icm->denominacion = $clave;
			$icm->valor = $valor;
			$icm->save();

			$infocontacto_id = $icm->infocontacto_id;
			$icm = new InfoContacto();
			$icm->infocontacto_id = $infocontacto_id;
			$icm->get();
			$im->add_infocontacto($icm);
		}

		$icim = new InfoContactoIntegrante($im);
		$icim->save();

		$im = new Integrante();
		$im->integrante_id = $integrante_id;
		$im->get();

		$this->model->equipo_id = $equipo_id;
		$this->model->get();
		$this->model->add_integrante($im);

		$iem = new IntegranteEquipo($this->model);
		$iem->save();

		header("Location: " . URL_APP . "/equipo/editar/{$equipo_id}");
	}

	function actualizar_integrante() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$equipo_id = filter_input(INPUT_POST, 'equipo_id');
		$integrante_id = filter_input(INPUT_POST, 'integrante_id');
		$infocontacto = $_POST['infocontacto'];

		$im = new Integrante();
		$im->integrante_id = $integrante_id;
		$im->get();
		$im->denominacion = filter_input(INPUT_POST, 'denominacion');
		$im->documento = filter_input(INPUT_POST, 'documento');
		$im->curso = filter_input(INPUT_POST, 'curso');
		$im->save();

		$im = new Integrante();
		$im->integrante_id = $integrante_id;
		$im->get();
		$infocontacto_collection = $im->infocontacto_collection;
		foreach ($infocontacto_collection as $clave=>$valor) {
			$icm = new InfoContacto();
			$icm->infocontacto_id = $valor->infocontacto_id;
			$icm->delete();
		}

		$im->infocontacto_collection = array();
		foreach ($infocontacto as $clave=>$valor) {
			$icm = new InfoContacto();
			$icm->denominacion = $clave;
			$icm->valor = $valor;
			$icm->save();

			$infocontacto_id = $icm->infocontacto_id;
			$icm = new InfoContacto();
			$icm->infocontacto_id = $infocontacto_id;
			$icm->get();
			$im->add_infocontacto($icm);
		}

		$icim = new InfoContactoIntegrante($im);
		$icim->save();
		header("Location: " . URL_APP . "/equipo/editar/{$equipo_id}");
	}

	function eliminar($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$equipo_id = $arg;
		$this->model->equipo_id = $equipo_id;
		$this->model->get();
		$escuela_id = $this->model->escuela->escuela_id;

		$integrante_collection = $this->model->integrante_collection;
		$infocontacto_equipo_collection = $this->model->infocontacto_collection;

		foreach ($infocontacto_equipo_collection as $infocontacto) {
			$icm = new InfoContacto();
			$icm->infocontacto_id = $infocontacto->infocontacto_id;
			$icm->delete();
		}

		foreach ($integrante_collection as $clave=>$valor) {
			$integrante_id = $valor->integrante_id;
			$infocontacto_integrante_collection = $valor->infocontacto_collection;
			foreach ($infocontacto_integrante_collection as $infocontacto) {
				$icm = new InfoContacto();
				$icm->infocontacto_id = $infocontacto->infocontacto_id;
				$icm->delete();
			}

			$im = new Integrante();
			$im->integrante_id = $integrante_id;
			$im->delete();
		}

		$em = new Escuela();
		$em->escuela_id = $escuela_id;
		$em->get();
		$infocontacto_escuela_collection = $em->infocontacto_collection;

		foreach ($infocontacto_escuela_collection as $infocontacto) {
			$icm = new InfoContacto();
			$icm->infocontacto_id = $infocontacto->infocontacto_id;
			$icm->delete();
		}

		$em->delete();
		$this->model->delete();
		header("Location: " . URL_APP . "/equipo/panel");
	}

	function eliminar_integrante($arg) {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		$integrante_id = $arg;
		$select = "ie.compuesto AS ID";
		$from = "integranteequipo ie";
		$where = "ie.compositor = {$integrante_id} LIMIT 1";
		$equipo = CollectorCondition()->get('Equipo', $where, 4, $from, $select);
		$equipo_id = (is_array($equipo) AND !empty($equipo)) ? $equipo[0]["ID"] : 0;
		
		if ($equipo_id != 0) {
			$im = new Integrante();
			$im->integrante_id = $integrante_id;
			$im->get();
			$infocontacto_collection = $im->infocontacto_collection;
			foreach ($infocontacto_collection as $infocontacto) {
				$icm = new InfoContacto();
				$icm->infocontacto_id = $infocontacto->infocontacto_id;
				$icm->delete();
			}

			$im->delete();
			header("Location: " . URL_APP . "/equipo/editar/{$equipo_id}");
		} else {
			header("Location: " . URL_APP . "/equipo/panel");
		}
	}

	function descargar() {
		SessionHandler()->check_session();
		SessionHandler()->checkPerfil('3,9');
		require_once "tools/excelreport.php";
		$select = "e.equipo_id AS EQUID, e.zona AS ZONA, e.numero AS EQUIPO, e.denominacion AS NOMBRE, e.asesor AS CONTACTO,
				   (SELECT ic.valor FROM infocontacto ic INNER JOIN infocontactoequipo ice ON ic.infocontacto_id = ice.compositor WHERE ice.compuesto = e.equipo_id AND ic.denominacion = 'Email Oficial') AS EMAIL,
				   (SELECT ic.valor FROM infocontacto ic INNER JOIN infocontactoescuela ice ON ic.infocontacto_id = ice.compositor WHERE ice.compuesto = es.escuela_id AND ic.denominacion = 'Teléfono') AS TELEFONO,
				   es.denominacion AS INSTITUCION, p.denominacion AS PROVINCIA, es.ciudad AS LOCALIDAD, es.codigopostal AS CODPOS,
				   es.direccion AS DIRECCION, es.rector AS DIRECTOR, es.asesor AS ASESOR";
		$from = "equipo e INNER JOIN escuela es ON e.escuela = es.escuela_id INNER JOIN provincia p ON es.provincia = p.provincia_id";
		$equipo_collection = CollectorCondition()->get('Equipo', NULL, 4, $from, $select);

		if (is_array($equipo_collection) AND !empty($equipo_collection)) {
			$integrante_ids = array();
			$max_cant_integrantes = 0;
			foreach ($equipo_collection as $clave=>$valor) {				
				$equipo_id = $valor["EQUID"];
				$select = "ice.compositor AS INTREGRANTE_ID";
				$from = "integranteequipo ice";
				$where = "ice.compuesto = {$equipo_id}";
				$integrante_collection = CollectorCondition()->get('Equipo', $where, 4, $from, $select);
				$i = 1;
				if (is_array($integrante_collection) AND !empty($integrante_collection)) {
					$cant_integrantes = count($integrante_collection);
					foreach ($integrante_collection as $integrante) {
						$integrante_id = $integrante["INTREGRANTE_ID"];
						$im = new Integrante();
						$im->integrante_id = $integrante_id;
						$im->get();

						$equipo_collection[$clave]["INTEGRANTE_{$i}"] = $im->denominacion;
						$equipo_collection[$clave]["DNI_{$i}"] = $im->documento;
						$equipo_collection[$clave]["CURSO_{$i}"] = $im->curso;
						$equipo_collection[$clave]["EMAIL_{$i}"] = $im->infocontacto_collection[0]->valor;
						$equipo_collection[$clave]["TELÉFONO_{$i}"] = $im->infocontacto_collection[1]->valor;

						$i = $i + 1;

					}

					$max_cant_integrantes = ($cant_integrantes > $max_cant_integrantes) ? $cant_integrantes : $max_cant_integrantes;
				}
			}
		}



		$subtitulo = "EQUIPOS";
		$array_encabezados = array('ID', 'ZONA', 'EQUIPO', 'NOMBRE', 'CONTACTO', 'EMAIL OFICIAL', 'TELÉFONO', 'INSTITUCIÓN', 'PROVINCIA',
								   'LOCALIDAD', 'COD POSTAL', 'DIRECCIÓN', 'DIRECTOR', 'ASESOR');

		if ($max_cant_integrantes > 0) {
			for ($i=1; $i <= $max_cant_integrantes; $i++) { 
				$array_encabezados[] = "INTEGRANTE{$i}";
				$array_encabezados[] = "DNI";
				$array_encabezados[] = "EMAIL";
				$array_encabezados[] = "TELÉFONO";
				$array_encabezados[] = "CURSO";
			}
		}

		$array_exportacion = array();
		$array_exportacion[] = $array_encabezados;
		foreach ($equipo_collection as $equipo) {
			$array_temp = array();
			foreach ($equipo as $clave=>$valor) $array_temp[] = $valor;
			$array_exportacion[] = $array_temp;
		}

		ExcelReport()->extraer_informe($array_exportacion, $subtitulo);
	}
}
?>