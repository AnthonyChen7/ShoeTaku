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

		$sql = $this->prepareInsertSql($table, $columns);
		
		if ($method == 'POST'){
			
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
		$ownerId = 1;//$_POST["ownerId"];
		$isWanted = $_POST["isWanted"];

		$columns = array("brand", "model", "size", "itemCondition", "description", "imageUrl", "ownerId", "isWanted");
		$values = array($brand, $model, $size, $itemCnd, $description, $imageUrl, $ownerId, $isWanted);

		$sql = $this->prepareInsertSql($table, $columns);

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