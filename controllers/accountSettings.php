<?php 


require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
require_once(__DIR__.'/restapi.php');

define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');
define("ISSUER", 'ShoeTaku');

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

class AccountSettings extends Restapi{
	
	private $signer;

	function __construct(){
		parent::__construct();
		
		$this->signer = new Sha256();


		$method = $_SERVER['REQUEST_METHOD'];
		
		if($method == 'GET'){
			$this->retrieveInfo();
		}
		else if($method == 'POST' && isset($_POST['old_password']) && !empty($_POST['old_password'])){
			
			$this->changePassword();
			
		}else{
			$this->updateInfo();
		}
	}
	
	function retrieveInfo(){
		$token = $_GET["token"];
		$token = (new Parser())->parse((string) $token); // Parses from a string

		$table = "user";
		$columns = array("userId","email","password","firstName","lastName","city","countryCode");
		$where=array('userId');
		$values = array($token->getHeader('jti'));
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
		
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		$result = $stmt->fetchAll();
		
		$this->disconnect();
		
		if(count($result)==1){
			$result = $result[0];

		// $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
		// $data->setIssuer(ISSUER);
		// $data->setAudience('');
		// $data->setId($result['userId']);

		// var_dump($token->validate($data)); // true, because validation information is equals to data contained on the token
			
		}else{
			$result = null;
		}
		
		echo json_encode($result);
	}
	
	function updateInfo(){
		
		$result = array();
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		$password = $_POST['password'];
		
		$token = $_POST["token"];
		$token = (new Parser())->parse((string) $token); // Parses from a string
		
		$table = "user";
		$columns = array("password","firstName","lastName","city","countryCode");
		$where=array('userId');
		$values = array($password,$firstName,$lastName,$city,$country,$token->getHeader('jti'));
		
		$sql = $this->prepareUpdateSql($table,$columns,$where);
		
		try{
		$this->connect();
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		
		$result["success"] = true;

		}catch (Exception $e) {
			$result["success"] = false;
		}
		
		$this->disconnect();
		
		echo json_encode($result);
		
	}
	
	function changePassword(){
		$oldPassword = $_POST['old_password'];
		$newPassword = $_POST['new_password'];
		
		$token = $_POST["token"];
		$token = (new Parser())->parse((string) $token); // Parses from a string
		
		//queries user and checks if old password matches
		$table = "user";
		$columns = array("password");
		$where=array('userId');
		$values = array($token->getHeader('jti'));
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
		
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		$result = $stmt->fetchAll();
		
		$this->disconnect();
		
		$data = array();
		
		if(count($result)==1){
			$result = $result[0];
			
			if(password_verify($oldPassword,$result['password'])){
				$newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
				$data['password_match'] = true;
				$data['new_password']=$newPassword;
			}else{
				$data['password_match'] = false;
			}
			
		}else{
			$data['password_match'] = false;
		}
		
		echo json_encode($data);
	}
}

?>