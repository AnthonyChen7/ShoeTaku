<?php

require_once(__DIR__.'/rest.inc.php');

class Restapi extends Rest
{

	function __construct()
	{
		parent::__construct();
	}

	private function json($data)
	{
		if(is_array($data)){
			return json_encode($data);
		}
	}

	public function processApi()
	{
		$requestArray = explode("/", $_REQUEST['x']);
		$cont = $requestArray[0];
		$fileName = $cont.".php";

		if (file_exists($fileName)){
			require_once(__DIR__."/".$fileName);
	
			$controller = new $cont;

		}
		else
			$this->response('Page Not Found: '.$cont,404);
	}

	public function redirect($url)
	{
    	header('Location: http://' . $url);
    	exit();
	}

}

$restApi = new Restapi();
$restApi->processApi();

?>
