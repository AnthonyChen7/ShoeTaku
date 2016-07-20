<?php 
require_once(__DIR__.'/restapi.php');
include_once (__DIR__.'/validate.php');

class ForgotPassword extends Restapi{
	
	function __construct(){
		parent::__construct();
		
		$validate = new ValidateForms();
		
		if($validate->isForgotPasswordFormValid()){
			$this->forgotPassword();
		}
		
	}
	
	private function forgotPassword(){
		return true;
	}
	
	
}

?>