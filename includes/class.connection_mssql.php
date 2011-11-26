<?php

Class I3_Connection {

	var $connection;

	function i3_db() {
		define("HOST", "HELIX");
		define("DB", "NSI_BCG");
		define("UNAME", "sa");
		define("PWORD", "1nfin1ty");

		$this -> connection = mssql_connect(HOST, UNAME, PWORD) or die("Could not connect: " . mssql_error());
		$db = mssql_select_db(DB, $this -> connection) or die("Unable to select database" . mssql_error());
		return true;
	}

	function i3_close() {
		mssql_close($this -> connection) or die("Unable to close database: " . mssql_errno());
		$this -> connection = false;
		return;
	}

}
