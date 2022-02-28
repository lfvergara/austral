<?php


class Comentario extends StandardObject {
	
	function __construct() {
		$this->comentario_id = 0;
        $this->denominacion = '';
        $this->correoelectronico = '';
        $this->equipo = '';
        $this->contenido = '';
        $this->fecha = '';
        $this->hora = '';
        $this->activo = 0;
	}
}
?>