<?php
class YSSQueryCompanyInsertUser extends AMQuery
{
	protected function initialize()
	{
		// $options will be a YSSUser
		$company_id = (int)$this->dbh->real_escape_string($this->options['company_id']);
		$user_id    = (int)$this->dbh->real_escape_string($this->options['user_id']);
		
		$this->sql = <<<SQL
		INSERT INTO company_user (company_id, user_id) VALUES ($company_id, $user_id);
SQL;
	}
}
?>