<?php
include '../db.php';
//This script is to facilitate to the multiple professors on once case feature of cc6.  It assumes you are running ClinicCases 5

//get the assigned professor names from professor and professor 2

$get_profs = mysql_query("SELECT `id`,`professor`,`professor2` from `cm`");

//loop through the array, putting all values in professor in a comma seperated list.

while ($r = mysql_fetch_array($get_profs))
	{
		$data = $r[professor] . ',' . $r[professor2];
		$update = mysql_query("UPDATE `cm` SET `professor` = '$data' WHERE `id` = '$r[id]' LIMIT 1");	
		
	}

//error
if (mysql_errno($connection)) { 
  $error = "Sorry, there was an error updating the professor fields.  Maybe this will help: MySQL error ". mysql_errno($connection).": ". mysql_error($connection)."\n<br>When executing:<br>\n$get_profs\n<br>";die;

//delete column professor2
}
$delete_col = mysql_query("ALTER TABLE `cm` DROP COLUMN `professor2`");

if (mysql_errno($connection)) { 
  $error = "Sorry, there was an error deleting the second professor field.  Maybe this will help: MySQL error ".mysql_errno($connection).": ". mysql_error($connection)."\n<br>When executing:<br>\n$delete_col\n<br>"; die;
}
echo "Assigned Professors updated.";


?>
