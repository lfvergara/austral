<?php
require_once "modules/comentario/model.php";


class Foro extends StandardObject {
	
	function __construct() {
		$this->foro_id = 0;
        $this->denominacion = '';
        $this->correoelectronico = '';
        $this->equipo = '';
        $this->contenido = '';
		$this->fecha = '';
		$this->hora = '';
		$this->activo = 0;
		$this->comentario_collection = array();
	}

	function add_comentario(Comentario $comentario) {
        $this->comentario_collection[] = $comentario;
    }
}

class ComentarioForo {
    
    function __construct(Foro $foro=null) {
        $this->comentarioforo_id = 0;
        $this->compuesto = $foro;
        $this->compositor = $foro->comentario_collection;
    }

    function get() {
        $sql = "SELECT compositor FROM comentarioforo WHERE compuesto=?";
        $datos = array($this->compuesto->foro_id);
        $resultados = execute_query($sql, $datos);
        if($resultados){
            foreach($resultados as $array) {
                $obj = new Comentario();
                $obj->comentario_id = $array['compositor'];
                $obj->get();
                $this->compuesto->add_comentario($obj);
            }
        }
    }

    function save() {
        $this->destroy();
        $tuplas = array();
        $datos = array();
        $sql = "INSERT INTO comentarioforo (compuesto, compositor)
                VALUES ";
        foreach($this->compositor as $comentario) {
            $tuplas[] = "(?, ?)";
            $datos[] = $this->compuesto->foro_id;
            $datos[] = $comentario->comentario_id;
        }
        $sql .= implode(', ', $tuplas);
        execute_query($sql, $datos);
    }

    function destroy() {
        $sql = "DELETE FROM comentarioforo WHERE compuesto=?";
        $datos = array($this->compuesto->foro_id);
        execute_query($sql, $datos);
    }
}
?>