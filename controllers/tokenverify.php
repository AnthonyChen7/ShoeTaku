<?php 

//this class will validate and verify the jwt

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/vendor/autoload.php' );
include_once __DIR__.'/tokencreator.php';
require_once(__DIR__.'/restapi.php');

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class TokenVerify extends Restapi{

private $token;
private $signer;
private $data;


function __construct($token,$userId) {	
$this->token = (new Parser())->parse((string) $token); // Parses from a string
$this->data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
$this->data->setIssuer(ISSUER);
$this->data->setId($userId);
$this->signer = new Sha256();	
}

//If token hasn't been messed with and hasn't expire, true is returned
function isTokenValid(){
	if($this->token->validate($this->data) && $this->token->verify($this->signer,RANDOM_STRING) && !$this->isTokenInDb() ){
		return true;
	}else{
		return false;
	}
}

//make public for now...
function isTokenInDb(){
	$result = false;
	
	$table = "invalid_token";
	$columns = array("*");
	$where = array("token");
	//$values=array("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjMifQ.eyJpc3MiOiJTaG9lVGFrdSIsImp0aSI6IjMiLCJpYXQiOjE0NjY5ODI2MzksImV4cCI6MTU2Njk4MjYzOH0.wHsDnRmW2e50q1GFI1VN4BG0ajl2L_UKWJoEjb77c9g");
	$values=array($this->token);
	$limOff = array();
	
	$sql = $this->prepareSelectSql($table,$columns,$where,$limOff);
	
	try{
		
	$this->connect();
	
	$stmt = $this->conn->prepare($sql);
		
	$stmt->execute($values);
		
	$result = $stmt->fetchAll();
	
	if(count($result)>0){
		$result = true;	
	}
	
	$this->disconnect();	
		
	}catch(Exception $e){
		$result = true;
	}
	
	return $result;
}

}

?>