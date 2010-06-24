<?php
class YSSQueryUserWithUsernameInDomain extends AMQuery
{
	protected function initialize()
	{
		$username = $this->dbh->real_escape_string($this->options['username']);
		$domain = $this->dbh->real_escape_string($this->options['domain']);
		
		$this->sql = <<<SQL
		SELECT id, domain, username, email, firstname, lastname, password, `timestamp` FROM user WHERE username = '$username' AND domain = '$domain';
SQL;
	}
}
?>