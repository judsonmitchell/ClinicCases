<?php

	function write_log ($dbh,$username,$ip,$sess_id,$type)
	
		{
			$in = $dbh->prepare("INSERT INTO cm_logs (username, ip, session_id, type) VALUES (?, ?, ?, ?)");
			
			$data = array($username,$ip,$sess_id,$type);
			
			$in->execute($data);
			
		}
	
