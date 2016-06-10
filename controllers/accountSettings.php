<?php 

require_once(__DIR__.'/restapi.php');

define("RANDOM_STRING", '70bpyytrEVHXNC99PvjKfNcgHLwByB2B9eGExqiBYSG6LdnjdT2q9nARwCKWVNy');

class AccountSettings extends Restapi{
	
	function __construct(){
		parent::__construct();
		
		$method = $_SERVER['REQUEST_METHOD'];
		
		echo $_GET["token"];
	}
}

?>