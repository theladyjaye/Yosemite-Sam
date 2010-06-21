<?php
class YSSQueryUserInsert extends AMQuery
{
	protected function initialize()
	{
		$domain     = $this->dbh->real_escape_string($this->options['domain']);
		$username  = $this->dbh->real_escape_string($this->options['username']);
		$email     = $this->dbh->real_escape_string($this->options['email']);
		$firstname = $this->dbh->real_escape_string($this->options['firstname']);
		$lastname  = $this->dbh->real_escape_string($this->options['lastname']);
		$password  = $this->dbh->real_escape_string($this->options['password']);
		
		$this->sql = <<<SQL
		INSERT INTO user (domain, username, email, firstname, lastname, password) VALUES ('$domain', '$username', '$email', '$firstname', '$lastname', '$password');
SQL;
	}
}

?>