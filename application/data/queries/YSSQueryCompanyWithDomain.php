<?php
class YSSQueryCompanyWithDomain extends AMQuery
{
	protected function initialize()
	{
		$domain = $this->dbh->real_escape_string($this->options);
		
		$this->sql = <<<SQL
		SELECT id, name, domain FROM company WHERE domain = '$domain';
SQL;
	}
}
?>