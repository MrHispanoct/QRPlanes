<?php
/**
 * Clase para el modelo que representa a la tabla "player".
 */
require_once 'src/response.php';
require_once 'src/database.php';
require_once 'src/authModel.php';

/*CORS headers*/
//include_once 'CORS.php';
/*CORS Preflight OPTIONS request*/
/*if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {    

	return http_response_code(204);;    
}*/

class Usuario extends Database
{
	/**
	 * Atributo que indica la tabla asociada a la clase del modelo
	 */
	private $table = 'usuario';

	/**
	 * Array con los campos de la tabla que se pueden usar como filtro para recuperar registros
	 */
	private $allowedConditions_get = array(
		'id',
  		'username',
  		'password',
  		'token',
  		'nombres',
  		'perfil',
  		'page'
	);

	/**
	 * Array con los campos de la tabla que se pueden proporcionar para insertar registros
	 */
	private $allowedConditions_insert = array(
		'username',
		'password',
		'nombres',
		'perfil'
	);

	/**
	 * Array con los campos de la tabla que se pueden proporcionar para actualizar registros
	 */
	private $allowedConditions_update = array(
		'nombres',
		'perfil'
	);

	/**
	 * Método para validar los datos que se mandan para insertar un registro, comprobar campos obligatorios, valores válidos, etc.
	 */
	private function validate($data) {

		for ($i = 0; $i < count($this->allowedConditions_insert); $i++) {
			
			if (!isset($data[$this->allowedConditions_insert[$i]]) || empty($data[$this->allowedConditions_insert[$i]])) {
				
				$response = array(
					'result' => 'error',
					'details' => 'El campo '.$this->allowedConditions_insert[$i].' es obligatorio'
				);
	
				Response::result(400, $response);
				exit;
			}
		}
		
		return true;
	}
	/**
	 * Método para validar los datos que se mandan para actualizar un registro, comprobar campos obligatorios, valores válidos, etc.
	 */
	private function validateUpdate($data) {

		foreach ($data as $key => $value) {

			if (empty($data[$key])) {

				$response = array(
					'result' => 'error',
					'details' => 'El campo '.$key.' no puede estar vacio'
				);
	
				Response::result(400, $response);
				exit;
			}
		}
		
		return true;
	}
	/**
	 * Método para recuperar registros, pudiendo indicar algunos filtros 
	 */
	public function get($params){
		foreach ($params as $key => $param) {
			if(!in_array($key, $this -> allowedConditions_get)){
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
	
				Response::result(400, $response);
				exit;
			}
		}

		$alumnos = parent::getDB($this->table, $params);

		return $alumnos;
	}
	/**
	 * Metodo para buscar que el usuario no exista en la tabla
	 */
	public function getValidUsername($username) {
		
		$data = parent::getDB($this->table,$username);

		if (!empty($data)) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Método para guardar un registro en la base de datos, recibe como parámetro el JSON con los datos a insertar
	 */
	public function insert($params)
	{
		foreach ($params as $key => $param) {
			if(!in_array($key, $this->allowedConditions_insert)){
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la solicitud'
				);
	
				Response::result(400, $response);
				exit;
			}

			if ($this->getValidUsername(array('username' => $params['username']))) {
				$response = array(
					'result' => 'error',
					'details' => 'El usuario ya existe'
				);
	
				Response::result(400, $response);
				exit;
			}
		}

		if($this->validate($params)){

			$params['password'] = hash('sha256', $params['password']);
			
			return parent::insertDB($this->table, $params);
		}
	}
	/**
	 * Método para actualizar un registro en la base de datos, se indica el id del registro que se quiere actualizar
	 */
	public function update($id, $params)
	{
		foreach ($params as $key => $param) {

			if(!in_array($key, $this->allowedConditions_update)){
				unset($params[$key]);
				$response = array(
					'result' => 'error',
					'details' => 'Error en la campos introducidos'
				);
	
				Response::result(400, $response);
				exit;
			}		
		}

		if($this->validateUpdate($params)){
			
			$affected_rows = parent::updateDB($this->table, $id, $params);

			if($affected_rows == 0){
				$response = array(
					'result' => 'error',
					'details' => 'No hubo cambios'
				);

				Response::result(200, $response);
				exit;
			}
		}
	}
	
	/**
	 * Método para borrar un registro de la base de datos, se indica el id del registro que queremos eliminar
	 */
	public function delete($id)
	{
		$affected_rows = parent::deleteDB($this->table, $id);

		if ($affected_rows == 0){
			$response = array(
				'result' => 'error',
				'details' => 'No hubo cambios'
			);

			Response::result(200, $response);
			exit;
		}
	}
	
}

?>