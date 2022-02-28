<?php
require_once "modules/configuraciondecision/model.php";
require_once "modules/configuraciondecision/view.php";


class ConfiguracionDecisionController {

	function __construct() {
		$this->model = new ConfiguracionDecision();
		$this->view = new ConfiguracionDecisionView();
	}
}
?>