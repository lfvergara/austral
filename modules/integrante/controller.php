<?php
require_once "modules/integrante/model.php";
require_once "modules/integrante/view.php";
require_once "modules/infocontacto/model.php";


class IntegranteController {

	function __construct() {
		$this->model = new Integrante();
		$this->view = new IntegranteView();
	}
}
?>