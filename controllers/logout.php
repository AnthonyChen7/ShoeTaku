<?php 
//stores invalid token in db on logout
require_once(__DIR__.'/restapi.php');
include_once __DIR__.'/tokencreator.php';

class Logout extends Restapi{
	
	function __construct(){
		parent::_construct();
		$this->storeTokenInDB();
	}
	
	/**
	Token is considered invalid when user logs out
	Store it in db
	*/
	private function storeTokenInDB(){
		
		$token = $_POST["token"];
		$tokenCreator = TokenCreator::initParseToken($token);
		$parsedToken = $tokenCreator->getToken();
		
		$currTime = time();
		
		$table="invalid_token";
		$columns=array("tokenId","expiry_time");
		$values=array();
		$limOff = array();
		$where=array();
		
		$sql = $this->prepareInsertSql($table,$columns,$where,$limOff);
		
		try{
			// $this->connect();
			
			//TODO
			//Also, if token is already expired. Don't bother to store in db
			
			
			
			// $this->disconnect();
		}
	}
	
}

?>