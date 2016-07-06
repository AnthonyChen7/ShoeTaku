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
		
		if($this->areFieldsValid()){
			$this->register();
		}else{
			$this->response("Invalid Fields!",400);
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
		
		//retrieve user ID so we can create token....
		$columns=array("userId");
		$values = array($email);
		$where=array("email");
		
		$sql = $this->prepareSelectSql($table, $columns, $where ,$limOff);
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		$result = $result[0];
		
		//merge fb account if fb account exists
		$table = "fbuser";
		$columns=array("*");
		$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		
		$check = $stmt->fetchAll();
		
		if(count($check)===1){
			$check = $check[0];
			
			if($check["isMerged"]==="0"){
			//merge accounts	
			$fbTable = "fbuser";
			$columns = array("userId","isMerged");
			$where = array("id");
			$values = array($result['userId'], 1, $check['id']);
			$sql = $this->prepareUpdateSql($fbTable, $columns, $where);
			
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($values);
			}

		}
		
		$tokenCreator = TokenCreator::createToken($result['userId'],false);
		$token = $tokenCreator->getToken();
		
		}catch (Exception $e) {
			$token = "error";
			$this->response($e->getMessage(), 500);
		}

		$this->disconnect();
		
		// return all our data to an AJAX call
		echo $token;
	}
	
	private function areFieldsValid(){
		foreach($_POST as $key=>$value){
			if(empty($_POST[$key])){
				return false;
			}
		}
		
		return true;
	}

}


?>
