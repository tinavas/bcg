<?php

require_once 'class.connection_mysql.php';

Class User extends Connection {
	
	function __construct()
	{
		@session_start();
		
		//set default sessions
		$_SESSION['bcg_dialer'] = 'i3';
		
		//connect
		$this->local_db();
	}
	
	function chk_login($username, $password, $dialer)
	{
		//escape character
		$username = mysql_escape_string($username);
		$password = mysql_escape_string($password);
		$dialer = mysql_escape_string($dialer);
		
		//query user
		$query = '
			SELECT *
			FROM bcg_users
			WHERE username_i3 = "'.$username.'"
				AND password = "'.$password.'"
		';
		
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) > 0)
		{
			//set session
			$_SESSION['bcg_dialer'] = $dialer;
			$_SESSION['bcg_agent'] = $username;
			$_SESSION['bcg_admin'] = $this->is_admin($username);
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function is_admin($username) {
		//query user
		$query = '
			SELECT admin
			FROM bcg_users
			WHERE username_i3 = "'.$username.'"
		';
		
		$result = mysql_query($query);
		
		$row = mysql_fetch_object($result);
		
		return $row->admin;
	}
	
}