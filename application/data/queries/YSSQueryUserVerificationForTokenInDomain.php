<?php
class YSSQueryUserVerificationForTokenInDomain extends AMQuery
{
	protected function initialize()
	{
		$token     = $this->dbh->real_escape_string($this->options['token']);
		$domain    = $this->dbh->real_escape_string($this->options['domain']);

		$this->sql = <<<SQL
		SELECT user_id FROM user_verification WHERE token='$token' AND domain='$domain';
SQL;
	}
}
?>