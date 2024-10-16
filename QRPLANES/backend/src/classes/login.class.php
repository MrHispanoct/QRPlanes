<?php
/**
 * Clase para trabajar con la autentificación en la API
 * Hace uso de las clases implementadas en la carpeta "jwt" para realizar la autentificación mediante token
 * El token se genera a partir del id del usuario, por lo que cada usuario tendrá siempre un token distinto. Además del id, para generar el token se hace uso de una clave secreta que es un atributo de la clase
 */
require_once 'jwt/JWT.php';
require_once 'src/authModel.php';
require_once 'src/response.php';

use Firebase\JWT\JWT;

class Authentication extends AuthModel
{
	/**
	 * Tabla donde estarán los usuarios
	 */
	private $table = 'usuario';

	/**
	 * Clave secreta para realizar la encriptación y desencriptación del token, se debería cambiar por una clave robusta
	 */
	private $key = 'clave_secreta';
	/**
	 * Método para que un usuario se autentifique con un nombre de usuario y una contraseña
	 */
	public function signIn($user)
	{
		if(!isset($user['username']) || !isset($user['password']) || empty($user['username']) || empty($user['password'])){
			$response = array(
				'result' => 'error',
				'details' => 'Los campos password y username son obligatorios'
			);
			
			Response::result(400, $response);
			exit;
		}

		$result = parent::login($user['username'], hash('sha256' , $user['password']));

		if(sizeof($result) == 0){
			$response = array(
				'result' => 'error',
				'details' => 'El usuario y/o la contraseña son incorrectas'
			);

			Response::result(403, $response);
			exit;
		}

		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'id' => $result[0]['id'],
				'nombres' => $result[0]['nombres'],
				'perfil' => $result[0]['perfil']
			)
		);

		$jwt = JWT::encode($dataToken, $this->key);

		parent::update($result[0]['id'], $jwt);

		return $jwt;
	}
	/**
	 * Metodo para obtener el token valido
	 */
	public function getToken($userId) {

		$user = parent::getById($userId);

		$dataToken = array(
			'iat' => time(),
			'data' => array(
				'id' => $user[0]['id'],
				'nombres' => $user[0]['nombres'],
				'perfil' => $user[0]['perfil']
			)
		);

		$jwt = JWT::encode($dataToken, $this->key);

		$result = parent::update($user[0]['id'], $jwt);
		
		return $result;
	}

	/**
	 * Método para verificar si un token es válido cuando se realiza una petición a la API
	 * El token se manda como header poniendo en name "api-key" y como value el valor del token
	 */
	public function verify()
    {
        if(!isset($_SERVER['HTTP_API_KEY'])){
    
            $response = array(
                'result' => 'error',
                'details' => 'Usted no tiene los permisos para esta solicitud'
            );
        
            Response::result(403, $response);
            exit;

        }

        $jwt = $_SERVER['HTTP_API_KEY'];

        try {
            $data = JWT::decode($jwt, $this->key, array('HS256'));

			$user = parent::getById($data->data->id);

			if($user[0]['token'] != $jwt){
				throw new Exception();
			}
			
            return $data;
        } catch (\Throwable $th) {
            
            $response = array(
                'result' => 'error',
                'details' => 'No tiene los permisos para esta solicitud'
            );
        
            Response::result(403, $response);
            exit;
        }
    }
	/**
	 * Método para verificar si un token es válido cuando se realiza una petición a la API y que el usuario es Admin
	 * El token se manda como header poniendo en name "api-key" y como value el valor del token
	 */
	public function verifyAdmin()
    {
        if(!isset($_SERVER['HTTP_API_KEY'])){
    
            $response = array(
                'result' => 'error',
                'details' => 'Usted no tiene los permisos para esta solicitud'
            );
        
            Response::result(403, $response);
            exit;

        }

        $jwt = $_SERVER['HTTP_API_KEY'];

        try {
            $data = JWT::decode($jwt, $this -> key, array('HS256'));
			$user = parent::getById($data->data->id);
			
			if ($user[0]['token'] != $jwt) {
				throw new Exception();
			} else if ($user[0]['perfil'] != 1) {
				throw new Exception();
			}
			
            return $data;
        } catch (\Throwable $th) {
            
            $response = array(
                'result' => 'error',
                'details' => 'No tiene los permisos de administrador para esta solicitud'
            );
        
            Response::result(403, $response);
            exit;
        }
    }
}
