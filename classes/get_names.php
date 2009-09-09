<?php

class get_names {
	
	
	
	function get_users_name($username)
		{
			
			$q = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$username' LIMIT 1");
			$qq = mysql_fetch_object($q);
			$real_name = $qq->first_name . " " . $qq->last_name;
			return $real_name;
			
			
		}
	
	
	
	function get_clients_name($id)
		{
			
			$q = mysql_query("SELECT * FROM `cm` WHERE `id` = '$id' LIMIT 1");
			$qq = mysql_fetch_object($q);
			$real_name = $qq->first_name . " $qq->m_initial" . " $qq->last_name";
			return $real_name;
			
			
		}
	
	
	
	
	
	
	
	
	
}











?>
