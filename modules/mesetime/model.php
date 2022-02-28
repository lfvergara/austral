<?php


class MeseTime extends StandardObject {
	
	function __construct() {
		$this->mesetime_id = 0;
        $this->denominacion = '';
		$this->fecha = '';
		$this->hora = '';
		$this->ubicacion = 0;
	}
}
?>