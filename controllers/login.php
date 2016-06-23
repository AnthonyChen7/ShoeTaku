<?php 

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');

include_once __DIR__.'/tokencreator.php';

class Login extends Restapi{
	
	function __construct()
	{
		parent::__construct();

		$this->checkCredentials();
		
	}
	
	private function checkCredentials(){
		
		//store data inside the array to pass back

		$token = NULL;
		
		$email = $_POST["email"];
		$password = $_POST["password"];
		
		$table = "user";
		$columns = array("userId","email","password");
		$where = array("email");
		$values = array($email);
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);		
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		
		if(count($result)===1){
		
		/**
		Since email is a unique key
		we would expect there to be only 1 result
		**/
			
		$object = $result[0];
			
		if($email === $object['email'] && password_verify($password,$object['password'])){
		
		$tokenCreator = new TokenCreator($object['userId']); 
		$token = $tokenCreator->getToken();
						
		}

		}

		$this->disconnect();		
		// return all our data to an AJAX call
		echo $token;

	}
	
}


?>
