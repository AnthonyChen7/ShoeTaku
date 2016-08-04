<?php

require_once(__DIR__.'/restapi.php');

class ResetPassword extends Restapi{
	
	function __construct(){
		parent::__construct();
		$this->resetPassword();
	}
	
	private function resetPassword(){
		
		//Get the URL
		$url = $_SERVER['HTTP_REFERER'];
		
		//Get the id in url
		parse_str( parse_url($url, PHP_URL_QUERY), $parts );
		
		$newPassword = $_POST['new_password'];
		$confirm = $_POST['confirm_new_password'];
		
		try{
			
			//TODO retrieve user id from pass table
			//TODO update user table with new password
			
		}catch(Exception $e){
			
		}
	}
	
}
 

?>