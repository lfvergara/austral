<?php


class Cronograma extends StandardObject {
	
	function __construct() {
		$this->cronograma_id = 0;
        $this->denominacion = '';
        $this->contenido = '';
		$this->fecha = '';
		$this->hora = '';
		$this->ubicacion = 0;
	}
}
?>