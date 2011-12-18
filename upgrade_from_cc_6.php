<?php
define("CC_DBHOST","");
define("CC_DBUSERNAME","");
define("CC_DBPASSWD", "");
define("CC_DATABASE_NAME","");

$connection = mysql_pconnect(CC_DBHOST,CC_DBUSERNAME,CC_DBPASSWD)
    or die ("Couldn't connect to server.");
$db = mysql_select_db(CC_DATABASE_NAME, $connection)
    or die("Couldn't select database.");
    
//This fixes incorrect date entries from cc6.  Date field on case notes had 00:00:00 in the
// time part which led to incorrect sorts when displaying case notes.  This was fixed in r663
// of add_time.php, but was not corrected in casenote_edit.php. There are therefore, a lot of
//incorrect entries in some dbs.  

$query = mysql_query("SELECT id, date, datestamp from cm_case_notes ORDER BY datestamp desc");
while ($r = mysql_fetch_array($query))
{
	$date_parts = explode(' ',$r['date']);
	$datestamp_parts = explode(' ',$r['datestamp']);

	if ($date_parts[1] = '00:00:00')
	{
		
		$new_date = $date_parts[0] . " " . $datestamp_parts[1];
		$update = mysql_query("UPDATE cm_case_notes set date = '$new_date' WHERE id = '$r[id]' LIMIT 1 ");
	
	}
	
}

echo "Case note date entries corrected<br>";
