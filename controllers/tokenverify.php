<?php 

//this class will validate and verify the jwt

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
include_once __DIR__.'/tokencreator.php';

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class TokenVerify{

private $token;
private $signer;
private $data;


function __construct($token,$userId){	
$this->token = (new Parser())->parse((string) $token); // Parses from a string
$this->data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
$this->data->setIssuer(ISSUER);
$this->data->setId($userId);
$this->signer = new Sha256();	
}

//If token hasn't been messed with and hasn't expire, true is returned
function isTokenValid(){
	if($this->token->validate($this->data) && $this->token->verify($this->signer,RANDOM_STRING) ){
		return true;
	}else{
		return false;
	}
}

//make public for now...
function isTokenValid(){
	$result = true;
}

}

?>