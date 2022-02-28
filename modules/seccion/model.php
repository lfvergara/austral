<?php


class Seccion extends StandardObject {
	
	function __construct() {
		$this->seccion_id = 0;
        $this->denominacion = '';
        $this->contenido = '';
		$this->imagen = '';
	}
}
?>