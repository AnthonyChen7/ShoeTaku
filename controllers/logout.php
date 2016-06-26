<?php 
//stores invalid token in db on logout
require_once(__DIR__.'/restapi.php');
include_once __DIR__.'/tokencreator.php';

class Logout extends Restapi{
	
	function __construct(){
		parent::__construct();
		$this->storeTokenInDB();
	}
	
	/**
	Token is considered invalid when user logs out
	Store it in db
	*/
	private function storeTokenInDB(){

		$tokenCreator = TokenCreator::initParseToken($_POST["token"]);
		$parsedToken = $tokenCreator->getToken();		
		$table="invalid_token";
		$columns = array('tokenId',"token","expiryTime");
		$where = array('tokenId');
		$limOff = array();
			
		//only insert if token hasn't expired yet
		$currTime = time();
		$expiryTime = $parsedToken->getClaim('exp');
		
		try{
		
		$this->connect();
			
			if($currTime <= $expiryTime){
				//echo "not expired";

					//register it to db
					$values = array($parsedToken->getHeader('jti'), $parsedToken,$expiryTime);
					$sql = $this->prepareInsertSql($table, $columns, $where, $limOff);
					$stmt = $this->conn->prepare($sql);
					$stmt->execute($values);
					
				$this->disconnect();
				$this->response("success",200);
			}
		}catch(Exception $e){
			$this->response("error occurred",200);
		}
	
	
	
	}
}

?>