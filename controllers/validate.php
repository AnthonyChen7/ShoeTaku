<?php 

define("INVALID_EMAIL", "Invalid Email Entered!");
define("INVALID_PASSWORD", "Invalid Password Entered!");
define("INVALID_CITY", "Invalid City Entered!");
define("INVALID_FIRSTNAME", "Invalid First Name Entered!");
define("INVALID_LASTNAME", "Invalid Last Name Entered!");
define("INVALID_COUNTRY", "Invalid Country Entered!");

define("INVALID_CONFIRM_PASSWORD", "Please confirm your password!");
define("PASSWORD_NOT_MATCH", "Passwords don't match!");

require_once(__DIR__.'/restapi.php');
include_once (__DIR__.'/countries.php');

class ValidateForms extends Restapi {
	
	function __construct() {
		parent::__construct();
	}
	
	function isLoginFormValid(){
		
		if(empty($_POST['email']) || ctype_space( $_POST['email'] )  ){
			$this->response(INVALID_EMAIL,400);
		}
		else if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
			$this->response(INVALID_EMAIL,400);
		}
		
		if(empty($_POST['password']) || ctype_space( $_POST['password'] )  ){
			$this->response(INVALID_PASSWORD,400);
		}
		
		return true;
		
	}
	
	function isRegisterFormValid(){
		
		$isCountryValid = new Countries();
		
		if(empty($_POST['email']) || ctype_space( $_POST['email'] )  ){
			$this->response(INVALID_EMAIL,400);
		}
		else if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
			$this->response(INVALID_EMAIL,400);
		}

		if(empty($_POST['password']) || ctype_space( $_POST['password'] )  ){
			$this->response(INVALID_PASSWORD,400);
		}
		
		if(empty($_POST['confirmPassword']) || ctype_space( $_POST['confirmPassword'] )  ){
			$this->response(INVALID_CONFIRM_PASSWORD,400);
		}else if($_POST['confirmPassword'] != $_POST['password']){
			$this->response(PASSWORD_NOT_MATCH,400);
		}

		if(empty($_POST['firstName']) || ctype_space( $_POST['firstName'] )  ){
			$this->response(INVALID_FIRSTNAME,400);
		}
		
		if(empty($_POST['lastName']) || ctype_space( $_POST['lastName'] )  ){
			$this->response(INVALID_LASTNAME,400);
		}
		
		if(empty($_POST['city']) || ctype_space( $_POST['city'] )  ){
			$this->response(INVALID_CITY,400);
		}
		
		if(empty($_POST['country']) || ctype_space( $_POST['country'] )  ){
			$this->response(INVALID_COUNTRY,400);
		}
		else if($isCountryValid->isCountryValid($_POST['country'])===false){
			$this->response(INVALID_COUNTRY,400);
		}
		
		return true;
		
	}
	
	function isUpdateFormValid(){
		
		$isCountryValid = new Countries();
		
		if(empty($_POST['firstName']) || ctype_space( $_POST['firstName'] )  ){
			$this->response(INVALID_FIRSTNAME,400);
		}
		
		if(empty($_POST['lastName']) || ctype_space( $_POST['lastName'] )  ){
			$this->response(INVALID_LASTNAME,400);
		}
		
		if(empty($_POST['city']) || ctype_space( $_POST['city'] )  ){
			$this->response(INVALID_CITY,400);
		}
		
		if(empty($_POST['country']) || ctype_space( $_POST['country'] )  ){
			$this->response(INVALID_COUNTRY,400);
		}
		else if($isCountryValid->isCountryValid($_POST['country'])===false){
			$this->response(INVALID_COUNTRY,400);
		}
		
		return true;
	}
	
	function isPasswordFormValid(){
		
		$isCountryValid = new Countries();
		
		if(empty($_POST['old_password']) || ctype_space( $_POST['old_password'] )  ){
			$this->response("Please enter your old password!",400);
		}
		
		if(empty($_POST['new_password']) || ctype_space( $_POST['new_password'] )  ){
			$this->response("Please enter a new valid password!",400);
		}
		
		if(empty($_POST['confirm_new_password']) || ctype_space( $_POST['confirm_new_password'] )  ){
			$this->response(INVALID_CONFIRM_PASSWORD,400);
		}
		else if($_POST['new_password'] != $_POST['confirm_new_password']){
			$this->response(PASSWORD_NOT_MATCH,400);
		}
		
		return true;
	}
	
	function isResetPasswordFormValid(){
		if(empty($_POST['new_password']) || ctype_space( $_POST['new_password'] )  ){
			$this->response("Please enter a new valid password!",400);
		}
		
		if(empty($_POST['confirm_new_password']) || ctype_space( $_POST['confirm_new_password'] )  ){
			$this->response(INVALID_CONFIRM_PASSWORD,400);
		}
		else if($_POST['new_password'] != $_POST['confirm_new_password']){
			$this->response(PASSWORD_NOT_MATCH,400);
		}
		
		return true;
	}
	
	function isForgotPasswordFormValid(){
		
		if(empty($_POST['email']) || ctype_space( $_POST['email'] )  ){
			$this->response(INVALID_EMAIL,400);
		}
		else if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
			$this->response(INVALID_EMAIL,400);
		}
		
		return true;
	}
	
}

?>