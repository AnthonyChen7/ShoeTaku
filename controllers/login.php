<?php 

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\firebase\php-jwt\src\JWT.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\lcobucci\jwt\src\Configuration.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\gamegos\jwt\src\Token.php');
//require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\gamegos\jwt\src\Encoder.php');

//use Firebase\JWT;
//use \firebase\php-jwt;
//use \firebase\jwt\src;


use Lcobucci\JWT\Builder;

class Login extends Restapi{
	
	function __construct()
	{
		parent::__construct();

		
		$this->checkCredentials();
		
	}
	
	private function checkCredentials(){
		// array to pass back data
		$data = array();
		
		$token = array();      
		
		$email = $_POST["email"];
		$password = $_POST["password"];
		
		$table = "user";
		$columns = array("*");
		$where = array("email", "password");
		$values = array($email, $password);
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);		
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		$this->disconnect();
		
		if(count($result)===1){
			$data["success"] = true;
			$data["email"] = $email;
			
			$token["success"] = true;
			$token["email"] = $email;
			
			

// $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
//                         ->setAudience('http://example.org') // Configures the audience (aud claim)
//                         ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
//                         ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
//                         ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
//                         ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
//                         ->set('uid', 1) // Configures a new claim, called "uid"
//                         ->getToken(); // Retrieves the generated token


// $token->getHeaders(); // Retrieves the token headers
// $token->getClaims(); // Retrieves the token claims
			
			//JWT::encode($token, 'secret_server_key');
			//$config = new Configuration(); // This object helps to simplify the creation of the dependencies
                               // instead of using "?:" on constructors.
						
		}else{
			$data["success"] = false;
			
			$token["success"] = false;
			$token["email"] = "";
		}
		
		
		
		// return all our data to an AJAX call
    	echo json_encode($data);
	}
	
}


?>
