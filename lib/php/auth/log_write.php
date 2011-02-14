<?php

	function write_log ($username,$ip,$sess_id,$type)
	
		{
			
			$in = mysql_query("INSERT INTO `cm_logs` (`id`, `username`, `timestamp`, `ip`, `session_id`, `type`) VALUES (NULL, '$username', CURRENT_TIMESTAMP, '$ip', '$sess_id', '$type')");
				
		}
	
