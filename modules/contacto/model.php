<?php


class Contacto extends StandardObject {
	
	function __construct() {
		$this->contacto_id = 0;
        $this->denominacion = '';
        $this->correoelectronico = '';
        $this->equipo = '';
        $this->mensaje = '';
		$this->fecha = '';
		$this->hora = '';
	}
}
?>