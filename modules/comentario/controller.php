<?php
require_once "modules/comentario/model.php";
require_once "modules/comentario/view.php";


class ComentarioController {

	function __construct() {
		$this->model = new Comentario();
		$this->view = new ComentarioView();
	}
}
?>