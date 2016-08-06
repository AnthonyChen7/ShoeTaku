<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' ); 

require_once(__DIR__.'/restapi.php');
require_once(__DIR__.'/validate.php');

require(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\phpmailer\phpmailer\PHPMailerAutoload.php' );

//in sec
define("EXPIRY_TIME",120);

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

		
		//check if email exists first in db
		$table = "user";
		$columns = array("userId,email");
		$where = array("email");
		$values = array($_POST['email']);
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
		
		try{
			
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		
		$this->disconnect();
		
		if(count($result)===1){
					
					$object = $result[0];
					
					$this->connect();
					
					//check if there was already a password request within the last 24 hours
					$table = "password_change_requests";
					$columns=array("expiryTime");
					$values = array($object['userId']);
					$where = array("userId");
					$limOff = array();
					
					$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
					
					$stmt = $this->conn->prepare($sql);
		
					$stmt->execute($values);
					$temp = $stmt->fetchAll();
					
					if(count($temp > 0)){
						foreach($temp as $value){
							if(time() < $value['expiryTime']){
								//there is already a non-expired record
								$this->disconnect();
								$this->response("A password reset link has already been sent to the specified email!",500);
							}
						}
					}
					
					// no record exists or record is already expired... so insert
					$columns=array("expiryTime","userId");
					$values = array(time()+EXPIRY_TIME,$object['userId']);
					$sql = $this->prepareInsertSql($table,$columns);
					
					$stmt = $this->conn->prepare($sql);
		
					$stmt->execute($values);
					
				 	$idInserted = $this->conn->lastInsertId();
					
					$this->disconnect();
					
					$mail->Body = "Hello, " . $_POST['email'] . 
					  "<br/ >
						<br />
						You have requested to reset your password, please click on the password reset link below. 
						<br />
						<a href='http://localhost:8080/partials/reset-password.html?id=" . $idInserted ." '> http://localhost:8080/partials/reset-password.html?id=" . $idInserted . "</a>
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
			$this->response($e->getMessage(), 500);
		}
		
		
	}
	
	
}

?>