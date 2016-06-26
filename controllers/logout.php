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
		
		$json = file_get_contents("php://input"); 
		$jsonData = json_decode($json,true);

		$tokenCreator = TokenCreator::initParseToken($jsonData["token"]);
		$parsedToken = $tokenCreator->getToken();		
		$table="invalid_token";
		$columns = array('tokenId',"expiryTime");
		$where = array('tokenId');
		$limOff = array();
			
		//only insert if token hasn't expired yet
		$currTime = time();
		$expiryTime = $parsedToken->getClaim('exp');
		
		try{
		
		$this->connect();
			
			if($currTime <= $expiryTime){
				//echo "not expired";
				
				//check if it exists in db
				$values = array($parsedToken->getHeader('jti'));
				$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);
				$stmt = $this->conn->prepare($sql);
				$stmt->execute($values);
				$result = $stmt->fetchAll();
				if(count($result)===1){
					//echo "exists";
				}else{
					//echo "not exists";
					
					//register it to db
					$values = array($parsedToken->getHeader('jti'), $parsedToken);
				}
				
			}else{
				//echo "expired";
			}
		}catch(Exception $e){
			
		}
	
	$this->disconnect();
	
	}
}

?>