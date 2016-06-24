<?php 

//this class will validat and verify the jwt

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
include_once __DIR__.'/tokencreator.php';

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class TokenVerify{

private $token;
private $signer;
private $data;


function __construct($token,$userId){	
$this->token = (new Parser())->parse((string) $token); // Parses from a string
$this->data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
$this->data->setIssuer(ISSUER);
$this->data->setId($userId);	
}

function isTokenValid(){
	if($this->token->validate($this->data)){
		return true;
	}else{
		return false;
	}
}

}

?>