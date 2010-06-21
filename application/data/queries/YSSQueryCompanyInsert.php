<?php
class YSSQueryCompanyInsert extends AMQuery
{
	protected function initialize()
	{
		// $options will be a YSSUser
		$name  = $this->dbh->real_escape_string($this->options['name']);
		$domain = $this->dbh->real_escape_string($this->options['domain']);
		
		$this->sql = <<<SQL
		INSERT INTO company (name, domain) VALUES ('$name', '$domain');
SQL;
	}
}
?>