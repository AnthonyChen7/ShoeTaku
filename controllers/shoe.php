<?php

require_once(__DIR__.'/restapi.php');
include(__DIR__.'/tokencreator.php');
include(__DIR__.'/tokenverify.php');

class Shoe extends Restapi
{

	function __construct()
	{
		parent::__construct();
		$this->processEndpoints();
	}

	private function processEndpoints()
	{
		$result = false;
		$method = $_SERVER['REQUEST_METHOD'];
		$requestArray = explode("/", $_REQUEST['x']);
		$length = count($requestArray);
		$json = file_get_contents("php://input");
		// $this->response($json,200);
		$elementCount;
		$data;
		if ($json){
			$data = json_decode($json, TRUE);
			$elementCount  = count($data);
		}
		
		if ($method == 'POST'){
			// Base case: /controllers/shoe   Create a new Shoe

			if ($length == 1){
				// handles pagination
				if($elementCount == 2){
					$page = $data['page'];
					$per_page = $data['per_page'];
					// if($page!=1){
					// 	$this->response($page,200);
					// }
					if($page < 1){
						$pageNumError = "Current Page cannot be less than 1";
						$this->response($pageNumError,400);
					}

					if($page != 1){
						$start = ($page - 1) * $per_page;
					}else{
						$start = 0;
					}

					$numArticles = $this->getTotalNumberOfPosts($data);
					$totalNumPage = ceil($numArticles[0] / $per_page); // Total number of page

					$result['articleList'] = $this->getListofSellPosts($data,$start);
					$result['totalNumPage'] = $totalNumPage;

 				}
 				// create shoe button
 				// 7 json objects passed on from sellPage-controller.js
 				else if($elementCount == 9){
 					$result = $this->createShoe($data);
 				}
				else{
				}
				
				$this->response($result, 200);
			}
			if ($length == 2){
				$id = $requestArray[1];
				if (is_int($id) && $id >= 0){
					$result = $this->editShoe($id);
				}
			}
		}

		if ($method == 'GET'){
			if ($length == 1) $this->getShoes();
			if ($length == 2){
				$id = $requestArray[1];
				if (is_int($id) && $id >= 0){
					$result = $this->getShoe($id);
				}
			}
		}

		if ($method == 'DELETE'){
			if ($length == 1) $result = false;
			if ($length == 2){
				$id = $requestArray[1];
				if (is_int($id) && $id >= 0) 
					$result = $this->deleteShoe($id);
			}
		}
		if ($result){
			// if good request
		}else{
			// if bad request
		}

	}

	private function getListofSellPosts($data,$start){
		
		$sql = "SELECT shoeId,title,price,created
				FROM Sell 
				ORDER BY created DESC LIMIT 10 OFFSET ". $start;

		$this->connect();
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();

		$stmt->setFetchMode(PDO::FETCH_OBJ);

		$shoeInfo_array = array();
		$shoePostList_array["shoePostArray"] = array();
		
		while( $result = $stmt->fetch() ) {
			$shoeInfo_array["shoeId"] = $result->shoeId;
			$shoeInfo_array["title"] = $result->title;
			$shoeInfo_array["price"] = $result->price;
			$shoeInfo_array["created"] = $result->created;

			array_push($shoePostList_array["shoePostArray"], $shoeInfo_array);
		}

		$this->disconnect();

		return $shoePostList_array;

	}

	private function getTotalNumberOfPosts($data){

		$table = "Sell";
		$columns = array("count(*)");
		$where = array();
		$limOff = array();

		$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);

		$this->connect();
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_NUM);
		$this->disconnect();

		return $result;
	}

	private function createShoe($data)
	{
		$table = "Shoe";
		$title = $data["title"];

		$token = $data['token'];
		$parsedToken = TokenCreator::initParseToken( $token );
		$tokenVerifier = new TokenVerify($token,$parsedToken->getToken()->getHeader('jti'));
		if(!($tokenVerifier->isTokenValid())){
			return false;
		}
		$userID = $parsedToken->getToken()->getHeader('jti');
		
		if($title == null || (strlen($title)>30)){
			return false;
		}		
		$brand = $data["shoeBrand"];
		if($brand == "-- Select a Brand --" || $brand == null){
			return false;
		}
		$model = $data["shoeModel"];
		if($model == null || (strlen($model)>25)){
			return false;
		}
		$size = $data["shoeSize"];
		if($size == 15){
			return false;
		}
		$itemCnd = $data["itemCondition"];
		if($itemCnd == 6){
			return false;
		}
		$description = $data["description"];
		if($description == null){
			return false;
		}
		// get userID
		$ownerId = 3;//$data["ownerId"];

		// 1 is for sell
		// 0 is for buy
		$isWanted = $data["isWanted"];
		$price = $data["price"];

		// imgur API 
		$url = null;
		
		// if(isset($data["image"]))
		// {
		// 	$img = $data["image"];

		// 	if (isset($data['submit'])){ 
		// 		if($img['name']==''){  
		// 			echo "<h2>An Image Please.</h2>";
		// 		}else{
		// 			$filename = $img['tmp_name'];
		// 			// $client_id = imgur client id; 
		// 			$handle = fopen($filename, "r");
		// 			$data = fread($handle, filesize($filename));
		// 			$pvars   = array('image' => base64_encode($data));
		// 			$timeout = 30;
					
		// 			$curl = curl_init();
		// 			curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
		// 			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		// 			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
		// 			curl_setopt($curl, CURLOPT_POST, 1);
		// 			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 			curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
		// 			$out = curl_exec($curl);
		// 			curl_close ($curl);
		// 			$pms = json_decode($out, true);
		// 			$url = $pms['data']['link'];
		// 	 	}
		// 	}
		// }else if(isset($data["url"])){
		// 	$url = $data["url"];

		// }else{
		// 	// No $url is set
		// 	// error!!!
		// }
		$limOff = array();
		$columns = array("brand", "model", "size", "itemCondition", "description", "imageUrl", "ownerId", "isWanted");
		$values = array($brand, $model, $size, $itemCnd, $description, $url, $ownerId, $isWanted);
		$sql = $this->prepareInsertSql($table, $columns, $limOff);


		try
		{
			$this->connect();
			$stmt = $this->conn->prepare($sql);
			$result = $stmt->execute($values);
			$shoeId = $this->conn->lastInsertId();
			//$this->redirect($_SERVER['SERVER_NAME']);

		} catch (Exception $e) {
			error_log("db exception");
			$result = FALSE;
			//c$this->redirect($_SERVER['SERVER_NAME']);
		}
		
		$this->disconnect();

		// SELL shoe creation
		if($isWanted == 1){
			$table = "Sell";
			$limOff = array();
			$created = date("Y-m-d H:i:s"); ;
			$columns = array("title", "price", "shoeId","created");
			$values = array($title, $price, $shoeId, $created);
			$sql = $this->prepareInsertSql($table, $columns, $limOff);

			try
			{
				$this->connect();
				$stmt = $this->conn->prepare($sql);
				$result = $stmt->execute($values);
				//$this->redirect($_SERVER['SERVER_NAME']);

			} catch (Exception $e) {
				error_log("db exception");
				$result = FALSE;
				//c$this->redirect($_SERVER['SERVER_NAME']);
			}
			$this->disconnect();
		}
				
		// }
		
		return $sql;
	}

	// pass shoeID and find corresponding userID
	private function editShoe($id)
	{

	}


	/** 
	whats the difference between getShoes and getShoe
	*/
	private function getShoes()
	{

	}

	private function getShoe($id)
	{

	}


	private function deleteShoe($id)
	{

	}

}

?>