<?php
class YSSQueryCompanyDetailsWithDomain extends AMQuery
{
	protected function initialize()
	{
		$domain = $this->dbh->real_escape_string($this->options);
		
		/*$this->sql = <<<SQL
		SELECT id, name, domain, `timestamp` FROM company WHERE domain = '$domain';
SQL;*/
		$active = YSSUserActiveState::kActive;
		$this->sql = <<<SQL
SELECT c.id, 
c.name,
c.domain,
c.`timestamp`,
c.logo,
COUNT(*) users
FROM company c, company_user cu, user u
WHERE c.domain = '$domain'
AND u.id = cu.user_id
AND cu.company_id = c.id
AND u.active = $active;
SQL;
	}
}
?>