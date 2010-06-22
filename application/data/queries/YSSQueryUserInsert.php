<?php
class YSSQueryUserInsert extends AMQuery
{
	protected function initialize()
	{
		$date      = new DateTime("now", new DateTimeZone("UTC"));
		$timestamp = $date->format(DateTime::ISO8601);
		
		$domain    = $this->dbh->real_escape_string($this->options['domain']);
		$username  = $this->dbh->real_escape_string($this->options['username']);
		$email     = $this->dbh->real_escape_string($this->options['email']);
		$firstname = $this->dbh->real_escape_string($this->options['firstname']);
		$lastname  = $this->dbh->real_escape_string($this->options['lastname']);
		$password  = $this->dbh->real_escape_string($this->options['password']);
		
		$this->sql = <<<SQL
		INSERT INTO user (domain, username, email, firstname, lastname, password, timestamp) VALUES ('$domain', '$username', '$email', '$firstname', '$lastname', '$password', '$timestamp');
SQL;
	}
}

?>