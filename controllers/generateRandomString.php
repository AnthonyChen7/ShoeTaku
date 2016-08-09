<?php 

class GenerateRandomString{
	
	function __construct(){
		
	}
	
	function generateRandomString(){
		return uniqid('', true);
	}
	
}

?>