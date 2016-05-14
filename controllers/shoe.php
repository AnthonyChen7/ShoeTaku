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
		$table = "Shoe";
		$brand = $_POST["brand"];
		$model = $_POST["model"];
		$size = $_POST["size"];
		$itemCnd = $_POST["itemCondition"];
		$description = $_POST["description"];
		$imageUrl = $_POST["imageUrl"];
		$ownerId = $_POST["ownerId"];

		$columns = array("brand", "model", "size", "itemCondition", "description", "imageUrl", "ownerId");
		$values = array($brand, $model, $size, $itemCnd, $description, $imageUrl, $ownerId);

		$sql = $this->prepareInsertSql($table, $columns);
		echo($sql);
		
		if ($method == 'POST'){
			//$json = file_get_contents("php://input");
			
			// Base case: /controllers/shoe   Create a new Shoe
			if ($length == 1)
			{
				$result = $this->createShoe();
				if ($length == 2){
				$id = $requestArray[1];
				if (is_int($id) && $id >= 0){
					$result = $this->editShoe($id);}
				}
			}
		}

		if ($method == 'GET'){
			if ($length == 1) $this->getShoes();
			if ($length == 2){
				$id = $requestArray[1];
				if (is_int($id) && $id >= 0) 
					$result = $this->getShoe($id);
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

	private function createShoe()
	{
		$table = "Shoe";
		$brand = $_POST["brand"];
		$model = $_POST["model"];
		$size = $_POST["size"];
		$itemCnd = $_POST["itemCondition"];
		$description = $_POST["description"];
		$imageUrl = $_POST["imageUrl"];
		$ownerId = $_POST["ownerId"];

		$columns = array("brand", "model", "size", "itemCondition", "description", "imageUrl", "ownerId");
		$values = array($brand, $model, $size, $itemCnd, $description, $imageUrl, $ownerId);

		$sql = $this->prepareInsertSql($table, $columns);
		print($sql);
		echo($sql);

		try
		{
			$this->connect();
			$stmt = $this->conn->prepare($sql);
			$result = $stmt->execute($values);

		} catch (Exception $e) {
			$result = FALSE;
		}

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