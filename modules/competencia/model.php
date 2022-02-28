<?php
require_once "modules/rondacompetencia/model.php";
require_once "modules/estadocompetencia/model.php";
require_once "modules/configuraciondecision/model.php";


class Competencia extends StandardObject {

	function __construct(RondaCompetencia $rondacompetencia=NULL, EstadoCompetencia $estadocompetencia=NULL, 
                         ConfiguracionDecision $configuraciondecision=NULL) {
    	$this->competencia_id = 0;
        $this->denominacion = '';
        $this->administrador = '';
        $this->periodo = 0;
        $this->maximo_equipos = 0;
        $this->activo_equipos = 0;
        $this->activo_reportes = 0;
        $this->fecha_inicio_inscripcion = '';
        $this->fecha_fin_inscripcion = '';
        $this->correoelectronico = '';
        $this->rondacompetencia = $rondacompetencia;
        $this->estadocompetencia = $estadocompetencia;
        $this->configuraciondecision = $configuraciondecision;
    }
}
?>