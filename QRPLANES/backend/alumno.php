<?php
/**
 *	Script que se usa en los endpoints para trabajar con registros de la tabla aLUMNO
 *	La clase "alumno.class.php" es la clase del modelo, que representa a un alumno de la tabla
*/
include_once 'CORS.php';
/*CORS Preflight OPTIONS request*/
/**if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {   

	return http_response_code(202);

}*/
/*############################################Añadido Modificado #######################################*/
if (isset($_GET['token'])) {

	$_SERVER['HTTP_API_KEY'] = $_GET['token'];
	unset($_GET['token']);
	//$_GET = array('expediente' => $_GET['expediente']);
	
}


require_once 'src/response.php';
require_once 'src/classes/alumno.class.php';
require_once 'src/classes/login.class.php';

 

$auth = new Authentication();
$auth -> verify();

$user = new Alumno();

/**
 * Se mira el tipo de petición que ha llegado a la API y dependiendo de ello se realiza una u otra accción
 */
switch ($_SERVER['REQUEST_METHOD']) {
	/**
	 * Si se ha recibido un GET se llama al método get() del modelo y se le pasan los parámetros recibidos por GET en la petición
	 */
	case 'GET':
		$params = $_GET;
		$alumnos = $user->get($params);

		$response = array(
			'result' => 'ok',
			'alumnos' => $alumnos
		);

		Response::result(200, $response);

		break;
	/**
	 * Para cualquier otro tipo de petición se devuelve un mensaje de error 404.
	 */
	default:
		$response = array(
			'result' => 'error'
		);

		Response::result(404, $response);

		break;
}
?>