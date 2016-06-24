<?php 

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/vendor/autoload.php' );
/**

This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');
include_once __DIR__.'/tokencreator.php';

class Register extends Restapi{
	
	function __construct()
	{
		parent::__construct();
		$this->register();
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
