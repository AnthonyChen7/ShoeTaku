<?php 


require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '\vendor\autoload.php' );
require_once(__DIR__.'/restapi.php');

define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');
define("ISSUER", 'ShoeTaku');

use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;

class AccountSettings extends Restapi{
	
	function __construct(){
		parent::__construct();
		
		$method = $_SERVER['REQUEST_METHOD'];
		
		if($method == 'GET'){
			$this->retrieveInfo();
		}
	}
	
	function retrieveInfo(){
		$token = $_GET["token"];
		
		//parse the token
		$token = (new Parser())->parse((string)$token);
		
		//validate & verify token first...
		$signer = new Sha256();
		
		$data = new ValidationData();
		$data->setIssuer(ISSUER);
		//$data->setId($token->getClaim('jti'));
		
		if($token->verify($signer,RANDOM_STRING) && $token->validate($data)){
			
			echo "true";
			
		}
	}
}

?>