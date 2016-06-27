<?php 

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');
include_once(__DIR__.'/tokencreator.php');

class Authentication extends Restapi{
	
	function __construct(){
		parent::__construct();
		$this->fbLogin();
	}
	
	private function nonFBLogin(){
		//store data inside the array to pass back
		$token = NULL;	
		$email = $_POST["email"];
		$password = $_POST["password"];
		$table = "User";
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
		/* Since email is a unique keywe would expect there to be only 1 result */	
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

	private function fBLogin(){
		
		if (isset($_POST)){
			$email = $_POST["email"];
			$fName = $_POST["first_name"];
			$lName = $_POST["last_name"];
			$isFacebook = True;
			if (isset($_POST["location"])){
				$city = $_POST["location"]["city"];
				$countryCode = $_POST["location"]["country_code"];
				$insertColumns = array("email", "firstName", "lastName", "city", "countryCode", "isFacebook");
				$insertValues = array($email, $fName, $lName, $city, $countryCode, $isFacebook);
			}else{
				$insertColumns = array("email", "firstName", "lastName", "isFacebook");
				$insertValues = array($email, $fName, $lName, $isFacebook);
			}
			
			
			$table = "User";
			$columns = array("userId","email","isFacebook", "isMerged");
			$where = array("email");
			$values = array($email);
			$limOff = array();
			$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);

			$insertSql = $this->prepareInsertSql($table, $insertColumns);
			$result;
			$data;
			
			try{
				$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);		
				$this->connect();
				$stmt = $this->conn->prepare($sql);
				$stmt->execute($values);
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
			}catch (Exception $e){
				$this->response($e->getMessage(), 500);
			}

			// account already in db
			if (isset($result) && $result != false){
				if (!$result["isMerged"] && $result["isFacebook"]){
					// User has logged in through FB before
					// TODO: Code to give token back to the user
				
					$result = array(
						"isMerged" => $result["isMerged"],
						"isFacebook" => $result["isFacebook"]
						);
				}else if (!$result["isMerged"] && !$result["isFacebook"]){
					// Have to merge fb with nonfb
					$data = array(
						"isMerged" => $result["isMerged"],
						"isFacebook" => $result["isFacebook"]
						);
				
				}else{
					// User's account already merged w nonFB account
					// TODO: Code to give token back to the user
					$result = array(
						"isMerged" => $result["isMerged"],
						"isFacebook" => $result["isFacebook"]
						);

				}
			}else{
				// account not in db. Insert to db
				try{
					$this->connect();
					$stmt = $this->conn->prepare($insertSql);
					$result = $stmt->execute($insertValues);
					
					if ($result == true){
						// TODO: Code to give token back to the user
						$result = array(
							"isMerged" => false,
							"isFacebook" => true
							);
					
					}else{
						$message = "Please refresh and try again";
						$this->response($message, 500);
					}

				}catch (Exception $e){
					$this->response($e->getMessage(), 500);
				}
			}

			$this->response($result,200);
		}
		
	}

	private function mergeAccount(){

	}

	private function storeTokenInDB(){
		$table="invalid_token";
		$columns=array("tokenId","expiry_time");
		$values=array();
		$limOff = array();
		$where=array();
		
		$sql = $this->prepareInsertSql($table,$columns,$where,$limOff);
		
		
			$this->connect();
			
			//TODO
			//Also, if token is already expired. Don't bother to store in db
			
			$this->disconnect();
		
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
		
		} catch (Exception $e) {
			$token = "error";
		}

		$this->disconnect();
		
		// return all our data to an AJAX call
		echo $token;
	}
	
}


?>
