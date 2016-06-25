<?php

require_once(__DIR__.'/restapi.php');

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
		$data;
		if ($json){
				$data = json_decode($json, TRUE);
				// json error detector
				// switch(json_last_error())
			 //        {
			 //            case JSON_ERROR_DEPTH:
			 //                $error =  ' - Maximum stack depth exceeded';
			 //                break;
			 //            case JSON_ERROR_CTRL_CHAR:
			 //                $error = ' - Unexpected control character found';
			 //                break;
			 //            case JSON_ERROR_SYNTAX:
			 //                $error = ' - Syntax error, malformed JSON';
			 //                break;
			 //            case JSON_ERROR_NONE:
			 //            default:
			 //                $error = '';                    
			 //        }
			 //        if (!empty($error))
			 //            throw new Exception('JSON Error: '.$error);
			}
		
		if ($method == 'POST'){
			// Base case: /controllers/shoe   Create a new Shoe
			if ($length == 1){
				if(isset($data)){

					$page = $data['page'];
					$per_page = $data['per_page']; 


					if($page != 1){
						$start = ($page - 1) * $per_page;
					}else{
						$start = 0;
					}


					$numArticles = $this->getTotalNumberOfPosts($data);

					$numPage = ceil($numArticles[0] / $per_page); // Total number of page

					$result['articleList'] = $this->getListofSellPosts($data,$start);
					$result['page'] = $page;
					$result['numPage'] = $numPage;

					$result['convertJSON'] = $this->getListofSellPosts($data,$start);
 				}
				else{
					$result = $this->createShoe();
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
		$table = "Shoe";
		$columns = array("shoeId,brand,model");
		$where = array();
		$limOff = array(4,$start);

		$sql = $this->prepareSelectSql($table, $columns, $where, $limOff);
		$this->connect();
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();

		$stmt->setFetchMode(PDO::FETCH_OBJ);
		$articleList = '';
		while( $result = $stmt->fetch() ) {
			$articleList .= '<div id = "sellPost" class="well well-sm">' . $result->shoeId . '. <b>' . $result->brand . '</b><p>' . $result->model . '</p></div>';
		}
		$articleList = json_encode($articleList);
		$this->disconnect();

		// return $result;
		return $articleList;

	}

	private function getTotalNumberOfPosts($data){
		$totalNumOfPosts = $data["totalNumberOfPost"];

		$table = "Shoe";
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

	private function createShoe()
	{
		$table = "Shoe";
		$brand = $_POST["brand"];
		$model = $_POST["model"];
		$size = $_POST["size"];
		$itemCnd = $_POST["itemCondition"];
		$description = $_POST["description"];
		$ownerId = 0;//$_POST["ownerId"];
		$isWanted = $_POST["isWanted"];
		$url;
		
		if(isset($_POST["image"]))
		{
			$img = $_POST["image"];

			if (isset($_POST['submit'])){ 
				if($img['name']==''){  
					echo "<h2>An Image Please.</h2>";
				}else{
					$filename = $img['tmp_name'];
					// $client_id = imgur client id; 
					$handle = fopen($filename, "r");
					$data = fread($handle, filesize($filename));
					$pvars   = array('image' => base64_encode($data));
					$timeout = 30;
					
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
					curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
					curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
					$out = curl_exec($curl);
					curl_close ($curl);
					$pms = json_decode($out, true);
					$url = $pms['data']['link'];
			 	}
			}
		}else if(isset($_POST["url"])){
			$url = $_POST["url"];

		}else{
			// No $url is set
			// error!!!
		}

		$columns = array("brand", "model", "size", "itemCondition", "description", "imageUrl", "ownerId", "isWanted");
		$values = array($brand, $model, $size, $itemCnd, $description, $url, $ownerId, $isWanted);
		$sql = $this->prepareInsertSql($table, $columns);

		try
		{
			$this->connect();
			$stmt = $this->conn->prepare($sql);
			$result = $stmt->execute($values);

			$this->redirect($_SERVER['SERVER_NAME']);

		} catch (Exception $e) {
			$result = FALSE;
			//c$this->redirect($_SERVER['SERVER_NAME']);
		}
		$this->disconnect();

		return $result;
	}

	private function editShoe($id)
	{

	}

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