<?php 

//this class will create a JWT and convert a JWT into a string

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/vendor/autoload.php' );

include_once __DIR__.'/generateRandomString.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

//time is in seconds
define("EXPIRATION_TIME",120);

define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');
define("ISSUER", 'ShoeTaku');
define("TIMED_OUT", 'Session timed out!');

class TokenCreator{

private $token;
private $signer;
private $stringGenerator;

function __construct(){
		$this->signer = new Sha256();
		$this->stringGenerator = new GenerateRandomString();
}

public static function createToken( $userId, $isFb ) {
    	$instance = new self();
    	$instance->token = (new Builder())
						->setIssuer(ISSUER) 
						->setId($userId, true)
                        ->setIssuedAt(time()) 
                        ->setExpiration(time() + EXPIRATION_TIME)
						->set('isFb', $isFb) 
						->set('random_string', $instance->stringGenerator->generateRandomString())
						->sign($instance->signer, RANDOM_STRING) 
                        ->getToken();

    	return $instance;
}

//Given a JWT, parse it into a string
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