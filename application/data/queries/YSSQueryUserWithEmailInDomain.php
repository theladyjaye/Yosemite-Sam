<?php
class YSSQueryUserWithEmailInDomain extends AMQuery
{
	protected function initialize()
	{
		$email = $this->dbh->real_escape_string($this->options['email']);
		$domain = $this->dbh->real_escape_string($this->options['domain']);
		
		$this->sql = <<<SQL
		SELECT id, level, domain, username, email, firstname, lastname, password, active, `timestamp` FROM user WHERE email = '$email' AND domain = '$domain';
SQL;
	}
}
?>