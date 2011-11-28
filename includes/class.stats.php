<?php

require_once 'class.connection_mysql.php';

Class Stats extends Connection {
	
	public function __construct()
	{
	}
	
	public function dispo_summary()
	{
		$query = "
			SELECT last_dispo, count(*) as total
			FROM bcg_records 
			GROUP BY last_dispo
			ORDER BY last_dispo
		";
		
		$this->local_db();
		
		$result = mysql_query($query);
		
		$this->Close();
		
		return $result;
	}
	
	public function agent_summary()
	{
		$query = "
			SELECT user, last_dispo, count(*) as total
			FROM bcg_records 
			GROUP BY  user, last_dispo
			ORDER BY  user, last_dispo
		";
		
		$this->local_db();
		
		$result = mysql_query($query);
		
		$this->Close();
		
		return $result;
	}
	
}