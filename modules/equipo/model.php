<?php
require_once "modules/escuela/model.php";
require_once "modules/integrante/model.php";
require_once "modules/infocontacto/model.php";


class Equipo extends StandardObject {
	
	function __construct(Escuela $escuela=NULL) {
		$this->equipo_id = 0;
        $this->denominacion = '';
        $this->asesor = '';
        $this->numero = 0;
        $this->zona = 0;
        $this->escuela = $escuela;
        $this->integrante_collection = array();
        $this->infocontacto_collection = array();
	}

	function add_integrante(Integrante $integrante) {
        $this->integrante_collection[] = $integrante;
    }

    function add_infocontacto(InfoContacto $infocontacto) {
        $this->infocontacto_collection[] = $infocontacto;
    }
}

class IntegranteEquipo {
    
    function __construct(Equipo $equipo=null) {
        $this->integranteequipo_id = 0;
        $this->compuesto = $equipo;
        $this->compositor = $equipo->integrante_collection;
    }

    function get() {
        $sql = "SELECT compositor FROM integranteequipo WHERE compuesto=?";
        $datos = array($this->compuesto->equipo_id);
        $resultados = execute_query($sql, $datos);
        if($resultados){
            foreach($resultados as $array) {
                $obj = new Integrante();
                $obj->integrante_id = $array['compositor'];
                $obj->get();
                $this->compuesto->add_integrante($obj);
            }
        }
    }

    function save() {
        $this->destroy();
        $tuplas = array();
        $datos = array();
        $sql = "INSERT INTO integranteequipo (compuesto, compositor)
                VALUES ";
        foreach($this->compositor as $integrante) {
            $tuplas[] = "(?, ?)";
            $datos[] = $this->compuesto->equipo_id;
            $datos[] = $integrante->integrante_id;
        }
        $sql .= implode(', ', $tuplas);
        execute_query($sql, $datos);
    }

    function destroy() {
        $sql = "DELETE FROM integranteequipo WHERE compuesto=?";
        $datos = array($this->compuesto->equipo_id);
        execute_query($sql, $datos);
    }
}

class InfoContactoEquipo {
    
    function __construct(Equipo $equipo=null) {
        $this->infocontactoequipo_id = 0;
        $this->compuesto = $equipo;
        $this->compositor = $equipo->infocontacto_collection;
    }

    function get() {
        $sql = "SELECT compositor FROM infocontactoequipo WHERE compuesto=?";
        $datos = array($this->compuesto->equipo_id);
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
        $sql = "INSERT INTO infocontactoequipo (compuesto, compositor)
                VALUES ";
        foreach($this->compositor as $infocontacto) {
            $tuplas[] = "(?, ?)";
            $datos[] = $this->compuesto->equipo_id;
            $datos[] = $infocontacto->infocontacto_id;
        }
        $sql .= implode(', ', $tuplas);
        execute_query($sql, $datos);
    }

    function destroy() {
        $sql = "DELETE FROM infocontactoequipo WHERE compuesto=?";
        $datos = array($this->compuesto->equipo_id);
        execute_query($sql, $datos);
    }
}
?>