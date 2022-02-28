<?php
require_once "modules/escuela/model.php";
require_once "modules/escuela/view.php";
require_once "modules/provincia/model.php";
require_once "modules/infocontacto/model.php";


class EscuelaController {

	function __construct() {
		$this->model = new Escuela();
		$this->view = new EscuelaView();
	}
}
?>