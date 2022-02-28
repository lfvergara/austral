<?php


class EquipoDecision extends StandardObject {
	
	function __construct() {
		$this->equipodecision_id = 0;
                $this->precio = 0.00;
                $this->produccion = 0.00;
                $this->marketing = 0.00;
                $this->inversion = 0.00;
                $this->iandd = 0.00;
                $this->fecha = '';
                $this->hora = '';
                $this->periodo = 0;
        	$this->rondacompetencia_id = 0;
        	$this->equipo_id = 0;
        }
}
?>