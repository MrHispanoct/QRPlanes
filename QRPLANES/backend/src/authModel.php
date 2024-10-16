<?php
/**
 * Clase del modelo para la tabla de usuarios
 * Representa un registro de la tabla de usuarios y permite hacer el login, obtener un token de un usuario y actualizar el token de un usuario
 */
require_once 'src/config/connection.params.php';

class AuthModel
{
	private $connection;
	
	public function __construct(){
		$connectionParams = new ConnectionParams();
		
		$this->connection = new mysqli($connectionParams->getDbHost(),$connectionParams->getDbuser(),$connectionParams->getDbpass(),$connectionParams->getDbname(),$connectionParams->getDbport());

		if($this->connection->connect_errno){
			echo 'Error de conexión a la base de datos';
			exit;
		}
	}

	/**
	 * Método para autentificarse en la API
	 */
	public function login($username, $password)
	{
		$query = "SELECT id, nombres, username, perfil FROM usuario WHERE username = '$username' AND password = '$password'";

		$results = $this->connection->query($query);

		$resultArray = array();

		if($results != false){
			foreach ($results as $value) {
				$resultArray[] = $value;
			}
		}

		return $resultArray;
	}

	/**
	 * Método para actualizar el token de un usuario con un determinado id
	 * Se utiliza después de una autentificación correcta
	 */
	public function update($id, $token)
	{
		$query = "UPDATE usuario SET token = '$token' WHERE id = $id";

		$this->connection->query($query);
		
		if(!$this->connection->affected_rows){
			return 0;
		}

		return $this->connection->affected_rows;
	}

	/**
	 * Método para obtener el token de un determinado id
	 */
	public function getById($id)
	{
		$query = "SELECT * FROM usuario WHERE id = $id";

		$results = $this->connection->query($query);

		$resultArray = array();

		if($results != false){
			foreach ($results as $value) {
				$resultArray[] = $value;
			}
		}

		return $resultArray;
	}
}
