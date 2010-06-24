<?php
class YSSQueryUserWithEmail extends AMQuery
{
	protected function initialize()
	{
		$email = $this->dbh->real_escape_string($this->options);
		
		$this->sql = <<<SQL
		SELECT id, domain, username, email, firstname, lastname, password, `timestamp` FROM user WHERE email = '$email';
SQL;
	}
}
?>