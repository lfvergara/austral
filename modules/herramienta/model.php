<?php


class Herramienta extends StandardObject {
	
	function __construct() {
		$this->herramienta_id = 0;
        $this->denominacion = '';
        $this->contenido = '';
		$this->fecha = '';
		$this->hora = '';
		$this->ubicacion = 0;
	}
}
?>