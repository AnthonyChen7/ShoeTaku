<?php 

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');

class Login extends Restapi{
	
	function __construct()
	{
		parent::__construct();
		$this->doSomething();
	}
	
	private function doSomething(){
		
		$email = $_POST["uid"];
		$password = $_POST["password"];
		
		$table = "user";
		$columns = array("*");
		$where = array();
		$values = array($email, $password);
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);
		
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		
		$stmt->execute();
		
		$result = $stmt->fetchAll();
		$this->disconnect();
		
		$this->response($result, 200);
	}
	
}


?>
