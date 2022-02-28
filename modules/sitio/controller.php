<?php
require_once "modules/sitio/view.php";
require_once "modules/provincia/model.php";
require_once "modules/escuela/model.php";
require_once "modules/infocontacto/model.php";
require_once "modules/equipo/model.php";
require_once "modules/usuario/model.php";
require_once "modules/usuariodetalle/model.php";
require_once "modules/integrante/model.php";
require_once "modules/competencia/model.php";


class SitioController {

	function __construct() {
		$this->view = new SitioView();
	}

	function inscripcion() {
		$provincia_collection = Collector()->get('Provincia');
		$cm = new Competencia();	
		$cm->competencia_id = 1;
		$cm->get();
		$this->view->inscripcion($provincia_collection, $cm);
	}

	function continuar_inscripcion() {
		$cm = new Competencia();	
		$cm->competencia_id = 1;
		$cm->get();
		$datos_array = array('{equipo_denominacion}'=>filter_input(INPUT_POST, 'equipo_denominacion'),
							 '{usuario}'=>filter_input(INPUT_POST, 'usuario'),
							 '{equipo_asesor}'=>filter_input(INPUT_POST, 'equipo_asesor'),
							 '{equipo_email_oficial}'=>filter_input(INPUT_POST, 'equipo_email_oficial'),
							 '{equipo_email_alternativo}'=>filter_input(INPUT_POST, 'equipo_email_alternativo'),
							 '{escuela_denominacion}'=>filter_input(INPUT_POST, 'escuela_denominacion'),
							 '{rector}'=>filter_input(INPUT_POST, 'rector'),
							 '{escuela_asesor}'=>filter_input(INPUT_POST, 'escuela_asesor'),
							 '{provincia}'=>filter_input(INPUT_POST, 'provincia'),
							 '{direccion}'=>filter_input(INPUT_POST, 'direccion'),
							 '{ciudad}'=>filter_input(INPUT_POST, 'ciudad'),
							 '{escuela_telefono}'=>filter_input(INPUT_POST, 'escuela_telefono'),
							 '{escuela_email}'=>filter_input(INPUT_POST, 'escuela_email'),
							 '{codigopostal}'=>filter_input(INPUT_POST, 'codigopostal'));

		$this->view->continuar_inscripcion($datos_array, $cm);
	}

	function finalizar_inscripcion() {
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
		$eqm = new Equipo();
		$eqm->denominacion = $equipo_denominacion;
		$eqm->asesor = filter_input(INPUT_POST, 'equipo_asesor');
		$eqm->numero = 0;
		$eqm->zona = 0;
		$eqm->escuela = $escuela_id;
		$eqm->save();
		$equipo_id = $eqm->equipo_id;

		$eqm = new Equipo();
		$eqm->equipo_id = $equipo_id;
		$eqm->get();

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

			$eqm->add_infocontacto($icm);
		}

		$icem = new InfoContactoEquipo($eqm);
		$icem->save();
		
		$usuario = filter_input(INPUT_POST, "usuario");
		$contrasena = $usuario . "$1";
		$user = hash(ALGORITMO_USER, $usuario);
		$pass = hash(ALGORITMO_PASS, $contrasena);
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

		$eqm = new Equipo();
		$eqm->equipo_id = $equipo_id;
		$eqm->get();
		$eqm->usuario_id = $usuario_id;
		$eqm->save();

		// AGREGAR INTEGRANTE
		for ($i=1; $i < 6; $i++) { 
			$int_denominacion = filter_input(INPUT_POST, "int{$i}_denominacion");
			$int_documento = filter_input(INPUT_POST, "int{$i}_documento");
			$int_curso = filter_input(INPUT_POST, "int{$i}_curso");
			$int_telefono = filter_input(INPUT_POST, "int{$i}_telefono");
			$int_email = filter_input(INPUT_POST, "int{$i}_email");

			if ($int_denominacion != '' AND $int_documento =! '') {
				$im = new Integrante();
				$im->denominacion = $int_denominacion;
				$im->documento = $int_documento;
				$im->curso = $int_curso;
				$im->save();
				$integrante_id = $im->integrante_id;
				
				$im = new Integrante();
				$im->integrante_id = $integrante_id;
				$im->get();

				$ticm = new InfoContacto();
				$ticm->denominacion = 'Teléfono';
				$ticm->valor = $int_telefono;
				$ticm->save();
				$infocontacto_id = $ticm->infocontacto_id;

				$ticm = new InfoContacto();
				$ticm->infocontacto_id = $infocontacto_id;
				$ticm->get();

				$micm = new InfoContacto();
				$micm->denominacion = 'Email';
				$micm->valor = $int_email;
				$micm->save();
				$infocontacto_id = $micm->infocontacto_id;

				$micm = new InfoContacto();
				$micm->infocontacto_id = $infocontacto_id;
				$micm->get();

				$im->add_infocontacto($ticm);
				$im->add_infocontacto($micm);

				$icim = new InfoContactoIntegrante($im);
				$icim->save();

				$im = new Integrante();
				$im->integrante_id = $integrante_id;
				$im->get();
				
				$eqmf = new Equipo();
				$eqmf->equipo_id = $equipo_id;
				$eqmf->get();
				$eqmf->add_integrante($im);

				$iem = new IntegranteEquipo($eqmf);
				$iem->save();

				$destino = $correoelectronico_usuario;
				$array_datos = array("{usuario}"=>$usuario, "{contrasena}"=>$contrasena);
				$emailHelper = new EmailHelper();
				$emailHelper->informa_acceso($destino, $array_datos);	
			}		
		}

		header("Location: " . URL_APP . "/sitio/home");
	}

	function generar_usuario($arg) {
		$usuario = $arg;
		$select = "u.usuario_id";
		$from = "usuario u";
		$where = "u.denominacion = '{$usuario}'";
		$temp_usuario = CollectorCondition()->get('Usuario', $where, 4, $from, $select);
		if (is_array($temp_usuario) AND !empty($temp_usuario)) {
			$usuario = $usuario . date('i') . date('s');
		}

		print $usuario;
	}
}
?>