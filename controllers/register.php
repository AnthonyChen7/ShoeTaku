<?php 

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
/**

This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');

//use \Firebase\JWT\JWT;
//use \firebase\jwt;
//use \firebase\jwt\src;

class Register extends Restapi{
	
	function __construct()
	{
		parent::__construct();
		$this->register();
	}
	
	private function register(){
		// array to pass back data
		$data = array();
		
		//$token = array();      
		
		$email = $_POST["email"];
		
		$password = $_POST["password"];
		
		//hash the password using bcrypt method
		$password = password_hash($password,PASSWORD_BCRYPT);
		
		$firstName = $_POST["firstName"];
		$lastName = $_POST["lastName"];
		$age = $_POST["age"];
		$city = $_POST["city"];
		$country = $_POST["country"];
		
		$table = "user";
		$columns = array("email","password","firstName","lastName","age","city", "countryCode");
		$where = array();
		$values = array($email, $password, $firstName, $lastName, $age, $city, $country);
		$limOff = array();
		
		$sql = $this->prepareInsertSql($table, $columns, $where, $limOff);
		
		try{
				
		$this->connect();
		$stmt = $this->conn->prepare($sql);
		
		$result = $stmt->execute($values);
		
		$data["success"] = true;
		
		}catch (Exception $e) {
			$data["success"]=false;
			$data["errorMsg"] = $email . " already exists. Please select specify another email!";
		}

		$this->disconnect();

		// return all our data to an AJAX call
    	echo json_encode($data);
	}
	
}


?>
