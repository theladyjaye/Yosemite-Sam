<?php
class YSSQueryCompanyInsert extends AMQuery
{
	protected function initialize()
	{
		//$date      = new DateTime("now", new DateTimeZone("UTC"));
		//$timestamp = $date->format(DateTime::ISO8601);
		$timestamp = YSSApplication::timestamp_now();
		
		// $options will be a YSSUser
		$name  = $this->dbh->real_escape_string($this->options['name']);
		$domain = $this->dbh->real_escape_string($this->options['domain']);
		
		$this->sql = <<<SQL
		INSERT INTO company (name, domain, timestamp) VALUES ('$name', '$domain', '$timestamp');
SQL;
	}
}
?>