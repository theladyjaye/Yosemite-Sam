<?php
class YSSQueryUserVerificationInsert extends AMQuery
{
	protected function initialize()
	{
		//$date      = new DateTime("now", new DateTimeZone("UTC"));
		//$timestamp = $date->format(DateTime::ISO8601);
		$timestamp = YSSApplication::timestamp_now();
		
		$token     = $this->dbh->real_escape_string($this->options['token']);
		$domain    = $this->dbh->real_escape_string($this->options['domain']);
		$user_id   = (int) $this->dbh->real_escape_string($this->options['user_id']);
		
		$this->sql = <<<SQL
		INSERT INTO user_verification (token, domain, user_id, timestamp) VALUES ('$token', '$domain', '$user_id', '$timestamp');
SQL;
	}
}
?>