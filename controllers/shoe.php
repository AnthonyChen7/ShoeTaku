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
		
		if ($method == 'POST'){
			$json = file_get_contents("php://input");
			
			// Base case: /controllers/shoe   Create a new Shoe
			if ($length == 1)
			{
				$result = $this->createShoe();
				if ($length == 2){
				$id = $requestArray[1];
				if (is_int($id) && $id >= 0) 
					$result = $this->editShoe($id);
				}
			}
		}

		if ($method == 'GET'){
			if ($length == 1) $this->getShoe();
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

	}

	private function editShoe($id)
	{

	}

	private function getShoe()
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