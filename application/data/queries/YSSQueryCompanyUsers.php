<?php
class YSSQueryCompanyUsers extends AMQuery
{
	protected function initialize()
	{
		$company_id = (int) $this->dbh->real_escape_string($this->options['company_id']);
		
		$this->sql = <<<SQL
		SELECT u.id, u.level, u.username, u.email, u.firstname, u.lastname
		FROM user u, company_user cu 
		WHERE cu.company_id = $company_id 
		AND cu.user_id = u.id
		AND u.active = 1;
SQL;
	}
}
?>