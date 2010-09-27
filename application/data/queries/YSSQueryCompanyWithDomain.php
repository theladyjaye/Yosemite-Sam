<?php
class YSSQueryCompanyWithDomain extends AMQuery
{
	protected function initialize()
	{
		$domain = $this->dbh->real_escape_string($this->options);
		
		/*$this->sql = <<<SQL
		SELECT id, name, domain, `timestamp` FROM company WHERE domain = '$domain';
SQL;*/
		$this->sql = <<<SQL
		SELECT c.id, c.name, c.domain, c.`timestamp`, COUNT(*) users FROM company c, company_user cu WHERE c.domain = '$domain' AND cu.company_id = c.id;
SQL;
	}
}
?>