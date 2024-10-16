<?php
/**
 * Clase para el modelo que representa a la tabla "player".
 */
require_once 'src/response.php';
require_once 'src/database.php';

class Alumno extends Database
{
	
	/**
	 * Atributo que indica la tabla asociada a la clase del modelo
	 */
	
	private $table = 'alumno';

	/**
	 * Array con los campos de la tabla que se pueden usar como filtro para recuperar registros
	 */
	private $allowedConditions_get = array(
		'expediente',
  		'dni',
  		'nombre',
  		'apellido1',
  		'apellido2',
  		'fecha_nac',
  		'movil',
  		'telefono_casa',
  		'padre',
  		'movil_padre',
  		'madre',
  		'movil_madre',
  		'autoriza_salida',
  		'autoriza_imagen',
  		'page'
	);

	/**
	 * Método para validar los datos que se mandan para insertar un registro, comprobar campos obligatorios, valores válidos, etc.
	 */
	private function validate($data){
		
		if (!isset($data['expendiente']) || empty($data['expediente'])) {
			$response = array(
				'result' => 'error',
				'details' => 'El campo expediente es obligatorio'
			);

			Response::result(400, $response);
			exit;
		}

		if(!isset($data['nota']) || empty($data['nota'])){
			$response = array(
				'result' => 'error',
				'details' => 'El campo nota es obligatorio'
			);

			Response::result(400, $response);
			exit;
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
	 * Método para borrar un registro de la base de datos, se indica el id del registro que queremos eliminar
	 */
	public function delete($id)
	{
		$affected_rows = parent::deleteDB($this->table, $id);

		if($affected_rows==0){
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