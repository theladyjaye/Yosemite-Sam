<?php
class YSSQueryCompanyExistsWithDomain extends AMQuery
{
	protected function initialize()
	{
		$domain = $this->dbh->real_escape_string($this->options);
		$this->sql = <<<SQL
		SELECT c.id FROM company c WHERE c.domain = '$domain';
SQL;
	}
}
?>