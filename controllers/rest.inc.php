<?php

require_once(__DIR__.'/sql-builder.php');

class REST extends SqlQueryBuilder
{
	
	public $_allow = array();
	public $_content_type = "application/json";
	public $_request = array();
	
	private $_method = "";		
	private $_code = 200;
	
	public function __construct()
	{

	}
	
	public function get_referer()
	{
		return $_SERVER['HTTP_REFERER'];
	}
	
	public function response($data,$status)
	{
		$this->_code = ($status)?$status:200;
		$this->set_headers();
		echo json_encode($data);
		exit;
	}
	// For a list of http codes checkout http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	private function get_status_message()
	{
		$status = array(
					200 => 'OK',
					201 => 'Created',  
					204 => 'No Content',
					400 => 'Bad Request',
					401 => 'Unauthorized',
					404 => 'Not Found',
					405 => 'Method Not Allowed',  
					406 => 'Not Acceptable',
					500 => 'Internal Server Error');
		return ($status[$this->_code])?$status[$this->_code]:$status[500];
	}
	
	private function set_headers()
	{
		header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
		header("Content-Type: ".$this->_content_type);
	}
}	
?>