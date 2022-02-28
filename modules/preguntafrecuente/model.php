<?php


class PreguntaFrecuente extends StandardObject {
	
	function __construct() {
		$this->preguntafrecuente_id = 0;
        $this->pregunta = '';
        $this->contenido = '';
		$this->fecha = '';
		$this->hora = '';
		$this->ubicacion = 0;
	}
}
?>