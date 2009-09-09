<?php
include '../db.php';

//This is a script to update the message database from ClinicCases 3 to ClinicCases5 (there was no 4).  The fields read and archive are now comma-seperated lists
//of each user who has marked read or archived a post.  Before it was a yes/no value.  BACK UP YOUR CM_MESSAGES TABLE BEFORE RUNNING THIS SCRIPT!!!

$get_messages = mysql_query("SELECT * FROM `cm_messages`");
while ($r = mysql_fetch_array($get_messages))
	{
	
		if ($r[read] == 'yes')	
		{$update_read = mysql_query("UPDATE `cm_messages` SET `read` = '$r[to],'  WHERE `id` = '$r[id]' LIMIT 1");}
		
		if ($r[archive] == 'yes')
		{$update_archive = mysql_query("UPDATE `cm_messages` SET `archive` = '$r[to],'  WHERE `id` = '$r[id]' LIMIT 1");}
		
		
	}

//NOW, DELETE THIS SCRIPT FROM YOUR INSTALLATION!
echo "Done. You should now delete this script from you server.";
?>
