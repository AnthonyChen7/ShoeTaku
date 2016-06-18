<?php

class ViewController
{
	protected $ROOT = "..";
	private $html;

	function __construct(){
		$this->html = $this->ROOT."/partials/error.html"; 
	}

	public function renderView(){
		
		$method = $_SERVER['REQUEST_METHOD'];

		if ($method == 'POST')
		{
			$json = file_get_contents("php://input");
			$data = json_decode($json, TRUE);
			var_dump($data);
			if (isset($data))
			{
				$page = $data['page'];
				if ($page == "")
					$this->html = $this->ROOT."/index.html";
				else
					$this->html = $this->ROOT."/partials/".$page.".html";
			}
		}

		readfile($this->html);
	}
}

$viewController = new ViewController();
$viewController->renderView();

?>