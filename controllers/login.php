<?php 

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );

/**
This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

define("USE_TIME", 60);
define("EXPIRATION_TIME", 60);
define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');
define("ISSUER", 'ShoeTaku');

class Login extends Restapi{
	
	function __construct()
	{
		parent::__construct();

		$this->checkCredentials();
		
	}
	
	private function checkCredentials(){
		
		//store data inside the array to pass back
		
		
		$token = NULL;
		      
		$signer = new Sha256();
		
		$email = $_POST["email"];
		$password = $_POST["password"];
		
		$table = "user";
		$columns = array("userId","email","password");
		$where = array("email");
		$values = array($email);
		$limOff = array();
		
		$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);		
		$this->connect();
		
		$stmt = $this->conn->prepare($sql);
		
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		
		if(count($result)===1){
		
		/**
		Since email is a unique key
		we would expect there to be only 1 result
		**/
			
		$object = $result[0];
			
		if($email === $object['email'] && password_verify($password,$object['password'])){

		$token = (new Builder())
						->setIssuer(ISSUER) // Configures the issuer (iss claim)
                        //->setAudience('http://example.org') // Configures the audience (aud claim)
                        ->setId($object['userId'], true) // Configures the id (jti claim), replicating as a header item
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setNotBefore(time() + USE_TIME) // Configures the time that the token can be used (nbf claim)
                        ->setExpiration(time() + EXPIRATION_TIME) // Configures the expiration time of the token (nbf claim)
                        //->set('userId', $object['userId']) // Configures a new claim, called "uid"
						->sign($signer, RANDOM_STRING) // creates a signature using "testing" as key
                        ->getToken(); // Retrieves the generated token	
				
			}

		}

		$this->disconnect();

		// return all our data to an AJAX call
		echo $token;
		
	}
	
}


?>
