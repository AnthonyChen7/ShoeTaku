<?php 

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
/**

This class handles the non-FB authentication
*/

require_once(__DIR__.'/restapi.php');

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

define("USE_TIME", 60);
define("EXPIRATION_TIME", 3600);
define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');
define("ISSUER", 'ShoeTaku');

class Register extends Restapi{
	
	function __construct()
	{
		parent::__construct();
		$this->register();
	}
	
	private function register(){
		// array to pass back data
		//store token inside this array
		//$data = array();
		$token = NULL;
		$signer = new Sha256();
		
		$result = NULL;
				
		$email = $_POST["email"];
		
		$password = $_POST["password"];
		
		//hash the password using bcrypt method
		$password = password_hash($password,PASSWORD_BCRYPT);
		
		$firstName = $_POST["firstName"];
		$lastName = $_POST["lastName"];
		// $age = $_POST["age"];
		$city = $_POST["city"];
		$country = $_POST["country"];
		
		$table = "user";
		// $columns = array("email","password","firstName","lastName","age","city", "countryCode");
		$columns = array("email","password","firstName","lastName","city", "countryCode");
		$where = array();
		// $values = array($email, $password, $firstName, $lastName, $age, $city, $country);
		$values = array($email, $password, $firstName, $lastName, $city, $country);
		$limOff = array();
		
		$sql = $this->prepareInsertSql($table, $columns, $where, $limOff);
		
		try{
				
		$this->connect();
		$stmt = $this->conn->prepare($sql);
		
		$result = $stmt->execute($values);
		
		//retrieve user ID....
		$columns=array("userId");
		$values = array($email);
		$where=array("email");
		
		$sql = $this->prepareSelectSql($table, $columns, $where ,$limOff);
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($values);
		
		$result = $stmt->fetchAll();
		$result = $result[0];
		
		// $data["success"] = true;
		$token = (new Builder())
						->setIssuer(ISSUER) // Configures the issuer (iss claim)
                        //->setAudience('http://example.org') // Configures the audience (aud claim)
                        ->setId($result['userId'], true) // Configures the id (jti claim), replicating as a header item
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setNotBefore(time() + USE_TIME) // Configures the time that the token can be used (nbf claim)
                        ->setExpiration(time() + EXPIRATION_TIME) // Configures the expiration time of the token (nbf claim)
                        //->set('userId', $result['userId']) // Configures a new claim, called "uid"
						->sign($signer, RANDOM_STRING) // creates a signature using "testing" as key
                        ->getToken(); // Retrieves the generated token
		
		}catch (Exception $e) {
			$token = "error";
		}

		$this->disconnect();
		
		// return all our data to an AJAX call
		echo $token;
	}
	
}


?>
