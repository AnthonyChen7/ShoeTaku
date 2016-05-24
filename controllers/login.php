<?php 

require_once(__DIR__.'/restapi.php');

class Login extends Restapi{
	
	function __construct()
	{
		parent::__construct();
		$this->doSomething();
	}
	
	private function doSomething(){
		
		
		$result = "method is ";
		
		$this->response($result, 200);
	}
	
}
 
// $uid = $_POST["uid"];
// $upass = $_POST["password"];

// echo "uid is " + $uid;
// echo "pass is " + $upass;

// $host = 'localhost:8080';
// $user = 'root';
// $password = '';

// //opens/connects to an sql server
// $connection = mysqli_connect($host, $user, $password, 'shoetaku');

// $query = "select email,password from user where email ="+ $uid+ " and password =" + $upass;
// $result = mysqli_query($connection,$query);

// if($result){
// 	echo "Success";
// }else{
// 	echo "not success";
// }

// mysqli_close($connection);

?>
