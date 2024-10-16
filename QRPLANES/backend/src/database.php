<?php
/**
 * Clase con la lógica para conectarse a la base de datos. 
 * Incluye métodos para recuperar registros, actualizar y borrarlos de cualquier tabla de la base de datos, además de poder filtrar las consultas.
 */
require_once 'src/config/connection.params.php';
/*CORS Preflight OPTIONS request*/
/*CORS headers*/
//include_once 'CORS.php';
/*CORS Preflight OPTIONS request*/
/*if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {    

	return 0;    
}*/

class Database
{
	private $connection;
	/**
	 * Atributo que indica la cantidad de registros por página a la hora de recuperar datos
	 */
	private $results_page = 50;

	public function __construct(){
		$connectionParams = new ConnectionParams();
		
		$this->connection = new mysqli($connectionParams->getDbHost(),$connectionParams->getDbuser(),$connectionParams->getDbpass(),$connectionParams->getDbname(),$connectionParams->getDbport());

		if($this->connection->connect_errno){
			echo 'Error de conexión a la base de datos';
			exit;
		}
	}

	/**
	 * Método para recuperar datos de una tabla, pudiendo indicar filtros con el parámetro $extra
	 */
	public function getDB($table, $extra = null)
	{
		$page = 0;
		$query = "SELECT * FROM $table";

		if(isset($extra['page'])){
			$page = $extra['page'];
			unset($extra['page']);
		}

		if($extra != null){
			$query .= ' WHERE';

			foreach ($extra as $key => $condition) {
				$query .= ' '.$key.' = "'.$condition.'"';
				if($extra[$key] != end($extra)){
					$query .= " AND ";
				}
			}
		}

		/**
		 * Aquí se paginan los resultados para evitar recuperar todos los registros de una tabla que contenga muchísimos
		 */
		if($page > 0){
			$since = (($page-1) * $this->results_page);
			$query .= " LIMIT $since, $this->results_page";
		}
		else{
			$query .= " LIMIT 0, $this->results_page";
		}

		$results = $this->connection->query($query);
		$resultArray = array();

		foreach ($results as $value) {
			$resultArray[] = $value;
		}

		return $resultArray;
	}

	/**
	 * Método para insertar un nuevo registro
	 */
	public function insertDB($table, $data)
	{
		$fields = implode(',', array_keys($data));
		$values = '"';
		$values .= implode('","', array_values($data));
		$values .= '"';

		$query = "INSERT INTO $table (".$fields.') VALUES ('.$values.')';
		$this->connection->query($query);

		/*Actulizar el token*/

		return $this->connection->insert_id;
	}

	/**
	 * Método para actualizar un registro de la BD
	 * Hay que indicar el registro mediante un campo que sea clave primaria y que debe llamarse "id"
	 */
	public function updateDB($table, $id, $data)
	{	
		$query = "UPDATE $table SET ";
		foreach ($data as $key => $value) {
			$query .= "$key = '$value'";
			if(sizeof($data) > 1 && $key != array_key_last($data)){
				$query .= " , ";
			}
		}

		$query .= ' WHERE id = '.$id;

		$this->connection->query($query);

		if(!$this->connection->affected_rows){
			return 0;
		}

		return $this->connection->affected_rows;
	}

	/**
	 * Método para eliminar un registro de la BD
	 * Hay que indicar el registro mediante un campo que sea clave primaria y que debe llamarse "id"
	 */
	public function deleteDB($table, $id)
	{
		$query = "DELETE FROM $table WHERE id = $id";
		$this->connection->query($query);

		if(!$this->connection->affected_rows){
			return 0;
		}

		return $this->connection->affected_rows;
	}
	
	
	/**
     * Método para crear un nuevo usuario en la base de datos
     */
    public function createUser($userData)
    {
        $username = $userData['username'];
        $password = hash('sha256', $userData['password']);

        $query = "INSERT INTO usuario (username, password) VALUES (?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    /**
     * Método para eliminar un usuario de la base de datos
     */
    public function deleteUser($userId)
    {
        $query = "DELETE FROM usuario WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $userId);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

}


?>