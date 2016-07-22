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
		// $mail = new PHPMailer;
		
		// //Set PHPMailer to use the sendmail transport
		// $mail->isSendmail();
		
		// $mail->IsSMTP();
		// $mail->Host = "smtp.gmail.com";
		
		// //Enable SMTP debugging
		// // 0 = off (for production use)
		// // 1 = client messages
		// // 2 = client and server messages
		// $mail->SMTPDebug = 2;
		// //Ask for HTML-friendly debug output
		// $mail->Debugoutput = 'html';
		
		// //Set the encryption system to use - ssl (deprecated) or tls
		// $mail->SMTPSecure = 'tls';
		
		// // optional
		// // used only when SMTP requires authentication  
		// $mail->SMTPAuth = true;
		// $mail->Username = 'shoetaku97@gmail.com';
		// $mail->Password = 'shoetaku123';
		
		// $mail->setFrom("from@example.com");
		// $mail->addAddress($_POST['email']);
		// $mail->Subject = "Subject";
		// $mail->Body = "Body";
		
		// if(!$mail->send()){
		// 	echo "Message could not be sent";
		// 	echo 'Mailer Error: ' . $mail->ErrorInfo;
		// }else{
		// 	echo 'Message sent';
		// }
		
		ini_set("SMTP","ssl://smtp.gmail.com");
		ini_set("smtp_port","587");
		
		$to = $_POST['email'];
		$subject = "Subject";
		$txt = 'test';
		$headers = "From yo mama";
		mail($to,$subject,$txt,$headers);
		echo true;
	}
	
	
}

?>