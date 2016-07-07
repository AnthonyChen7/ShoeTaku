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
		
		if($this->areFieldsValid()){
			$this->checkCredentials();
		}else{
			$this->response("Invalid Fields!",400);
		}

	}
	
	private function checkCredentials(){

		$token = NULL;
				
		$email = $_POST["email"];
		$password = $_POST["password"];
		
		$table = "user";
		$columns = array("userId","email","password");
		$where = array("email");
		$values = array($email);
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);
		
		try{
				
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
		
				$tokenCreator = TokenCreator::createToken($object['userId'],false);
				$token = $tokenCreator->getToken();
						
			}

		}
		
		}catch(Exception $e){
			$token = null;
			$this->response($e->getMessage(), 500);
		}

		$this->disconnect();		
		// return all our data to an AJAX call
		echo $token;

	}
	
	private function areFieldsValid(){
		foreach($_POST as $key=>$value){
			if(empty($_POST[$key]) || ctype_space($_POST[$key])){
				return false;
			}
			
			if($key==="email"){
				if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
					$this->response("Invalid Email!",400);
				}
			}
			
		}
		
		return true;
	}
	
}


?>
