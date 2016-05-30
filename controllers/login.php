<?php 

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\firebase\php-jwt\src\JWT.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\lcobucci\jwt\src\Configuration.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\gamegos\jwt\src\Token.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\gamegos\jwt\src\Encoder.php');

//use Firebase\JWT;
//use \firebase\php-jwt;
//use \firebase\jwt\src;


use Lcobucci\JWT\Builder;

class Login extends Restapi{
	
	function __construct()
	{
		parent::__construct();

		
		$this->checkCredentials();
		
	}
	
	private function checkCredentials(){
		// array to pass back data
		$data = array();
		
		$token = array();      
		
		$email = $_POST["email"];
		$password = $_POST["password"];
				
		$table = "user";
		$columns = array("email","password");
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
		
		//check if password needs re-hashing first
		if(password_needs_rehash($object['password'],PASSWORD_BCRYPT)){
			
			$hash= password_hash($password, PASSWORD_BCRYPT);
			
			$values = array($hash,$object['email']);
			//update with new hashed password in db
			$columns = array("password");
			$sql = $this->prepareUpdateSql($table,$columns,$where);
			
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($values);
			
			//check if newly-hashed password matches inputted password
			if($email === $object['email'] && password_verify($object['password'],$hash)){
			$data["success"] = true;
			$data["email"] = $email;
			
			}else{
			$data["success"] = false;
			}
			
		}else{
			
			if($email === $object['email'] && password_verify($password,$object['password'])){
			$data["success"] = true;
			$data["email"] = $email;
			
			}else{
			$data["success"] = false;
			}
			
	}
	
		}else{
			
		$data["success"] = false;
			
		}
		
		$this->disconnect();

		// return all our data to an AJAX call
    	
		echo json_encode($data);
		//$this->response($data,200);
	}
	
}


?>
