<?php


class ConfiguracionDecision extends StandardObject {
	
	function __construct() {
		$this->configuraciondecision_id = 0;
        $this->activo = 0;
        $this->texto = '';
		$this->precio_minimo = 0.00;
		$this->precio_maximo = 0.00;
		$this->produccion_minimo = 0.00;
		$this->produccion_maximo = 0.00;
		$this->marketing_minimo = 0.00;
		$this->marketing_maximo = 0.00;
		$this->inversion_minimo = 0.00;
		$this->inversion_maximo = 0.00;
		$this->iandd_minimo = 0.00;
		$this->iandd_maximo = 0.00;
	}
}
?>