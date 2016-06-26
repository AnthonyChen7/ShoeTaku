<?php 


require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
require_once(__DIR__.'/restapi.php');
include_once __DIR__.'/tokencreator.php';
include_once __DIR__.'/tokenverify.php';

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

class AccountSettings extends Restapi{
	
	private $signer;

	function __construct(){
		parent::__construct();
		
		$this->signer = new Sha256();

		// $method = $_SERVER['REQUEST_METHOD'];
		
		//var_dump($_POST);
		
		// if($method == 'GET'){
		// if(isset($_POST['action'])&&!empty($_POST['action'])&& $_POST['action']==='retrieve'){
			$this->retrieveInfo();
		// }
		// else{
		// 	$this->updateInfo();
		// }
	}
	
	/**
	Retrieves account info to be displayed in account settings.html
	Don't retrieve password for security concern purposes
	*/
	function retrieveInfo(){
		
		
		
		// $token = $_GET["token"];
		$token = $_POST["token"];

		$parsedToken = TokenCreator::initParseToken( $token );

		$table = "user";
		$columns = array("firstName","lastName","city","countryCode");
		$where=array('userId');
		$values = array($parsedToken->getToken()->getHeader('jti'));
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
		
		$tokenVerifier = new TokenVerify($token,$parsedToken->getToken()->getHeader('jti'));
		
			if($tokenVerifier->isTokenValid()){
				
				try{
			
					$this->connect();
		
					$stmt = $this->conn->prepare($sql);
					$stmt->execute($values);
					$result = $stmt->fetchAll();
				}catch(Exception $e){
					$result = null;
				}
		
			$this->disconnect();
		
				if(count($result)==1){
					$result = $result[0];
				}else{
					$result = null;
				}
			
			}else{
				$result['error']= TIMED_OUT;	
			}
		//var_dump($result);
		 //echo json_encode($result);
		//echo $result;
		$this->response($result,200);
	}
	
	function updateInfo(){
		
		$result = array();
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		
		$oldPassword = $_POST['old_password'];
		$newPassword = $_POST['new_password'];
		
		$token = $_POST["token"];
		$parsedToken = TokenCreator::initParseToken( $token );
		$tokenVerifier = new TokenVerify($token,$parsedToken->getToken()->getHeader('jti'));

		if($tokenVerifier->isTokenValid()){
		
		try{
		$this->connect();
		
		//retrieve old password first
		$table = "user";
		$columns = array("password");
		$where=array('userId');
		$values = array($parsedToken->getToken()->getHeader('jti'));
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		$object = $stmt->fetchAll();
		
			if(count($object)==1){
				$object = $object[0];
		
		
				if(password_verify($oldPassword,$object['password'])){
			
					$table = "user";
					$columns = array("password","firstName","lastName","city","countryCode");
					$where=array('userId');
		
					$sql = $this->prepareUpdateSql($table,$columns,$where);
					$stmt = $this->conn->prepare($sql);
		
					$newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
		
					$values = array($newPassword,$firstName,$lastName,$city,$country,$parsedToken->getToken()->getHeader('jti'));
		
					$stmt->execute($values);
		
					$result["success"] = true;
		
				}else{
					$result['error']= "Old password doesn't match. Changes not saved!";
				}
			}else{
				$result['error']='Unable to unable to update user account!';
			}

		}catch (Exception $e) {
			$result["success"] = false;
		}
		
		$this->disconnect();
		
		}else{
			$result['error']= TIMED_OUT;
		}
		
		echo json_encode($result);
		
	}
	
	// function changePassword(){
	// 	$oldPassword = $_POST['old_password'];
	// 	$newPassword = $_POST['new_password'];
		
	// 	$token = $_POST["token"];
	// 	$parsedToken = TokenCreator::initParseToken( $token );
	// 	$tokenVerifier = new TokenVerify($token,$parsedToken->getToken()->getHeader('jti'));
		
	// 	//queries user and checks if old password matches
	// 	$table = "user";
	// 	$columns = array("password");
	// 	$where=array('userId');
	// 	$values = array($parsedToken->getToken()->getHeader('jti'));
	// 	$limOff = array();
		
	// 	$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
		
	// 	if($tokenVerifier->isTokenValid()){
		
	// 	$this->connect();
		
	// 	$stmt = $this->conn->prepare($sql);
	// 	$stmt->execute($values);
	// 	$result = $stmt->fetchAll();
		
	// 	$this->disconnect();
		
	// 	$data = array();
		
	// 	if(count($result)==1){
	// 		$result = $result[0];
			
	// 		if(password_verify($oldPassword,$result['password'])){
	// 			$newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
	// 			$data['password_match'] = true;
	// 			$data['new_password']=$newPassword;
	// 		}else{
	// 			$data['password_match'] = false;
	// 		}
			
	// 	}else{
	// 		$data['password_match'] = false;
	// 	}
		
	// 	}else{
	// 		$data['error']=TIMED_OUT;
	// 	}
		
	// 	echo json_encode($data);
	// }
}

?>