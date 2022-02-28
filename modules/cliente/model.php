<?php
require_once "modules/provincia/model.php";
require_once "modules/documentotipo/model.php";
require_once "modules/infocontacto/model.php";


class Cliente extends StandardObject {
	
	function __construct(Provincia $provincia=NULL, DocumentoTipo $documentotipo=NULL) {
		$this->cliente_id = 0;
        $this->apellido = '';
        $this->nombre = '';
		$this->documento = 0;
        $this->domicilio = '';
        $this->codigopostal = '';
        $this->barrio = '';
        $this->latitud = '';
        $this->longitud = '';
		$this->observacion = '';
        $this->provincia = $provincia;
        $this->documentotipo = $documentotipo;
        $this->infocontacto_collection = array();
	}

	function add_infocontacto(InfoContacto $infocontacto) {
        $this->infocontacto_collection[] = $infocontacto;
    }
}

class InfoContactoCliente {
    
    function __construct(Cliente $cliente=null) {
        $this->infocontactocliente_id = 0;
        $this->compuesto = $cliente;
        $this->compositor = $cliente->infocontacto_collection;
    }

    function get() {
        $sql = "SELECT compositor FROM infocontactocliente WHERE compuesto=?";
        $datos = array($this->compuesto->cliente_id);
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
        $sql = "INSERT INTO infocontactocliente (compuesto, compositor)
                VALUES ";
        foreach($this->compositor as $infocontacto) {
            $tuplas[] = "(?, ?)";
            $datos[] = $this->compuesto->cliente_id;
            $datos[] = $infocontacto->infocontacto_id;
        }
        $sql .= implode(', ', $tuplas);
        execute_query($sql, $datos);
    }

    function destroy() {
        $sql = "DELETE FROM infocontactocliente WHERE compuesto=?";
        $datos = array($this->compuesto->cliente_id);
        execute_query($sql, $datos);
    }
}
?>