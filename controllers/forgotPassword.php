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
		
		$mail->setFrom("from@example.com");
		$mail->addAddress($_POST['email']);
		$mail->Subject = "Subject";
		$mail->Body = "Body";
		
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
		
		if(count($result)===1){
			
			if(!$mail->send()){
			// echo "Message could not be sent";
			// echo 'Mailer Error: ' . $mail->ErrorInfo;
			$this->response("Password reset link not successfully sent to email!",500);
		}else{
			//echo 'Message sent';
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