<?php
class YSSQueryUserWithId extends AMQuery
{
	protected function initialize()
	{
		$id = (int)$this->dbh->real_escape_string($this->options);
		
		$this->sql = <<<SQL
		SELECT id, level, domain, username, email, firstname, lastname, password, active, `timestamp` FROM user WHERE id = '$id';
SQL;
	}
}
?>