<?php

class Database{

	private $servername = "localhost";
	private $username = "shoetakusa";
	private $password = "shoetaku";
	private $database = "shoetaku";
	private $contentType = "application/json";
	private $statusCode;
	public $conn;

	function __construct()
	{

	}

	public function connect()
	{
		try
		{
			$this->conn = new PDO("mysql:host=$this->servername;dbname=$this->database", 
				$this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch (PDOException $e)
		{
			$this->response(500, $e->getMessage());
		}
	}
	
	public function disconnect()
	{
		$this->conn = null;
	}

}

?>