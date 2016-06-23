<?php 

//this class will validat and verify the jwt

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

define("EXPIRATION_TIME", 60);
define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');
define("ISSUER", 'ShoeTaku');

class TokenCreator{

private $token;
private $signer;

function __construct($userId){
		$this->signer = new Sha256();
		$this->token = (new Builder())
						->setIssuer(ISSUER) // Configures the issuer (iss claim)
						->setId($userId, true)
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setExpiration(time() + EXPIRATION_TIME) // Configures the expiration time of the token (nbf claim)
						->sign($this->signer, RANDOM_STRING) // creates a signature using "testing" as key
                        ->getToken(); // Retrieves the generated token

}

function getToken(){
	return $this->token;
}

}

?>