<?php
require_once "modules/provincia/model.php";
require_once "modules/infocontacto/model.php";


class Escuela extends StandardObject {
	
	function __construct(Provincia $provincia=NULL) {
		$this->escuela_id = 0;
        $this->denominacion = '';
        $this->rector = '';
		$this->asesor = '';
        $this->direccion = '';
        $this->ciudad = '';
        $this->codigopostal = '';
        $this->provincia = $provincia;
        $this->infocontacto_collection = array();
	}

	function add_infocontacto(InfoContacto $infocontacto) {
        $this->infocontacto_collection[] = $infocontacto;
    }
}

class InfoContactoEscuela {
    
    function __construct(Escuela $escuela=null) {
        $this->infocontactoescuela_id = 0;
        $this->compuesto = $escuela;
        $this->compositor = $escuela->infocontacto_collection;
    }

    function get() {
        $sql = "SELECT compositor FROM infocontactoescuela WHERE compuesto=?";
        $datos = array($this->compuesto->escuela_id);
        $resultados = execute_query($sql, $datos);
        if($resultados){
            foreach($resultados as $array) {
                $obj = new InfoContacto();
                $obj->infocontacto_id = $array['compositor'];
                $obj->get();
                $this->compuesto->add_infocontacto($obj);
            }
        }
    }

    function save() {
        $this->destroy();
        $tuplas = array();
        $datos = array();
        $sql = "INSERT INTO infocontactoescuela (compuesto, compositor)
                VALUES ";
        foreach($this->compositor as $infocontacto) {
            $tuplas[] = "(?, ?)";
            $datos[] = $this->compuesto->escuela_id;
            $datos[] = $infocontacto->infocontacto_id;
        }
        $sql .= implode(', ', $tuplas);
        execute_query($sql, $datos);
    }

    function destroy() {
        $sql = "DELETE FROM infocontactoescuela WHERE compuesto=?";
        $datos = array($this->compuesto->escuela_id);
        execute_query($sql, $datos);
    }
}
?>