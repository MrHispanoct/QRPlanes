<?php
/*CORS headers*/
include_once 'CORS.php';
/*CORS Preflight OPTIONS request*/
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {    

	return http_response_code(204);    
}#####################################################*/
/*############################################Añadido Modificado #######################################*/
if (isset($_GET['token'])) {
	$_SERVER['HTTP_API_KEY'] = $_GET['token'];
	unset($_GET['token']);
}
/**
 *	Script que se usa en los endpoints para trabajar con registros de la tabla PLAYER
 *	La clase "player.class.php" es la clase del modelo, que representa a un jugador de la tabla
*/
require_once 'src/response.php';
require_once 'src/classes/usuario.class.php';
require_once 'src/classes/login.class.php';


/**
 * Verificamos que el token y el usuario es correcto
 */
$auth = new Authentication();
$auth -> verifyAdmin();


$jwt = $_SERVER['HTTP_API_KEY'];


$user = new Usuario();

/**
 * Se mira el tipo de petición que ha llegado a la API y dependiendo de ello se realiza una u otra accción
 */
switch ($_SERVER['REQUEST_METHOD']) {
	/**
	 * Si se ha recibido un GET se llama al método get() del modelo y se le pasan los parámetros recibidos por GET en la petición
	 */
	case 'GET':
		$params = $_GET;

		$usuarios = $user -> get($params);

		$response = array(
			'result' => 'ok',
			'usuarios' => $usuarios
		);

		Response::result(200, $response);

		break;
		
	/**
	 * Si se recibe un POST, se comprueba si se han recibido parámetros y en caso afirmativo se usa el método insert() del modelo
	 */
	case 'POST':
		$params = json_decode(file_get_contents('php://input'), true);

		if (isset($_GET['method'])) {
			switch ($_GET['method']) {
				case 'DELETE':
					unset($_GET['method']);
					if(!isset($_GET['id']) || empty($_GET['id'])){
						$response = array(
							'result' => 'error',
							'details' => 'Error en la solicitud Id para eliminar'
						);
			
						Response::result(400, $response);
						exit;
					}
			
					$user->delete($_GET['id']);
			
					$response = array(
						'result' => 'ok'
					);
			
					Response::result(200, $response);
					exit;
				case 'PATCH':
					unset($_GET['method']);

					if(!isset($params) || !isset($_GET['id']) || empty($_GET['id'])){
						$response = $params;
						$response = array(
							'result' => 'error',
							'details' => 'Error en la solicitud para modificar'
						);
			
						Response::result(400, $response);
						exit;
					}
			
					$user->update($_GET['id'], $params);
			
					if ((isset($params['nombres']) || isset($params['perfil']))) {
						$auth->getToken($_GET['id']);
					}
			
					$response = array(
						'result' => 'ok'
					);
			
					Response::result(200, $response);
					exit;
				
				default:
					$response = array(
						'result' => 'error',
						'details' => 'Error General'
					);
		
					Response::result(404, $response);
					exit;
			}
		}

		if(!isset($params)){
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud de parametros'
			);

			Response::result(400, $response);
			exit;
		}


		$insert_id = $user->insert($params);

		if (!empty($insert_id)) {
			$auth->getToken($insert_id);
		}

		$response = array(
			'result' => 'ok',
			'insert_id' => $insert_id
		);

		Response::result(201, $response);
		break;
	/**
	 * Cuando es PATCH, comprobamos si la petición lleva el id del usuario que hay que actualizar. En caso afirmativo se usa el método update() del modelo.
	 */
	case 'PATCH':
		$params = json_decode(file_get_contents('php://input'), true);

		if(!isset($params) || !isset($_GET['id']) || empty($_GET['id'])){
			$response = $params;
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud para modificar'
			);

			Response::result(400, $response);
			exit;
		}

		$user->update($_GET['id'], $params);

		if ((isset($params['nombres']) || isset($params['perfil']))) {
			$auth->getToken($_GET['id']);
		}

		$response = array(
			'result' => 'ok'
		);

		Response::result(200, $response);	
		break;

	/**
	 * Cuando se solicita un DELETE se comprueba que se envíe un id de jugador. En caso afirmativo se utiliza el método delete() del modelo.
	 */
	case 'DELETE':
		if(!isset($_GET['id']) || empty($_GET['id'])){
			$response = array(
				'result' => 'error',
				'details' => 'Error en la solicitud Id para eliminar'
			);

			Response::result(400, $response);
			exit;
		}

		$user->delete($_GET['id']);

		$response = array(
			'result' => 'ok'
		);

		Response::result(200, $response);
		break;
		
	/**
	 * Para cualquier otro tipo de petición se devuelve un mensaje de error 404.
	 */
	default:
		$response = array(
			'result' => 'error',
			'detais' => 'Error General'
		);

		Response::result(404, $response);

		break;
}
?>