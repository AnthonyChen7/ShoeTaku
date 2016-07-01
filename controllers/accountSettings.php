<?php 


require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/vendor/autoload.php' );
require_once(__DIR__.'/restapi.php');
include_once __DIR__.'/tokencreator.php';
include_once __DIR__.'/tokenverify.php';

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

define("NON_FB_TABLE", "user");

class AccountSettings extends Restapi{
		
	private $signer;

	function __construct(){
		parent::__construct();
		
		$this->signer = new Sha256();

		if(isset($_POST['action'])&&!empty($_POST['action'])&& $_POST['action']==='retrieve'){
			$this->retrieveInfo();
		}
		else if(isset($_POST['action'])&&!empty($_POST['action'])&& $_POST['action']==='update'){
		$this->updateInfo();
		}else{
			$this->changePassword();
		}
	}
	
	/**
	Retrieves account info to be displayed in account settings.html
	Don't retrieve password for security concern purposes
	*/
	function retrieveInfo(){
		
		$token = $_POST['token'];
		
		$parsedToken = TokenCreator::initParseToken( $token );
		$tokenVerifier = new TokenVerify($token,$parsedToken->getToken()->getHeader('jti'));
		
		if($tokenVerifier->isTokenValid() && $parsedToken->getToken()->getClaim('isFb')===false ){
			$result = $this->retrieveNonFbInfo($parsedToken);
		}else{
			$result['error']= TIMED_OUT;	
		}
	
		$this->response($result,200);
	}
	
	function updateInfo(){
		
		$result = array();
		
		$token = $_POST["token"];
		$parsedToken = TokenCreator::initParseToken( $token );
		$tokenVerifier = new TokenVerify($token,$parsedToken->getToken()->getHeader('jti'));

		if($tokenVerifier->isTokenValid() && $parsedToken->getToken()->getClaim('isFb')===false){
		$result = $this->updateNonFbInfo($parsedToken);		
		}else{
			$result['error']= TIMED_OUT;
			$this->response($e->getMessage(), 500);
		}

		$this->response($result,200);
		
	}
	
	function changePassword(){
		$oldPassword = $_POST['old_password'];
		$newPassword = $_POST['new_password'];
		
		$token = $_POST["token"];
		$parsedToken = TokenCreator::initParseToken( $token );
		$tokenVerifier = new TokenVerify($token,$parsedToken->getToken()->getHeader('jti'));
		
		//queries user and checks if old password matches
		$columns = array("password");
		$where=array('userId');
		$values = array($parsedToken->getToken()->getHeader('jti'));
		$limOff = array();
		
		$sql = $this->prepareSelectSql(NON_FB_TABLE,$columns,$where,$limOff);
		
		//only non-fb users can change password
		if($tokenVerifier->isTokenValid() && $parsedToken->getToken()->getClaim('isFb')===false){

		try{
			
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		$result = $stmt->fetchAll();

		$data = array();
		
		if(count($result)==1){
			$result = $result[0];
			
			if(password_verify($oldPassword,$result['password'])){
				$newPassword = password_hash($newPassword, PASSWORD_BCRYPT);

				//update user db
				$values =array($newPassword,$parsedToken->getToken()->getHeader('jti'));
				$sql = $this->prepareUpdateSql(NON_FB_TABLE,$columns,$where);
				$stmt = $this->conn->prepare($sql);
				$stmt->execute($values);
				
				$data['password_match'] = true;
			}else{
				$data['password_match'] = false;
			}
			
		}else{
			$data['password_match'] = false;
		}
		
		}catch(Exception $e){
			$data['error']="Error occured on server";
			$this->response($e->getMessage(), 500);
		}
		
		}else{
			$data['error']=TIMED_OUT;
		}
		
		$this->disconnect();
		
		$this->response($data,200);
	}
	
	private function retrieveNonFbInfo($parsedToken){
		$columns = array("firstName","lastName","city","countryCode");
		$where=array('userId');
		$values = array($parsedToken->getToken()->getHeader('jti'));
		$limOff = array();
		
		$sql = $this->prepareSelectSql(NON_FB_TABLE,$columns,$where,$limOff);
				try{
			
					$this->connect();
		
					$stmt = $this->conn->prepare($sql);
					$stmt->execute($values);
					$result = $stmt->fetchAll();
				}catch(Exception $e){
					$result = null;
					$this->response($e->getMessage(), 500);
				}
		
			$this->disconnect();
		
				if(count($result)==1){
					$result = $result[0];
				}else{
					$result = null;
				}

			return $result;
	}
	
	private function updateNonFbInfo($parsedToken){
		$result = array();
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		
		try{
		$this->connect();

		$columns = array("firstName","lastName","city","countryCode");
		$where=array('userId');
		$values = array($firstName,$lastName,$city,$country,$parsedToken->getToken()->getHeader('jti'));
		
		$sql = $this->prepareUpdateSql(NON_FB_TABLE,$columns,$where);
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		
		$result["success"]=true;


		}catch (Exception $e) {
			$result["success"] = false;
		}
		
		$this->disconnect();
		
		return $result;
	}
}

?>