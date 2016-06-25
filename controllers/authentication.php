<?php 

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');

include_once __DIR__.'/tokencreator.php';

class Authentication extends Restapi
{
	
	function __construct()
	{
		parent::__construct();
		$this->checkCredentials();
	}
	
	private function checkCredentials()
	{
		
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
		
		$tokenCreator = TokenCreator::createToken($object['userId']);
		$token = $tokenCreator->getToken();				
		}

	}

		$this->disconnect();		
		// return all our data to an AJAX call
		echo $token;
	}

	private function storeTokenInDB()
	{
		$table="invalid_token";
		$columns=array("tokenId","expiry_time");
		$values=array();
		$limOff = array();
		$where=array();
		
		$sql = $this->prepareInsertSql($table,$columns,$where,$limOff);
		
		try{
			$this->connect();
			
			//TODO
			//Also, if token is already expired. Don't bother to store in db
			
			$this->disconnect();
		}
	}

	private function register(){

		$token = NULL;

		$result = NULL;
				
		$email = $_POST["email"];
		
		$password = $_POST["password"];
		
		//hash the password using bcrypt method
		$password = password_hash($password,PASSWORD_BCRYPT);
		
		$firstName = $_POST["firstName"];
		$lastName = $_POST["lastName"];
		$city = $_POST["city"];
		$country = $_POST["country"];
		
		$table = "user";
		$columns = array("email","password","firstName","lastName","city", "countryCode");
		$where = array();
		$values = array($email, $password, $firstName, $lastName, $city, $country);
		$limOff = array();
		
		$sql = $this->prepareInsertSql($table, $columns, $where, $limOff);
		
		try{
				
		$this->connect();
		$stmt = $this->conn->prepare($sql);
		
		$result = $stmt->execute($values);
		
		//retrieve user ID....
		$columns=array("userId");
		$values = array($email);
		$where=array("email");
		
		$sql = $this->prepareSelectSql($table, $columns, $where ,$limOff);
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		$result = $result[0];
		
		$tokenCreator = TokenCreator::createToken($result['userId']);
		$token = $tokenCreator->getToken();
		
		}catch (Exception $e) {
			$token = "error";
		}

		$this->disconnect();
		
		// return all our data to an AJAX call
		echo $token;
	}
	


	
}


?>
