<?php

require_once(__DIR__.'/restapi.php');
include_once __DIR__.'/validate.php';

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
		
		//Get the id in url
		parse_str( parse_url($url, PHP_URL_QUERY), $parts );
		
		$newPassword = $_POST['new_password'];
		$confirm = $_POST['confirm_new_password'];
		
		try{
			//retrieve user id from id in URL
			$this->connect();
			
			$table = "password_change_requests";
			$columns = array("userId","expiryTime");
			$limOff = array();

			$where = array('id');
			
			$values = array($parts['id']);
			$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($values);

			$result=$stmt->fetchAll();
			
			if(count($result)==1){
				$result = $result[0];
			}else{
				$this->response("Unable to reset password",500);
			}
			
			$table = "user";
			$where = array('userId');
			$columns = array("password");

			$values = array(password_hash($newPassword, PASSWORD_BCRYPT), $result['userId']);
			$sql = $this->prepareUpdateSql($table, $columns, $where);
			$stmt = $this->conn->prepare($sql);
			$stmt->execute($values);
			
			$this->disconnect();
			
			$this->response("success",200);
			
		}catch(Exception $e){
			$this->response($e->getMessage(), 500);
		}
	}
	
}
 

?>