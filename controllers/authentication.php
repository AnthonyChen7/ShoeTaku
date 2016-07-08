<?php 

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');
include_once(__DIR__.'/tokencreator.php');
include_once __DIR__.'/countries.php';

class Authentication extends Restapi{
	
	function __construct(){
		parent::__construct();
		
		if(isset($_POST['action']) && $_POST['action']==='login'){
			if($this->areFieldsValid()){
				$this->nonfbLogin();
			}else{
				$this->response("Invalid Fields!",400);
			}	
		}else if(isset($_POST['action']) && $_POST['action']==='register'){
			
			if($this->areFieldsValid()){
			
			$isCountryValid = new Countries();
			
			if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
				$this->response("Invalid email!",400);
			}
	
			else if($isCountryValid->isCountryValid($_POST['country'])===false){
				$this->response("Invalid country!",400);
			}

			else if($_POST['password']!=$_POST['confirmPassword']){
				$this->response("Passwords don't match!",400);
			}
			
			else{
				$this->register();
			}	

		}
		else{
			$this->response("Invalid Fields!",400);
		}
		}
		else if(isset($_POST['action']) && $_POST['action']==='logout'){
			$this->storeTokenInDB();
		}
		else{
			$this->fbLogin();	
		}
		
	}
	
	private function nonfbLogin(){

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

	private function fBLogin(){
		if (isset($_POST)){
			$id = $_POST["id"];
			$email = $_POST["email"];
			$fName = $_POST["first_name"];
			$lName = $_POST["last_name"];
			$isMerged = false;
			if (isset($_POST["location"])){
				$city = $_POST["location"]["city"];
				$countryCode = $_POST["location"]["country_code"];
				$insertColumns = array("id", "email", "firstName", "lastName", "city", "countryCode", "isMerged");
				$insertValues = array($email, $fName, $lName, $city, $countryCode, $isMerged);
			}else{
				$insertColumns = array("id", "email", "firstName", "lastName", "isMerged");
				$insertValues = array($id, $email, $fName, $lName, $isMerged);
			}
			
			$userTable = "User";
			$fbTable = "FBUser";
			$userColumns = array("userId");
			$fbColumns = array("id","userId","isMerged");
			$where = array("email");
			$values = array($email);
			$limOff = array();

			$check;
			$result;
			$data;
			
			try{
				$sql = $this->prepareSelectSql($fbTable, $fbColumns, $where, $limOff);		
				$this->connect();
				$stmt = $this->conn->prepare($sql);
				$stmt->execute($values);
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$this->disconnect();

			}catch (Exception $e){
				$this->response($e->getMessage(), 500);
			}
			// account already in db FBUser table
			if (isset($result) && $result != false){
				try{
					$checkSql = $this->prepareSelectSql($userTable, $userColumns, $where, $limOff);
					$this->connect();
					$stmt = $this->conn->prepare($checkSql);
					$stmt->execute($values);
					$check = $stmt->fetch(PDO::FETCH_ASSOC);
					$this->disconnect();

					}catch (Exception $e){
						$this->response($e->getMessage(), 500);
				}
				
				if (!$result["isMerged"]){
					// check if in User table
					if (isset($check) && $check != false && isset($check["userId"])){
						// merge accounts
						$this->mergeAccount($id, $check["userId"]);
						$isFacebook = false;
						$tokenCreator = TokenCreator::createToken($check['userId'], $isFacebook);
						$token = $tokenCreator->getToken();
						$result = array("token" => (string)$token);

					}else{
						// No nonFB account

						$isFacebook = true;
						$tokenCreator = TokenCreator::createToken($id, $isFacebook);

						$token = $tokenCreator->getToken();
						$result = array("token" => (string)$token);
					}

				}else{
					//already merged
					$isFacebook = false;
					$tokenCreator = TokenCreator::createToken($check["userId"], $isFacebook);
					$token = $tokenCreator->getToken();
					$result = array("token" => (string)$token);
				}
			}else{
				// account not in db. Insert to db
				try{
					$insertSql = $this->prepareInsertSql($fbTable, $insertColumns);
					$this->connect();
					$stmt = $this->conn->prepare($insertSql);
					$insertResult = $stmt->execute($insertValues);
					$this->disconnect();
					$isFacebook = true;

					if ($insertResult == true){
						$tokenCreator = TokenCreator::createToken($id, $isFacebook);
						$token = $tokenCreator->getToken();
						$result = array("token" => (string)$token);
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

	private function mergeAccount($id, $userId){
		try{
			$fbTable = "FBUser";
			$columns = array("userId","isMerged");
			$where = array("id");
			$values = array($userId, 1, $id);
			$sql = $this->prepareUpdateSql($fbTable, $columns, $where);

			$this->connect();
			$stmt = $this->conn->prepare($sql);
			$result = $stmt->execute($values);

			$this->disconnect();
			return true;

		}catch (Exception $e){
			$this->response($e->getMessage(), 500);
			return false;
		}
		
	}

	/**
	Token is considered invalid when user logs out
	Store it in db
	*/
	private function storeTokenInDB(){

		$tokenCreator = TokenCreator::initParseToken($_POST["token"]);
		$parsedToken = $tokenCreator->getToken();		
		$table="invalid_token";
		$columns = array('tokenId',"token","expiryTime");
		$where = array('tokenId');
		$limOff = array();
			
		//only insert if token hasn't expired yet
		$currTime = time();
		$expiryTime = $parsedToken->getClaim('exp');
		
		try{
		
		$this->connect();
			
			if($currTime <= $expiryTime){

					//register it to db
					$values = array($parsedToken->getHeader('jti'), $parsedToken,$expiryTime);
					$sql = $this->prepareInsertSql($table, $columns, $where, $limOff);
					$stmt = $this->conn->prepare($sql);
					$stmt->execute($values);
					
				$this->disconnect();
				$this->response("success",200);
			}
		}catch(Exception $e){
			$this->response("error occurred",200);
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
