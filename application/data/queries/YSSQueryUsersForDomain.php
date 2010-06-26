<?php
class YSSQueryUsersForDomain extends AMQuery
{
	protected function initialize()
	{
		$session = YSSSession::sharedSession();
		$domain  = $this->dbh->real_escape_string($this->options['domain']);
		$id      = (int) $this->dbh->real_escape_string($session->currentUser->id);
		
		$this->sql = <<<SQL
		SELECT id, level, domain, username, email, firstname, lastname, password, active, `timestamp` FROM user WHERE domain = '$domain' AND id != '$id';
SQL;
	}
}
?>