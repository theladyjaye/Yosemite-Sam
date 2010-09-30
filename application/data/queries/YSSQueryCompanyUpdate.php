<?php
class YSSQueryCompanyUpdate extends AMQuery
{
	protected function initialize()
	{
		
		$id    = (int) $this->dbh->real_escape_string($this->options['id']);
		
		unset($this->options['id']);
		array_walk($this->options, array($this, 'build_query'));
		
		$target = implode($this->options, ' AND ');
		
		$this->sql = <<<SQL
		UPDATE company SET $target WHERE id = $id;
SQL;

	}
	
	private function build_query(&$value, $key)
	{
		$value = $key."='".$this->dbh->real_escape_string($value)."'";
	}
}
?>