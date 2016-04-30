<?php

require_once(__DIR__.'/rest.inc.php');

class Restapi extends Rest
{

	function __construct(){
		parent::__construct();
	}

	private function json($data){
		if(is_array($data)){
			return json_encode($data);
		}
	}

	public function processApi(){
		$requestArray = explode("/", $_REQUEST['x']);
		$cont = $requestArray[0];
		$fileName = $cont.".php";
		$func = $requestArray[1];

		if (file_exists($fileName)){
			require_once(__DIR__."/".$fileName);
	
			$controller = new $cont;

			if((int) method_exists($controller, $func) > 0){
				$data = $controller->$func();
			
			}else{
				$this->response('Function Not Found', 404);
			}
		}
		else
			$this->response('Page Not Found: '.$cont,404);
	}

}

$restApi = new Restapi();
$restApi->processApi();

?>
