<?php

require_once(__DIR__.'/restapi.php');

class Testing extends Restapi
{
	function __construct(){
		parent::__construct();
	}

	public function tests(){
		$method = $_SERVER['REQUEST_METHOD'];

		if ($method == 'POST')
		{
			$json = file_get_contents("php://input");
			$data = $_SERVER['REQUEST_URI'];
			$this->response($data, 200);
		}
	}

}

?>