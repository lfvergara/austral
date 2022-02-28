<?php


class Integrante extends StandardObject {
	
	function __construct() {
		$this->integrante_id = 0;
        $this->denominacion = '';
        $this->documento = 0;
        $this->curso = '';
        $this->infocontacto_collection = array();
	}

	function add_infocontacto(InfoContacto $infocontacto) {
        $this->infocontacto_collection[] = $infocontacto;
    }
}

class InfoContactoIntegrante {
    
    function __construct(Integrante $integrante=null) {
        $this->infocontactointegrante_id = 0;
        $this->compuesto = $integrante;
        $this->compositor = $integrante->infocontacto_collection;
    }

    function get() {
        $sql = "SELECT compositor FROM infocontactointegrante WHERE compuesto=?";
        $datos = array($this->compuesto->integrante_id);
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
        $sql = "INSERT INTO infocontactointegrante (compuesto, compositor)
                VALUES ";
        foreach($this->compositor as $infocontacto) {
            $tuplas[] = "(?, ?)";
            $datos[] = $this->compuesto->integrante_id;
            $datos[] = $infocontacto->infocontacto_id;
        }
        $sql .= implode(', ', $tuplas);
        execute_query($sql, $datos);
    }

    function destroy() {
        $sql = "DELETE FROM infocontactointegrante WHERE compuesto=?";
        $datos = array($this->compuesto->integrante_id);
        execute_query($sql, $datos);
    }
}
?>