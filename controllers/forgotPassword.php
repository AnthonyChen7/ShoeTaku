<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' ); 

require_once(__DIR__.'/restapi.php');
require_once(__DIR__.'/validate.php');

require(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\phpmailer\phpmailer\PHPMailerAutoload.php' );

class ForgotPassword extends Restapi{
	
	function __construct(){
		parent::__construct();
		
		$validate = new ValidateForms();
		
		if($validate->isForgotPasswordFormValid()){
			$this->forgotPassword();
		}
		
	}
	
	private function forgotPassword(){

		$mail = new PHPMailer;
		
		$mail->IsSMTP();
		$mail->Host = "smtp.gmail.com";
		
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		//$mail->SMTPDebug = 3;
		//Ask for HTML-friendly debug output
		//$mail->Debugoutput = 'html';
		
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
		// optional
		// used only when SMTP requires authentication  
		$mail->SMTPAuth = true;
		$mail->Username = 'shoetaku97@gmail.com';
		$mail->Password = 'shoetaku123';
		$mail->isHTML(true); 
		
		$mail->setFrom('shoetaku97@gmail.com', 'ShoeTaku');
		$mail->addAddress($_POST['email']);
		$mail->Subject = "Forgot Password";

		
		//check if email exists first
		$table = "user";
		$columns = array("email");
		$where = array("email");
		$values = array($_POST['email']);
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
		
		try{
			
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		//var_dump($result);
		if(count($result)===1){
			
					$mail->Body = "Hello, " . $_POST['email'] . 
					  "<br/ >
						<br />
						You have requested to reset your password, please click on the password reset link below. 
						<br />
						<a href='http://localhost:8080/partials/reset-password.html'>Link goes here</a>
						<br/>
						<br />
						If you didn't request this, please ignore this email.
						<br />
						<br />
						Sincerely, 
						<br / >
						ShoeTaku Administrator";
			
			if(!$mail->send()){
	
				$this->response("Password reset link not successfully sent to email!",500);
			}else{
				$this->response("Password reset link successfully sent to email!",200);
			}
	
		}else{
			$this->response("Email does not exist!",500);
		}
			
		}catch(Exception $e){
			$this->response("Email does not exist!", 500);
		}
		
		
	}
	
	
}

?>