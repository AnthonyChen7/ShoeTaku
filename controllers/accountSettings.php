<?php 


require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
require_once(__DIR__.'/restapi.php');

define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');
define("ISSUER", 'ShoeTaku');

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

class AccountSettings extends Restapi{
	
	function __construct(){
		parent::__construct();
		
		$method = $_SERVER['REQUEST_METHOD'];
		
		if($method == 'GET'){
			$this->retrieveInfo();
		}
		else if($method == 'POST'){
			$this->updateInfo();
			
		}
	}
	
	function retrieveInfo(){
		$token = $_GET["token"];
		$token = (new Parser())->parse((string) $token); // Parses from a string
		
		$table = "user";
		$columns = array("email","firstName","lastName","city","countryCode");
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
		}else{
			$result = null;
		}
		
		echo json_encode($result);
	}
	
	function updateInfo(){
		
		$result = array();
		
		// $email = $_POST["email"];
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		
		$token = $_POST["token"];
		$token = (new Parser())->parse((string) $token); // Parses from a string
		
		$table = "user";
		$columns = array("firstName","lastName","city","countryCode");
		$where=array('userId');
		$values = array($firstName,$lastName,$city,$country,$token->getHeader('jti'));
		
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
}

?>