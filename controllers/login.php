<?php 

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
/**

This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');

//use \Firebase\JWT\JWT;
//use \firebase\jwt;
//use \firebase\jwt\src;

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
		$columns = array("*");
		$where = array("email", "password");
		$values = array($email, $password);
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);		
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		$this->disconnect();
		
		if(count($result)===1){
			$data["success"] = true;
			$data["email"] = $email;
			
			$token["success"] = true;
			$token["email"] = $email;
			
			//JWT::encode($token, 'secret_server_key');
						
		}else{
			$data["success"] = false;
			
			$token["success"] = false;
			$token["email"] = "";
		}
		
		
		
		// return all our data to an AJAX call
    	echo json_encode($data);
	}
	
}


?>
