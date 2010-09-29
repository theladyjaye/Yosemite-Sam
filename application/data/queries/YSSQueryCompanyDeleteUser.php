<?php
class YSSQueryCompanyDeleteUser extends AMQuery
{
	protected function initialize()
	{
		$this->isMultiQuery = true;
		
		$company_id = (int)$this->dbh->real_escape_string($this->options['company_id']);
		$user_id    = (int)$this->dbh->real_escape_string($this->options['user_id']);
		
		// this could probably be handeled by a foreign key constraint, just delete the user
		// and this should get cleaned up too. Dunno if we want to bind ourselves to that behavior yet.
		/*
			TODO decide how we want to handle deleting a user from a company.  Should it delete the user too?
			what also happens to the users history.  We should probably just mark the user as active = false;
			but then we also need to do something to ensure the deleted users email is returned to the available pool.
		*/
		$this->sql = <<<SQL
DELETE FROM company_user WHERE company_id = $company_id AND user_id = $user_id;
DELETE FROM user WHERE id = $user_id;
DELETE FROM user_verification WHERE user_id = $user_id;
SQL;
	}
}
?>