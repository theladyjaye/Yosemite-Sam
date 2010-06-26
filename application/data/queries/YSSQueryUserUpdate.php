<?php
/*
	TODO Yes, this the atomic (as in bomb) option for an update, we are just updating EVERYTHING.
	Rethink how to better take advantage of updates, instead of this a-bomb of one.
*/
class YSSQueryUserUpdate extends AMQuery
{
	protected function initialize()
	{
		$id        = (int) $this->dbh->real_escape_string($this->options['id']);
		$level     = $this->dbh->real_escape_string($this->options['level']);
		$domain    = $this->dbh->real_escape_string($this->options['domain']);
		$username  = $this->dbh->real_escape_string($this->options['username']);
		$email     = $this->dbh->real_escape_string($this->options['email']);
		$firstname = $this->dbh->real_escape_string($this->options['firstname']);
		$lastname  = $this->dbh->real_escape_string($this->options['lastname']);
		$password  = $this->dbh->real_escape_string($this->options['password']);
		$active    = (int) $this->dbh->real_escape_string($this->options['active']);
		
		$this->sql = <<<SQL
		UPDATE user SET level = '$level', domain = '$domain', username = '$username', email='$email', firstname='$firstname', lastname='$lastname', password='$password', active='$active' WHERE id='$id';
SQL;
	}
}

?>