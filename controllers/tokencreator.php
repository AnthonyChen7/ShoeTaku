<?php 

//this class will validat and verify the jwt

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

define("EXPIRATION_TIME", 999);
define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');
define("ISSUER", 'ShoeTaku');
define("TIMED_OUT", 'Session timed out!');

class TokenCreator{

private $token;
private $signer;

function __construct(){
		$this->signer = new Sha256();
}

public static function createToken( $userId ) {
    	$instance = new self();
    	$instance->token = (new Builder())
						->setIssuer(ISSUER) // Configures the issuer (iss claim)
						->setId($userId, true)
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setExpiration(time() + EXPIRATION_TIME) // Configures the expiration time of the token (nbf claim)
						->sign($instance->signer, RANDOM_STRING) // creates a signature using "testing" as key
                        ->getToken(); // Retrieves the generated token

    	return $instance;
    }

public static function initParseToken( $token ) {
    	$instance = new self();
    	$instance->token = (new Parser())->parse((string) $token);
    	return $instance;
    }

function getToken(){
	return $this->token;
}

}

?>