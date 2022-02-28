<?php
require_once "modules/cliente/model.php";
require_once "modules/cliente/view.php";
require_once "modules/provincia/model.php";
require_once "modules/documentotipo/model.php";
require_once "modules/infocontacto/model.php";


class ClienteController {

	function __construct() {
		$this->model = new Cliente();
		$this->view = new ClienteView();
	}

	function panel() {
    	SessionHandler()->check_session();
		$this->view->panel();
	}

	function listar() {
		$select = "c.cliente_id AS ID, c.barrio AS BARRIO, pr.denominacion AS PROVINCIA, c.codigopostal AS CODPOSTAL, 
				   CONCAT(c.apellido, ' ', c.nombre) AS CLIENTE, CONCAT(dt.denominacion, ' ', c.documento) AS DOCUMENTO, 
				   CONCAT(c.barrio, ' - ', c.domicilio) AS DOMICILIO,
				   (SELECT ic.valor FROM infocontacto ic INNER JOIN infocontactocliente icc ON ic.infocontacto_id = icc.compositor WHERE 
				   icc.compuesto = c.cliente_id AND denominacion = 'Celular') AS CELULAR";
		$from = "cliente c INNER JOIN provincia pr ON c.provincia = pr.provincia_id INNER JOIN 
				 documentotipo dt ON c.documentotipo = dt.documentotipo_id";
		$cliente_collection = CollectorCondition()->get('Cliente', NULL, 4, $from, $select);
		$this->view->listar($cliente_collection);
	}

	function agregar() {
    	SessionHandler()->check_session();		
		$provincia_collection = Collector()->get('Provincia');
		$documentotipo_collection = Collector()->get('DocumentoTipo');
		$this->view->agregar($provincia_collection, $documentotipo_collection);
	}

	function consultar($arg) {
		SessionHandler()->check_session();		
		$this->model->cliente_id = $arg;
		$this->model->get();
		$this->view->consultar($this->model);
	}

	function editar($arg) {
		SessionHandler()->check_session();		
		$this->model->cliente_id = $arg;
		$this->model->get();
		$provincia_collection = Collector()->get('Provincia');
		$documentotipo_collection = Collector()->get('DocumentoTipo');
		$this->view->editar($provincia_collection, $documentotipo_collection, $this->model);
	}

	function guardar() {
		SessionHandler()->check_session();
		$this->model->apellido = strtoupper(filter_input(INPUT_POST, 'apellido'));	
		$this->model->nombre = strtoupper(filter_input(INPUT_POST, 'nombre'));	
		$this->model->documento = filter_input(INPUT_POST, 'documento');	
		$this->model->documentotipo = filter_input(INPUT_POST, 'documentotipo');	
		$this->model->provincia = filter_input(INPUT_POST, 'provincia');	
		$this->model->codigopostal = filter_input(INPUT_POST, 'codigopostal');	
		$this->model->barrio = strtoupper(filter_input(INPUT_POST, 'barrio'));	
		$this->model->latitud = strtoupper(filter_input(INPUT_POST, 'latitud'));	
		$this->model->longitud = strtoupper(filter_input(INPUT_POST, 'longitud'));	
		$this->model->domicilio = strtoupper(filter_input(INPUT_POST, 'domicilio'));	
		$this->model->observacion = strtoupper(filter_input(INPUT_POST, 'observacion'));	
		$this->model->save();
		$cliente_id = $this->model->cliente_id;

		$this->model = new Cliente();
		$this->model->cliente_id = $cliente_id;
		$this->model->get();

		$array_infocontacto = $_POST['infocontacto'];
		if (!empty($array_infocontacto)) {
			foreach ($array_infocontacto as $clave=>$valor) {
				$icm = new InfoContacto();
				$icm->denominacion = $clave;
				$icm->valor = $valor;
				$icm->save();
				$infocontacto_id = $icm->infocontacto_id;
				
				$icm = new InfoContacto();
				$icm->infocontacto_id = $infocontacto_id;
				$icm->get();

				$this->model->add_infocontacto($icm);
			}

			$iccm = new InfoContactoCliente($this->model);
			$iccm->save();
		}
	
		header("Location: " . URL_APP . "/cliente/editar/{$cliente_id}");
	}

	function actualizar() {
		SessionHandler()->check_session();
		$cliente_id = filter_input(INPUT_POST, 'cliente_id');
		$this->model->cliente_id = $cliente_id;
		$this->model->denominacion = filter_input(INPUT_POST, 'denominacion');	
		$this->model->documento = filter_input(INPUT_POST, 'documento');	
		$this->model->documentotipo = filter_input(INPUT_POST, 'documentotipo');	
		$this->model->provincia = filter_input(INPUT_POST, 'provincia');	
		$this->model->codigopostal = filter_input(INPUT_POST, 'codigopostal');	
		$this->model->barrio = filter_input(INPUT_POST, 'barrio');	
		$this->model->latitud = filter_input(INPUT_POST, 'latitud');	
		$this->model->longitud = filter_input(INPUT_POST, 'longitud');	
		$this->model->domicilio = filter_input(INPUT_POST, 'domicilio');	
		$this->model->observacion = filter_input(INPUT_POST, 'observacion');	
		$this->model->save();
		
		$this->model = new Cliente();
		$this->model->cliente_id = $cliente_id;
		$this->model->get();

		$array_infocontacto = $_POST['infocontacto'];
		if (!empty($array_infocontacto)) {
			foreach ($array_infocontacto as $clave=>$valor) {
				$icm = new InfoContacto();
				$icm->infocontacto_id = $clave;
				$icm->get();
				$icm->valor = $valor;
				$icm->save();
			}
		}
	
		header("Location: " . URL_APP . "/cliente/editar/{$cliente_id}");
	}

	function buscar() {
		$buscar = filter_input(INPUT_POST, 'buscar');
		$select = "c.cliente_id AS CLIENTE_ID, c.barrio AS BARRIO, pr.denominacion AS PROVINCIA, c.codigopostal AS CODPOSTAL, 
				   c.razon_social AS RAZON_SOCIAL, CONCAT(dt.denominacion, ' ', c.documento) AS DOCUMENTO";
		$from = "cliente c INNER JOIN provincia pr ON c.provincia = pr.provincia_id INNER JOIN 
				 documentotipo dt ON c.documentotipo = dt.documentotipo_id";
		$where = "c.denominacion LIKE '%{$buscar}%' OR c.documento LIKE '%{$buscar}%'";
		$cliente_collection = CollectorCondition()->get('Cliente', $where, 4, $from, $select);
		$this->view->listar($cliente_collection);
	}

	function verifica_documento_ajax($arg) {
		$select = "COUNT(*) AS DUPLICADO";
		$from = "cliente c";
		$where = "c.documento = {$arg}";
		$flag = CollectorCondition()->get('Cliente', $where, 4, $from, $select);
		print $flag[0]["DUPLICADO"];
	}
}
?>