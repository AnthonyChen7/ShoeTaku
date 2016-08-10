<?php

require_once(__DIR__.'/restapi.php');
include_once __DIR__.'/validate.php';
include_once __DIR__.'/tokencreator.php';
include_once __DIR__.'/tokenverify.php';

class ResetPassword extends Restapi{

	function __construct(){
		parent::__construct();
		
		$validate = new ValidateForms();
		if($validate->isResetPasswordFormValid()){
			$this->resetPassword();
		}
		
	}
	
	private function resetPassword(){
		
		//TODO encryption & expiry time validation
		
		//Get the URL
		$url = $_SERVER['HTTP_REFERER'];
		
		//Get the token in url
		parse_str( parse_url($url, PHP_URL_QUERY), $parts );
		
		//validate token first

		$newPassword = $_POST['new_password'];
		$confirm = $_POST['confirm_new_password'];
		
		try{
			
			$parsedToken = TokenCreator::initParseToken($parts['id']);
			$tokenVerifier = new TokenVerify($parts['id'],$parsedToken->getToken()->getHeader('jti'));
			
			if(!$tokenVerifier->isTokenValid()){
			$this->response("Invalid URL!",500);
			}
			
			$this->connect();
			
			 $table = "password_change_requests";
			 $columns = array("*");
			 $limOff = array();

			 $where = array('token');
			
			 $values = array($parts['id']);
			 $sql = $this->prepareSelectSql($table, $columns, $where, $limOff);
			 $stmt = $this->conn->prepare($sql);
			 $stmt->execute($values);

			 $result=$stmt->fetchAll();
			 
			if(count($result)==1){
				$result = $result[0];
				
				//compare token in db with token from url
				//check that they both contain the same random string & header
				$parsedTokenDB = TokenCreator::initParseToken($result['token']);
				
				if( ($parsedToken->getToken()->getHeader('jti') == $parsedTokenDB->getToken()->getHeader('jti') ) &&
				($parsedToken->getToken()->getClaim('random_string') == $parsedTokenDB->getToken()->getClaim('random_string') ) ){
					
			$table = "user";
			$where = array('userId');
			$columns = array("password");

			$values = array(password_hash($newPassword, PASSWORD_BCRYPT), $result['userId']);
			$sql = $this->prepareUpdateSql($table, $columns, $where);
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($values);
			
			$this->disconnect();
					
				}else{
					$this->response("Unable to reset password",500);
				}
				
				// if(time() > $result['expiryTime']){
				// 	$this->disconnect();
				// 	$this->response("This password reset link has expired!",500);
				// }
				
			}else{
				$this->disconnect();
				$this->response("Unable to reset password",500);
			}

			$this->response("success",200);
			
		}
		catch(InvalidArgumentException $e){
			$this->response("Invalid URL!", 500);
		}
		catch(Exception $e){
			$this->response($e->getMessage(), 500);
		}
	}
	
}
 

?>