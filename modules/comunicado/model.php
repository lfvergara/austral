<?php


class Comunicado extends StandardObject {
	
	function __construct() {
		$this->comunicado_id = 0;
        $this->denominacion = '';
        $this->contenido = '';
		$this->fecha = '';
		$this->hora = '';
		$this->ubicacion = 0;
	}
}
?>