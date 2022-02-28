<?php
# Ambiente del sistema
const AMBIENTE = "desa";
const SO_UNIX = true;

# Credenciales para la conexión con la base de datos MySQL
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '2553digital29';
const DB_NAME = 'austral.prod';


# Algoritmos utilizados para la encriptación de credenciales
# para el registro y acceso de usuarios del sistema
const ALGORITMO_USER = 'crc32';
const ALGORITMO_PASS = 'sha512';
const ALGORITMO_FINAL = 'md5';


# Direcciones a recursos estáticos de interfaz gráfica
const TEMPLATE = "static/template.html";
if (SO_UNIX == true) {
	define('URL_APP', "");
	define('URL_STATIC', "/static/template/");
	
	# Directorio private del sistema
	$url_private = "/var/www/austral/private/";
	define('URL_PRIVATE', $url_private);
	ini_set("include_path", URL_PRIVATE);
} else {
	define('URL_APP', "/austral");
	define('URL_STATIC', "/austral/static/template/");

	# Directorio private del sistema
	$url_private = "c:/australFiles/private/";
	define('URL_PRIVATE', $url_private);
	ini_set("include_path", URL_PRIVATE);
}

# Configuración estática del sistema
const APP_TITTLE = "Austral";
const APP_VERSION = "";
const APP_ABREV = "austral";
const LOGIN_URI = "/usuario/login";
const DEFAULT_MODULE = "usuario";
const DEFAULT_ACTION = "perfil";

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
ini_set('include_path', DOCUMENT_ROOT);

session_start();
$session_vars = array('login'=>false);
foreach($session_vars as $var=>$value) {
    if(!isset($_SESSION[$var])) $_SESSION[$var] = $value;
}
?>
