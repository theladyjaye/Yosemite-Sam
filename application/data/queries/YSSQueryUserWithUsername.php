<?php
class YSSQueryUserWithUsername extends AMQuery
{
	protected function initialize()
	{
		$username = $this->dbh->real_escape_string($this->options);
		
		$this->sql = <<<SQL
		SELECT id, domain, username, email, firstname, lastname, password FROM user WHERE email = '$username';
SQL;
	}
}
?>