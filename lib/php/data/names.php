<?php

//This file includes all functions which deal with formatting names

function username_to_lastname ($name)

	{
		
		$query = mysql_query("SELECT `username`,`last_name` FROM `cm_users` WHERE `username` = '$name' LIMIT 1");

		$r = mysql_fetch_array($query);

		return $r['last_name'];
		
		
	}

