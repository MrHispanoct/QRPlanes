<?php
/**
 * Clase que hace de endpoint para la autentificación
 * Se debe mandar por POST un json con el usuario y la contraseña
 */
/*CORS headers*/
include_once 'CORS.php';
/*CORS Preflight OPTIONS request*/
/*if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {   

	return 0;    
}######################################################1*/

require_once 'src/classes/login.class.php';
require_once 'src/response.php';

$auth = new Authentication();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'POST':
		$user = json_decode(file_get_contents('php://input'), true);

		$token = $auth->signIn($user);

		$response = array(
			'result' => 'ok',
			'token' => $token
		);

		Response::result(201, $response);

		break;
	/**
	 * Para cualquier otro tipo de petición se devuelve un mensaje de error 404.
	 */
	default:
		$response = array(
			'result' => 'error',
			'details' => 'error en la solicitud'
		);

		Response::result(404, $response);

		break;
}