<?php
class YSSQueryUserWithEmail extends AMQuery
{
	protected function initialize()
	{
		$email = $this->dbh->real_escape_string($this->options);
		
		$this->sql = <<<SQL
		SELECT id, level, domain, username, email, firstname, lastname, password, active, `timestamp` FROM user WHERE email = '$email';
SQL;
	}
}
?>