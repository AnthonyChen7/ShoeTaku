<?php 

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');

class Login extends Restapi{
	
	function __construct()
	{
		parent::__construct();
		$this->checkCredentials();
	}
	
	private function checkCredentials(){
		// array to pass back data
		$data = array();      
		
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
						
		}else{
			$data["success"] = false;
		}
		
		
		
		// return all our data to an AJAX call
    	echo json_encode($data);
	}
	
}


?>
