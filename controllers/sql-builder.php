<?php

require_once(__DIR__.'/database.php');

class SqlQueryBuilder extends Database
{
	protected $INSERT = "INSERT INTO ";
	protected $VALUES = "VALUES ";
	protected $SELECT = "SELECT ";
	protected $UPDATE = "UPDATE ";
	protected $SET = "SET ";
	protected $BIND = "?";
	protected $SPACE = " ";
	protected $COMMA = ", ";
	protected $VALUEBIND = "= ? ";
	protected $WHERE = " WHERE ";
	protected $WHEREDELIMIT = " AND ";
	protected $FROM = " FROM ";

	function __construct()
	{
		parent::__construct();
	}

	public function prepareInsertSql($tableName, $columnArr)
	{	
		if (count($columnArr) == 0 || is_null($tableName))
		{
			return false;
		}
		$columns = $columnArr;
		$table = $tableName.$this->SPACE;
		$columnSql;
		$valueSql;

		$valueBind = $this->BIND;
		for ($i = 0; $i < count($columns) - 1; $i++)
		{
			$valueBind .= $this->COMMA.$this->BIND;
		}
		$valueBind = "(".$valueBind.")";
		
		$columnSql = implode($this->COMMA, $columns);
		$columnSqlParan = "(".$columnSql.") ";
		
		$sql = $this->INSERT.$table.$columnSqlParan.$this->VALUES.$valueBind;

		return $sql;
	}

	public function prepareUpdateSql($tableName, $columnArr, $whereArr)
	{
		if (is_null($tableName) || count($columnArr) == 0  || count($whereArr) == 0)
		{
			return false;
		}
		$columns = $columnArr;
		$where = $whereArr;
		$table = $tableName.$this->SPACE;
		$valueSql;
		$whereSql;

		$valueSql = $this->prepareUpdateValueSql($columns);
		$whereSql = $this->prepareWhereSql($where);
		$sql = $this->UPDATE.$table.$this->SET.$valueSql.$whereSql;

		return $sql;
	}

	public function prepareSelectSql($tableName, $columnArr, $whereArr ,$limitOffsetArr)
	{
		if (count($columnArr) == 0 || is_null($tableName))
		{
			return false;
		}
		$sql;
		$table = $tableName;
		$columns = $columnArr;
		$where = $whereArr;
		$limitOffset = $limitOffsetArr;
		
		$columnSql = implode($this->COMMA, $columns);
		$whereSql = $this->prepareWhereSql($where);

		$limitOffsetSql = (empty($limitOffsetArr))? "" : "LIMIT ". $limitOffset[0] . " OFFSET " . $limitOffset[1];
		$sql = $this->SELECT . $columnSql . $this->FROM . $table . $whereSql . $limitOffsetSql;

		return $sql;
	}

	private function prepareWhereSql($whereArr)
	{
		$array = $whereArr;
		foreach ($array as &$value) $value = $this->WHERE . $value . $this->VALUEBIND;

		$sql = implode($this->WHEREDELIMIT, $array);
		return $sql;
	}

	private function prepareUpdateValueSql($valueArr)
	{
		$array = $valueArr;
		foreach ($array as &$value) $value = $value . $this->VALUEBIND;

		$sql = implode($this->COMMA, $array);
		return $sql;
	}

}

?>