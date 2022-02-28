<?php


class InformeCompania extends StandardObject {
	
	function __construct() {
		$this->informecompania_id = 0;
        $this->periodo = 0;
        $this->zona = 0;
		$this->fecha = '';
		$this->hora = '';
		$this->equipo_id = 0;
	}
}
?>