<?php
/*
	WARNING! THIS ASSUMES MYSQL
	You will need to change the methods:
	
	AMQuery::execute
	AMQuery::count
	AMQuery::one
	AMQuery::current
	
	to match the needs of your desired DB package
*/
class AMQuery implements Iterator, Countable
{
	public $sql  = '';
	public $isMultiQuery;
	
	protected $currentIndex;
	protected $result;
	protected $dbh;
	protected $options;
	
	function __construct($dbh, $options=null)
	{
		$this->dbh     = $dbh;
		$this->options = $options;
		$this->initialize();
	}
	
	protected function initialize(){}
	
	public function execute()
	{
		if($this->isMultiQuery)
			$this->dbh->multi_query($this->__toString());
		else
			$this->result = $this->dbh->query($this->__toString());
	}
	
	public function __toString()
	{
		return $this->sql;
	}

	
	public function count()
	{
		if($this->result)
		{
			return $this->result->num_rows;
		}
		else
		{
			$this->execute();
			return $this->result->num_rows;
		}
	}
	
	public function one()
	{
		if(!$this->result)
			$this->execute();
			
		$row = $this->result->fetch_assoc();
		return $row;
	}
	
	/* ITERATOR METHODS */
	
	public function rewind() 
	{    
		if(!$this->result)
			$this->execute();
		
		$this->currentIndex = 0;
	}

	public function current() 
	{
		$row    = $this->result->fetch_assoc();
		return $row;
	}

	public function key() 
	{
		return $this->currentIndex;
	}

	public function next() 
	{
		$this->currentIndex++;
	}

	public function valid() 
	{
		$var =  $this->currentIndex < count($this);
		return $var;
	}
	
	public function __destruct()
	{
		/*if($this->result)
		{
			$this->result->free();
			$this->result = null;
		}*/
		$this->result = null;
		$this->sql    = null;
	}
}
?>