<?php
class YSSQueryUserVerificationRemoveForTokenInDomain extends AMQuery
{
	protected function initialize()
	{
		$token     = $this->dbh->real_escape_string($this->options['token']);
		$domain    = $this->dbh->real_escape_string($this->options['domain']);
		$user_id   = (int) $this->dbh->real_escape_string($this->options['user_id']);
		
		$this->sql = <<<SQL
		DELETE FROM user_verification WHERE token='$token' AND domain='$domain' AND user_id='$user_id';
SQL;
	}
}
?>